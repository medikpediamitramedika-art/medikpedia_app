# Developer Quick Reference - Import System

## Column Mapping Cheat Sheet

### Excel Template → Database Mapping

```
EXCEL COLUMN    →    DATABASE FIELD    →    NOTES
─────────────────────────────────────────────────────
nama_obat       →    nama_obat         →    Product name
pabrik          →    kategori          →    Manufacturer
komposisi       →    deskripsi         →    Composition
indikasi        →    (in deskripsi)    →    Usage/indication
retail          →    harga             →    Price
stok            →    stok              →    Stock quantity
golongan        →    is_resep          →    KERAS=true, BEBAS=false (Obat only)
(auto)          →    is_resep          →    true (Produk Resep only)
```

---

## Controller Methods Reference

### AdminMedicineImportController
**Purpose:** Import both Obat Biasa (BEBAS) and Obat Resep (KERAS)

**Template Columns:** `nama_obat | pabrik | komposisi | indikasi | golongan | retail | stok`

**Key Methods:**
- `downloadTemplate()` - Returns Excel template with 7 columns
- `import()` - Main entry point for file upload
- `importCsv()` - Handles CSV files
- `importExcel()` - Handles XLS/XLSX files
- `processRows()` - Maps Excel columns to database fields
- `validateRow()` - Validates each row of data

**Special Logic:**
```php
$golongan = strtoupper($data['golongan'] ?? 'BEBAS');
$isResep = ($golongan === 'KERAS');  // Determines is_resep flag
```

---

### AdminPrescriptionImportController
**Purpose:** Import Produk Resep (prescription products)

**Template Columns:** `nama_obat | pabrik | komposisi | indikasi | retail | stok`

**Key Methods:**
- `downloadTemplate()` - Returns Excel template with 6 columns
- `import()` - Main entry point for file upload
- `importCsv()` - Handles CSV files
- `importExcel()` - Handles XLS/XLSX files
- `processRows()` - Maps Excel columns to database fields
- `validateRow()` - Validates each row of data

**Special Logic:**
```php
'is_resep' => true,  // Always true for prescription products
```

---

### AdminPrescriptionProductImportController
**Purpose:** Import Produk Resep Khusus (specialized prescription products)

**Template Columns:** `nama_obat | pabrik | komposisi | indikasi | retail | stok`

**Key Methods:** Same as AdminPrescriptionImportController

**Special Logic:**
```php
'is_resep' => true,  // Always true for prescription products
```

---

## Code Patterns

### Correct Field Mapping Pattern
```php
Medicine::create([
    'nama_obat' => $data['nama_obat'],
    'kategori'  => $data['pabrik'] ?? $data['kategori'] ?? '',
    'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['retail']),
    'stok'      => (int) preg_replace('/[^0-9]/', '', $data['stok']),
    'deskripsi' => $data['komposisi'] ?? '',
    'is_resep'  => true,  // or based on golongan
]);
```

### Correct Validation Pattern
```php
private function validateRow(array $data, int $lineNum): array
{
    $errors = [];
    
    // Check required fields
    if (empty($data['nama_obat'])) {
        $errors[] = "Baris {$lineNum}: nama_obat kosong.";
    }
    
    // Check numeric fields
    if (!is_numeric(preg_replace('/[^0-9.]/', '', $data['retail'] ?? ''))) {
        $errors[] = "Baris {$lineNum}: retail tidak valid.";
    }
    
    return $errors;
}
```

---

## Common Mistakes to Avoid

❌ **WRONG:**
```php
'kategori' => $data['perusahaan'] ?? '',  // Field doesn't exist in template
'harga'    => $data['harga'],             // Should be $data['retail']
'deskripsi' => $data['deskripsi'],        // Should be $data['komposisi']
```

✅ **CORRECT:**
```php
'kategori' => $data['pabrik'] ?? '',      // Matches template column
'harga'    => $data['retail'],            // Matches template column
'deskripsi' => $data['komposisi'] ?? '',  // Matches template column
```

---

## File Format Support

| Format | Support | Notes |
|--------|---------|-------|
| CSV | ✅ Yes | Recommended format |
| XLS | ✅ Yes | Excel XML (SpreadsheetML) |
| XLSX | ⚠️ Limited | Must save as CSV first |
| TXT | ✅ Yes | If comma-delimited |

---

## Error Messages

### Validation Errors
```
"Baris {lineNum}: {field} kosong."
"Baris {lineNum}: {field} tidak valid ({value})."
"Baris {lineNum}: golongan harus BEBAS atau KERAS."
```

### File Errors
```
"File CSV kosong atau hanya berisi header."
"Kolom tidak lengkap. Kolom yang kurang: {columns}"
"Format file harus CSV atau Excel (.xls/.xlsx)."
"File Excel tidak bisa dibaca. Pastikan file tidak rusak."
```

---

## Database Schema

### medicines table
```sql
CREATE TABLE medicines (
    id BIGINT PRIMARY KEY,
    nama_obat VARCHAR(255) NOT NULL,
    kategori VARCHAR(255),
    harga DECIMAL(10, 2),
    stok INT,
    deskripsi TEXT,
    gambar VARCHAR(255),
    is_resep BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Key Fields for Import
- `nama_obat` - Product name (required)
- `kategori` - Manufacturer/company (required)
- `harga` - Price (required, numeric)
- `stok` - Stock quantity (required, numeric)
- `deskripsi` - Description/composition (required)
- `is_resep` - Prescription flag (boolean)

---

## Routes

### Medicine Import Routes
```
GET  /admin/medicines/import/template          → Download template
GET  /admin/medicines/import                   → Show import form
POST /admin/medicines/import                   → Process import
```

### Prescription Import Routes
```
GET  /admin/prescriptions/import/template      → Download template
GET  /admin/prescriptions/import               → Show import form
POST /admin/prescriptions/import               → Process import
```

### Prescription Product Import Routes
```
GET  /admin/prescriptions/products/import/template  → Download template
GET  /admin/prescriptions/products/import           → Show import form
POST /admin/prescriptions/products/import           → Process import
```

---

## Testing Commands

### Test Import Functionality
```bash
# Download template
curl http://localhost/admin/medicines/import/template -o template.xls

# Test with sample CSV
php artisan tinker
>>> $file = new \Illuminate\Http\UploadedFile('path/to/file.csv', 'file.csv');
>>> app(AdminMedicineImportController::class)->import(new Request(['file' => $file]));
```

### Check Database
```bash
php artisan tinker
>>> App\Models\Medicine::where('is_resep', true)->count()
>>> App\Models\Medicine::where('is_resep', false)->count()
```

---

## Performance Notes

- **Max File Size:** 2MB
- **Batch Processing:** Rows processed one at a time
- **Error Handling:** Stops on first validation error per row
- **Database:** Uses `create()` method (no bulk insert)

### Optimization Tips
- For large imports (>1000 rows), consider using `insert()` instead of `create()`
- Add progress tracking for user feedback
- Consider async processing for very large files

---

## Debugging Tips

### Enable Debug Mode
```php
// In controller
dd($data);  // Dump and die to see data structure
```

### Check Column Names
```php
// Verify header row
dd($header);  // Should contain: ['nama_obat', 'pabrik', 'komposisi', ...]
```

### Validate Mapping
```php
// Check if field exists in data array
if (!isset($data['pabrik'])) {
    dd('pabrik field missing!');
}
```

### Test CSV Parsing
```php
$line = "Paracetamol,KIMIA FARMA,Paracetamol 500mg,Demam,5000,100";
$row = str_getcsv($line, ',', '"');
dd($row);  // Should be array with 6 elements
```

---

## Related Documentation

- `IMPORT_SYSTEM_DOCUMENTATION.md` - Full system documentation
- `EXCEL_TEMPLATE_STRUCTURE.md` - Template structure and styling
- `IMPORT_COLUMN_REFERENCE.md` - Column reference guide
- `COLUMN_MAPPING_FIX_SUMMARY.md` - Fix details
- `CHANGES_APPLIED.md` - Code changes

---

## Quick Links

- **Template Download:** `/admin/medicines/import/template`
- **Import Form:** `/admin/medicines/import`
- **Database:** `medicines` table
- **Model:** `App\Models\Medicine`
- **Controllers:** `app/Http/Controllers/Admin*ImportController.php`

---

**Last Updated:** April 15, 2026  
**Version:** 1.0  
**Status:** ✅ Current
