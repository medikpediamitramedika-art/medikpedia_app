<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMedicineController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminNewsController;
use App\Http\Controllers\AdminMedicineImportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminActivityController;

// ===== FRONTEND ROUTES =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products');
Route::get('/obat/{id}', [HomeController::class, 'show'])->name('medicines.show');
Route::get('/kategori/{kategori}', [HomeController::class, 'byCategory'])->name('medicines.category');

// Promo Routes
Route::get('/promo', [NewsController::class, 'index'])->name('news.index');
Route::get('/promo/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/tentang-kami', [NewsController::class, 'about'])->name('about');
Route::get('/hubungi-kami', function () { return view('contact'); })->name('contact');
Route::get('/aktivitas', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/farmakologi', [\App\Http\Controllers\HomeController::class, 'farmakologi'])->name('farmakologi');

// ===== AUTH ROUTES =====
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Register admin (hanya untuk setup awal)
Route::get('/admin/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/admin/register', [AuthController::class, 'register'])->name('register.post');

// ===== ADMIN ROUTES (Protected) =====
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Obat Management
    Route::resource('medicines', AdminMedicineController::class, ['as' => 'admin']);
    Route::post('medicines/{medicine}/update-stock', [AdminMedicineController::class, 'updateStock'])->name('admin.medicines.updateStock');

    // Import Obat via Excel/CSV
    Route::get('medicines-import', [AdminMedicineImportController::class, 'showImportForm'])->name('admin.medicines.import');
    Route::post('medicines-import', [AdminMedicineImportController::class, 'import'])->name('admin.medicines.import.process');
    Route::get('medicines-import/template', [AdminMedicineImportController::class, 'downloadTemplate'])->name('admin.medicines.import.template');

    // News Management
    Route::resource('news', AdminNewsController::class, ['as' => 'admin']);

    // Aktivitas Management
    Route::resource('activities', AdminActivityController::class, ['as' => 'admin']);
});
