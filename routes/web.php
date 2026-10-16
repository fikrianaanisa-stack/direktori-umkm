<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;

// Halaman utama website
Route::get('/', function () {
    return view('welcome');
});

// Menampilkan daftar UMKM
Route::get('/umkm', [UmkmController::class, 'index'])
    ->name('umkm.index');

// Menampilkan formulir tambah UMKM
Route::get('/umkm/create', [UmkmController::class, 'create'])
    ->middleware(['auth', 'admin'])
    ->name('umkm.create');

// Menyimpan data UMKM baru
Route::post('/umkm', [UmkmController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('umkm.store');

// Menampilkan formulir edit UMKM
Route::get('/umkm/{id}/edit', [UmkmController::class, 'edit'])
    ->middleware(['auth', 'admin'])
    ->name('umkm.edit');

// Menyimpan perubahan data UMKM
Route::put('/umkm/{id}', [UmkmController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('umkm.update');

// Menampilkan detail UMKM
Route::get('/umkm/{id}', [UmkmController::class, 'show'])
    ->name('umkm.show');

// Menghapus data UMKM
Route::delete('/umkm/{id}', [UmkmController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('umkm.destroy');

// Route Daftar Produk
Route::get('/produk', [ProdukController::class, 'index'])
    ->name('produk.index');

// Route Formulir Tambah Produk
Route::get('/produk/create', [ProdukController::class, 'create'])
    ->middleware(['auth', 'admin'])
    ->name('produk.create');

// Route Simpan Produk
Route::post('/produk', [ProdukController::class, 'store'])
    ->middleware(['auth', 'admin'])
    ->name('produk.store');

 // Menampilkan detail produk untuk pengunjung
Route::get('/produk/{id}', [ProdukController::class, 'show'])
    ->whereNumber('id')
    ->name('produk.show');

// Route Formulir Edit Produk
Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])
    ->middleware(['auth', 'admin'])
    ->name('produk.edit');

// Route Memperbarui Data Produk
Route::put('/produk/{id}', [ProdukController::class, 'update'])
    ->middleware(['auth', 'admin'])
    ->name('produk.update');

// Route Menghapus Data Produk
Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])
    ->middleware(['auth', 'admin'])
    ->name('produk.destroy');

// Menampilkan halaman login
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

// Memproses login
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

// Memproses logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard khusus Super Admin dan Admin
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

// Halaman pengelolaan UMKM khusus Admin dan Super Admin */
Route::get('/admin/umkm', [UmkmController::class, 'adminIndex'])
    ->middleware(['auth', 'admin'])
    ->name('admin.umkm.index');

 //Menampilkan detail UMKM khusus Admin dan Super Admin.
Route::get('/admin/umkm/{id}', [UmkmController::class, 'adminShow'])
    ->middleware(['auth', 'admin'])
    ->name('admin.umkm.show');

// Daftar produk admin
Route::get('/admin/produk', [ProdukController::class, 'adminIndex'])
    ->middleware(['auth', 'admin'])
    ->name('admin.produk.index');