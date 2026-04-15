<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPrescriptionController extends Controller
{
    private array $companies = Companies::LIST;
    
    // List produk resep
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Medicine::where('is_resep', true)->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $medicines  = $query->paginate(10)->withQueryString();
        $categories = Companies::LIST;

        return view('admin.prescriptions.index', compact('medicines', 'search', 'kategori', 'categories'));
    }

    // Form tambah produk resep
    public function create()
    {
        return view('admin.prescriptions.create', ['categories' => $this->companies]);
    }

    // Simpan produk resep baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string', 'max:255'],
            'indikasi'  => ['required', 'string', 'max:255'],
            'golongan'  => ['required', 'string', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        // Gabung komposisi dan indikasi untuk deskripsi
        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        
        // Hapus field yang tidak perlu di database
        unset($validated['komposisi']);
        unset($validated['indikasi']);

        $validated['is_resep'] = true;

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $image     = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/medicines', $imageName);
            $validated['gambar'] = 'medicines/' . $imageName;
        }

        Medicine::create($validated);

        return redirect()->route('admin.prescriptions.index')
                       ->with('success', 'Produk resep berhasil ditambahkan!');
    }

    // Form edit produk resep
    public function edit(Medicine $prescription)
    {
        if (!$prescription->is_resep) {
            abort(404);
        }
        
        return view('admin.prescriptions.edit', [
            'medicine'   => $prescription,
            'categories' => $this->companies,
        ]);
    }

    // Update produk resep
    public function update(Request $request, Medicine $prescription)
    {
        if (!$prescription->is_resep) {
            abort(404);
        }

        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string', 'max:255'],
            'indikasi'  => ['required', 'string', 'max:255'],
            'golongan'  => ['required', 'string', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        // Gabung komposisi dan indikasi untuk deskripsi
        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        
        // Hapus field yang tidak perlu di database
        unset($validated['komposisi']);
        unset($validated['indikasi']);

        $validated['is_resep'] = true;

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($prescription->gambar) {
                Storage::delete('public/' . $prescription->gambar);
            }

            // Upload gambar baru
            $image     = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/medicines', $imageName);
            $validated['gambar'] = 'medicines/' . $imageName;
        } elseif ($request->boolean('delete_gambar') && $prescription->gambar) {
            // Hapus foto tanpa upload baru
            Storage::delete('public/' . $prescription->gambar);
            $validated['gambar'] = null;
        }

        $prescription->update($validated);

        return redirect()->route('admin.prescriptions.index')
                       ->with('success', 'Produk resep berhasil diupdate!');
    }

    // Hapus produk resep
    public function destroy(Medicine $prescription)
    {
        if (!$prescription->is_resep) {
            abort(404);
        }

        // Hapus gambar
        if ($prescription->gambar) {
            Storage::delete('public/' . $prescription->gambar);
        }

        $prescription->delete();

        return redirect()->route('admin.prescriptions.index')
                       ->with('success', 'Produk resep berhasil dihapus!');
    }

    // Update stok
    public function updateStock(Request $request, Medicine $prescription)
    {
        if (!$prescription->is_resep) {
            abort(404);
        }

        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $prescription->update(['stok' => $validated['stok']]);

        return back()->with('success', 'Stok berhasil diupdate!');
    }

    // Form import produk resep
    public function showImportForm()
    {
        return view('admin.prescriptions.import', ['categories' => $this->companies]);
    }

    // Import produk resep dari CSV/Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120'],
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['csv', 'xls', 'xlsx', 'txt'])) {
            return back()->withErrors(['file' => 'Format file harus CSV atau Excel (.xls/.xlsx).']);
        }

        // Excel XML (template yang kita generate)
        if (in_array($extension, ['xls', 'xlsx'])) {
            $content = file_get_contents($file->getRealPath());
            if (strpos($content, 'urn:schemas-microsoft-com:office:spreadsheet') !== false
                || strpos($content, '<Workbook') !== false) {
                return $this->importExcelXml($content, true);
            }
            if (strpos($content, 'PK') === 0) {
                return back()->withErrors(['file' => 'Format .xlsx tidak didukung. Buka di Excel → Save As → CSV → upload file CSV.']);
            }
        }

        // CSV / TXT
        return $this->importCsvFile($file, true);
    }

    // Parse CSV untuk produk resep
    private function importCsvFile($file, bool $resepOnly = false)
    {
        $path    = $file->getRealPath();
        $content = file_get_contents($path);

        // Hapus BOM
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\r", "\n", $content);

        // Parse header dari baris pertama
        $firstNewline = strpos($content, "\n");
        if ($firstNewline === false) {
            return back()->withErrors(['file' => 'File CSV tidak valid.']);
        }

        $headerLine = substr($content, 0, $firstNewline);
        $rest       = substr($content, $firstNewline + 1);

        $header = array_map('trim', str_getcsv($headerLine, ',', '"'));

        // Wajib ada kolom ini
        $required = ['PABRIK', 'NAMA PRODUK', 'RETAIL'];
        $missing  = array_diff($required, $header);
        if (!empty($missing)) {
            return back()->withErrors([
                'file' => 'Kolom tidak lengkap. Kolom yang kurang: ' . implode(', ', $missing) .
                          '. Kolom yang ditemukan: ' . implode(', ', $header),
            ]);
        }

        $dataLines = array_filter(explode("\n", $rest), fn($l) => trim($l) !== '');

        return $this->processImportRows($header, array_values($dataLines), $resepOnly);
    }

    // Parse Excel XML untuk produk resep
    private function importExcelXml(string $content, bool $resepOnly = false)
    {
        libxml_use_internal_errors(true);
        $content = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $content);
        $content = preg_replace('/<\?mso-application[^?]*\?>/i', '', $content);
        $xml = simplexml_load_string($content);

        if ($xml === false) {
            return back()->withErrors(['file' => 'File Excel tidak bisa dibaca.']);
        }

        $worksheet = null;
        foreach ($xml->Worksheet as $ws) {
            $name = strtolower((string) $ws->attributes()['Name'] ?? '');
            if ($name !== 'petunjuk') {
                $worksheet = $ws;
                break;
            }
        }

        if (!$worksheet || !isset($worksheet->Table)) {
            return back()->withErrors(['file' => 'Sheet data tidak ditemukan.']);
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
            return back()->withErrors(['file' => 'File Excel kosong.']);
        }

        $header  = array_map('trim', $rows[0]);
        $missing = array_diff(['PABRIK', 'NAMA PRODUK', 'RETAIL'], $header);
        if (!empty($missing)) {
            return back()->withErrors(['file' => 'Kolom tidak lengkap: ' . implode(', ', $missing)]);
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach (array_slice($rows, 1) as $lineNum => $row) {
            $row  = array_pad($row, count($header), '');
            $data = array_map('trim', array_combine($header, $row));

            if (empty($data['NAMA PRODUK'])) { $skipped++; continue; }

            $golongan = strtoupper($data['GOLONGAN'] ?? '');
            $isResep  = ($golongan === 'KERAS');

            if ($resepOnly && !$isResep) { $skipped++; continue; }

            Medicine::create([
                'nama_obat' => $data['NAMA PRODUK'],
                'kategori'  => $data['PABRIK'] ?? '',
                'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['RETAIL'] ?? '0'),
                'stok'      => (int) preg_replace('/[^0-9]/', '', $data['STOK'] ?? '0'),
                'deskripsi' => trim(($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? ''), ' |'),
                'is_resep'  => $isResep,
            ]);
            $imported++;
        }

        $message = "Berhasil mengimpor {$imported} produk resep.";
        if ($skipped > 0) $message .= " {$skipped} baris dilewati.";

        return redirect()->route('admin.prescriptions.index')->with('success', $message);
    }

    // Proses baris CSV
    private function processImportRows(array $header, array $dataLines, bool $resepOnly = false)
    {
        $imported   = 0;
        $skipped    = 0;
        $errors     = [];
        $headerCount = count($header);

        // Join all lines first, then re-split properly to handle multiline quoted fields
        $fullContent = implode("\n", $dataLines);
        $rows = [];
        $current = '';
        $inQuote = false;

        for ($i = 0; $i < strlen($fullContent); $i++) {
            $char = $fullContent[$i];
            if ($char === '"') {
                $inQuote = !$inQuote;
                $current .= $char;
            } elseif ($char === "\n" && !$inQuote) {
                if (trim($current) !== '') {
                    $rows[] = $current;
                }
                $current = '';
            } else {
                $current .= $char;
            }
        }
        if (trim($current) !== '') {
            $rows[] = $current;
        }

        foreach ($rows as $lineNum => $line) {
            $row = str_getcsv(rtrim($line, "\r\n"), ',', '"');

            // Pad or trim to match header count
            if (count($row) < $headerCount) {
                $row = array_pad($row, $headerCount, '');
            } elseif (count($row) > $headerCount) {
                $row = array_slice($row, 0, $headerCount);
            }

            $data = array_map('trim', array_combine($header, $row));

            if (empty($data['NAMA PRODUK'])) { $skipped++; continue; }

            $golongan = strtoupper($data['GOLONGAN'] ?? '');
            $isResep  = ($golongan === 'KERAS');

            // Jika import khusus resep, skip yang BEBAS
            if ($resepOnly && !$isResep) { $skipped++; continue; }

            $harga = (float) preg_replace('/[^0-9.]/', '', $data['RETAIL'] ?? '0');
            if ($harga <= 0) {
                $errors[] = "Baris " . ($lineNum + 2) . ": RETAIL tidak valid ({$data['RETAIL']}).";
                $skipped++;
                continue;
            }

            $komposisi = $data['KOMPOSISI'] ?? '';
            $indikasi  = $data['INDIKASI'] ?? '';
            $deskripsi = $komposisi;
            if ($indikasi) {
                $deskripsi .= ' | ' . $indikasi;
            }

            Medicine::create([
                'nama_obat' => $data['NAMA PRODUK'],
                'kategori'  => $data['PABRIK'] ?? '',
                'harga'     => $harga,
                'stok'      => (int) preg_replace('/[^0-9]/', '', $data['STOK'] ?? '0'),
                'deskripsi' => $deskripsi,
                'is_resep'  => $isResep,
            ]);
            $imported++;
        }

        if ($imported === 0 && !empty($errors)) {
            return back()->withErrors(['file' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        $message = "Berhasil mengimpor {$imported} produk resep.";
        if ($skipped > 0) $message .= " {$skipped} baris dilewati (BEBAS atau kosong).";
        if (!empty($errors)) $message .= " " . count($errors) . " baris error.";

        return redirect()->route('admin.prescriptions.index')->with('success', $message);
    }

    // Download template CSV
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_produk_resep.xls"',
            'Cache-Control'       => 'max-age=0',
        ];

        $columns = ['PABRIK', 'NAMA PRODUK', 'RETAIL', 'STOK', 'KOMPOSISI', 'INDIKASI', 'GOLONGAN'];

        $contohData = [
            ['KIMIA FARMA', 'Amoxicillin 500mg',  15000, 50,  'Amoxicillin 500 mg', 'Infeksi bakteri', 'KERAS'],
            ['KALBE',       'Cefixime 200mg',      25000, 30,  'Cefixime 200 mg',   'Infeksi saluran kemih', 'KERAS'],
            ['DEXA',        'Ciprofloxacin 500mg', 20000, 100, 'Ciprofloxacin 500 mg', 'Infeksi bakteri', 'KERAS'],
        ];

        $xml = $this->buildTemplateXml($columns, $contohData);

        return response($xml, 200, $headers);
    }

    private function buildTemplateXml(array $columns, array $rows): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel">' . "\n";
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header"><Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>';
        $xml .= '<Interior ss:Color="#1d4ed8" ss:Pattern="Solid"/>';
        $xml .= '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="data"><Font ss:Size="10"/><Alignment ss:Vertical="Center"/></Style>';
        $xml .= '<Style ss:ID="number"><Font ss:Size="10"/>';
        $xml .= '<Alignment ss:Horizontal="Right" ss:Vertical="Center"/>';
        $xml .= '<NumberFormat ss:Format="0"/></Style>';
        $xml .= '</Styles>';
        $xml .= '<Worksheet ss:Name="Data Obat"><Table ss:DefaultRowHeight="20">';
        foreach ([120, 180, 80, 60, 150, 200, 80] as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>';
        }
        $xml .= '<Row ss:Height="24">';
        foreach ($columns as $col) {
            $xml .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . htmlspecialchars($col) . '</Data></Cell>';
        }
        $xml .= '</Row>';
        foreach ($rows as $row) {
            $xml .= '<Row ss:Height="20">';
            foreach ($row as $i => $val) {
                if ($i === 2 || $i === 3) {
                    $xml .= '<Cell ss:StyleID="number"><Data ss:Type="Number">' . (int)$val . '</Data></Cell>';
                } else {
                    $xml .= '<Cell ss:StyleID="data"><Data ss:Type="String">' . htmlspecialchars((string)$val) . '</Data></Cell>';
                }
            }
            $xml .= '</Row>';
        }
        $xml .= '</Table></Worksheet>';
        $xml .= '<Worksheet ss:Name="Petunjuk"><Table><Column ss:Width="500"/>';
        foreach ([
            'PETUNJUK PENGISIAN', '',
            '1. Isi data di sheet "Data Obat"',
            '2. Jangan ubah nama kolom di baris pertama',
            '3. Kolom PABRIK      : Nama perusahaan farmasi (wajib)',
            '4. Kolom NAMA PRODUK : Nama lengkap obat (wajib)',
            '5. Kolom RETAIL      : Harga retail (angka saja, tanpa Rp)',
            '6. Kolom STOK        : Jumlah stok awal (angka, boleh 0)',
            '7. Kolom KOMPOSISI   : Komposisi/kandungan obat',
            '8. Kolom INDIKASI    : Kegunaan/indikasi obat',
            '9. Kolom GOLONGAN    : BEBAS atau KERAS (wajib)',
            '', 'Simpan sebagai CSV atau langsung upload file .xls ini.',
        ] as $p) {
            $xml .= '<Row><Cell><Data ss:Type="String">' . htmlspecialchars($p) . '</Data></Cell></Row>';
        }
        $xml .= '</Table></Worksheet></Workbook>';
        return $xml;
    }
}
