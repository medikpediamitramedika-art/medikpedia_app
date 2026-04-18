<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminProdukImportController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk Kami
Route::get('/produk-kami', [ProductController::class, 'index'])->name('products.index');

// Detail produk
Route::get('/obat/{id}', [HomeController::class, 'show'])->name('medicines.show');

// Kategori
Route::get('/kategori/{kategori}', [HomeController::class, 'byCategory'])->name('medicines.category');

Route::get('/hubungi-kami', function () {
    return view('contact');
})->name('contact');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('admin.dashboard.stats');

    // Produk Kami
    Route::resource('produk', AdminProdukController::class, ['as' => 'admin']);
    Route::post('produk/{produk}/update-stock', [AdminProdukController::class, 'updateStock'])->name('admin.produk.updateStock');

    // Import Produk
    Route::get('produk-import', [AdminProdukImportController::class, 'showImportForm'])->name('admin.produk.import');
    Route::post('produk-import', [AdminProdukImportController::class, 'import'])->name('admin.produk.import.process');
    Route::get('produk-import/template', [AdminProdukImportController::class, 'downloadTemplate'])->name('admin.produk.import.template');
});
