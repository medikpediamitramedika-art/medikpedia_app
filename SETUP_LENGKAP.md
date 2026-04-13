# 🚀 SETUP LENGKAP MEDIKPEDIA - STEP BY STEP

Panduan lengkap untuk menjalankan project Medikpedia dari awal hingga selesai.

---

## ⚡ Quick Start (5 Menit)

Jika sudah ada project dan dependencies terinstall:

```bash
# 1. Setup environment
cp .env.example .env
php artisan key:generate

# 2. Setup database
php artisan migrate --seed

# 3. Build assets
npm run build

# 4. Link storage
php artisan storage:link

# 5. Run server
php artisan serve
```

Buka browser: **http://127.0.0.1:8000**

---

## 📋 Detail Setup (Dari Awal)

### STEP 1: Clone/Extract Project

```bash
cd medicpedia_app
```

---

### STEP 2: Install Dependencies PHP

```bash
composer install
```

**Output yang diharapkan:**
```
Generating optimized autoload files
Created 45 classes
All good. Autoloader generation completed successfully.
```

---

### STEP 3: Setup Laravel Environment

Buat file `.env`:
```bash
cp .env.example .env
```

Generate app key:
```bash
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medikpedia
DB_USERNAME=root
DB_PASSWORD=
```

---

### STEP 4: Setup Database MySQL

**Opsi A: Menggunakan MySQL CLI**
```bash
mysql -u root

# Di dalam MySQL prompt
CREATE DATABASE medikpedia;
EXIT;
```

**Opsi B: Menggunakan PHPMyAdmin**
1. Buka http://localhost/phpmyadmin
2. Buat database baru dengan nama `medikpedia`

---

### STEP 5: Run Migrations & Seeding

Run migrations:
```bash
php artisan migrate
```

**Output yang diharapkan:**
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated: 0001_01_01_000000_create_users_table (45ms)
Migrating: 0001_01_01_000001_create_cache_table
Migrated: 0001_01_01_000001_create_cache_table (28ms)
...
```

Run seeder untuk data awal:
```bash
php artisan db:seed
```

**Output yang diharapkan:**
```
Seeding: Database\Seeders\AdminSeeder
Seeded: Database\Seeders\AdminSeeder (12ms)
Seeding: Database\Seeders\MedicineSeeder
Seeded: Database\Seeders\MedicineSeeder (45ms)
Database seeding completed successfully.
```

---

### STEP 6: Install Node Dependencies

```bash
npm install
```

**Output yang diharapkan:**
```
added 83 packages, and audited 84 packages in 30s
```

---

### STEP 7: Build Assets (Vite)

Build untuk production:
```bash
npm run build
```

**Output yang diharapkan:**
```
vite v7.3.2 building client environment for production...
✓ 53 modules transformed.
public/build/manifest.json             0.33 kB │ gzip:  0.17 kB
public/build/assets/app-DoGbhkvz.css  51.12 kB │ gzip: 10.79 kB
public/build/assets/app-BjqOcoUn.js   37.21 kB │ gzip: 14.89 kB
✓ built in 1.59s
```

---

### STEP 8: Buat Symlink untuk Storage

Agar gambar obat bisa diakses publik:
```bash
php artisan storage:link
```

**Output yang diharapkan:**
```
The [public/storage] link has been connected to [storage/app/public]. Success!
```

---

### STEP 9: Jalankan Development Server

```bash
php artisan serve
```

**Output yang diharapkan:**
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

---

## 🎉 Selesai!

Buka browser dan akses:
- **Frontend**: http://127.0.0.1:8000
- **Admin Login**: http://127.0.0.1:8000/admin/login

---

## 🔑 Akun Default untuk Login

### Admin Account 1
- **Email**: admin@medikpedia.com
- **Password**: admin123456

### Admin Account 2
- **Email**: test@medikpedia.com
- **Password**: test123456

---

## 🧪 Testing Features

### 1. Test Frontend (Home & Search)
1. Buka http://127.0.0.1:8000
2. Lihat list obat dalam bentuk card
3. Coba cari obat di search box
4. Klik "Lihat Detail" untuk lihat detail obat

### 2. Test Admin Login
1. Buka http://127.0.0.1:8000/admin/login
2. Login dengan email: `admin@medikpedia.com`
3. Password: `admin123456`

### 3. Test Dashboard Admin
Setelah login, Anda akan melihat:
- Total obat (15 dari seeder)
- Total stok
- Obat dengan stok rendah
- List obat terbaru

### 4. Test CRUD Obat
- **Tambah**: Klik tombol "Tambah Obat Baru"
- **Edit**: Di list obat, klik "Edit"
- **Hapus**: Di list obat, klik "Hapus"
- **Upload Gambar**: Upload gambar saat tambah/edit

### 5. Test Search di Admin
Di halaman list obat, bisa di-filter dan pagination

---

## 📁 File & Folder Penting

| Lokasi | Kegunaan |
|--------|----------|
| `.env` | Konfigurasi app (database, app key, dsb) |
| `database/migrations/` | Struktur database |
| `database/seeders/` | Data awal |
| `app/Models/` | Database models |
| `app/Http/Controllers/` | Business logic |
| `resources/views/` | Template HTML |
| `routes/web.php` | URL routing |
| `storage/app/public/medicines/` | Folder gambar obat |
| `public/build/` | Build hasil Vite |

---

## 💾 Database Relationship

```
users (1) -----> (Many) sessions
    |
    └-----> role = 'admin' untuk admin user

medicines (Many) -----> (1) kategori (string)
```

---

## 🔧 Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback    # Undo last migration
php artisan db:seed             # Run seeders
php artisan db:seed --class=AdminSeeder  # Run specific seeder

# Tinker (Interactive Shell)
php artisan tinker
# Cek di dalam prompt:
> User::all()
> Medicine::all()

# Cache
php artisan cache:clear        # Clear cache
php artisan config:cache       # Cache config

# Development
npm run dev                      # Dev server dengan hot reload
npm run build                    # Build production assets

# Server
php artisan serve              # Run development server
php artisan serve --host=0.0.0.0 --port=8080  # Custom host/port
```

---

## ⚠️ Common Issues & Solutions

### Issue 1: "Vite manifest not found"
**Penyebab**: Assets belum di-build
**Solusi**:
```bash
npm run build
```

### Issue 2: "SQLSTATE[HY000]" - Database connection error
**Penyebab**: Database config salah atau MySQL tidak berjalan
**Solusi**:
1. Cek `.env` file - apakah DB_HOST, DB_NAME, DB_USERNAME benar?
2. Pastikan MySQL service sudah running
3. Cek connection string di `.env`

### Issue 3: "Class not found" saat run seeder
**Penyebab**: Autoloader belum update
**Solusi**:
```bash
composer dumpautoload
php artisan db:seed
```

### Issue 4: Storage link tidak exist
**Penyebab**: Symlink belum dibuat
**Solusi**:
```bash
php artisan storage:link
```

### Issue 5: Port 8000 sudah dipakai
**Solusi**: Gunakan port berbeda
```bash
php artisan serve --port=8001
# Atau buka http://127.0.0.1:8001
```

### Issue 6: Gambar tidak ditampilkan
**Penyebab**: Storage symlink tidak dibuat
**Solusi**:
```bash
php artisan storage:link
```

---

## 📊 Struktur Data Seeder

### Admin Users (2 akun)
- admin@medikpedia.com / admin123456
- test@medikpedia.com / test123456

### Medicines (15 obat)
Kategori obat yang sudah ada:
- Antibiotik (2 obat)
- Analgesik (2 obat)
- Antiinflamasi (2 obat)
- Vitamin (3 obat)
- Suplemen (1 obat)
- Obat Batuk (2 obat)
- Vitamin Anak (1 obat)
- Lainnya (2 obat)

---

## 🎓 Next Steps untuk Learning

Setelah setup berhasil:

1. **Explore Kode**:
   - Baca `routes/web.php` untuk understand routing
   - Baca controller untuk understand business logic
   - Baca view untuk understand template

2. **Modifikasi**:
   - Ubah warna di CSS
   - Tambah field baru di form
   - Buat kategori baru

3. **Extend Features**:
   - Tambah fitur rating/review
   - Tambah fitur wishlist
   - Tambah fitur keranjang belanja

4. **Database Optimization**:
   - Buat relationship antar tabel
   - Buat efficient queries dengan eager loading
   - Buat database indexes

---

## 🚨 PENTING: Sebelum Deploy ke Production

1. Update `.env`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Generate app key:
   ```bash
   php artisan key:generate
   ```

3. Optimize untuk production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

4. Build assets:
   ```bash
   npm run build
   ```

5. Setup cronjob untuk queue (jika ada):
   ```bash
   php artisan queue:work
   ```

---

**Good luck! Happy learning! 🎉**
