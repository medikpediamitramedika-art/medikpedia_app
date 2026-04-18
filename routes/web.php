<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\AdminPrescriptionController;
use App\Http\Controllers\AdminPrescriptionImportController;
use App\Http\Controllers\AdminPrescriptionProductController;
use App\Http\Controllers\AdminPrescriptionProductImportController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminProdukImportController;
use App\Http\Controllers\AdminGrosirController;
use App\Http\Controllers\AdminGrosirImportController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products-grosir', [ProductController::class, 'grosir'])->name('products.grosir');

// Prescriptions routes
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/customer/logout', [AuthController::class, 'customerLogout'])->name('customer.logout');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Medicines management
    Route::resource('medicines', AdminMedicineController::class);
    Route::post('medicines/{medicine}/update-stock', [AdminMedicineController::class, 'updateStock'])->name('medicines.update-stock');
    Route::get('medicines-import', [AdminMedicineImportController::class, 'showImportForm'])->name('medicines.import');
    Route::post('medicines-import', [AdminMedicineImportController::class, 'import'])->name('medicines.import.process');
    
    // Prescriptions management
    Route::resource('prescriptions', AdminPrescriptionController::class);
    Route::post('prescriptions/{prescription}/update-stock', [AdminPrescriptionController::class, 'updateStock'])->name('prescriptions.update-stock');
    Route::get('prescriptions-import', [AdminPrescriptionImportController::class, 'showImportForm'])->name('prescriptions.import');
    Route::post('prescriptions-import', [AdminPrescriptionImportController::class, 'import'])->name('prescriptions.import.process');
    
    // Prescription Products management
    Route::resource('prescription-products', AdminPrescriptionProductController::class);
    Route::post('prescription-products/{prescriptionProduct}/update-stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('prescription-products.update-stock');
    Route::get('prescription-products-import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('prescription-products.import');
    Route::post('prescription-products-import', [AdminPrescriptionProductImportController::class, 'import'])->name('prescription-products.import.process');
    
    // Produk management
    Route::resource('produk', AdminProdukController::class);
    Route::post('produk/{produk}/update-stock', [AdminProdukController::class, 'updateStock'])->name('produk.update-stock');
    Route::get('produk-import', [AdminProdukImportController::class, 'showImportForm'])->name('produk.import');
    Route::post('produk-import', [AdminProdukImportController::class, 'import'])->name('produk.import.process');
    
    // Grosir management
    Route::resource('grosir', AdminGrosirController::class);
    Route::post('grosir/{grosir}/update-stock', [AdminGrosirController::class, 'updateStock'])->name('grosir.update-stock');
    Route::get('grosir-import', [AdminGrosirImportController::class, 'showImportForm'])->name('grosir.import');
    Route::post('grosir-import', [AdminGrosirImportController::class, 'import'])->name('grosir.import.process');
});

// DEBUG ROUTE - Hapus setelah selesai debug
Route::get('/debug-images', function() {
    $medicines = \App\Models\Medicine::whereNotNull('gambar')->take(5)->get();
    
    $debug = [
        'base_path' => base_path(),
        'public_path' => public_path(),
        'storage_path' => storage_path(),
        'public_storage_path' => public_path('storage'),
        'medicines_path' => public_path('storage/medicines'),
        'medicines_exists' => is_dir(public_path('storage/medicines')),
        'medicines_writable' => is_writable(public_path('storage/medicines')),
        'app_url' => config('app.url'),
        'request_url' => request()->url(),
        'request_root' => request()->root(),
        'medicines' => []
    ];
    
    foreach ($medicines as $med) {
        $fullPath = public_path('storage/' . $med->gambar);
        $debug['medicines'][] = [
            'id' => $med->id,
            'nama' => $med->nama_obat,
            'gambar_db' => $med->gambar,
            'full_path' => $fullPath,
            'file_exists' => file_exists($fullPath),
            'file_size' => file_exists($fullPath) ? filesize($fullPath) : 0,
            'url_asset' => asset('storage/' . $med->gambar),
            'url_url' => url('storage/' . $med->gambar),
        ];
    }
    
    // List files in storage/medicines
    $medicinesDir = public_path('storage/medicines');
    if (is_dir($medicinesDir)) {
        $files = scandir($medicinesDir);
        $debug['files_in_medicines'] = array_filter($files, fn($f) => !in_array($f, ['.', '..']));
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});
