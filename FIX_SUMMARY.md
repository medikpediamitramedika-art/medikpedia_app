# 🔧 Summary Perbaikan Bug Upload Foto di Form Edit

## 📌 Masalah
Saat edit produk (Obat OTC atau Produk Resep) dan menambah foto, terjadi bug route.

---

## ✅ Perbaikan yang Sudah Dilakukan

### 1. **AdminMedicineController.php**
File: `app/Http/Controllers/AdminMedicineController.php`

**Perubahan:**
- ✅ Tambah validasi `'delete_gambar' => ['nullable', 'boolean']`
- ✅ Ubah `$request->boolean('delete_gambar')` menjadi `$request->input('delete_gambar') == '1'`
- ✅ Tambah `unset($validated['delete_gambar'])` sebelum update

### 2. **AdminPrescriptionProductController.php**
File: `app/Http/Controllers/AdminPrescriptionProductController.php`

**Perubahan di method `store()`:**
- ✅ Tambah validasi `'golongan' => ['required', 'in:BEBAS,KERAS']`
- ✅ Tambah logika `$validated['is_resep'] = ($validated['golongan'] === 'KERAS')`

**Perubahan di method `update()`:**
- ✅ Tambah validasi `'golongan' => ['required', 'in:BEBAS,KERAS']`
- ✅ Tambah validasi `'delete_gambar' => ['nullable', 'boolean']`
- ✅ Tambah logika `$validated['is_resep'] = ($validated['golongan'] === 'KERAS')`
- ✅ Ubah `$request->boolean('delete_gambar')` menjadi `$request->input('delete_gambar') == '1'`
- ✅ Tambah `unset($validated['delete_gambar'])` sebelum update

### 3. **Form Edit (View)**
File: 
- `resources/views/admin/medicines/edit.blade.php`
- `resources/views/admin/prescriptions/products/edit.blade.php`

**Sudah Benar:**
- ✅ Form memiliki `enctype="multipart/form-data"`
- ✅ Form memiliki `@csrf` dan `@method('PUT')`
- ✅ Input file memiliki `name="gambar"`
- ✅ Checkbox hapus memiliki `name="delete_gambar" value="1"`
- ✅ Route action sudah benar

---

## 🚀 Cara Deploy ke Server

### Step 1: Upload File yang Diubah
Upload file berikut ke server:
```
app/Http/Controllers/AdminMedicineController.php
app/Http/Controllers/AdminPrescriptionProductController.php
```

### Step 2: Clear Cache di Server
Login ke server via SSH, lalu jalankan:

```bash
cd /path/to/your/project

# Clear semua cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan clear-compiled

# Atau gunakan script
bash clear-cache-server.sh
```

### Step 3: Restart Services
```bash
# Restart PHP-FPM (sesuaikan versi PHP)
sudo systemctl restart php8.1-fpm

# Restart Nginx
sudo systemctl restart nginx

# Atau restart Apache
sudo systemctl restart apache2
```

### Step 4: Verify
1. Clear browser cache (Ctrl + Shift + R)
2. Test edit produk dan upload foto
3. Cek apakah foto berhasil tersimpan di `storage/app/public/medicines/`

---

## 🧪 Testing

### Test Case 1: Edit Tanpa Mengubah Foto
1. Buka halaman edit produk
2. Ubah nama obat atau field lain
3. **Jangan** upload foto baru
4. **Jangan** centang "Hapus Foto"
5. Klik "Simpan Perubahan"
6. **Expected:** Data berhasil diupdate, foto lama tetap ada

### Test Case 2: Edit dan Upload Foto Baru
1. Buka halaman edit produk
2. Pilih foto baru untuk diupload
3. Klik "Simpan Perubahan"
4. **Expected:** Data berhasil diupdate, foto lama terhapus, foto baru tersimpan

### Test Case 3: Edit dan Hapus Foto
1. Buka halaman edit produk (yang sudah punya foto)
2. Centang checkbox "Hapus Foto"
3. **Jangan** upload foto baru
4. Klik "Simpan Perubahan"
5. **Expected:** Data berhasil diupdate, foto terhapus

### Test Case 4: Edit, Hapus Foto Lama, Upload Foto Baru
1. Buka halaman edit produk (yang sudah punya foto)
2. Centang checkbox "Hapus Foto"
3. Pilih foto baru untuk diupload
4. Klik "Simpan Perubahan"
5. **Expected:** Data berhasil diupdate, foto lama terhapus, foto baru tersimpan

---

## 🐛 Troubleshooting

### Masalah: Foto tidak muncul setelah upload

**Penyebab:** Storage link belum dibuat

**Solusi:**
```bash
php artisan storage:link
```

**Verify:**
```bash
ls -la public/storage
# Harus ada symbolic link ke ../../storage/app/public
```

---

### Masalah: Error "The gambar failed to upload"

**Penyebab:** Permission folder storage salah

**Solusi:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
```

---

### Masalah: Route tidak ditemukan (404)

**Penyebab:** Route cache belum di-clear

**Solusi:**
```bash
php artisan route:clear
php artisan cache:clear
```

**Verify:**
```bash
php artisan route:list --name=medicines.update
# Harus muncul: PUT|PATCH admin/medicines/{medicine}
```

---

### Masalah: Perubahan di local sudah benar, tapi di server masih error

**Penyebab:** File belum terupload atau cache belum di-clear

**Solusi:**
1. Pastikan file controller sudah terupload ke server
2. Cek tanggal modifikasi file:
   ```bash
   ls -lh app/Http/Controllers/AdminMedicineController.php
   ```
3. Clear semua cache:
   ```bash
   php artisan optimize:clear
   ```
4. Restart PHP-FPM dan web server
5. Clear browser cache (Ctrl + Shift + R)

---

## 📁 Struktur File yang Benar

```
project/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── AdminMedicineController.php ✅ UPDATED
│           └── AdminPrescriptionProductController.php ✅ UPDATED
├── resources/
│   └── views/
│       └── admin/
│           ├── medicines/
│           │   ├── create.blade.php ✅ OK
│           │   └── edit.blade.php ✅ OK
│           └── prescriptions/
│               └── products/
│                   ├── create.blade.php ✅ OK
│                   └── edit.blade.php ✅ OK
├── storage/
│   └── app/
│       └── public/
│           └── medicines/ ← Foto disimpan di sini
└── public/
    └── storage/ → ../../storage/app/public (symbolic link)
```

---

## 🔍 Cara Cek Apakah Sudah Benar

### 1. Cek Controller
```bash
cat app/Http/Controllers/AdminMedicineController.php | grep "delete_gambar"
```

**Expected Output:**
```php
'delete_gambar' => ['nullable', 'boolean'],
elseif ($request->input('delete_gambar') == '1' && $medicine->gambar) {
unset($validated['delete_gambar']);
```

### 2. Cek Route
```bash
php artisan route:list --name=medicines
```

**Expected Output:**
```
PUT|PATCH  admin/medicines/{medicine}  admin.medicines.update
```

### 3. Cek Storage Link
```bash
ls -la public/storage
```

**Expected Output:**
```
lrwxrwxrwx ... storage -> ../../storage/app/public
```

### 4. Cek Permission
```bash
ls -la storage/app/public/
```

**Expected Output:**
```
drwxrwxr-x ... medicines
```

---

## 📞 Jika Masih Bermasalah

Kirim informasi berikut:

1. **Screenshot error** (jika ada)
2. **Laravel log:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```
3. **Browser console error** (F12 → Console)
4. **Network request** (F12 → Network → klik request yang error)
5. **PHP version:**
   ```bash
   php -v
   ```
6. **Laravel version:**
   ```bash
   php artisan --version
   ```
7. **Output command:**
   ```bash
   php artisan route:list --name=medicines.update
   ```

---

## ✨ File Helper yang Dibuat

1. **clear-cache-server.sh** - Script untuk clear cache di server
2. **DEPLOY_CHECKLIST.md** - Checklist lengkap untuk deploy
3. **TEST_UPLOAD_ISSUE.md** - Panduan troubleshooting upload
4. **test-form-edit.html** - Form HTML untuk test upload
5. **check-update.php** - Script PHP untuk cek file terupdate
6. **FIX_SUMMARY.md** - Summary perbaikan (file ini)

---

## 🎯 Kesimpulan

Semua perbaikan sudah dilakukan di:
- ✅ Controller (AdminMedicineController & AdminPrescriptionProductController)
- ✅ View (form edit sudah benar)
- ✅ Validasi (sudah handle delete_gambar dan golongan)

**Yang perlu dilakukan:**
1. Upload file controller ke server
2. Clear cache di server
3. Restart PHP-FPM dan web server
4. Test upload foto

Jika sudah mengikuti semua langkah di atas dan masih error, kemungkinan besar masalahnya ada di:
- Permission folder storage
- Storage link belum dibuat
- PHP configuration (upload_max_filesize, post_max_size)
