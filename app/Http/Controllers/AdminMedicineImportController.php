<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;

class AdminMedicineImportController extends Controller
{
    private array $companies = Companies::LIST;

    /**
     * Download template dalam format Excel XML (.xls) — langsung rapi di Excel
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_obat.xls"',
            'Cache-Control'       => 'max-age=0',
        ];

        $columns = ['nama_obat', 'perusahaan', 'harga', 'stok', 'deskripsi'];

        $contohData = [
            ['Paracetamol 500mg',  'KIMIA FARMA',  5000,  100, 'Obat pereda nyeri dan demam'],
            ['Amoxicillin 500mg',  'KALBE',        15000,  50, 'Antibiotik untuk infeksi bakteri'],
            ['Vitamin C 1000mg',   'SANBE',         8000, 200, 'Suplemen vitamin C untuk daya tahan tubuh'],
        ];

        $xml = $this->buildExcelXml($columns, $contohData);

        return response($xml, 200, $headers);
    }

    /**
     * Build Excel XML (SpreadsheetML) — tidak butuh library eksternal
     */
    private function buildExcelXml(array $columns, array $rows): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel">' . "\n";

        // Style
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header">';
        $xml .= '<Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>';
        $xml .= '<Interior ss:Color="#1d4ed8" ss:Pattern="Solid"/>';
        $xml .= '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>';
        $xml .= '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>';
        $xml .= '</Style>';
        $xml .= '<Style ss:ID="data">';
        $xml .= '<Font ss:Size="10"/>';
        $xml .= '<Alignment ss:Vertical="Center"/>';
        $xml .= '</Style>';
        $xml .= '<Style ss:ID="number">';
        $xml .= '<Font ss:Size="10"/>';
        $xml .= '<Alignment ss:Horizontal="Right" ss:Vertical="Center"/>';
        $xml .= '<NumberFormat ss:Format="0"/>';
        $xml .= '</Style>';
        $xml .= '</Styles>';

        $xml .= '<Worksheet ss:Name="Data Obat">';
        $xml .= '<Table ss:DefaultRowHeight="20">';

        // Lebar kolom
        $widths = [180, 120, 80, 60, 300];
        foreach ($widths as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>';
        }

        // Baris header
        $xml .= '<Row ss:Height="24">';
        foreach ($columns as $col) {
            $xml .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . htmlspecialchars($col) . '</Data></Cell>';
        }
        $xml .= '</Row>';

        // Baris data contoh
        foreach ($rows as $row) {
            $xml .= '<Row ss:Height="20">';
            foreach ($row as $i => $val) {
                // kolom harga (index 2) dan stok (index 3) sebagai Number
                if ($i === 2 || $i === 3) {
                    $xml .= '<Cell ss:StyleID="number"><Data ss:Type="Number">' . (int)$val . '</Data></Cell>';
                } else {
                    $xml .= '<Cell ss:StyleID="data"><Data ss:Type="String">' . htmlspecialchars((string)$val) . '</Data></Cell>';
                }
            }
            $xml .= '</Row>';
        }

        $xml .= '</Table>';

        // Auto-filter pada header
        $xml .= '<AutoFilter x:Range="R1C1:R1C5" xmlns="urn:schemas-microsoft-com:office:excel"/>';

        $xml .= '</Worksheet>';

        // Sheet petunjuk
        $xml .= '<Worksheet ss:Name="Petunjuk">';
        $xml .= '<Table>';
        $xml .= '<Column ss:Width="500"/>';
        $petunjuk = [
            ['PETUNJUK PENGISIAN'],
            [''],
            ['1. Isi data di sheet "Data Obat"'],
            ['2. Jangan ubah nama kolom di baris pertama'],
            ['3. Kolom nama_obat  : Nama lengkap obat (wajib)'],
            ['4. Kolom perusahaan : Nama perusahaan farmasi (wajib), pilih salah satu:'],
            ['   ACTAVIS, ALTAMED, BALATIF, BERLICO, BERNOFARM, BUFA, CAPLANG,'],
            ['   CASPER, CIUBROS CITO, COMBIPHAR, CORONET, CORSA, DARYA VARIA,'],
            ['   DEXA, DIPA, ERELA, ERLIMPEX, ERRITA, ESCOLAB, FAHRENHEIT,'],
            ['   FUTAMED, GALENIUM, GMP, GRAHA, GSK, HARSEN, HEXPARM JAYA,'],
            ['   HISAMITSU, HOLI, HUFA, IFARS, IFI, INDOFARMA, INTERBAT,'],
            ['   ITRASAL, KALBE, KIMIA FARMA, KONIMEX, LANDSON, LAPI, MAHAKAM,'],
            ['   MEDIKA, MEDIKON, MEF, MEGA, MEIJI, MEPRO, MERCK, MERSI,'],
            ['   META RATNA, MOLEX AYUS, MULIA, MUTIFA, NICHOLAST, NOVAPHARIN,'],
            ['   NOVEL, NUFARINDO, PHAROS, PIM, PYRIDAM, RAMA, SAMCO,'],
            ['   SAMPHARINDO, SANBE, SELES, SINDE, STERLING, SYNERGY, TAISHO,'],
            ['   TAKEDA, TEMPO SCAN, TIA, TRIFA, TRIMAN, TROPICA, WIDATRA, ZENITH'],
            ['5. Kolom harga      : Angka saja, tanpa Rp atau titik (contoh: 5000)'],
            ['6. Kolom stok       : Angka saja (contoh: 100)'],
            ['7. Kolom deskripsi  : Deskripsi singkat obat (wajib)'],
            [''],
            ['Setelah diisi, simpan sebagai CSV lalu upload di halaman Import.'],
        ];
        foreach ($petunjuk as $p) {
            $xml .= '<Row><Cell><Data ss:Type="String">' . htmlspecialchars($p[0]) . '</Data></Cell></Row>';
        }
        $xml .= '</Table></Worksheet>';

        $xml .= '</Workbook>';

        return $xml;
    }

    /**
     * Tampilkan form import
     */
    public function showImportForm()
    {
        return view('admin.medicines.import', ['categories' => $this->companies]);
    }

    /**
     * Proses import file CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:2048'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Validasi ekstensi manual (lebih reliable dari MIME check)
        if (! in_array($extension, ['csv', 'xls', 'xlsx', 'txt'])) {
            return back()->withErrors(['file' => 'Format file harus CSV atau Excel (.xls/.xlsx). File yang diupload: .' . $extension]);
        }

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->importExcel($file);
        }

        return $this->importCsv($file);
    }

    /**
     * Import dari file CSV
     */
    private function importCsv($file)
    {
        $path    = $file->getRealPath();
        $content = file_get_contents($path);

        // Hapus BOM jika ada
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        $lines = array_filter(explode("\n", $content), fn($l) => trim($l) !== '');
        $lines = array_values($lines);

        if (count($lines) < 2) {
            return back()->withErrors(['file' => 'File CSV kosong atau hanya berisi header.']);
        }

        $header = $this->parseCsvLine($lines[0]);
        $header = array_map('trim', $header);

        $required = ['nama_obat', 'harga', 'stok', 'deskripsi'];
        // Support kolom 'perusahaan' (baru) atau 'kategori' (lama)
        $missing  = array_diff($required, $header);
        $hasKategori   = in_array('kategori', $header);
        $hasPerusahaan = in_array('perusahaan', $header);

        if (!empty($missing) || (!$hasKategori && !$hasPerusahaan)) {
            $allRequired = array_merge($required, ['perusahaan (atau kategori)']);
            return back()->withErrors([
                'file' => 'Kolom tidak lengkap. Kolom yang kurang: ' . implode(', ', array_diff($allRequired, $header)),
            ]);
        }

        return $this->processRows($header, array_slice($lines, 1));
    }

    /**
     * Import dari file Excel (.xls XML SpreadsheetML atau .xlsx yang disimpan sebagai CSV)
     */
    private function importExcel($file)
    {
        $content = file_get_contents($file->getRealPath());

        // Kasus 1: File adalah Excel XML (SpreadsheetML) — format yang kita generate
        if (strpos($content, 'urn:schemas-microsoft-com:office:spreadsheet') !== false
            || strpos($content, '<Workbook') !== false) {
            return $this->importExcelXml($content);
        }

        // Kasus 2: File .xlsx (ZIP-based) — tidak bisa diparse tanpa ext-zip
        // Tapi coba dulu baca sebagai teks biasa/CSV (kadang user save-as CSV dengan ekstensi xls)
        if (strpos($content, 'PK') === 0) {
            return back()->withErrors([
                'file' => 'Format .xlsx tidak didukung langsung. Silakan buka file di Excel → Save As → pilih "CSV (Comma delimited)" → upload file CSV tersebut.',
            ]);
        }

        // Kasus 3: File .xls yang sebenarnya berisi teks/CSV
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $lines   = array_filter(explode("\n", $content), fn($l) => trim($l) !== '');
        $lines   = array_values($lines);

        if (count($lines) >= 2) {
            $header = $this->parseCsvLine($lines[0]);
            $header = array_map('trim', $header);
            $required = ['nama_obat', 'kategori', 'harga', 'stok', 'deskripsi'];
            if (empty(array_diff($required, $header))) {
                return $this->processRows($header, array_slice($lines, 1));
            }
        }

        return back()->withErrors([
            'file' => 'Format file tidak dikenali. Gunakan template yang sudah disediakan dan simpan sebagai CSV sebelum upload.',
        ]);
    }

    /**
     * Parse Excel XML (SpreadsheetML) — format yang dihasilkan downloadTemplate()
     */
    private function importExcelXml(string $content): mixed
    {
        // Suppress XML errors, parse dengan libxml
        libxml_use_internal_errors(true);

        // Hapus namespace agar SimpleXML mudah parse
        $content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $content);
        $content = preg_replace('/<\?mso-application[^?]*\?>/i', '', $content);

        $xml = simplexml_load_string($content);

        if ($xml === false) {
            return back()->withErrors(['file' => 'File Excel tidak bisa dibaca. Pastikan file tidak rusak.']);
        }

        // Cari worksheet pertama (sheet "Data Obat")
        $worksheet = null;
        foreach ($xml->Worksheet as $ws) {
            $name = (string) $ws->attributes()['Name'] ?? '';
            // Ambil sheet pertama yang bukan "Petunjuk"
            if (strtolower($name) !== 'petunjuk') {
                $worksheet = $ws;
                break;
            }
        }

        if (!$worksheet || !isset($worksheet->Table)) {
            return back()->withErrors(['file' => 'Sheet "Data Obat" tidak ditemukan di file Excel.']);
        }

        $rows = [];
        foreach ($worksheet->Table->Row as $row) {
            $rowData = [];
            foreach ($row->Cell as $cell) {
                $rowData[] = trim((string) $cell->Data);
            }
            $rows[] = $rowData;
        }

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'File Excel kosong atau hanya berisi header.']);
        }

        // Baris pertama = header
        $header   = array_map('trim', $rows[0]);
        $required = ['nama_obat', 'harga', 'stok', 'deskripsi'];
        $hasKategori   = in_array('kategori', $header);
        $hasPerusahaan = in_array('perusahaan', $header);
        $missing  = array_diff($required, $header);

        if (!empty($missing) || (!$hasKategori && !$hasPerusahaan)) {
            return back()->withErrors([
                'file' => 'Kolom tidak lengkap. Kolom yang kurang: ' . implode(', ', array_merge($missing, (!$hasKategori && !$hasPerusahaan) ? ['perusahaan'] : [])),
            ]);
        }

        // Konversi array rows ke format string lines untuk processRows
        $dataRows = array_slice($rows, 1);
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($dataRows as $lineNum => $row) {
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $data = array_combine($header, $row);
            $data = array_map('trim', $data);

            if (empty($data['nama_obat'])) {
                $skipped++;
                continue;
            }

            $rowErrors = $this->validateRow($data, $lineNum + 2);
            if (!empty($rowErrors)) {
                $errors  = array_merge($errors, $rowErrors);
                $skipped++;
                continue;
            }

            Medicine::create([
                'nama_obat' => $data['nama_obat'],
                'kategori'  => $data['perusahaan'] ?? $data['kategori'] ?? '',
                'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['harga']),
                'stok'      => (int) preg_replace('/[^0-9]/', '', $data['stok']),
                'deskripsi' => $data['deskripsi'],
            ]);

            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()->withErrors(['file' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $message = "Berhasil mengimpor {$imported} obat.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('admin.medicines.index')->with('success', $message);
    }

    /**
     * Proses baris data dan simpan ke database
     */
    private function processRows(array $header, array $dataLines)
    {
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($dataLines as $lineNum => $line) {
            $row = $this->parseCsvLine($line);

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $data = array_combine($header, $row);
            $data = array_map('trim', $data);

            // Skip baris kosong
            if (empty($data['nama_obat'])) {
                $skipped++;
                continue;
            }

            // Validasi per baris
            $rowErrors = $this->validateRow($data, $lineNum + 2);
            if (!empty($rowErrors)) {
                $errors = array_merge($errors, $rowErrors);
                $skipped++;
                continue;
            }

            Medicine::create([
                'nama_obat' => $data['nama_obat'],
                'kategori'  => $data['perusahaan'] ?? $data['kategori'] ?? '',
                'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['harga']),
                'stok'      => (int) preg_replace('/[^0-9]/', '', $data['stok']),
                'deskripsi' => $data['deskripsi'],
            ]);

            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()->withErrors(['file' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $message = "Berhasil mengimpor {$imported} obat.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati.";
        }

        return redirect()->route('admin.medicines.index')->with('success', $message);
    }

    /**
     * Validasi satu baris data
     */
    private function validateRow(array $data, int $lineNum): array
    {
        $errors = [];

        $perusahaan = $data['perusahaan'] ?? $data['kategori'] ?? '';
        if (empty($perusahaan)) {
            $errors[] = "Baris {$lineNum}: perusahaan kosong.";
        }

        if (!is_numeric(preg_replace('/[^0-9.]/', '', $data['harga'] ?? ''))) {
            $errors[] = "Baris {$lineNum}: harga tidak valid ({$data['harga']}).";
        }

        if (!is_numeric(preg_replace('/[^0-9]/', '', $data['stok'] ?? ''))) {
            $errors[] = "Baris {$lineNum}: stok tidak valid ({$data['stok']}).";
        }

        if (empty($data['deskripsi'])) {
            $errors[] = "Baris {$lineNum}: deskripsi kosong.";
        }

        return $errors;
    }

    /**
     * Parse satu baris CSV dengan benar (handle quoted fields)
     */
    private function parseCsvLine(string $line): array
    {
        $line = rtrim($line, "\r\n");
        $result = str_getcsv($line, ',', '"');
        return $result;
    }
}
