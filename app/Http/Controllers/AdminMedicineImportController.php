<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMedicineImportController extends Controller
{
    private array $companies = Companies::LIST;

    public function showImportForm()
    {
        return view('admin.medicines.import', [
            'categories' => $this->companies
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            return back()->withErrors(['file' => 'Format harus CSV / XLS / XLSX']);
        }

        if (in_array($ext, ['xls', 'xlsx'])) {
            return $this->importExcel($file);
        }

        return $this->importCsv($file);
    }

    /**
     * =========================
     * CSV IMPORT
     * =========================
     */
    private function importCsv($file)
    {
        $content = file_get_contents($file->getRealPath());

        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $lines   = array_values(array_filter(explode("\n", $content)));

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File kosong']);
        }

        // AUTO DETECT DELIMITER
        $delimiter = str_contains($lines[0], ';') ? ';' : ',';

        $header = array_map(
            fn($h) => strtoupper(trim($h)),
            str_getcsv($lines[0], $delimiter)
        );

        $required = ['PABRIK','NAMA PRODUK','RETAIL','KOMPOSISI','INDIKASI','GOLONGAN'];

        $missing = array_diff($required, $header);

        if (!empty($missing)) {
            return back()->withErrors([
                'file' => 'Header kurang: ' . implode(', ', $missing)
            ]);
        }

        return $this->processRows($header, array_slice($lines, 1), $delimiter);
    }

    /**
     * =========================
     * EXCEL IMPORT
     * =========================
     */
    private function importExcel($file)
    {
        $content = file_get_contents($file->getRealPath());

        if (strpos($content, '<Workbook') !== false) {
            return $this->importExcelXml($content);
        }

        if (strpos($content, 'PK') === 0) {
            return back()->withErrors([
                'file' => 'File XLSX tidak didukung. Save as CSV.'
            ]);
        }

        return back()->withErrors([
            'file' => 'Format Excel tidak dikenali'
        ]);
    }

    /**
     * =========================
     * EXCEL XML PARSER
     * =========================
     */
    private function importExcelXml($content)
    {
        libxml_use_internal_errors(true);

        $content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $content);

        $xml = simplexml_load_string($content);

        if (!$xml) {
            return back()->withErrors(['file' => 'File rusak']);
        }

        $rows = [];

        foreach ($xml->Worksheet[0]->Table->Row as $row) {
            $r = [];
            foreach ($row->Cell as $cell) {
                $r[] = trim((string)$cell->Data);
            }
            $rows[] = $r;
        }

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'Data kosong']);
        }

        $header = array_map(fn($h) => strtoupper(trim($h)), $rows[0]);

        return $this->processArrayRows($header, array_slice($rows, 1));
    }

    /**
     * =========================
     * PROCESS CSV ROW
     * =========================
     */
    private function processRows($header, $lines, $delimiter)
    {
        $rows = [];

        foreach ($lines as $line) {
            $rows[] = str_getcsv($line, $delimiter);
        }

        return $this->processArrayRows($header, $rows);
    }

    /**
     * =========================
     * CORE IMPORT
     * =========================
     */
    private function processArrayRows($header, $rows)
    {
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        if (count($rows) > 2000) {
            return back()->withErrors([
                'file' => 'Maksimal 2000 baris'
            ]);
        }

        DB::beginTransaction();

        try {

            foreach ($rows as $i => $row) {

                if (count($row) !== count($header)) {
                    $skipped++;
                    $errors[] = "Baris " . ($i+2) . ": jumlah kolom tidak sesuai";
                    continue;
                }

                $data = array_combine($header, $row);
                $data = array_map('trim', $data);

                if (empty($data['NAMA PRODUK'])) {
                    $skipped++;
                    continue;
                }

                $rowErrors = $this->validateRow($data, $i + 2);

                if ($rowErrors) {
                    $errors = array_merge($errors, $rowErrors);
                    $skipped++;
                    continue;
                }

                $golongan = strtoupper($data['GOLONGAN']);
                $isResep  = $golongan === 'KERAS';

                $deskripsi = trim(
                    ($data['KOMPOSISI'] ?? '') .
                    ' | ' .
                    ($data['INDIKASI'] ?? '')
                );

                Medicine::updateOrCreate(
                    ['nama_obat' => $data['NAMA PRODUK']],
                    [
                        'kategori'  => $data['PABRIK'],
                        'harga'     => $this->parseHarga($data['RETAIL']), // 🔥 FIX HARGA
                        'stok'      => (int) preg_replace('/[^0-9]/', '', $data['STOK'] ?? 0),
                        'deskripsi' => $deskripsi,
                        'is_resep'  => $isResep,
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

        $msg = "Import berhasil: {$imported}";
        if ($skipped) $msg .= " | Skip: {$skipped}";

        return redirect()->route('admin.medicines.index')
            ->with('success', $msg);
    }

    /**
     * =========================
     * PARSE HARGA (INDONESIA FORMAT)
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

    /**
     * =========================
     * VALIDATION
     * =========================
     */
    private function validateRow($data, $line)
    {
        $err = [];

        if (empty($data['PABRIK']))
            $err[] = "Baris {$line}: PABRIK kosong";

        if (empty($data['KOMPOSISI']))
            $err[] = "Baris {$line}: KOMPOSISI kosong";

        if (empty($data['INDIKASI']))
            $err[] = "Baris {$line}: INDIKASI kosong";

        if (!in_array(strtoupper($data['GOLONGAN']), ['BEBAS','KERAS']))
            $err[] = "Baris {$line}: GOLONGAN salah";

        if (!is_numeric($this->parseHarga($data['RETAIL'])))
            $err[] = "Baris {$line}: RETAIL tidak valid";

        if (!is_numeric($data['STOK'] ?? 0))
            $err[] = "Baris {$line}: STOK tidak valid";

        return $err;
    }
}