<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah UMKM
        $totalUmkm = Umkm::count();

        // Menghitung jumlah produk
        $totalProduk = Produk::count();

        // Menghitung jumlah pengunjung terdaftar
        $totalPengunjung = User::where('role', 'pengunjung')->count();

        // Menghitung jumlah pengelola
        $totalPengelola = User::whereIn('role', [
            'super_admin',
            'admin'
        ])->count();

        // Menampilkan dashboard
        return view('admin.dashboard', compact(
            'totalUmkm',
            'totalProduk',
            'totalPengunjung',
            'totalPengelola'
        ));
    }
}
