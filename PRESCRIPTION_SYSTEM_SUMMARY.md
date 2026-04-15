# Sistem Produk Resep - Ringkasan Perubahan

## Deskripsi
Halaman "Produk Promo" telah diubah menjadi halaman "Produk Resep" dengan kontrol dan tampilan yang sama dengan halaman produk biasa.

## Perubahan yang Dilakukan

### 1. Database
- **Migration Baru**: `2026_04_15_000000_add_is_resep_to_medicines_table.php`
  - Menambahkan kolom `is_resep` (boolean, default: false) ke tabel `medicines`

### 2. Model
- **app/Models/Medicine.php**
  - Menambahkan `is_resep` ke `$fillable`
  - Menambahkan `is_resep` ke `$casts` sebagai boolean

### 3. Controller
- **app/Http/Controllers/PrescriptionController.php** (BARU)
  - Menampilkan hanya obat dengan `is_resep = true`
  - Filter: search, perusahaan, sort (sama seperti ProductController)
  - Pagination: 12 item per halaman

- **app/Http/Controllers/AdminMedicineController.php**
  - Update `store()` method: handle checkbox `is_resep`
  - Update `update()` method: handle checkbox `is_resep`

### 4. View Frontend
- **resources/views/prescriptions.blade.php** (BARU)
  - Copy dari `products.blade.php` dengan modifikasi:
  - Title: "Produk Resep - Medikpedia"
  - Header: Icon file-prescription, prescription-bottle-medical, stethoscope
  - Filter: Cari Produk Resep, Perusahaan, Urutkan
  - Card: Menampilkan badge "Resep" berwarna kuning
  - Route: `/produk-resep`

### 5. View Admin
- **resources/views/admin/medicines/create.blade.php**
  - Tambah checkbox "Produk Resep Dokter" dengan styling kuning
  - Penjelasan: "Centang jika obat ini memerlukan resep dokter untuk pembelian"

- **resources/views/admin/medicines/edit.blade.php**
  - Tambah checkbox "Produk Resep Dokter" dengan styling kuning
  - Penjelasan: "Centang jika obat ini memerlukan resep dokter untuk pembelian"

- **resources/views/admin/medicines/index.blade.php**
  - Tambah kolom "Tipe" di tabel
  - Menampilkan badge "Resep" (kuning) atau "Biasa" (biru)

### 6. Routes
- **routes/web.php**
  - Tambah route: `GET /produk-resep` → `PrescriptionController@index` (name: `prescriptions`)
  - Route promo tetap ada untuk admin: `GET /promo` → `NewsController@index`

### 7. Navigation
- **resources/views/layouts/frontend.blade.php**
  - Ubah navbar: "Produk Promo" → "Produk Resep"
  - Icon: `fa-tag` → `fa-file-prescription`
  - Route: `route('news.index')` → `route('prescriptions')`

### 8. Seeder
- **database/seeders/MedicineSeeder.php**
  - Tambah field `is_resep` untuk setiap obat
  - Obat dengan `is_resep = true`:
    - Amoksisilin 500mg
    - Omeprazole 20mg
    - Cefixime 200mg

## Fitur Produk Resep

### Frontend
- ✅ Halaman listing dengan filter (search, perusahaan, sort)
- ✅ Pagination (12 item per halaman)
- ✅ Badge "Resep" pada setiap card
- ✅ Tombol "Lihat Detail" untuk melihat detail obat
- ✅ Tombol "Tambah ke Keranjang" (jika stok tersedia)
- ✅ Responsive design (mobile-friendly)

### Admin
- ✅ Checkbox untuk menandai obat sebagai "Produk Resep"
- ✅ Kolom "Tipe" di tabel listing (Resep/Biasa)
- ✅ Filter dan search tetap berfungsi
- ✅ Import Excel tetap berfungsi

## Kontrol Akses
- Halaman produk resep dapat diakses oleh semua user (publik)
- Admin dapat mengelola obat resep melalui admin panel
- Tidak ada pembatasan pembelian (sistem masih menggunakan WhatsApp)

## Testing
Untuk test sistem:

1. **Lihat Produk Resep**
   ```
   http://localhost:8000/produk-resep
   ```

2. **Admin - Tambah Obat Resep**
   - Login ke admin panel
   - Manajemen Obat → Tambah Obat
   - Centang "Produk Resep Dokter"
   - Simpan

3. **Admin - Edit Obat Resep**
   - Manajemen Obat → Edit
   - Centang/uncentang "Produk Resep Dokter"
   - Simpan

4. **Filter Produk Resep**
   - Cari berdasarkan nama
   - Filter berdasarkan perusahaan
   - Urutkan berdasarkan harga/nama

## Catatan
- Halaman "Produk Promo" (News) tetap ada untuk admin
- Sistem keranjang dan WhatsApp tetap berfungsi untuk produk resep
- Tidak ada validasi khusus untuk pembelian produk resep (bisa ditambahkan di masa depan)
