# Sistem Import Excel & CRUD untuk Obat Biasa dan Resep

## Ringkasan Implementasi

Sistem ini memungkinkan import data obat dari file Excel/CSV dengan kolom yang sesuai dengan format data yang disediakan. Sistem mendukung dua tipe produk:
- **Obat Biasa** (BEBAS) - `is_resep = false`
- **Obat Resep** (KERAS) - `is_resep = true`

---

## Struktur Kolom Excel/CSV

### Template Obat Biasa & Resep (Unified)

| Kolom | Tipe | Wajib | Keterangan |
|-------|------|-------|-----------|
| `nama_obat` | String | ✓ | Nama lengkap obat |
| `pabrik` | String | ✓ | Nama perusahaan farmasi |
| `komposisi` | String | ✓ | Kandungan/komposisi obat |
| `indikasi` | String | ✓ | Kegunaan/indikasi obat |
| `golongan` | String | ✓ | BEBAS atau KERAS (untuk obat biasa) |
| `retail` | Numeric | ✓ | Harga retail (angka saja) |
| `stok` | Integer | ✓ | Jumlah stok |

### Template Produk Resep (Khusus)

| Kolom | Tipe | Wajib | Keterangan |
|-------|------|-------|-----------|
| `nama_obat` | String | ✓ | Nama lengkap obat |
| `pabrik` | String | ✓ | Nama perusahaan farmasi |
| `komposisi` | String | ✓ | Kandungan/komposisi obat |
| `indikasi` | String | ✓ | Kegunaan/indikasi obat |
| `retail` | Numeric | ✓ | Harga retail (angka saja) |
| `stok` | Integer | ✓ | Jumlah stok |

**Catatan:** Semua produk yang diimpor via template produk resep otomatis ditandai sebagai `is_resep = true`

---

## File-File yang Dimodifikasi/Dibuat

### 1. **AdminMedicineImportController.php** (DIMODIFIKASI)
- **Lokasi:** `app/Http/Controllers/AdminMedicineImportController.php`
- **Perubahan:**
  - Template Excel diperbarui dengan kolom: `nama_obat`, `pabrik`, `komposisi`, `indikasi`, `golongan`, `retail`, `stok`
  - Validasi kolom `golongan` (harus BEBAS atau KERAS)
  - Otomatis set `is_resep = true` jika golongan = KERAS
  - Mapping kolom: `retail` → `harga`, `komposisi` → `deskripsi`

### 2. **AdminMedicineController.php** (DIMODIFIKASI)
- **Lokasi:** `app/Http/Controllers/AdminMedicineController.php`
- **Perubahan:**
  - Tambah filter `tipe` di method `index()` untuk filter obat biasa/resep
  - Query: `where('is_resep', false)` untuk obat biasa, `where('is_resep', true)` untuk resep

### 3. **AdminPrescriptionProductController.php** (BARU)
- **Lokasi:** `app/Http/Controllers/AdminPrescriptionProductController.php`
- **Fungsi:**
  - CRUD lengkap untuk produk resep
  - Hanya menampilkan produk dengan `is_resep = true`
  - Validasi otomatis set `is_resep = true` saat create/update

### 4. **AdminPrescriptionProductImportController.php** (BARU)
- **Lokasi:** `app/Http/Controllers/AdminPrescriptionProductImportController.php`
- **Fungsi:**
  - Import khusus untuk produk resep
  - Template dengan header merah (berbeda dari obat biasa)
  - Semua data otomatis ditandai `is_resep = true`
  - Validasi kolom: `nama_obat`, `pabrik`, `komposisi`, `indikasi`, `retail`, `stok`

---

## Cara Menggunakan

### A. Import Obat Biasa & Resep (Unified)

1. **Download Template:**
   - Klik "Download Template" di halaman Import Obat
   - File: `template_import_obat.xls`

2. **Isi Data:**
   - Sheet "Data Obat" - isi kolom sesuai format
   - Kolom `golongan`: pilih BEBAS atau KERAS
   - Jika KERAS → otomatis `is_resep = true`

3. **Simpan & Upload:**
   - Simpan sebagai CSV (File → Save As → CSV)
   - Upload file di halaman Import Obat

4. **Hasil:**
   - Obat dengan golongan BEBAS → `is_resep = false`
   - Obat dengan golongan KERAS → `is_resep = true`

### B. Import Produk Resep (Khusus)

1. **Download Template:**
   - Klik "Download Template" di halaman Import Produk Resep
   - File: `template_import_produk_resep.xls`

2. **Isi Data:**
   - Sheet "Data Produk Resep" - isi kolom sesuai format
   - Tidak ada kolom `golongan` (semua otomatis resep)

3. **Simpan & Upload:**
   - Simpan sebagai CSV
   - Upload file di halaman Import Produk Resep

4. **Hasil:**
   - Semua produk otomatis `is_resep = true`

---

## Validasi Data

### Validasi Kolom Wajib
- `nama_obat`: tidak boleh kosong
- `pabrik`: tidak boleh kosong
- `komposisi`: tidak boleh kosong
- `indikasi`: tidak boleh kosong
- `retail`: harus angka
- `stok`: harus angka

### Validasi Khusus
- **Golongan** (obat biasa): harus BEBAS atau KERAS
- **Format Harga**: angka saja, tanpa Rp atau titik pemisah
- **Format Stok**: angka saja

### Error Handling
- Baris dengan error ditampilkan dengan nomor baris
- Maksimal 5 error ditampilkan per upload
- Baris kosong otomatis dilewati (tidak dihitung error)

---

## Mapping Database

### Tabel `medicines`

```sql
CREATE TABLE medicines (
    id BIGINT PRIMARY KEY,
    nama_obat VARCHAR(255),
    kategori VARCHAR(255),        -- Pabrik/Perusahaan
    harga DECIMAL(12,2),          -- Dari kolom 'retail'
    stok INTEGER,
    deskripsi TEXT,               -- Dari kolom 'komposisi'
    gambar VARCHAR(255) NULLABLE,
    is_resep BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Mapping Kolom Excel → Database

| Excel | Database | Keterangan |
|-------|----------|-----------|
| `nama_obat` | `nama_obat` | Langsung |
| `pabrik` | `kategori` | Nama perusahaan |
| `komposisi` | `deskripsi` | Kandungan obat |
| `retail` | `harga` | Harga retail |
| `stok` | `stok` | Jumlah stok |
| `golongan` | `is_resep` | KERAS=true, BEBAS=false |
| `indikasi` | - | Disimpan di `deskripsi` (opsional) |

---

## Routes yang Diperlukan

Tambahkan routes berikut di `routes/web.php`:

```php
// Obat Biasa & Resep (Unified)
Route::prefix('admin/medicines')->middleware('auth', 'admin')->group(function () {
    Route::get('/', [AdminMedicineController::class, 'index'])->name('admin.medicines.index');
    Route::get('/create', [AdminMedicineController::class, 'create'])->name('admin.medicines.create');
    Route::post('/', [AdminMedicineController::class, 'store'])->name('admin.medicines.store');
    Route::get('/{medicine}/edit', [AdminMedicineController::class, 'edit'])->name('admin.medicines.edit');
    Route::put('/{medicine}', [AdminMedicineController::class, 'update'])->name('admin.medicines.update');
    Route::delete('/{medicine}', [AdminMedicineController::class, 'destroy'])->name('admin.medicines.destroy');
    Route::post('/{medicine}/stock', [AdminMedicineController::class, 'updateStock'])->name('admin.medicines.updateStock');
    
    // Import
    Route::get('/import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('admin.medicines.import.template');
    Route::get('/import', [AdminMedicineImportController::class, 'showImportForm'])->name('admin.medicines.import.form');
    Route::post('/import', [AdminMedicineImportController::class, 'import'])->name('admin.medicines.import');
});

// Produk Resep (Khusus)
Route::prefix('admin/prescriptions/products')->middleware('auth', 'admin')->group(function () {
    Route::get('/', [AdminPrescriptionProductController::class, 'index'])->name('admin.prescriptions.products.index');
    Route::get('/create', [AdminPrescriptionProductController::class, 'create'])->name('admin.prescriptions.products.create');
    Route::post('/', [AdminPrescriptionProductController::class, 'store'])->name('admin.prescriptions.products.store');
    Route::get('/{product}/edit', [AdminPrescriptionProductController::class, 'edit'])->name('admin.prescriptions.products.edit');
    Route::put('/{product}', [AdminPrescriptionProductController::class, 'update'])->name('admin.prescriptions.products.update');
    Route::delete('/{product}', [AdminPrescriptionProductController::class, 'destroy'])->name('admin.prescriptions.products.destroy');
    Route::post('/{product}/stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('admin.prescriptions.products.updateStock');
    
    // Import
    Route::get('/import/template', [AdminPrescriptionProductImportController::class, 'downloadTemplate'])->name('admin.prescriptions.products.import.template');
    Route::get('/import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('admin.prescriptions.products.import.form');
    Route::post('/import', [AdminPrescriptionProductImportController::class, 'import'])->name('admin.prescriptions.products.import');
});
```

---

## Fitur-Fitur

### ✓ Import Excel/CSV
- Support format `.xls`, `.xlsx`, `.csv`
- Template dengan contoh data
- Validasi kolom otomatis
- Error reporting per baris

### ✓ CRUD Lengkap
- Create, Read, Update, Delete
- Filter berdasarkan tipe (biasa/resep)
- Search & pagination
- Upload gambar

### ✓ Validasi Data
- Kolom wajib
- Format data (angka, teks)
- Golongan (BEBAS/KERAS)
- Duplikasi (opsional)

### ✓ Pemisahan Tipe Produk
- Obat Biasa: `is_resep = false`
- Obat Resep: `is_resep = true`
- Filter otomatis di halaman masing-masing

---

## Contoh Data Excel

### Obat Biasa & Resep

```
nama_obat              | pabrik      | komposisi           | indikasi              | golongan | retail | stok
Paracetamol 500mg      | KIMIA FARMA | Paracetamol 500 mg  | Demam & nyeri         | BEBAS    | 5000   | 100
Amoxicillin 500mg      | KALBE       | Amoxicillin 500 mg  | Infeksi bakteri       | KERAS    | 15000  | 50
Vitamin C 1000mg       | SANBE       | Vitamin C 1000 mg   | Suplemen vitamin C    | BEBAS    | 8000   | 200
```

### Produk Resep

```
nama_obat              | pabrik      | komposisi           | indikasi              | retail | stok
Amoxicillin 500mg      | KALBE       | Amoxicillin 500 mg  | Infeksi bakteri       | 15000  | 50
Ciprofloxacin 500mg    | BERNOFARM   | Ciprofloxacin 500mg | Infeksi bakteri       | 25000  | 30
Metformin 500mg        | DEXA        | Metformin 500 mg    | Diabetes tipe 2       | 12000  | 100
```

---

## Tips & Trik

1. **Simpan sebagai CSV:**
   - Buka file Excel → File → Save As
   - Pilih format "CSV (Comma delimited)"
   - Upload file CSV

2. **Format Harga:**
   - Gunakan angka saja: `5000` bukan `Rp 5.000`
   - Sistem otomatis parse format

3. **Kolom Opsional:**
   - `indikasi` bisa disimpan di `deskripsi` untuk referensi
   - Gambar bisa diupload manual setelah import

4. **Bulk Update:**
   - Import ulang dengan data yang sama untuk update
   - Sistem akan create record baru (tidak update existing)

---

## Troubleshooting

### Error: "Kolom tidak lengkap"
- Pastikan semua kolom wajib ada di file
- Periksa nama kolom (case-sensitive)

### Error: "Format file tidak dikenali"
- Gunakan template yang disediakan
- Simpan sebagai CSV sebelum upload

### Error: "Harga tidak valid"
- Gunakan angka saja, tanpa Rp atau titik
- Contoh: `5000` bukan `Rp 5.000`

### Error: "Golongan harus BEBAS atau KERAS"
- Periksa spelling: BEBAS atau KERAS (huruf besar)
- Jangan ada spasi tambahan

---

## Catatan Penting

1. **Backup Data:** Selalu backup database sebelum import besar-besaran
2. **Validasi Manual:** Periksa beberapa data setelah import
3. **Duplikasi:** Sistem tidak mencegah duplikasi nama obat
4. **Update:** Untuk update data existing, gunakan form edit manual
5. **Gambar:** Upload gambar dilakukan manual setelah import

---

## Kontak & Support

Untuk pertanyaan atau masalah, hubungi tim development.
