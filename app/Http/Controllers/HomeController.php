<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Produk;
use App\Models\KategoriUmkm;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 6 UMKM terbaru
        $umkmTerbaru = Umkm::latest()
            ->take(6)
            ->get();

        // Mengambil 8 produk terbaru beserta data UMKM
        $produkTerbaru = Produk::with('umkm')
            ->latest()
            ->take(8)
            ->get();

        // Menghitung jumlah UMKM dan produk
        $jumlahUmkm = Umkm::count();
        $jumlahProduk = Produk::count();

        // Mengambil kategori UMKM beserta jumlah usaha
        $kategoriUmkm = KategoriUmkm::withCount('umkm')
            ->orderBy('id')
            ->get();

        // Mengirim data ke halaman utama
        return view('home', compact(
            'umkmTerbaru',
            'produkTerbaru',
            'jumlahUmkm',
            'jumlahProduk',
            'kategoriUmkm'
        ));
    }
}
