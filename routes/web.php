<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\AdminGrosirController; // 🔥 BARU
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\AdminGrosirImportController; // 🔥 BARU
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminActivityController;
use App\Http\Controllers\AdminPrescriptionController;
use App\Http\Controllers\AdminPrescriptionProductController;
use App\Http\Controllers\AdminPrescriptionProductImportController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// 🔥 RETAIL (existing)
Route::get('/produk-retail', [ProductController::class, 'index'])->name('products.retail');

// 🔥 GROSIR (BARU)
Route::get('/produk-grosir', [ProductController::class, 'grosir'])->name('products.grosir');

// Detail produk
Route::get('/obat/{id}', [HomeController::class, 'show'])->name('medicines.show');

// Kategori
Route::get('/kategori/{kategori}', [HomeController::class, 'byCategory'])->name('medicines.category');

// News / Promo
Route::get('/promo', [NewsController::class, 'index'])->name('news.index');
Route::get('/promo/{id}', [NewsController::class, 'show'])->name('news.show');

Route::get('/tentang-kami', [NewsController::class, 'about'])->name('about');
Route::get('/hubungi-kami', function () {
    return view('contact');
})->name('contact');

Route::get('/aktivitas', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/farmakologi', [HomeController::class, 'farmakologi'])->name('farmakologi');

// 🔥 PRODUK RESEP (public)
Route::get('/produk-resep', [PrescriptionController::class, 'index'])->name('prescriptions');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// register (setup awal)
Route::get('/admin/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('register.post');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | RETAIL MANAGEMENT (EXISTING)
    |--------------------------------------------------------------------------
    */
    Route::resource('medicines', AdminMedicineController::class, ['as' => 'admin']);
    Route::post('medicines/{medicine}/update-stock', [AdminMedicineController::class, 'updateStock'])->name('admin.medicines.updateStock');

    // Import Retail
    Route::get('medicines-import', [AdminMedicineImportController::class, 'showImportForm'])->name('admin.medicines.import');
    Route::post('medicines-import', [AdminMedicineImportController::class, 'import'])->name('admin.medicines.import.process');
    Route::get('medicines-import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('admin.medicines.import.template');


    /*
    |--------------------------------------------------------------------------
    | GROSIR MANAGEMENT (🔥 BARU)
    |--------------------------------------------------------------------------
    */
    Route::resource('grosir', AdminGrosirController::class, ['as' => 'admin']);
    Route::post('grosir/{grosir}/update-stock', [AdminGrosirController::class, 'updateStock'])->name('admin.grosir.updateStock');

    // Import Grosir
    Route::get('grosir-import', [AdminGrosirImportController::class, 'showImportForm'])->name('admin.grosir.import');
    Route::post('grosir-import', [AdminGrosirImportController::class, 'import'])->name('admin.grosir.import.process');
    Route::get('grosir-import/template', [AdminGrosirImportController::class, 'downloadTemplate'])->name('admin.grosir.import.template');


    /*
    |--------------------------------------------------------------------------
    | PRODUK RESEP
    |--------------------------------------------------------------------------
    */
    Route::resource('prescriptions', AdminPrescriptionController::class, ['as' => 'admin']);
    Route::post('prescriptions/{prescription}/update-stock', [AdminPrescriptionController::class, 'updateStock'])->name('admin.prescriptions.updateStock');

    // Import resep
    Route::get('prescriptions-import', [AdminPrescriptionController::class, 'showImportForm'])->name('admin.prescriptions.import');
    Route::post('prescriptions-import', [AdminPrescriptionController::class, 'import'])->name('admin.prescriptions.import.process');
    Route::get('prescriptions-import/template', [AdminPrescriptionController::class, 'downloadTemplate'])->name('admin.prescriptions.import.template');


    /*
    |--------------------------------------------------------------------------
    | PRODUK RESEP KHUSUS
    |--------------------------------------------------------------------------
    */
    Route::resource('prescriptions/products', AdminPrescriptionProductController::class, ['as' => 'admin.prescriptions']);
    Route::post('prescriptions/products/{product}/update-stock', [AdminPrescriptionProductController::class, 'updateStock'])->name('admin.prescriptions.products.updateStock');

    Route::get('prescriptions/products-import', [AdminPrescriptionProductImportController::class, 'showImportForm'])->name('admin.prescriptions.products.import');
    Route::post('prescriptions/products-import', [AdminPrescriptionProductImportController::class, 'import'])->name('admin.prescriptions.products.import.process');
    Route::get('prescriptions/products-import/template', [AdminPrescriptionProductImportController::class, 'downloadTemplate'])->name('admin.prescriptions.products.import.template');


    /*
    |--------------------------------------------------------------------------
    | AKTIVITAS
    |--------------------------------------------------------------------------
    */
    Route::resource('activities', AdminActivityController::class, ['as' => 'admin']);
});