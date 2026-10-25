<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Produk;
use App\Models\User;
use App\Models\KategoriUmkm;

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

        // Menghitung jumlah admin
        $totalPengelola = User::where('role', 'admin')->count();

        // Mengambil kategori UMKM yang memiliki data UMKM
        $kategoriUmkm = KategoriUmkm::withCount('umkm')
            ->has('umkm')
            ->orderBy('nama_kategori')
            ->get();

        $labelKategori = $kategoriUmkm->pluck('nama_kategori');
        $jumlahKategori = $kategoriUmkm->pluck('umkm_count');

        return view('admin.dashboard', compact(
            'totalUmkm',
            'totalProduk',
            'totalPengunjung',
            'totalPengelola',
            'labelKategori',
            'jumlahKategori'
        ));
    }
}
