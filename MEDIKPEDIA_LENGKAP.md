# 🏥 MEDIKPEDIA - Website Apotik Online

## Deskripsi Project

Medikpedia adalah website apotik online sederhana yang dibangun menggunakan Laravel terbaru dengan fitur lengkap namun mudah dipahami oleh siswa SMK. Project ini menampilkan cara membuat aplikasi e-commerce dengan fitur user (frontend) dan admin (backend) yang terintegrasi dengan baik.

---

## 🎯 Fitur Utama

### Frontend (User)
- ✅ Halaman home dengan daftar obat dalam bentuk card
- ✅ Fitur search/pencarian obat berdasarkan nama
- ✅ Halaman detail obat dengan informasi lengkap
- ✅ Responsive design (mobile friendly)
- ✅ UI modern dengan Tailwind CSS

### Admin
- ✅ Login admin dengan autentikasi
- ✅ Dashboard dengan statistik obat
- ✅ CRUD obat (Tambah, Edit, Hapus)
- ✅ Upload gambar obat
- ✅ Update stok obat
- ✅ Admin panel dengan sidebar modern

---

## 📁 Struktur Folder Project

```
medicpedia_app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php           # Handle halaman home & detail
│   │   │   ├── AuthController.php           # Handle login/register
│   │   │   ├── AdminDashboardController.php # Dashboard admin
│   │   │   └── AdminMedicineController.php  # CRUD obat admin
│   │   └── Middleware/
│   │       └── IsAdmin.php                  # Middleware untuk cek admin
│   └── Models/
│       ├── User.php
│       └── Medicine.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_04_08_000000_create_medicines_table.php
│   │   └── 2025_04_08_000001_add_role_to_users_table.php
│   └── seeders/
│       ├── AdminSeeder.php
│       ├── MedicineSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── frontend.blade.php           # Layout frontend
│   │   │   └── admin.blade.php              # Layout admin
│   │   ├── home.blade.php                   # Halaman home
│   │   ├── medicines/
│   │   │   └── detail.blade.php             # Detail obat
│   │   └── admin/
│   │       ├── login.blade.php              # Login admin
│   │       ├── register.blade.php           # Register admin
│   │       ├── dashboard.blade.php          # Dashboard admin
│   │       └── medicines/
│   │           ├── index.blade.php          # List obat
│   │           ├── create.blade.php         # Form tambah obat
│   │           └── edit.blade.php           # Form edit obat
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── routes/
│   └── web.php                              # Semua routes
├── storage/
│   └── app/public/medicines/                # Folder untuk gambar obat
├── public/
│   ├── index.php
│   └── build/                               # Hasil build Vite
├── .env                                     # Environment variables
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── tailwind.config.js
```

---

## 🗄️ Database Schema

### Tabel: users
| Field | Type | Keterangan |
|-------|------|-----------|
| id | bigint | Primary Key |
| name | varchar | Nama user |
| email | varchar | Email (unique) |
| password | varchar | Password hashed |
| role | enum | 'admin' atau 'user' |
| created_at | timestamp | Waktu buat |
| updated_at | timestamp | Waktu update |

### Tabel: medicines
| Field | Type | Keterangan |
|-------|------|-----------|
| id | bigint | Primary Key |
| nama_obat | varchar | Nama obat |
| kategori | varchar | Kategori obat |
| harga | decimal | Harga (12,2) |
| stok | integer | Jumlah stok |
| deskripsi | text | Deskripsi obat |
| gambar | varchar | Path gambar |
| created_at | timestamp | Waktu buat |
| updated_at | timestamp | Waktu update |

---

## 🚀 Cara Menjalankan Project

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL (atau SQLite untuk testing)
- Git

### Langkah-Langkah Setup

#### 1️⃣ Clone atau Extract Project
```bash
# Jika project sudah ada
cd medicpedia_app
```

#### 2️⃣ Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

#### 3️⃣ Setup Environment
```bash
# Copy file env
cp .env.example .env

# Generate app key
php artisan key:generate
```

#### 4️⃣ Setup Database
```bash
# Buat database MySQL (optional)
# CREATE DATABASE medikpedia;

# Run migrations
php artisan migrate

# (Optional) Jalankan seeder
php artisan db:seed
```

#### 5️⃣ Build Assets
```bash
# Build untuk production
npm run build

# Atau untuk development dengan hot reload
npm run dev
```

#### 6️⃣ Jalankan Server
```bash
# Cara 1: Built-in PHP Server
php artisan serve

# Server akan berjalan di http://127.0.0.1:8000
```

#### 7️⃣ Storage Link
```bash
# Buat symlink untuk storage gambar
php artisan storage:link
```

---

## 📝 Akun Default (Setelah Seeding)

### Admin 1
- **Email**: admin@medikpedia.com
- **Password**: admin123456
- **Role**: Admin

### Admin 2 (Test)
- **Email**: test@medikpedia.com
- **Password**: test123456
- **Role**: Admin

---

## 🔗 Routes/URL yang Tersedia

### Frontend Routes
| Method | URL | Controller | Keterangan |
|--------|-----|-----------|-----------|
| GET | `/` | HomeController@index | Halaman home |
| GET | `/obat/{id}` | HomeController@show | Detail obat |
| GET | `/kategori/{kategori}` | HomeController@byCategory | Filter kategori |

### Auth Routes
| Method | URL | Controller | Keterangan |
|--------|-----|-----------|-----------|
| GET | `/admin/login` | AuthController@loginForm | Form login |
| POST | `/admin/login` | AuthController@login | Process login |
| POST | `/admin/logout` | AuthController@logout | Logout |
| GET | `/admin/register` | AuthController@registerForm | Form register |
| POST | `/admin/register` | AuthController@register | Process register |

### Admin Routes (Protected)
| Method | URL | Controller | Keterangan |
|--------|-----|-----------|-----------|
| GET | `/admin/dashboard` | AdminDashboardController@index | Dashboard |
| GET | `/admin/medicines` | AdminMedicineController@index | List obat |
| GET | `/admin/medicines/create` | AdminMedicineController@create | Form tambah |
| POST | `/admin/medicines` | AdminMedicineController@store | Simpan obat baru |
| GET | `/admin/medicines/{id}/edit` | AdminMedicineController@edit | Form edit |
| PUT | `/admin/medicines/{id}` | AdminMedicineController@update | Update obat |
| DELETE | `/admin/medicines/{id}` | AdminMedicineController@destroy | Hapus obat |

---

## 🎨 Warna & Design

Desain project menggunakan palet warna kesehatan yang soft:
- **Primary**: Green (#10b981) - Warna utama kesehatan
- **Secondary**: Dark Green (#059669) - Warna gelap green
- **Accent**: Lighter Green (#34d399) - Aksen warna
- **Dark**: #1f2937 - Warna gelap untuk text
- **Light**: #f3f4f6 - Warna terang background

---

## 💡 Fitur Penting & Cara Menggunakannya

### 1. Upload Gambar
- Support format: JPG, PNG, GIF
- Max size: 2MB
- Gambar disimpan di `storage/app/public/medicines/`
- Untuk melihat gambar publik, jalankan: `php artisan storage:link`

### 2. Pencarian Obat
- Search by nama obat
- Search by kategori
- Search by deskripsi
- Real-time filtering

### 3. Validasi Form
- Semua form divalidasi di backend
- Error message ditampilkan ke user
- Form direset jika ada error

### 4. Admin Middleware
- Hanya user dengan role 'admin' bisa akses admin panel
- Jika user bukan admin, redirect ke home dengan pesan error
- Login required untuk semua admin routes

---

## 📦 Teknologi yang Digunakan

| Teknologi | Versi | Kegunaan |
|-----------|-------|---------|
| Laravel | 12.x | PHP Framework |
| PHP | 8.2+ | Backend language |
| Blade | - | Template engine |
| MySQL | 5.7+ | Database |
| Vite | 7.x | Build tool |
| Tailwind CSS | - | CSS Framework |
| Node.js | 16+ | JavaScript runtime |
| Composer | 2.x | PHP Package manager |

---

## 🐛 Troubleshooting

### Error: "Vite manifest not found"
**Solusi**: Run `npm run build` untuk build assets

### Error: "Storage disk not found"
**Solusi**: Run `php artisan storage:link` untuk membuat symlink

### Error: "SQLSTATE[HY000]: General error: 1030"
**Solusi**: Cek koneksi database di `.env` file

### Error: "Class not found"
**Solusi**: Run `composer autoload` untuk re-generate autoloader

### Upload gambar tidak berfungsi
**Solusi**: 
- Cek folder `storage/app/public` sudah ada
- Run `php artisan storage:link`
- Cek permission folder

---

## 🎓 Learning Path untuk SMK

Project ini cocok untuk pembelajaran:
1. **Intro MVC**: Pahami struktur Controller, Model, View
2. **Database**: Migrations, Seeders, Relationships
3. **Blade Template**: Template syntax dan loops
4. **Routing**: Resource routes dan middleware
5. **Form Handling**: Validation dan error handling
6. **File Upload**: Image upload dan storage
7. **Admin Dashboard**: CRUD operations
8. **Authentication**: Login dan roles

---

## 🔐 Security Notes

- Password selalu di-hash menggunakan bcrypt
- CSRF protection di semua form
- Input validation di semua endpoints
- Role-based access control
- XSS protection dengan blade escaping

---

## 📞 Support & Documentation

- **Laravel Docs**: https://laravel.com/docs
- **Blade Docs**: https://laravel.com/docs/blade
- **Tailwind CSS**: https://tailwindcss.com
- **Vite**: https://vitejs.dev

---

## 📄 License

Project ini untuk keperluan pendidikan SMK.

---

## ✅ Checklist Setup Selesai

- [x] Database migrations
- [x] Models dan relationships
- [x] Controllers lengkap
- [x] Routes/Routing
- [x] Frontend views (home, detail)
- [x] Admin views (login, dashboard, CRUD)
- [x] Seeders (admin, medicines)
- [x] Image upload & storage
- [x] Validasi form
- [x] Authentication & authorization
- [x] Responsive design
- [x] Dokumentasi lengkap

---

**Dibuat dengan ❤️ untuk pembelajaran SMK**
