# 🐛 Troubleshooting: Bug Route Saat Edit & Upload Foto

## Masalah yang Dilaporkan
Saat edit produk dan menambah foto, ada bug route yang masih ada.

---

## ✅ Yang Sudah Diperbaiki

### 1. Controller - AdminMedicineController.php
```php
public function update(Request $request, Medicine $medicine)
{
    $validated = $request->validate([
        'nama_obat' => ['required', 'string', 'max:255'],
        'kategori'  => ['required', 'string'],
        'harga'     => ['required', 'numeric', 'min:0'],
        'stok'      => ['required', 'integer', 'min:0'],
        'komposisi' => ['required', 'string', 'max:255'],
        'indikasi'  => ['required', 'string', 'max:255'],
        'golongan'  => ['required', 'in:BEBAS,KERAS'],
        'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        'delete_gambar' => ['nullable', 'boolean'], // ✅ DITAMBAHKAN
    ]);

    // ... kode lainnya

    // Handle upload gambar baru
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama
        if ($medicine->gambar) {
            Storage::delete('public/' . $medicine->gambar);
        }

        // Upload gambar baru
        $image = $request->file('gambar');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/medicines', $imageName);
        $validated['gambar'] = 'medicines/' . $imageName;
    } elseif ($request->input('delete_gambar') == '1' && $medicine->gambar) {
        // ✅ DIPERBAIKI: Gunakan input() bukan boolean()
        Storage::delete('public/' . $medicine->gambar);
        $validated['gambar'] = null;
    }

    $medicine->update($validated);

    return redirect()->route('admin.medicines.index')
                   ->with('success', 'Obat berhasil diupdate!');
}
```

### 2. Controller - AdminPrescriptionProductController.php
```php
public function update(Request $request, Medicine $product)
{
    if (!$product->is_resep) {
        abort(404);
    }

    $validated = $request->validate([
        'nama_obat' => ['required', 'string', 'max:255'],
        'kategori'  => ['required', 'string'],
        'harga'     => ['required', 'numeric', 'min:0'],
        'stok'      => ['required', 'integer', 'min:0'],
        'komposisi' => ['required', 'string', 'max:255'],
        'indikasi'  => ['required', 'string', 'max:255'],
        'golongan'  => ['required', 'in:BEBAS,KERAS'], // ✅ DITAMBAHKAN
        'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        'delete_gambar' => ['nullable', 'boolean'], // ✅ DITAMBAHKAN
    ]);

    // Tentukan is_resep berdasarkan golongan
    $validated['is_resep'] = ($validated['golongan'] === 'KERAS');
    
    // Gabung komposisi dan indikasi untuk deskripsi
    $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
    
    // Hapus field yang tidak perlu di database
    unset($validated['komposisi']);
    unset($validated['indikasi']);
    unset($validated['golongan']);
    unset($validated['delete_gambar']);

    // Handle upload gambar baru
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama
        if ($product->gambar) {
            Storage::delete('public/' . $product->gambar);
        }

        // Upload gambar baru
        $image = $request->file('gambar');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/medicines', $imageName);
        $validated['gambar'] = 'medicines/' . $imageName;
    } elseif ($request->input('delete_gambar') == '1' && $product->gambar) {
        // ✅ DIPERBAIKI: Gunakan input() bukan boolean()
        Storage::delete('public/' . $product->gambar);
        $validated['gambar'] = null;
    }

    $product->update($validated);

    return redirect()->route('admin.prescriptions.products.index')
                   ->with('success', 'Produk resep berhasil diupdate!');
}
```

---

## 🔍 Kemungkinan Penyebab Bug

### 1. **Cache Laravel Belum Di-Clear**
Route cache masih menyimpan versi lama.

**Solusi:**
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. **Gambar Tidak Muncul (Path Salah)**
Path gambar di view mungkin salah.

**Cek di view:**
```blade
@if($medicine->gambar)
    <img src="{{ asset('storage/' . $medicine->gambar) }}" alt="{{ $medicine->nama_obat }}">
@endif
```

**Path yang benar:**
- Database: `medicines/filename.jpg`
- Storage: `storage/app/public/medicines/filename.jpg`
- URL: `public/storage/medicines/filename.jpg`

### 3. **Storage Link Belum Dibuat**
Symbolic link dari `public/storage` ke `storage/app/public` belum ada.

**Solusi:**
```bash
php artisan storage:link
```

### 4. **Form Tidak Mengirim File**
Form harus memiliki `enctype="multipart/form-data"`.

**Cek di view:**
```blade
<form action="{{ route('admin.medicines.update', $medicine->id) }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- form fields -->
</form>
```

### 5. **Permission Folder Storage**
Folder storage tidak writable.

**Solusi:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
```

---

## 🧪 Test Manual

### Test 1: Cek Route
```bash
php artisan route:list --name=medicines.update
```

**Expected Output:**
```
PUT|PATCH  admin/medicines/{medicine}  admin.medicines.update
```

### Test 2: Cek Storage Link
```bash
ls -la public/storage
```

**Expected Output:**
```
lrwxrwxrwx 1 user user 30 Jan 1 12:00 storage -> ../../storage/app/public
```

### Test 3: Cek Permission
```bash
ls -la storage/app/public/medicines/
```

**Expected Output:**
```
drwxrwxr-x 2 www-data www-data 4096 Jan 1 12:00 .
```

### Test 4: Test Upload Manual
Buat file test PHP:

```php
<?php
// test-upload.php - Upload di root project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gambar'])) {
    $file = $_FILES['gambar'];
    $uploadDir = __DIR__ . '/storage/app/public/medicines/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    
    $filename = time() . '_' . basename($file['name']);
    $uploadPath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo "✅ Upload berhasil: " . $filename;
        echo "<br>Path: " . $uploadPath;
        echo "<br>URL: " . "/storage/medicines/" . $filename;
    } else {
        echo "❌ Upload gagal!";
        echo "<br>Error: " . $file['error'];
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Test Upload</title></head>
<body>
    <h1>Test Upload Gambar</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="gambar" accept="image/*" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
```

---

## 🎯 Langkah Debugging

### Step 1: Cek Error Log
```bash
tail -f storage/logs/laravel.log
```

### Step 2: Enable Debug Mode (Development Only)
Di `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

### Step 3: Test dengan dd()
Tambahkan di controller:

```php
public function update(Request $request, Medicine $medicine)
{
    // Debug: Lihat semua data yang dikirim
    dd([
        'has_file' => $request->hasFile('gambar'),
        'delete_gambar' => $request->input('delete_gambar'),
        'all_data' => $request->all(),
        'files' => $request->allFiles(),
    ]);
    
    // ... kode lainnya
}
```

### Step 4: Cek Database
```sql
SELECT id, nama_obat, gambar FROM medicines WHERE id = 1;
```

### Step 5: Cek File Fisik
```bash
ls -lh storage/app/public/medicines/
```

---

## 📋 Checklist Troubleshooting

- [ ] File controller sudah terupdate (cek dengan `cat app/Http/Controllers/AdminMedicineController.php | grep delete_gambar`)
- [ ] Cache sudah di-clear (`php artisan route:clear`)
- [ ] Storage link sudah dibuat (`php artisan storage:link`)
- [ ] Permission folder storage sudah benar (775)
- [ ] Form memiliki `enctype="multipart/form-data"`
- [ ] Route update sudah benar (`php artisan route:list --name=medicines.update`)
- [ ] Browser cache sudah di-clear (Ctrl+Shift+R)
- [ ] Test di Incognito mode
- [ ] Cek error log Laravel (`tail -f storage/logs/laravel.log`)
- [ ] PHP-FPM sudah di-restart (jika di server)
- [ ] Web server sudah di-restart (jika di server)

---

## 💡 Solusi Cepat

Jalankan command ini secara berurutan:

```bash
# 1. Clear semua cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan clear-compiled

# 2. Buat storage link
php artisan storage:link

# 3. Set permission
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 4. Jika di server, restart services
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
```

---

## 🆘 Jika Masih Error

### Coba Test Sederhana:

1. **Buat produk baru TANPA foto** → Apakah berhasil?
2. **Edit produk TANPA mengubah foto** → Apakah berhasil?
3. **Edit produk DAN upload foto baru** → Apakah berhasil?
4. **Edit produk DAN hapus foto** → Apakah berhasil?

### Kirim Info Berikut:

1. **Error message** (screenshot atau copy text)
2. **Laravel log** (`storage/logs/laravel.log`)
3. **Browser console error** (F12 → Console tab)
4. **Network tab** (F12 → Network tab → klik request yang error)
5. **PHP version** (`php -v`)
6. **Laravel version** (`php artisan --version`)

---

## 📝 Catatan Penting

1. **Jangan lupa `enctype="multipart/form-data"`** di form
2. **Path gambar di database**: `medicines/filename.jpg` (tanpa `storage/`)
3. **Path gambar di view**: `asset('storage/' . $medicine->gambar)`
4. **Storage link harus ada**: `public/storage` → `storage/app/public`
5. **Permission folder**: 775 untuk storage dan bootstrap/cache
6. **Clear cache** setiap kali update controller atau route
