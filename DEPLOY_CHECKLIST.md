# 🚀 Checklist Deploy ke Server

## Masalah: Perubahan di Local Tidak Muncul di Server

### Penyebab Umum:
1. ❌ Cache Laravel belum di-clear
2. ❌ File belum ter-upload dengan benar
3. ❌ PHP-FPM atau web server belum di-restart
4. ❌ Browser cache masih menyimpan versi lama

---

## ✅ Solusi Step-by-Step

### 1. Upload File yang Diubah ke Server

Pastikan file-file berikut sudah ter-upload:

```bash
# Controller yang diubah
app/Http/Controllers/AdminMedicineController.php
app/Http/Controllers/AdminPrescriptionProductController.php

# View yang diubah (jika ada)
resources/views/admin/medicines/edit.blade.php
resources/views/admin/prescriptions/products/edit.blade.php
resources/views/layouts/frontend.blade.php
```

**Cara Upload:**
- Via FTP/SFTP (FileZilla, WinSCP)
- Via Git: `git pull origin main`
- Via rsync: `rsync -avz local/ server:/path/to/project/`

---

### 2. Clear Semua Cache Laravel di Server

**Login ke server via SSH**, lalu jalankan:

```bash
# Masuk ke folder project
cd /path/to/your/project

# Clear semua cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan clear-compiled

# Atau gunakan script yang sudah dibuat
bash clear-cache-server.sh
```

**Atau jalankan satu per satu:**

```bash
# 1. Clear application cache
php artisan cache:clear

# 2. Clear route cache (PENTING untuk route issue!)
php artisan route:clear

# 3. Clear config cache
php artisan config:clear

# 4. Clear view cache
php artisan view:clear

# 5. Clear compiled classes
php artisan clear-compiled
```

---

### 3. Restart PHP-FPM dan Web Server

```bash
# Restart PHP-FPM (sesuaikan versi PHP Anda)
sudo systemctl restart php8.1-fpm
# atau
sudo systemctl restart php8.2-fpm
# atau
sudo systemctl restart php-fpm

# Restart Nginx
sudo systemctl restart nginx

# Atau restart Apache
sudo systemctl restart apache2
```

---

### 4. Verify File Permissions

```bash
# Set permission yang benar
sudo chown -R www-data:www-data /path/to/project
sudo chmod -R 755 /path/to/project
sudo chmod -R 775 /path/to/project/storage
sudo chmod -R 775 /path/to/project/bootstrap/cache
```

---

### 5. Check Storage Link

```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Verify
ls -la public/storage
```

---

### 6. Clear Browser Cache

Di browser:
- **Chrome/Edge**: `Ctrl + Shift + R` (Windows) atau `Cmd + Shift + R` (Mac)
- **Firefox**: `Ctrl + F5`
- Atau buka Incognito/Private mode

---

## 🔍 Debugging: Cek Apakah File Sudah Terupdate

### Cek versi file di server:

```bash
# Cek tanggal modifikasi file
ls -lh app/Http/Controllers/AdminMedicineController.php
ls -lh app/Http/Controllers/AdminPrescriptionProductController.php

# Atau lihat isi file
cat app/Http/Controllers/AdminMedicineController.php | grep "delete_gambar"
```

### Cek route yang terdaftar:

```bash
# Lihat semua route
php artisan route:list | grep medicines

# Cek route update
php artisan route:list | grep "medicines.*update"
```

---

## 🎯 Quick Fix Command (All-in-One)

Jalankan command ini di server:

```bash
cd /path/to/project && \
php artisan cache:clear && \
php artisan route:clear && \
php artisan config:clear && \
php artisan view:clear && \
php artisan clear-compiled && \
sudo systemctl restart php8.1-fpm && \
sudo systemctl restart nginx && \
echo "✅ Cache cleared and services restarted!"
```

---

## 📋 Checklist Lengkap

- [ ] File controller sudah di-upload ke server
- [ ] File view sudah di-upload ke server (jika ada perubahan)
- [ ] `php artisan cache:clear` sudah dijalankan
- [ ] `php artisan route:clear` sudah dijalankan
- [ ] `php artisan config:clear` sudah dijalankan
- [ ] `php artisan view:clear` sudah dijalankan
- [ ] PHP-FPM sudah di-restart
- [ ] Web server (Nginx/Apache) sudah di-restart
- [ ] Browser cache sudah di-clear (Ctrl+Shift+R)
- [ ] Test di Incognito mode
- [ ] File permissions sudah benar (775 untuk storage)
- [ ] Storage link sudah dibuat (`php artisan storage:link`)

---

## 🆘 Jika Masih Belum Berhasil

### 1. Cek Error Log

```bash
# Laravel log
tail -f storage/logs/laravel.log

# Nginx error log
sudo tail -f /var/log/nginx/error.log

# PHP-FPM error log
sudo tail -f /var/log/php8.1-fpm.log
```

### 2. Cek PHP Version

```bash
php -v
php artisan --version
```

### 3. Test Route Langsung

Buka browser dan test route:
```
https://your-domain.com/admin/medicines/1/edit
https://your-domain.com/admin/prescriptions/products/1/edit
```

### 4. Cek .env di Server

```bash
cat .env | grep APP_ENV
cat .env | grep APP_DEBUG
```

Pastikan:
- `APP_ENV=production`
- `APP_DEBUG=false` (untuk production)

---

## 💡 Tips Deployment

### Untuk Production:

Setelah clear cache, optimize untuk performa:

```bash
# Cache config (production only)
php artisan config:cache

# Cache routes (production only)
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Untuk Development:

Jangan cache di development:

```bash
# Clear semua cache
php artisan optimize:clear
```

---

## 🔐 Security Note

Jangan lupa set permission yang aman:

```bash
# Owner
sudo chown -R www-data:www-data /path/to/project

# Permissions
find /path/to/project -type f -exec chmod 644 {} \;
find /path/to/project -type d -exec chmod 755 {} \;

# Storage & cache writable
chmod -R 775 /path/to/project/storage
chmod -R 775 /path/to/project/bootstrap/cache
```

---

## 📞 Kontak Support

Jika masih ada masalah, hubungi:
- Server admin
- Hosting support
- Developer team
