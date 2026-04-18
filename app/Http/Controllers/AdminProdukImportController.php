<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProdukImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.produk.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'xlsx') {
            return $this->importXlsx($file);
        }

        // CSV dan XLS (text-based)
        return $this->importCsv($file);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="template_produk.xls"',
        ];

        $kategoriList = implode(', ', \App\Constants\Companies::LIST);

        $html  = '<table border="1">';
        $html .= '<tr>';
        $html .= '<th>PABRIK</th>';
        $html .= '<th>NAMA PRODUK</th>';
        $html .= '<th>HARGA</th>';
        $html .= '<th>STOK</th>';
        $html .= '<th>KOMPOSISI</th>';
        $html .= '<th>INDIKASI</th>';
        $html .= '<th>KATEGORI</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>KIMIA FARMA</td>';
        $html .= '<td>Paracetamol 500mg</td>';
        $html .= '<td>5000</td>';
        $html .= '<td>100</td>';
        $html .= '<td>Paracetamol 500 mg</td>';
        $html .= '<td>Demam &amp; nyeri</td>';
        $html .= '<td>PRODUK LENGKAP</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>WARDAH</td>';
        $html .= '<td>Pelembab Wajah SPF30</td>';
        $html .= '<td>85000</td>';
        $html .= '<td>50</td>';
        $html .= '<td>Aqua, Glycerin, SPF30</td>';
        $html .= '<td>Melembabkan &amp; melindungi kulit</td>';
        $html .= '<td>SKINCARE &amp; KOSMETIK</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>OMRON</td>';
        $html .= '<td>Tensimeter Digital</td>';
        $html .= '<td>350000</td>';
        $html .= '<td>20</td>';
        $html .= '<td>-</td>';
        $html .= '<td>Mengukur tekanan darah</td>';
        $html .= '<td>ALAT KESEHATAN</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '<br>';
        $html .= '<p><b>Kolom KATEGORI harus salah satu dari:</b> ' . $kategoriList . '</p>';
        $html .= '<p>Jika KATEGORI tidak diisi atau tidak sesuai, akan otomatis masuk ke <b>PRODUK LENGKAP</b>.</p>';

        return response($html, 200, $headers);
    }

    // ─── XLSX Parser (pure PHP, no ZipArchive needed) ────────────────────────

    private function importXlsx($file)
    {
        $path = $file->getRealPath();

        // XLSX adalah ZIP — coba ZipArchive dulu, fallback ke manual binary extract
        if (class_exists('ZipArchive')) {
            return $this->importXlsxViaZip($path);
        }

        // Fallback: extract ZIP secara manual menggunakan PharData (built-in PHP)
        return $this->importXlsxViaPhar($path);
    }

    private function importXlsxViaZip(string $path)
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return back()->withErrors(['file' => 'File XLSX tidak dapat dibuka.']);
        }

        $sharedStrings = $this->parseSharedStrings($zip->getFromName('xl/sharedStrings.xml') ?: '');
        $sheetXml      = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return back()->withErrors(['file' => 'Sheet tidak ditemukan dalam file XLSX.']);
        }

        return $this->processRows($this->parseSheetXml($sheetXml, $sharedStrings));
    }

    private function importXlsxViaPhar(string $path)
    {
        // Salin ke file .zip sementara agar PharData bisa baca
        $tmpZip = sys_get_temp_dir() . '/' . uniqid('xlsx_') . '.zip';
        copy($path, $tmpZip);

        try {
            $phar = new \PharData($tmpZip);

            $ssContent = '';
            if (isset($phar['xl/sharedStrings.xml'])) {
                $ssContent = file_get_contents($phar['xl/sharedStrings.xml']->getPathname());
            }

            if (!isset($phar['xl/worksheets/sheet1.xml'])) {
                return back()->withErrors(['file' => 'Sheet tidak ditemukan dalam file XLSX.']);
            }

            $sheetXml      = file_get_contents($phar['xl/worksheets/sheet1.xml']->getPathname());
            $sharedStrings = $this->parseSharedStrings($ssContent);

            return $this->processRows($this->parseSheetXml($sheetXml, $sharedStrings));
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca XLSX: ' . $e->getMessage()
                . ' — Coba simpan ulang file sebagai CSV dari Excel.']);
        } finally {
            @unlink($tmpZip);
        }
    }

    private function parseSharedStrings(string $xml): array
    {
        $sharedStrings = [];
        if (empty($xml)) return $sharedStrings;

        $ss = @simplexml_load_string($xml);
        if (!$ss) return $sharedStrings;

        foreach ($ss->si as $si) {
            $text = '';
            foreach ($si->r as $r) {
                $text .= (string) $r->t;
            }
            if (empty($text)) {
                $text = (string) $si->t;
            }
            $sharedStrings[] = $text;
        }

        return $sharedStrings;
    }

    private function parseSheetXml(string $sheetXml, array $sharedStrings): array
    {
        $sheet = @simplexml_load_string($sheetXml);
        if (!$sheet) return [];

        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $type  = (string) $cell['t'];
                $value = (string) $cell->v;

                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) $cell->is->t;
                }

                $rowData[] = trim($value);
            }
            if (!empty(array_filter($rowData))) {
                $rows[] = $rowData;
            }
        }

        return $rows;
    }

    // ─── CSV / XLS (text-based) Parser ───────────────────────────────────────

    private function importCsv($file)
    {
        $content = file_get_contents($file->getRealPath());

        if (mb_detect_encoding($content, ['UTF-16', 'UTF-16LE', 'UTF-16BE'], true)) {
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16');
        }

        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $lines   = array_values(array_filter(explode("\n", $content)));

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File kosong atau tidak valid.']);
        }

        $rows = [];
        foreach ($lines as $line) {
            $row    = preg_split('/[;,\t]/', $line);
            $row    = array_map(fn($v) => trim(preg_replace('/[^\x20-\x7E\xA0-\xFF]/', '', $v)), $row);
            $rows[] = $row;
        }

        return $this->processRows($rows);
    }

    // ─── Shared processing logic ──────────────────────────────────────────────

    private function processRows(array $rows)
    {
        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File kosong atau tidak valid.']);
        }

        // Cari baris header — baris pertama yang mengandung 'NAMA PRODUK' atau 'PABRIK'
        $headerIdx = 0;
        foreach ($rows as $i => $row) {
            $normalized = array_map(
                fn($h) => strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $h))),
                $row
            );
            if (in_array('NAMA PRODUK', $normalized) || in_array('PABRIK', $normalized)) {
                $headerIdx = $i;
                break;
            }
        }

        $header = array_map(
            fn($h) => strtoupper(trim(preg_replace('/[^\x20-\x7E]/', '', $h))),
            $rows[$headerIdx]
        );

        // Hapus kolom kosong dari header (kolom pertama sering kosong di Excel)
        $header = array_map(fn($h) => trim($h), $header);

        $required = ['NAMA PRODUK', 'PABRIK'];
        $missing  = array_diff($required, $header);

        if (!empty($missing)) {
            return back()->withErrors([
                'file' => 'Header tidak cocok. Kolom wajib: ' . implode(', ', $required)
                        . '. Ditemukan: ' . implode(', ', array_filter($header)),
            ]);
        }

        $imported      = 0;
        $skipped       = 0;
        $validKategori = Companies::LIST;

        DB::beginTransaction();
        try {
            foreach (array_slice($rows, $headerIdx + 1) as $row) {
                $row  = array_pad($row, count($header), '');
                $row  = array_slice($row, 0, count($header));
                $data = array_combine($header, $row);

                if (empty($data['NAMA PRODUK'])) {
                    $skipped++;
                    continue;
                }

                $katRaw    = strtoupper(trim($data['KATEGORI'] ?? $data['KATEGORI PRODUK'] ?? ''));
                $katProduk = in_array($katRaw, $validKategori) ? $katRaw : 'PRODUK LENGKAP';

                // HARGA bisa juga ditulis RETAIL (sesuai format file Excel)
                $hargaRaw = $data['HARGA'] ?? $data['RETAIL'] ?? '0';

                Medicine::updateOrCreate(
                    ['nama_obat' => $data['NAMA PRODUK']],
                    [
                        'kategori'        => $data['PABRIK'] ?? ($data['PABRIK/MEREK'] ?? ''),
                        'kategori_produk' => $katProduk,
                        'harga'           => $this->parseHarga($hargaRaw),
                        'stok'            => isset($data['STOK']) ? (int) preg_replace('/[^0-9]/', '', $data['STOK']) : 0,
                        'deskripsi'       => '',
                        'komposisi'       => ($data['KOMPOSISI'] ?? '') ?: null,
                        'indikasi'        => ($data['INDIKASI'] ?? '') ?: null,
                    ]
                );
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Error saat menyimpan data: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.produk.index')
            ->with('success', "Import berhasil: {$imported} produk ditambahkan/diperbarui, {$skipped} baris dilewati.");
    }

    private function parseHarga($value): float
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
