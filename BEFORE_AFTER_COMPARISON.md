# Before & After Comparison - Template Update

---

## Template Columns

### BEFORE ❌
```
nama_obat | pabrik | komposisi | indikasi | golongan | retail | stok
```

### AFTER ✅
**Obat Biasa & Resep:**
```
PABRIK | NAMA PRODUK | RETAIL | KOMPOSISI | INDIKASI | GOLONGAN
```

**Produk Resep:**
```
PABRIK | NAMA PRODUK | RETAIL | KOMPOSISI | INDIKASI
```

---

## Column Order

### BEFORE ❌
1. nama_obat (Product Name)
2. pabrik (Manufacturer)
3. komposisi (Composition)
4. indikasi (Indication)
5. golongan (Classification)
6. retail (Price)
7. stok (Stock)

### AFTER ✅
1. PABRIK (Manufacturer)
2. NAMA PRODUK (Product Name)
3. RETAIL (Price)
4. KOMPOSISI (Composition)
5. INDIKASI (Indication)
6. GOLONGAN (Classification) - Obat only

---

## Example Data

### BEFORE ❌
```csv
nama_obat,pabrik,komposisi,indikasi,golongan,retail,stok
Paracetamol 500mg,KIMIA FARMA,Paracetamol 500 mg,Demam & nyeri,BEBAS,5000,100
Amoxicillin 500mg,KALBE,Amoxicillin 500 mg,Infeksi bakteri,KERAS,15000,50
Vitamin C 1000mg,SANBE,Vitamin C 1000 mg,Suplemen vitamin C,BEBAS,8000,200
```

### AFTER ✅
```csv
PABRIK,NAMA PRODUK,RETAIL,KOMPOSISI,INDIKASI,GOLONGAN
KIMIA FARMA,Paracetamol 500mg,5000,Paracetamol 500 mg,Demam & nyeri,BEBAS
KALBE,Amoxicillin 500mg,15000,Amoxicillin 500 mg,Infeksi bakteri,KERAS
SANBE,Vitamin C 1000mg,8000,Vitamin C 1000 mg,Suplemen vitamin C,BEBAS
```

---

## Column Widths

### BEFORE ❌
```
nama_obat:  180
pabrik:     120
komposisi:  150
indikasi:   200
golongan:   80
retail:     80
stok:       60
```

### AFTER ✅
**Obat Biasa & Resep:**
```
PABRIK:      120
NAMA PRODUK: 180
RETAIL:      80
KOMPOSISI:   150
INDIKASI:    200
GOLONGAN:    80
```

**Produk Resep:**
```
PABRIK:      120
NAMA PRODUK: 180
RETAIL:      80
KOMPOSISI:   150
INDIKASI:    200
```

---

## Auto-Filter Range

### BEFORE ❌
```
Obat Biasa & Resep: R1C1:R1C7 (7 columns)
Produk Resep:       R1C1:R1C6 (6 columns)
```

### AFTER ✅
```
Obat Biasa & Resep: R1C1:R1C6 (6 columns)
Produk Resep:       R1C1:R1C5 (5 columns)
```

---

## Data Mapping

### BEFORE ❌
```php
Medicine::create([
    'nama_obat' => $data['nama_obat'],
    'kategori'  => $data['pabrik'] ?? $data['kategori'] ?? '',
    'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['retail']),
    'stok'      => (int) preg_replace('/[^0-9]/', '', $data['stok']),
    'deskripsi' => $data['komposisi'] ?? '',
    'is_resep'  => $isResep,
]);
```

### AFTER ✅
```php
Medicine::create([
    'nama_obat' => $data['NAMA PRODUK'],
    'kategori'  => $data['PABRIK'] ?? '',
    'harga'     => (float) preg_replace('/[^0-9.]/', '', $data['RETAIL']),
    'stok'      => 0,
    'deskripsi' => ($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? ''),
    'is_resep'  => $isResep,
]);
```

---

## Validation Rules

### BEFORE ❌
```php
$required = ['nama_obat', 'retail', 'stok'];
$hasPabrik = in_array('pabrik', $header);
$hasKomposisi = in_array('komposisi', $header);
$hasIndikasi = in_array('indikasi', $header);
$hasGolongan = in_array('golongan', $header);

if (!is_numeric(preg_replace('/[^0-9.]/', '', $data['retail'] ?? ''))) {
    $errors[] = "Baris {$lineNum}: retail tidak valid ({$data['retail']}).";
}

if (!is_numeric(preg_replace('/[^0-9]/', '', $data['stok'] ?? ''))) {
    $errors[] = "Baris {$lineNum}: stok tidak valid ({$data['stok']}).";
}
```

### AFTER ✅
```php
$required = ['NAMA PRODUK', 'RETAIL'];
$hasPabrik = in_array('PABRIK', $header);
$hasKomposisi = in_array('KOMPOSISI', $header);
$hasIndikasi = in_array('INDIKASI', $header);
$hasGolongan = in_array('GOLONGAN', $header);

if (!is_numeric(preg_replace('/[^0-9.]/', '', $data['RETAIL'] ?? ''))) {
    $errors[] = "Baris {$lineNum}: RETAIL tidak valid ({$data['RETAIL']}).";
}

// Stok validation removed (not in template)
```

---

## Instructions (Petunjuk)

### BEFORE ❌
```
3. Kolom nama_obat  : Nama lengkap obat (wajib)
4. Kolom pabrik     : Nama perusahaan farmasi (wajib)
5. Kolom komposisi  : Komposisi/kandungan obat (wajib)
6. Kolom indikasi   : Kegunaan/indikasi obat (wajib)
7. Kolom golongan   : BEBAS atau KERAS (wajib)
8. Kolom retail     : Harga retail (angka saja, tanpa Rp)
9. Kolom stok       : Jumlah stok (angka saja)
```

### AFTER ✅
```
3. Kolom PABRIK      : Nama perusahaan farmasi (wajib)
4. Kolom NAMA PRODUK : Nama lengkap obat (wajib)
5. Kolom RETAIL      : Harga retail (angka saja, tanpa Rp)
6. Kolom KOMPOSISI   : Komposisi/kandungan obat (wajib)
7. Kolom INDIKASI    : Kegunaan/indikasi obat (wajib)
8. Kolom GOLONGAN    : BEBAS atau KERAS (wajib)
```

---

## Error Messages

### BEFORE ❌
```
"Baris 2: pabrik kosong."
"Baris 2: komposisi kosong."
"Baris 2: indikasi kosong."
"Baris 2: golongan harus BEBAS atau KERAS (ditemukan: keras)."
"Baris 2: retail tidak valid (5.000)."
"Baris 2: stok tidak valid (100)."
```

### AFTER ✅
```
"Baris 2: PABRIK kosong."
"Baris 2: KOMPOSISI kosong."
"Baris 2: INDIKASI kosong."
"Baris 2: GOLONGAN harus BEBAS atau KERAS (ditemukan: keras)."
"Baris 2: RETAIL tidak valid (5.000)."
```

---

## Stok Field

### BEFORE ❌
```
- Stok was imported from template
- Required field in CSV
- Validated as numeric
```

### AFTER ✅
```
- Stok is NOT in template
- Automatically set to 0 during import
- Not validated from CSV
```

---

## Deskripsi Field

### BEFORE ❌
```php
'deskripsi' => $data['komposisi'] ?? ''
```

Result: Only composition stored

### AFTER ✅
```php
'deskripsi' => ($data['KOMPOSISI'] ?? '') . ' | ' . ($data['INDIKASI'] ?? '')
```

Result: Composition and indication combined with " | " separator

Example:
```
KOMPOSISI: Paracetamol 500 mg
INDIKASI: Demam & nyeri
Result: Paracetamol 500 mg | Demam & nyeri
```

---

## Summary of Changes

| Aspect | Before | After |
|--------|--------|-------|
| Column Names | lowercase | UPPERCASE |
| Column Order | nama_obat first | PABRIK first |
| Stok Column | Included | Removed |
| Stok Value | From template | Auto 0 |
| Deskripsi | Komposisi only | Komposisi + Indikasi |
| Validation | 7 fields | 5-6 fields |
| Error Messages | lowercase | UPPERCASE |
| Column Count (Obat) | 7 | 6 |
| Column Count (Resep) | 6 | 5 |

---

## Migration Guide

### For Users with Old CSV Files

1. **Download new template**
   - Go to `/admin/medicines/import/template`

2. **Convert old data to new format**
   - Old: `nama_obat,pabrik,komposisi,indikasi,golongan,retail,stok`
   - New: `PABRIK,NAMA PRODUK,RETAIL,KOMPOSISI,INDIKASI,GOLONGAN`

3. **Reorder columns**
   - Move PABRIK to first position
   - Move NAMA PRODUK to second position
   - Move RETAIL to third position
   - Remove STOK column

4. **Uppercase column names**
   - Change all column names to uppercase

5. **Save as CSV**
   - File → Save As → CSV (Comma delimited)

6. **Upload new file**
   - Go to import page
   - Upload converted CSV

---

**Status:** ✅ COMPLETE  
**All Changes:** Verified and tested  
**Ready for:** Production deployment
