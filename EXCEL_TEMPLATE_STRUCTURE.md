# Excel Template Structure Guide

## Template Download URLs

### 1. Obat Biasa & Resep (Unified)
**URL:** `/admin/medicines/import/template`
**File:** `template_import_obat.xls`

### 2. Produk Resep (Prescription Products)
**URL:** `/admin/prescriptions/products/import/template`
**File:** `template_import_produk_resep.xls`

### 3. Produk Resep (Legacy)
**URL:** `/admin/prescriptions/import/template`
**File:** `template_import_produk_resep.xls`

---

## Template Structure

### Sheet 1: Data Obat / Data Produk Resep

#### For Obat Biasa & Resep (7 columns)
```
┌─────────────────┬──────────────┬──────────────┬──────────────┬──────────┬────────┬──────┐
│   nama_obat     │    pabrik    │  komposisi   │  indikasi    │ golongan │ retail │ stok │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────┼────────┼──────┤
│ Paracetamol 500 │ KIMIA FARMA  │ Paracetamol  │ Demam & nyeri│  BEBAS   │  5000  │ 100  │
│ Amoxicillin 500 │ KALBE        │ Amoxicillin  │ Infeksi      │  KERAS   │ 15000  │  50  │
│ Vitamin C 1000  │ SANBE        │ Vitamin C    │ Suplemen     │  BEBAS   │  8000  │ 200  │
└─────────────────┴──────────────┴──────────────┴──────────────┴──────────┴────────┴──────┘
```

**Column Details:**
| Column | Type | Required | Format | Example |
|--------|------|----------|--------|---------|
| nama_obat | Text | Yes | Nama lengkap | Paracetamol 500mg |
| pabrik | Text | Yes | Nama perusahaan | KIMIA FARMA |
| komposisi | Text | Yes | Kandungan obat | Paracetamol 500 mg |
| indikasi | Text | Yes | Kegunaan | Demam & nyeri |
| golongan | Text | Yes | BEBAS atau KERAS | BEBAS |
| retail | Number | Yes | Harga (angka saja) | 5000 |
| stok | Number | Yes | Jumlah (angka saja) | 100 |

---

#### For Produk Resep (6 columns)
```
┌─────────────────┬──────────────┬──────────────┬──────────────┬────────┬──────┐
│   nama_obat     │    pabrik    │  komposisi   │  indikasi    │ retail │ stok │
├─────────────────┼──────────────┼──────────────┼──────────────┼────────┼──────┤
│ Amoxicillin 500 │ KALBE        │ Amoxicillin  │ Infeksi      │ 15000  │  50  │
│ Ciprofloxacin   │ BERNOFARM    │ Ciprofloxacin│ Infeksi      │ 25000  │  30  │
│ Metformin 500   │ DEXA         │ Metformin    │ Diabetes     │ 12000  │ 100  │
└─────────────────┴──────────────┴──────────────┴──────────────┴────────┴──────┘
```

**Column Details:**
| Column | Type | Required | Format | Example |
|--------|------|----------|--------|---------|
| nama_obat | Text | Yes | Nama lengkap | Amoxicillin 500mg |
| pabrik | Text | Yes | Nama perusahaan | KALBE |
| komposisi | Text | Yes | Kandungan obat | Amoxicillin 500 mg |
| indikasi | Text | Yes | Kegunaan | Infeksi bakteri |
| retail | Number | Yes | Harga (angka saja) | 15000 |
| stok | Number | Yes | Jumlah (angka saja) | 50 |

---

### Sheet 2: Petunjuk (Instructions)

Setiap template memiliki sheet kedua bernama "Petunjuk" yang berisi:

**For Obat Biasa & Resep:**
```
PETUNJUK PENGISIAN

1. Isi data di sheet "Data Obat"
2. Jangan ubah nama kolom di baris pertama
3. Kolom nama_obat  : Nama lengkap obat (wajib)
4. Kolom pabrik     : Nama perusahaan farmasi (wajib)
5. Kolom komposisi  : Komposisi/kandungan obat (wajib)
6. Kolom indikasi   : Kegunaan/indikasi obat (wajib)
7. Kolom golongan   : BEBAS atau KERAS (wajib)
8. Kolom retail     : Harga retail (angka saja, tanpa Rp)
9. Kolom stok       : Jumlah stok (angka saja)

Setelah diisi, simpan sebagai CSV lalu upload di halaman Import.
```

**For Produk Resep:**
```
PETUNJUK PENGISIAN PRODUK RESEP

1. Isi data di sheet "Data Produk Resep"
2. Jangan ubah nama kolom di baris pertama
3. Kolom nama_obat  : Nama lengkap obat (wajib)
4. Kolom pabrik     : Nama perusahaan farmasi (wajib)
5. Kolom komposisi  : Komposisi/kandungan obat (wajib)
6. Kolom indikasi   : Kegunaan/indikasi obat (wajib)
7. Kolom retail     : Harga retail (angka saja, tanpa Rp)
8. Kolom stok       : Jumlah stok (angka saja)

CATATAN: Semua produk yang diimpor akan otomatis ditandai sebagai PRODUK RESEP

Setelah diisi, simpan sebagai CSV lalu upload di halaman Import Produk Resep.
```

---

## Styling

### Header Row
- **Background Color**: 
  - Obat Biasa & Resep: Blue (#1d4ed8)
  - Produk Resep: Red (#dc2626)
- **Text Color**: White
- **Font Weight**: Bold
- **Font Size**: 11pt
- **Height**: 24pt

### Data Rows
- **Font Size**: 10pt
- **Height**: 20pt
- **Alignment**: Vertical center

### Column Widths
**For Obat Biasa & Resep:**
- nama_obat: 180
- pabrik: 120
- komposisi: 150
- indikasi: 200
- golongan: 80
- retail: 80
- stok: 60

**For Produk Resep:**
- nama_obat: 180
- pabrik: 120
- komposisi: 150
- indikasi: 200
- retail: 80
- stok: 60

---

## How to Use

### Step 1: Download Template
Click the download button on the import page to get the template file.

### Step 2: Open in Excel
Open the downloaded `.xls` file in Microsoft Excel or compatible spreadsheet application.

### Step 3: Fill Data
- Keep the header row unchanged
- Fill data starting from row 2
- Ensure all required columns are filled
- Use the "Petunjuk" sheet as reference

### Step 4: Save as CSV
1. Go to File → Save As
2. Choose "CSV (Comma delimited)" format
3. Save the file

### Step 5: Upload
1. Go to the import page
2. Select the saved CSV file
3. Click "Import"
4. Wait for confirmation message

---

## Important Rules

1. **Header Row**: Never modify the header row (column names)
2. **Required Fields**: All columns must be filled for each row
3. **Number Format**: 
   - `retail` and `stok` must be numbers only (no Rp, commas, or symbols)
   - Example: `5000` not `Rp 5.000`
4. **Text Format**: 
   - `nama_obat`, `pabrik`, `komposisi`, `indikasi` should be text
   - No special characters that might break CSV parsing
5. **Golongan**: Only for Obat Biasa & Resep, use exactly "BEBAS" or "KERAS"
6. **Empty Rows**: Don't leave empty rows in the middle of data
7. **File Format**: Save as CSV before uploading (not XLS or XLSX)

---

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Kolom tidak lengkap" | Ensure all required columns are present in header row |
| "File CSV kosong" | Make sure you have data rows (not just header) |
| "retail tidak valid" | Remove Rp symbol and commas, use numbers only (e.g., 5000) |
| "stok tidak valid" | Use numbers only, no text or symbols |
| "golongan harus BEBAS atau KERAS" | Use exactly "BEBAS" or "KERAS" (case-sensitive) |
| "Format file tidak dikenali" | Save as CSV format before uploading |
| "File Excel tidak bisa dibaca" | Use the provided template, don't create from scratch |

---

## Example Data

### Obat Biasa & Resep Example
```csv
nama_obat,pabrik,komposisi,indikasi,golongan,retail,stok
Paracetamol 500mg,KIMIA FARMA,Paracetamol 500 mg,Demam & nyeri,BEBAS,5000,100
Amoxicillin 500mg,KALBE,Amoxicillin 500 mg,Infeksi bakteri,KERAS,15000,50
Vitamin C 1000mg,SANBE,Vitamin C 1000 mg,Suplemen vitamin C,BEBAS,8000,200
```

### Produk Resep Example
```csv
nama_obat,pabrik,komposisi,indikasi,retail,stok
Amoxicillin 500mg,KALBE,Amoxicillin 500 mg,Infeksi bakteri,15000,50
Ciprofloxacin 500mg,BERNOFARM,Ciprofloxacin 500 mg,Infeksi bakteri,25000,30
Metformin 500mg,DEXA,Metformin 500 mg,Diabetes tipe 2,12000,100
```

---

## Support

If you encounter issues:
1. Check the "Petunjuk" sheet in the template
2. Review the "Common Issues & Solutions" section above
3. Ensure your data matches the format examples
4. Contact system administrator if problems persist
