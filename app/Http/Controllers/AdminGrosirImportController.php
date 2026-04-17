<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGrosirImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.grosir.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ]);

        return $this->importCsv($request->file('file'));
    }

    /**
     * =========================
     * IMPORT CSV
     * =========================
     */
    private function importCsv($file)
    {
        $content = file_get_contents($file->getRealPath());

        // Handle encoding Excel
        if (mb_detect_encoding($content, ['UTF-16', 'UTF-16LE', 'UTF-16BE'], true)) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16');
        }

        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $lines = array_values(array_filter(explode("\n", $content)));

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File kosong']);
        }

        // ===== HEADER =====
        $header = preg_split('/[;,]/', $lines[0]);

        $header = array_map(function ($h) {
            $h = trim($h);
            $h = preg_replace('/[^\x20-\x7E]/', '', $h);
            return strtoupper($h);
        }, $header);

        $required = ['PABRIK','NAMA PRODUK','RETAIL','KOMPOSISI','INDIKASI'];

        $missing = array_diff($required, $header);

        if (!empty($missing)) {
            return back()->withErrors([
                'file' => 'Header tidak cocok: ' . implode(', ', $header)
            ]);
        }

        return $this->processRows($header, array_slice($lines, 1));
    }

    /**
     * =========================
     * PROCESS ROWS
     * =========================
     */
    private function processRows($header, $lines)
    {
        $imported = 0;
        $skipped  = 0;

        DB::beginTransaction();

        try {

            foreach ($lines as $i => $line) {

                $row = preg_split('/[;,]/', $line);

                $row = array_map(function ($v) {
                    return trim(preg_replace('/[^\x20-\x7E]/', '', $v));
                }, $row);

                $row = array_pad($row, count($header), '');
                $row = array_slice($row, 0, count($header));

                $data = array_combine($header, $row);

                if (empty($data['NAMA PRODUK'])) {
                    $skipped++;
                    continue;
                }

                Medicine::updateOrCreate(
                    ['nama_obat' => $data['NAMA PRODUK'], 'is_grosir' => true],
                    [
                        'kategori'  => $data['PABRIK'],
                        'harga'     => $this->parseHarga($data['RETAIL']),
                        'stok'      => isset($data['STOK']) ? (int) preg_replace('/[^0-9]/', '', $data['STOK']) : 0,
                        'deskripsi' => ($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? ''),
                        'is_grosir' => true,
                        'is_resep'  => false,
                    ]
                );

                $imported++;
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'file' => 'Error: ' . $e->getMessage()
            ]);
        }

        return redirect()->route('admin.grosir.index')
            ->with('success', "Import grosir berhasil: {$imported}, skip: {$skipped}");
    }

    /**
     * =========================
     * PARSE HARGA INDONESIA
     * =========================
     */
    private function parseHarga($value)
    {
        if (!$value) return 0;

        $value = str_replace(['Rp', 'rp', ' '], '', $value);

        if (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace('.', '', $value);
        }

        return (float) $value;
    }
}