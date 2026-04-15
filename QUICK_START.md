# Quick Start Guide - Sistem Import Excel & CRUD

## 🚀 Mulai dalam 5 Menit

### Step 1: Setup Routes (1 menit)

Buka `routes/web.php` dan tambahkan di bagian akhir:

```php
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\AdminPrescriptionProductController;
use App\Http\Controllers\AdminPrescriptionProductImportController;

// Obat Biasa & Resep
Route::middleware(['auth', 'admin'])->prefix('admin/medicines')->group(function () {
    Route::get('/', [AdminMedicineController::class, 'index'])->name('admin.medicines.index');
    Route::get('/create', [AdminMedicineController::class, 'create'])->name('admin.medicines.create');
    Route::post('/', [AdminMedicineController::class, 'store'])->name('admin.medicines.store');
    Route::get('/{medicine}/edit', [AdminMedicineController::class, 'edit'])->name('admin.medicines.edit');
    Route::put('/{medicine}', [AdminMedicineController::class, 'update'])->name('admin.medicines.update');
    Route::delete('/{medicine}', [AdminMedicineController::class, 'destroy'])->name('admin.medicines.destroy');
    Route::post('/{medicine}/stock', [AdminMedicineController::class, 'updateStock'])->name('admin.medicines.updateStock');
    Route::get('/import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('admin.medicines.import.template');
    Route::get('/import', [AdminMedicineImportController::class, 'showImportForm'])->name('admin.medicines.import.form');
    Route::post('/import', [AdminMedicineImportController::class, 'import'])->name('admin.medicines.import');
});

// Produk Resep
Route::middleware(['auth', 'admin'])->prefix('admin/prescriptions/products')->group(function () {
    Route::get('/', [AdminPrescriptionProductController::class, 'index'])->name('admin.prescriptions.products.index');
    Route::get('/create', [AdminPrescriptionProductController::class, 'create'])->name('admin.prescriptions.products.create');
    Route::post('/', [AdminPrescriptionProductController::class, 'store'])->name('admin.prescriptions.products.store');
    Route::get('/{product}/edit', [AdminPrescriptionProductController::class, 'edit'])->name('admin.prescriptions.products.edit');
    Route::put('/{product}', [AdminPrescriptionProductController::class, 'update'])->name('admin.prescriptions.products.update');
    Route::delete('/{product}', [AdminPrescriptionProductController::class, 'destroy'])->name('admin.prescriptions.products.destroy');
    Route::post('/{product}/stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('admin.prescriptions.products.updateStock');
    Route::get('/import/template', [AdminPrescriptionProductImportController::class, 'downloadTemplate'])->name('admin.prescriptions.products.import.template');
    Route::get('/import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('admin.prescriptions.products.import.form');
    Route::post('/import', [AdminPrescriptionProductImportController::class, 'import'])->name('admin.prescriptions.products.import');
});
```

### Step 2: Verifikasi File (1 menit)

Pastikan file-file ini sudah ada:

```
✓ app/Http/Controllers/AdminMedicineController.php (DIMODIFIKASI)
✓ app/Http/Controllers/AdminMedicineImportController.php (DIMODIFIKASI)
✓ app/Http/Controllers/AdminPrescriptionProductController.php (BARU)
✓ app/Http/Controllers/AdminPrescriptionProductImportController.php (BARU)
✓ app/Models/Medicine.php (dengan kolom is_resep)
```

### Step 3: Test Routes (1 menit)

Buka browser dan test:

```
http://localhost:8000/admin/medicines
http://localhost:8000/admin/medicines/import
http://localhost:8000/admin/prescriptions/products
http://localhost:8000/admin/prescriptions/products/import
```

### Step 4: Download Template (1 menit)

```
http://localhost:8000/admin/medicines/import/template
http://localhost:8000/admin/prescriptions/products/import/template
```

### Step 5: Import Data (1 menit)

1. Download template
2. Isi data di Excel
3. Simpan sebagai CSV
4. Upload di halaman import

---

## 📊 Struktur Data

### Obat Biasa & Resep

| Kolom | Contoh | Tipe |
|-------|--------|------|
| nama_obat | Paracetamol 500mg | String |
| pabrik | KIMIA FARMA | String |
| komposisi | Paracetamol 500 mg | String |
| indikasi | Demam & nyeri | String |
| golongan | BEBAS atau KERAS | String |
| retail | 5000 | Number |
| stok | 100 | Number |

**Hasil:**
- BEBAS → `is_resep = false`
- KERAS → `is_resep = true`

### Produk Resep

| Kolom | Contoh | Tipe |
|-------|--------|------|
| nama_obat | Amoxicillin 500mg | String |
| pabrik | KALBE | String |
| komposisi | Amoxicillin 500 mg | String |
| indikasi | Infeksi bakteri | String |
| retail | 15000 | Number |
| stok | 50 | Number |

**Hasil:** Semua otomatis `is_resep = true`

---

## 🎯 Workflow

### Import Obat Biasa & Resep

```
1. Klik "Download Template" (admin/medicines/import/template)
   ↓
2. Isi data di Excel
   - Kolom golongan: BEBAS atau KERAS
   ↓
3. Simpan sebagai CSV
   - File → Save As → CSV (Comma delimited)
   ↓
4. Upload di halaman import (admin/medicines/import)
   ↓
5. Selesai!
   - BEBAS → obat biasa (is_resep = false)
   - KERAS → obat resep (is_resep = true)
```

### Import Produk Resep

```
1. Klik "Download Template" (admin/prescriptions/products/import/template)
   ↓
2. Isi data di Excel
   - Tidak ada kolom golongan
   ↓
3. Simpan sebagai CSV
   ↓
4. Upload di halaman import (admin/prescriptions/products/import)
   ↓
5. Selesai!
   - Semua otomatis produk resep (is_resep = true)
```

---

## 📝 Contoh Data

### Obat Biasa & Resep (Copy-Paste ke Excel)

```
nama_obat	pabrik	komposisi	indikasi	golongan	retail	stok
Paracetamol 500mg	KIMIA FARMA	Paracetamol 500 mg	Demam & nyeri	BEBAS	5000	100
Amoxicillin 500mg	KALBE	Amoxicillin 500 mg	Infeksi bakteri	KERAS	15000	50
Vitamin C 1000mg	SANBE	Vitamin C 1000 mg	Suplemen vitamin C	BEBAS	8000	200
Metformin 500mg	DEXA	Metformin 500 mg	Diabetes tipe 2	KERAS	12000	100
```

### Produk Resep (Copy-Paste ke Excel)

```
nama_obat	pabrik	komposisi	indikasi	retail	stok
Amoxicillin 500mg	KALBE	Amoxicillin 500 mg	Infeksi bakteri	15000	50
Ciprofloxacin 500mg	BERNOFARM	Ciprofloxacin 500 mg	Infeksi bakteri	25000	30
Metformin 500mg	DEXA	Metformin 500 mg	Diabetes tipe 2	12000	100
```

---

## ⚠️ Penting!

### Format Harga
```
✓ Benar: 5000
✗ Salah: Rp 5.000
✗ Salah: 5.000,00
```

### Golongan
```
✓ Benar: BEBAS atau KERAS
✗ Salah: bebas atau keras
✗ Salah: Bebas atau Keras
```

### Simpan sebagai CSV
```
1. Buka file di Excel
2. File → Save As
3. Pilih "CSV (Comma delimited)"
4. Klik Save
5. Upload file CSV
```

---

## 🔍 Validasi

Sistem akan otomatis validasi:

- ✓ Kolom wajib ada
- ✓ Harga adalah angka
- ✓ Stok adalah angka
- ✓ Golongan BEBAS atau KERAS
- ✓ Tidak ada kolom kosong

Jika ada error:
- Baris dengan error ditampilkan
- Baris error dilewati (tidak diimpor)
- Baris valid tetap diimpor

---

## 🎨 Fitur Tambahan

### Filter Obat Biasa/Resep

Di halaman list obat, ada filter:
```
- Semua (tampilkan semua)
- Biasa (hanya is_resep = false)
- Resep (hanya is_resep = true)
```

### CRUD Lengkap

Untuk setiap tipe:
- ✓ Create (tambah baru)
- ✓ Read (lihat list)
- ✓ Update (edit)
- ✓ Delete (hapus)
- ✓ Update Stok (update stok saja)

### Upload Gambar

Saat create/edit:
- Upload gambar obat
- Format: JPG, PNG, GIF
- Ukuran max: 10MB

---

## 🆘 Troubleshooting

### Error: "Kolom tidak lengkap"
**Solusi:** Pastikan semua kolom wajib ada di file

### Error: "Harga tidak valid"
**Solusi:** Gunakan angka saja (5000, bukan Rp 5.000)

### Error: "Golongan harus BEBAS atau KERAS"
**Solusi:** Periksa spelling (case-sensitive)

### File tidak terupload
**Solusi:** Simpan sebagai CSV sebelum upload

---

## 📚 Dokumentasi Lengkap

Untuk dokumentasi lebih lengkap, lihat:
- `IMPORT_SYSTEM_DOCUMENTATION.md` - Dokumentasi lengkap
- `ROUTES_SETUP.md` - Daftar routes
- `IMPLEMENTATION_SUMMARY.md` - Ringkasan implementasi

---

## ✅ Checklist

- [ ] Routes sudah ditambahkan
- [ ] File controllers sudah ada
- [ ] Model Medicine sudah update
- [ ] Migration sudah run
- [ ] Test routes di browser
- [ ] Download template
- [ ] Isi data contoh
- [ ] Simpan sebagai CSV
- [ ] Upload file
- [ ] Verifikasi data di database

---

**Selesai! 🎉**

Sistem sudah siap digunakan. Mulai import data obat Anda sekarang!
