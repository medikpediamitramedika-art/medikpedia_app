# 🏥 Medikpedia - Website Apotik Online

Website apotik online modern dengan profil perusahaan lengkap, katalog produk, dan berbagai layanan kesehatan.

## ✨ Fitur Utama

### 🏠 Halaman Utama (Home)
- Hero section yang menarik dengan call-to-action
- Fitur unggulan mengapa memilih Medikpedia
- Produk populer dengan rating
- Section promosi spesial

### 📋 Halaman Tentang Kami
- Profil perusahaan lengkap
- Visi dan misi perusahaan
- Nilai-nilai inti (kepercayaan, kualitas, kemanusiaan)
- Tim profesional dengan informasi lengkap
- Statistik perusahaan

### 🛍️ Halaman Produk
- Katalog lengkap produk obat dan suplemen
- Filter kategori produk
- Informasi harga dan rating
- Sistem pagination untuk navigasi mudah
- Tombol "Tambah ke Keranjang"

### 🎯 Halaman Layanan
- Layanan konsultasi online dengan apoteker
- Resep digital dan upload resep
- Pengiriman ekspres
- Paket langganan otomatis
- Program loyalitas dengan poin reward
- Paket membership (Silver, Gold, Platinum)

### 📞 Halaman Kontak
- Informasi kontak lengkap (alamat, telepon, email)
- Formulir kontak interaktif
- Integrasi media sosial
- Peta lokasi
- FAQ (Pertanyaan yang Sering Diajukan)

## 🎨 Desain & Teknologi

- **Framework**: Laravel 11
- **Frontend**: Bootstrap 5
- **Icon**: Font Awesome 6
- **Font**: Poppins (Google Fonts)
- **Warna Tema**: 
  - Primary: #10b981 (Hijau)
  - Secondary: #059669 (Hijau Tua)
  - Text Dark: #1f2937

## 📁 Struktur File

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php          # Layout utama (navbar & footer)
│   └── pages/
│       ├── home.blade.php         # Halaman beranda
│       ├── about.blade.php        # Halaman tentang kami
│       ├── products.blade.php     # Halaman produk
│       ├── services.blade.php     # Halaman layanan
│       └── contact.blade.php      # Halaman kontak

app/
├── Http/
│   └── Controllers/
│       └── PageController.php     # Controller untuk halaman-halaman

routes/
└── web.php                        # Route configuration
```

## 🚀 Cara Menjalankan

1. Clone atau download repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Salin file environment:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Jalankan development server:
   ```bash
   php artisan serve
   ```

6. Buka browser dan akses: `http://localhost:8000`

## 📄 Routes

| Halaman | URL | Controller Method |
|---------|-----|-------------------|
| Beranda | / | home |
| Tentang Kami | /tentang-kami | about |
| Produk | /produk | products |
| Layanan | /layanan | services |
| Kontak | /kontak | contact |

## 🎯 Fitur yang Dapat Dikembangkan

- [ ] Sistem shopping cart dan checkout
- [ ] Integrasi payment gateway
- [ ] User authentication & registration
- [ ] Database untuk produk
- [ ] Admin dashboard
- [ ] Sistem review & rating
- [ ] Live chat customer service
- [ ] Mobile app
- [ ] Email notification
- [ ] SMS gateway untuk notifikasi pengiriman

## 💡 Kustomisasi

### Mengubah Warna Tema
Edit variabel CSS di `resources/views/layouts/app.blade.php`:
```css
:root {
    --primary-color: #10b981;      /* Ubah warna primary */
    --secondary-color: #059669;    /* Ubah warna secondary */
    --text-dark: #1f2937;          /* Ubah warna text */
    --text-light: #6b7280;         /* Ubah warna text light */
    --border-color: #e5e7eb;       /* Ubah warna border */
}
```

### Mengubah Informasi Perusahaan
Edit footer di `resources/views/layouts/app.blade.php` untuk mengganti:
- Nama perusahaan
- Nomor telepon
- Email
- Alamat
- Social media links

## 📝 Konten yang Dapat Disesuaikan

- **Logo/Brand**: Ganti teks `Medikpedia` dengan nama perusahaan Anda
- **Produk**: Update gambar placeholder dengan foto produk asli
- **Harga**: Sesuaikan dengan harga produk sebenarnya
- **Informasi Kontak**: Update dengan detail kontak perusahaan Anda
- **Tim**: Tambahkan foto dan info tim sebenarnya
- **Layanan**: Customisasi layanan sesuai dengan penawaran Anda

## 🔒 Tips Keamanan

- Jangan share `.env` file
- Selalu gunakan HTTPS di production
- Validate & sanitize semua input user
- Setup firewall yang tepat
- Regular update Laravel dan dependencies

## 📧 Dukungan

Untuk pertanyaan atau dukungan lebih lanjut, silakan hubungi:
- Email: dev@medikpedia.com
- Phone: (021) 1234-5678

---

**Medikpedia** © 2024. All rights reserved.
