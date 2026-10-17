<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;

// Halaman utama publik Direktori UMKM Kota Ambon
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Direktori UMKM
Route::get('/umkm', [UmkmController::class, 'index'])
    ->name('umkm.index');

Route::get('/umkm/{id}', [UmkmController::class, 'show'])
    ->whereNumber('id')
    ->name('umkm.show');

// Katalog produk
Route::get('/produk', [ProdukController::class, 'index'])
    ->name('produk.index');

Route::get('/produk/{id}', [ProdukController::class, 'show'])
    ->whereNumber('id')
    ->name('produk.show');

// Autentikasi pengguna
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Route pengelolaan untuk Admin dan Super Admin
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

   
    // Pengelolaan data UMKM
    Route::get('/admin/umkm', [UmkmController::class, 'adminIndex'])
        ->name('admin.umkm.index');

    Route::get('/admin/umkm/create', [UmkmController::class, 'create'])
        ->name('umkm.create');

    Route::post('/admin/umkm', [UmkmController::class, 'store'])
        ->name('umkm.store');

    Route::get('/admin/umkm/{id}/edit', [UmkmController::class, 'edit'])
        ->whereNumber('id')
        ->name('umkm.edit');

    Route::put('/admin/umkm/{id}', [UmkmController::class, 'update'])
        ->whereNumber('id')
        ->name('umkm.update');

    Route::delete('/admin/umkm/{id}', [UmkmController::class, 'destroy'])
        ->whereNumber('id')
        ->name('umkm.destroy');

    Route::get('/admin/umkm/{id}', [UmkmController::class, 'adminShow'])
        ->whereNumber('id')
        ->name('admin.umkm.show');

    
    // Pengelolaan data produk
    Route::get('/admin/produk', [ProdukController::class, 'adminIndex'])
        ->name('admin.produk.index');

    Route::get('/admin/produk/create', [ProdukController::class, 'create'])
        ->name('produk.create');

    Route::post('/admin/produk', [ProdukController::class, 'store'])
        ->name('produk.store');

    Route::get('/admin/produk/{id}/edit', [ProdukController::class, 'edit'])
        ->whereNumber('id')
        ->name('produk.edit');

    Route::put('/admin/produk/{id}', [ProdukController::class, 'update'])
        ->whereNumber('id')
        ->name('produk.update');

    Route::delete('/admin/produk/{id}', [ProdukController::class, 'destroy'])
        ->whereNumber('id')
        ->name('produk.destroy');

});
