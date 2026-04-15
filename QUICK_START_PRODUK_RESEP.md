# Quick Start - Produk Resep

## 🚀 Mulai Menggunakan Produk Resep

### 1. Akses Halaman Produk Resep (Frontend)
```
URL: http://localhost:8000/produk-resep
```

**Fitur yang Tersedia:**
- ✅ Lihat daftar produk resep
- ✅ Filter berdasarkan nama obat
- ✅ Filter berdasarkan perusahaan
- ✅ Urutkan berdasarkan harga atau nama
- ✅ Pagination (12 item per halaman)
- ✅ Lihat detail produk
- ✅ Tambah ke keranjang
- ✅ Pesan via WhatsApp

### 2. Kelola Produk Resep (Admin)

#### Login Admin
```
URL: http://localhost:8000/admin/login
```

#### Tambah Produk Resep
1. Klik **Manajemen Obat** di sidebar
2. Klik tombol **Tambah Obat**
3. Isi form:
   - Nama Obat
   - Perusahaan
   - Harga
   - Stok
   - Deskripsi
   - Foto (opsional)
4. **Centang checkbox** "Produk Resep Dokter"
5. Klik **Simpan Obat**

#### Edit Produk Resep
1. Klik **Manajemen Obat** di sidebar
2. Cari obat yang ingin diedit
3. Klik tombol **Edit**
4. Ubah data sesuai kebutuhan
5. Centang/uncentang "Produk Resep Dokter" sesuai kebutuhan
6. Klik **Simpan Perubahan**

#### Hapus Produk Resep
1. Klik **Manajemen Obat** di sidebar
2. Cari obat yang ingin dihapus
3. Klik tombol **Hapus**
4. Konfirmasi penghapusan

### 3. Filter & Pencarian

#### Cari Berdasarkan Nama
```
Halaman: /produk-resep
Input: Nama obat (contoh: "Amoksisilin")
```

#### Filter Berdasarkan Perusahaan
```
Halaman: /produk-resep
Dropdown: Pilih perusahaan
```

#### Urutkan Hasil
```
Halaman: /produk-resep
Dropdown: 
  - Terbaru (default)
  - Harga Terendah
  - Harga Tertinggi
  - Nama A–Z
```

## 📊 Data Produk Resep

### Obat Resep yang Sudah Ada (dari Seeder)
1. **Amoksisilin 500mg** - Rp 12.000
   - Kategori: Antibiotik
   - Stok: 50

2. **Omeprazole 20mg** - Rp 15.000
   - Kategori: Analgesik
   - Stok: 60

3. **Cefixime 200mg** - Rp 25.000
   - Kategori: Antibiotik
   - Stok: 45

### Obat Biasa (Tidak Resep)
- Paracetamol 500mg
- Ibuprofen 400mg
- Vitamin C 1000mg
- Dan lainnya...

## 🎨 Styling & Tampilan

### Badge Produk Resep
- **Warna**: Kuning (#fef3c7)
- **Teks**: "Resep"
- **Icon**: File Prescription
- **Lokasi**: Di setiap card produk

### Badge di Admin
- **Produk Resep**: Kuning dengan icon file-prescription
- **Produk Biasa**: Biru dengan icon pills

## 🔄 Workflow Pemesanan

1. User membuka halaman `/produk-resep`
2. User melihat daftar produk resep
3. User klik "Lihat Detail" untuk melihat detail produk
4. User klik "Tambah ke Keranjang"
5. User klik "Pesan via WhatsApp"
6. Sistem membuka WhatsApp dengan pesan otomatis
7. User mengirim pesan ke admin
8. Admin memproses pesanan

## 📱 Responsive Design

Halaman produk resep sudah responsive untuk:
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (< 768px)

## 🔐 Akses Kontrol

| Halaman | Publik | Admin | Catatan |
|---------|--------|-------|---------|
| /produk-resep | ✅ | ✅ | Semua bisa akses |
| /admin/medicines | ❌ | ✅ | Hanya admin |
| /produk | ✅ | ✅ | Produk biasa |
| /promo | ✅ | ✅ | Berita/Promo |

## 🐛 Troubleshooting

### Halaman Produk Resep Tidak Muncul
**Solusi:**
1. Jalankan migration: `php artisan migrate`
2. Jalankan seeder: `php artisan db:seed --class=MedicineSeeder`
3. Clear cache: `php artisan cache:clear`

### Checkbox Produk Resep Tidak Muncul di Admin
**Solusi:**
1. Refresh halaman (Ctrl+F5)
2. Clear browser cache
3. Pastikan file `create.blade.php` dan `edit.blade.php` sudah diupdate

### Produk Resep Tidak Muncul di Halaman Frontend
**Solusi:**
1. Pastikan checkbox "Produk Resep Dokter" sudah dicentang saat menambah/edit obat
2. Verifikasi di database: `SELECT * FROM medicines WHERE is_resep = 1;`
3. Refresh halaman

## 📝 Catatan Penting

1. **Halaman Promo Tetap Ada**: Route `/promo` masih ada untuk admin
2. **Keranjang Tetap Berfungsi**: Produk resep dapat ditambahkan ke keranjang
3. **Tidak Ada Validasi Resep**: Sistem masih menggunakan WhatsApp, tidak ada validasi resep dokter
4. **Responsive**: Halaman sudah mobile-friendly

## 🎯 Next Steps (Opsional)

Fitur yang bisa ditambahkan di masa depan:
- [ ] Validasi resep dokter sebelum pembelian
- [ ] Upload resep dokter saat pemesanan
- [ ] Notifikasi email untuk pesanan produk resep
- [ ] Laporan penjualan produk resep
- [ ] Integrasi dengan sistem farmasi
- [ ] Verifikasi apoteker

## 📞 Support

Jika ada pertanyaan atau masalah, hubungi admin melalui:
- WhatsApp: 0858-9000-7359
- Email: admin@medikpedia.com
