# Setup Routes untuk Sistem Import & CRUD

Tambahkan routes berikut di file `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\AdminPrescriptionProductController;
use App\Http\Controllers\AdminPrescriptionProductImportController;

// ============================================
// OBAT BIASA & RESEP (UNIFIED)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin/medicines')->group(function () {
    // List, Create, Store, Edit, Update, Delete
    Route::get('/', [AdminMedicineController::class, 'index'])->name('admin.medicines.index');
    Route::get('/create', [AdminMedicineController::class, 'create'])->name('admin.medicines.create');
    Route::post('/', [AdminMedicineController::class, 'store'])->name('admin.medicines.store');
    Route::get('/{medicine}/edit', [AdminMedicineController::class, 'edit'])->name('admin.medicines.edit');
    Route::put('/{medicine}', [AdminMedicineController::class, 'update'])->name('admin.medicines.update');
    Route::delete('/{medicine}', [AdminMedicineController::class, 'destroy'])->name('admin.medicines.destroy');
    Route::post('/{medicine}/stock', [AdminMedicineController::class, 'updateStock'])->name('admin.medicines.updateStock');
    
    // Import
    Route::get('/import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('admin.medicines.import.template');
    Route::get('/import', [AdminMedicineImportController::class, 'showImportForm'])->name('admin.medicines.import.form');
    Route::post('/import', [AdminMedicineImportController::class, 'import'])->name('admin.medicines.import');
});

// ============================================
// PRODUK RESEP (KHUSUS)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin/prescriptions/products')->group(function () {
    // List, Create, Store, Edit, Update, Delete
    Route::get('/', [AdminPrescriptionProductController::class, 'index'])->name('admin.prescriptions.products.index');
    Route::get('/create', [AdminPrescriptionProductController::class, 'create'])->name('admin.prescriptions.products.create');
    Route::post('/', [AdminPrescriptionProductController::class, 'store'])->name('admin.prescriptions.products.store');
    Route::get('/{product}/edit', [AdminPrescriptionProductController::class, 'edit'])->name('admin.prescriptions.products.edit');
    Route::put('/{product}', [AdminPrescriptionProductController::class, 'update'])->name('admin.prescriptions.products.update');
    Route::delete('/{product}', [AdminPrescriptionProductController::class, 'destroy'])->name('admin.prescriptions.products.destroy');
    Route::post('/{product}/stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('admin.prescriptions.products.updateStock');
    
    // Import
    Route::get('/import/template', [AdminPrescriptionProductImportController::class, 'downloadTemplate'])->name('admin.prescriptions.products.import.template');
    Route::get('/import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('admin.prescriptions.products.import.form');
    Route::post('/import', [AdminPrescriptionProductImportController::class, 'import'])->name('admin.prescriptions.products.import');
});
```

## Daftar Routes

### Obat Biasa & Resep (Unified)

| Method | Route | Controller | Name | Fungsi |
|--------|-------|-----------|------|--------|
| GET | `/admin/medicines` | AdminMedicineController@index | `admin.medicines.index` | List obat |
| GET | `/admin/medicines/create` | AdminMedicineController@create | `admin.medicines.create` | Form tambah |
| POST | `/admin/medicines` | AdminMedicineController@store | `admin.medicines.store` | Simpan obat |
| GET | `/admin/medicines/{medicine}/edit` | AdminMedicineController@edit | `admin.medicines.edit` | Form edit |
| PUT | `/admin/medicines/{medicine}` | AdminMedicineController@update | `admin.medicines.update` | Update obat |
| DELETE | `/admin/medicines/{medicine}` | AdminMedicineController@destroy | `admin.medicines.destroy` | Hapus obat |
| POST | `/admin/medicines/{medicine}/stock` | AdminMedicineController@updateStock | `admin.medicines.updateStock` | Update stok |
| GET | `/admin/medicines/import/template` | AdminMedicineImportController@downloadTemplate | `admin.medicines.import.template` | Download template |
| GET | `/admin/medicines/import` | AdminMedicineImportController@showImportForm | `admin.medicines.import.form` | Form import |
| POST | `/admin/medicines/import` | AdminMedicineImportController@import | `admin.medicines.import` | Proses import |

### Produk Resep (Khusus)

| Method | Route | Controller | Name | Fungsi |
|--------|-------|-----------|------|--------|
| GET | `/admin/prescriptions/products` | AdminPrescriptionProductController@index | `admin.prescriptions.products.index` | List produk resep |
| GET | `/admin/prescriptions/products/create` | AdminPrescriptionProductController@create | `admin.prescriptions.products.create` | Form tambah |
| POST | `/admin/prescriptions/products` | AdminPrescriptionProductController@store | `admin.prescriptions.products.store` | Simpan produk |
| GET | `/admin/prescriptions/products/{product}/edit` | AdminPrescriptionProductController@edit | `admin.prescriptions.products.edit` | Form edit |
| PUT | `/admin/prescriptions/products/{product}` | AdminPrescriptionProductController@update | `admin.prescriptions.products.update` | Update produk |
| DELETE | `/admin/prescriptions/products/{product}` | AdminPrescriptionProductController@destroy | `admin.prescriptions.products.destroy` | Hapus produk |
| POST | `/admin/prescriptions/products/{product}/stock` | AdminPrescriptionProductController@updateStock | `admin.prescriptions.products.updateStock` | Update stok |
| GET | `/admin/prescriptions/products/import/template` | AdminPrescriptionProductImportController@downloadTemplate | `admin.prescriptions.products.import.template` | Download template |
| GET | `/admin/prescriptions/products/import` | AdminPrescriptionProductImportController@showImportForm | `admin.prescriptions.products.import.form` | Form import |
| POST | `/admin/prescriptions/products/import` | AdminPrescriptionProductImportController@import | `admin.prescriptions.products.import` | Proses import |

## Penggunaan di View

### Link ke halaman list
```blade
<a href="{{ route('admin.medicines.index') }}">Obat Biasa & Resep</a>
<a href="{{ route('admin.prescriptions.products.index') }}">Produk Resep</a>
```

### Link ke form tambah
```blade
<a href="{{ route('admin.medicines.create') }}">Tambah Obat</a>
<a href="{{ route('admin.prescriptions.products.create') }}">Tambah Produk Resep</a>
```

### Link download template
```blade
<a href="{{ route('admin.medicines.import.template') }}">Download Template Obat</a>
<a href="{{ route('admin.prescriptions.products.import.template') }}">Download Template Produk Resep</a>
```

### Link form import
```blade
<a href="{{ route('admin.medicines.import.form') }}">Import Obat</a>
<a href="{{ route('admin.prescriptions.products.import.form') }}">Import Produk Resep</a>
```

### Form edit
```blade
<form action="{{ route('admin.medicines.update', $medicine) }}" method="POST">
    @method('PUT')
    @csrf
    <!-- form fields -->
</form>

<form action="{{ route('admin.prescriptions.products.update', $product) }}" method="POST">
    @method('PUT')
    @csrf
    <!-- form fields -->
</form>
```

### Form delete
```blade
<form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST">
    @method('DELETE')
    @csrf
    <button type="submit">Hapus</button>
</form>

<form action="{{ route('admin.prescriptions.products.destroy', $product) }}" method="POST">
    @method('DELETE')
    @csrf
    <button type="submit">Hapus</button>
</form>
```

## Catatan

1. Pastikan middleware `auth` dan `admin` sudah dikonfigurasi
2. Gunakan `@csrf` di semua form POST/PUT/DELETE
3. Gunakan `@method('PUT')` atau `@method('DELETE')` untuk form HTML
4. Route names mengikuti convention: `admin.{resource}.{action}`
