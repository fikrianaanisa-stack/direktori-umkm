<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PengelolaController;
use App\Http\Controllers\Admin\KategoriUmkmController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UlasanController as AdminUlasanController;
use App\Http\Controllers\Pengunjung\ProfileController as PengunjungProfileController;
use App\Http\Controllers\Pengunjung\PasswordController as PengunjungPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Halaman publik
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Pencarian gabungan UMKM dan produk
Route::get('/pencarian', [HomeController::class, 'search'])
    ->name('search.index');

// Halaman kontak
Route::view('/kontak', 'kontak')
    ->name('kontak');

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

// Pengiriman rating dan ulasan pengunjung
Route::post('/ulasan', [UlasanController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('ulasan.store');

// Autentikasi
Route::middleware('guest')->group(function () {

    // Login admin dan pengunjung
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    // Registrasi akun pengunjung
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.store');
});


// Logout akun admin dan pengunjung
// Hanya pengguna yang sudah login yang dapat mengakses route ini
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// HALAMAN PENGUNJUNG
Route::middleware(['auth'])
    ->prefix('pengunjung')
    ->name('pengunjung.')
    ->group(function () {

        // Menampilkan profil pengunjung
        Route::get('/profile', [PengunjungProfileController::class, 'index'])
            ->name('profile.index');

        // Memperbarui profil pengunjung
        Route::put('/profile', [PengunjungProfileController::class, 'update'])
            ->name('profile.update');

        // Ubah password pengunjung
        Route::get('/password', [PengunjungPasswordController::class, 'edit'])->name('password.edit');
        Route::put('/password', [PengunjungPasswordController::class, 'update'])->name('password.update');

        // Menampilkan pemberitahuan verifikasi email
        Route::get('/verify-email', function () {
            abort_unless(auth()->user()->role === 'pengunjung', 403);

            return view('pengunjung.verify-email');
        })->name('verification.notice');

        // Memproses tautan verifikasi email
        Route::get('/verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
            abort_unless($request->user()->role === 'pengunjung', 403);

            $request->fulfill();

            return redirect()->route('pengunjung.profile.index')
                ->with('success', 'Email berhasil diverifikasi.');
        })->middleware('signed')->name('verification.verify');

        // Mengirim ulang email verifikasi
        Route::post('/email/verification-notification', function (Request $request) {
            abort_unless($request->user()->role === 'pengunjung', 403);

            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->route('pengunjung.profile.index')
                    ->with('success', 'Email Anda sudah terverifikasi.');
            }

            $request->user()->sendEmailVerificationNotification();

            return back()->with('success', 'Tautan verifikasi telah dikirim ulang.');
        })->middleware('throttle:6,1')->name('verification.send');

    });

// Pengalihan middleware verified ke halaman verifikasi pengunjung
Route::get('/email/verify', function () {
    return redirect()->route('pengunjung.verification.notice');
})->middleware('auth')->name('verification.notice');



// Halaman Admin
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    
    // Profil Admin
    Route::get('/admin/profile', [ProfileController::class, 'edit'])
        ->name('admin.profile.edit');

    Route::put('/admin/profile', [ProfileController::class, 'update'])
        ->name('admin.profile.update');

    // Ubah Password Admin
    Route::get('/admin/password', [ProfileController::class, 'editPassword'])
        ->name('admin.password.edit');

    Route::put('/admin/password', [ProfileController::class, 'updatePassword'])
        ->name('admin.password.update');

    // Pengelolaan UMKM
    Route::get('/admin/umkm', [UmkmController::class, 'adminIndex'])
        ->name('admin.umkm.index');

    Route::get('/admin/umkm/export/pdf', [UmkmController::class, 'exportPdf'])
        ->name('admin.umkm.export.pdf');

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

    // Pengelolaan Produk
    Route::get('/admin/produk', [ProdukController::class, 'adminIndex'])
        ->name('admin.produk.index');

    Route::get('/admin/produk/export/pdf', [ProdukController::class, 'exportPdf'])
        ->name('admin.produk.export.pdf');

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

    // Menghapus satu foto dari galeri produk
    Route::delete('/admin/produk/{id}/foto/{fotoId}', [ProdukController::class, 'destroyFoto'])
        ->whereNumber('id')
        ->whereNumber('fotoId')
        ->name('produk.foto.destroy');

    // Detail Produk Admin
    Route::get('/admin/produk/{id}', [ProdukController::class, 'adminShow'])
        ->whereNumber('id')
        ->name('admin.produk.show');
    
    // Pengelolaan Master Data
    Route::prefix('admin')->name('admin.')->group(function () {

        // Kategori UMKM
        Route::get('kategori-umkm/export/pdf', [KategoriUmkmController::class, 'exportPdf'])
            ->name('kategori-umkm.export.pdf');

        Route::resource('kategori-umkm', KategoriUmkmController::class)
            ->except(['show']);

       // Kategori Produk
        Route::get('kategori-produk/export/pdf', [KategoriProdukController::class, 'exportPdf'])
            ->name('kategori-produk.export.pdf');

        Route::resource('kategori-produk', KategoriProdukController::class)
            ->except(['show']);

        // Export PDF Data Wilayah
        Route::get('wilayah/export-pdf', [WilayahController::class, 'exportPdf'])
            ->name('wilayah.exportPdf');

        // Pengelolaan Data Wilayah
        Route::resource('wilayah', WilayahController::class)
            ->except(['show']);

    });

    // Riwayat Aktivitas Admin
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])
        ->name('admin.activity-logs.index');
    
    // Daftar Pengelola Admin (hanya menampilkan data)
    Route::get('/admin/pengelola', [PengelolaController::class, 'index'])
        ->name('admin.pengelola.index');

    // Moderasi ulasan pengunjung
    Route::get('/admin/ulasan', [AdminUlasanController::class, 'index'])
        ->name('admin.ulasan.index');

    // Menyetujui ulasan
    Route::patch('/admin/ulasan/{ulasan}/approve', [AdminUlasanController::class, 'approve'])
        ->name('admin.ulasan.approve');

    // Menolak ulasan
    Route::patch('/admin/ulasan/{ulasan}/reject', [AdminUlasanController::class, 'reject'])
        ->name('admin.ulasan.reject');

    
    // Menghapus ulasan yang sudah disetujui atau ditolak
    Route::delete('/admin/ulasan/{ulasan}', [AdminUlasanController::class, 'destroy'])
        ->name('admin.ulasan.destroy');

});
