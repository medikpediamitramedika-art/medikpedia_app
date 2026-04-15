# 🏗️ System Overview - Sistem Import Excel & CRUD

## 📊 Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN DASHBOARD                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────┐      ┌──────────────────────┐         │
│  │  OBAT BIASA & RESEP  │      │   PRODUK RESEP       │         │
│  │   (Unified)          │      │   (Khusus)           │         │
│  └──────────────────────┘      └──────────────────────┘         │
│           │                              │                       │
│           ├─ List                        ├─ List                │
│           ├─ Create                      ├─ Create              │
│           ├─ Edit                        ├─ Edit                │
│           ├─ Delete                      ├─ Delete              │
│           ├─ Update Stock                ├─ Update Stock        │
│           └─ Import                      └─ Import              │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  DATABASE        │
                    │  medicines table │
                    │  (is_resep flag) │
                    └──────────────────┘
```

---

## 🔄 Data Flow

### Import Obat Biasa & Resep

```
Excel File
    │
    ▼
┌─────────────────────────────────────┐
│ Download Template                   │
│ (nama_obat, pabrik, komposisi,      │
│  indikasi, golongan, retail, stok)  │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ User Isi Data                       │
│ - Kolom golongan: BEBAS/KERAS       │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Simpan sebagai CSV                  │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Upload File                         │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Validasi                            │
│ - Kolom wajib                       │
│ - Format data                       │
│ - Golongan BEBAS/KERAS              │
└─────────────────────────────────────┘
    │
    ├─ Error ──────────────────┐
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Tampilkan Error  │
    │                  │ (Max 5 error)    │
    │                  └──────────────────┘
    │
    ├─ Valid ──────────────────┐
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Mapping Data     │
    │                  │ retail → harga   │
    │                  │ komposisi → desc │
    │                  │ golongan → resep │
    │                  └──────────────────┘
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Simpan ke DB     │
    │                  │ - BEBAS: resep=0 │
    │                  │ - KERAS: resep=1 │
    │                  └──────────────────┘
    │                          │
    ▼                          ▼
┌─────────────────────────────────────┐
│ Selesai                             │
│ Tampilkan: X imported, Y skipped    │
└─────────────────────────────────────┘
```

### Import Produk Resep

```
Excel File
    │
    ▼
┌─────────────────────────────────────┐
│ Download Template                   │
│ (nama_obat, pabrik, komposisi,      │
│  indikasi, retail, stok)            │
│ [Tanpa kolom golongan]              │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ User Isi Data                       │
│ - Semua otomatis produk resep       │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Simpan sebagai CSV                  │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Upload File                         │
└─────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────┐
│ Validasi                            │
│ - Kolom wajib                       │
│ - Format data                       │
└─────────────────────────────────────┘
    │
    ├─ Error ──────────────────┐
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Tampilkan Error  │
    │                  │ (Max 5 error)    │
    │                  └──────────────────┘
    │
    ├─ Valid ──────────────────┐
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Mapping Data     │
    │                  │ retail → harga   │
    │                  │ komposisi → desc │
    │                  │ is_resep = true  │
    │                  └──────────────────┘
    │                          │
    │                          ▼
    │                  ┌──────────────────┐
    │                  │ Simpan ke DB     │
    │                  │ (Semua resep=1)  │
    │                  └──────────────────┘
    │                          │
    ▼                          ▼
┌─────────────────────────────────────┐
│ Selesai                             │
│ Tampilkan: X imported, Y skipped    │
└─────────────────────────────────────┘
```

---

## 📋 Database Schema

```sql
medicines table:
┌─────────────────────────────────────────────────────┐
│ id (PK)                                             │
│ nama_obat (VARCHAR)                                 │
│ kategori (VARCHAR) ← Pabrik/Perusahaan             │
│ harga (DECIMAL) ← Dari kolom retail                │
│ stok (INTEGER)                                      │
│ deskripsi (TEXT) ← Dari kolom komposisi            │
│ gambar (VARCHAR, nullable)                          │
│ is_resep (BOOLEAN) ← Dari kolom golongan           │
│ created_at (TIMESTAMP)                              │
│ updated_at (TIMESTAMP)                              │
└─────────────────────────────────────────────────────┘

Mapping:
┌──────────────────────────────────────────────────────┐
│ Excel Column    → Database Column                    │
├──────────────────────────────────────────────────────┤
│ nama_obat       → nama_obat                          │
│ pabrik          → kategori                          │
│ komposisi       → deskripsi                         │
│ indikasi        → (optional, bisa di deskripsi)    │
│ golongan        → is_resep (KERAS=1, BEBAS=0)      │
│ retail          → harga                             │
│ stok            → stok                              │
└──────────────────────────────────────────────────────┘
```

---

## 🎯 Use Cases

### Use Case 1: Import Obat Biasa

```
Admin:
1. Klik "Download Template" (obat biasa)
2. Isi data dengan golongan = BEBAS
3. Simpan sebagai CSV
4. Upload file
5. Sistem otomatis set is_resep = false
6. Data tersimpan sebagai obat biasa
```

### Use Case 2: Import Obat Resep

```
Admin:
1. Klik "Download Template" (obat biasa)
2. Isi data dengan golongan = KERAS
3. Simpan sebagai CSV
4. Upload file
5. Sistem otomatis set is_resep = true
6. Data tersimpan sebagai obat resep
```

### Use Case 3: Import Produk Resep Langsung

```
Admin:
1. Klik "Download Template" (produk resep)
2. Isi data (tanpa kolom golongan)
3. Simpan sebagai CSV
4. Upload file
5. Sistem otomatis set is_resep = true
6. Data tersimpan sebagai produk resep
```

### Use Case 4: Filter Obat Biasa/Resep

```
Admin:
1. Buka halaman "Obat Biasa & Resep"
2. Pilih filter "Biasa" → tampilkan is_resep = false
3. Atau pilih filter "Resep" → tampilkan is_resep = true
4. Atau pilih "Semua" → tampilkan semua
```

### Use Case 5: CRUD Produk Resep

```
Admin:
1. Buka halaman "Produk Resep"
2. Hanya menampilkan produk dengan is_resep = true
3. Bisa create, edit, delete, update stok
4. Semua produk baru otomatis is_resep = true
```

---

## 🔐 Validasi & Error Handling

### Validasi Kolom

```
┌─────────────────────────────────────┐
│ Kolom Wajib                         │
├─────────────────────────────────────┤
│ ✓ nama_obat                         │
│ ✓ pabrik                            │
│ ✓ komposisi                         │
│ ✓ indikasi                          │
│ ✓ retail                            │
│ ✓ stok                              │
│ ✓ golongan (hanya obat biasa)       │
└─────────────────────────────────────┘
```

### Validasi Format

```
┌─────────────────────────────────────┐
│ Format Validasi                     │
├─────────────────────────────────────┤
│ nama_obat: String (tidak kosong)    │
│ pabrik: String (tidak kosong)       │
│ komposisi: String (tidak kosong)    │
│ indikasi: String (tidak kosong)     │
│ retail: Numeric (angka saja)        │
│ stok: Integer (angka saja)          │
│ golongan: BEBAS atau KERAS          │
└─────────────────────────────────────┘
```

### Error Handling

```
┌─────────────────────────────────────┐
│ Error Handling                      │
├─────────────────────────────────────┤
│ Baris kosong → Dilewati (skip)      │
│ Kolom error → Ditampilkan           │
│ Format error → Ditampilkan          │
│ Validasi error → Ditampilkan        │
│ Max error → 5 per upload            │
│ Proses → Tetap jalan (skip error)   │
└─────────────────────────────────────┘
```

---

## 📱 User Interface Flow

### Admin Dashboard

```
┌─────────────────────────────────────────────────────┐
│ ADMIN DASHBOARD                                     │
├─────────────────────────────────────────────────────┤
│                                                     │
│ ┌─────────────────────────────────────────────┐   │
│ │ OBAT BIASA & RESEP                          │   │
│ ├─────────────────────────────────────────────┤   │
│ │ [List] [Tambah] [Import] [Download Template]│   │
│ │                                             │   │
│ │ Filter: [Semua] [Biasa] [Resep]            │   │
│ │ Search: [_______________]                  │   │
│ │                                             │   │
│ │ Tabel:                                      │   │
│ │ No | Nama | Pabrik | Harga | Stok | Aksi  │   │
│ │ 1  | ... | ... | ... | ... | [Edit][Hapus]│   │
│ │ 2  | ... | ... | ... | ... | [Edit][Hapus]│   │
│ │                                             │   │
│ │ [< Prev] [1] [2] [3] [Next >]              │   │
│ └─────────────────────────────────────────────┘   │
│                                                     │
│ ┌─────────────────────────────────────────────┐   │
│ │ PRODUK RESEP                                │   │
│ ├─────────────────────────────────────────────┤   │
│ │ [List] [Tambah] [Import] [Download Template]│   │
│ │                                             │   │
│ │ Filter: [Semua] [Kategori]                 │   │
│ │ Search: [_______________]                  │   │
│ │                                             │   │
│ │ Tabel:                                      │   │
│ │ No | Nama | Pabrik | Harga | Stok | Aksi  │   │
│ │ 1  | ... | ... | ... | ... | [Edit][Hapus]│   │
│ │ 2  | ... | ... | ... | ... | [Edit][Hapus]│   │
│ │                                             │   │
│ │ [< Prev] [1] [2] [3] [Next >]              │   │
│ └─────────────────────────────────────────────┘   │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

## 🔗 API Endpoints

### Obat Biasa & Resep

```
GET    /admin/medicines                    List obat
GET    /admin/medicines/create             Form tambah
POST   /admin/medicines                    Simpan obat
GET    /admin/medicines/{id}/edit          Form edit
PUT    /admin/medicines/{id}               Update obat
DELETE /admin/medicines/{id}               Hapus obat
POST   /admin/medicines/{id}/stock         Update stok
GET    /admin/medicines/import/template    Download template
GET    /admin/medicines/import             Form import
POST   /admin/medicines/import             Proses import
```

### Produk Resep

```
GET    /admin/prescriptions/products                    List produk
GET    /admin/prescriptions/products/create             Form tambah
POST   /admin/prescriptions/products                    Simpan produk
GET    /admin/prescriptions/products/{id}/edit          Form edit
PUT    /admin/prescriptions/products/{id}               Update produk
DELETE /admin/prescriptions/products/{id}               Hapus produk
POST   /admin/prescriptions/products/{id}/stock         Update stok
GET    /admin/prescriptions/products/import/template    Download template
GET    /admin/prescriptions/products/import             Form import
POST   /admin/prescriptions/products/import             Proses import
```

---

## 📊 Statistik & Metrics

```
┌─────────────────────────────────────┐
│ Sistem Metrics                      │
├─────────────────────────────────────┤
│ Controllers: 4 (2 baru, 2 modifikasi)
│ Routes: 20 (10 per tipe)            │
│ Kolom Excel: 7                      │
│ Validasi Rules: 6                   │
│ Error Handling: 5 tipe              │
│ Dokumentasi: 5 file                 │
│ Support Format: 3 (.xls, .xlsx, .csv)
│ Max Error Display: 5 per upload     │
│ Pagination: 10 item per halaman     │
└─────────────────────────────────────┘
```

---

## ✨ Fitur Unggulan

```
┌─────────────────────────────────────┐
│ Fitur Unggulan                      │
├─────────────────────────────────────┤
│ ✓ Unified Import (1 template)       │
│ ✓ Smart Validation                  │
│ ✓ Flexible Format Support           │
│ ✓ Separation of Concerns            │
│ ✓ Complete CRUD                     │
│ ✓ Filter & Search                   │
│ ✓ Error Handling                    │
│ ✓ Scalable Architecture             │
│ ✓ User-Friendly UI                  │
│ ✓ Comprehensive Documentation       │
└─────────────────────────────────────┘
```

---

## 🎯 Next Steps

```
1. Setup Routes
   └─ Tambahkan routes di routes/web.php

2. Create/Update Views
   └─ Buat views untuk list/create/edit

3. Test Import
   └─ Test dengan data sample

4. Test CRUD
   └─ Test create, edit, delete, update stok

5. Test Filter
   └─ Test filter obat biasa/resep

6. Deploy
   └─ Deploy ke production
```

---

**System Status:** ✅ READY FOR IMPLEMENTATION
