<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Produk;
use App\Models\KategoriUmkm;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 6 UMKM terbaru
        $umkmTerbaru = Umkm::latest()
            ->take(6)
            ->get();

     // Mengambil 8 produk terbaru beserta UMKM dan foto produk
        $produkTerbaru = Produk::with(['umkm', 'fotoProduk'])
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

    /**
     * Live Search UMKM dan produk.
     */
    public function search(Request $request)
    {
        // Mengambil kata kunci pencarian
        $kataKunci = trim((string) $request->query('cari', ''));

        // Validasi kata kunci
        $request->merge(['cari' => $kataKunci]);

        $request->validate([
            'cari' => 'nullable|string|max:100',
        ]);

        // Jika pencarian kosong
        if ($kataKunci === '') {
            return response()->json([
                'umkm' => [],
                'produk' => [],
            ]);
        }

        // Mencari UMKM berdasarkan nama
        $umkm = Umkm::query()
            ->where('nama_umkm', 'like', '%' . $kataKunci . '%')
            ->orderBy('nama_umkm', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($usaha) {
                return [
                    'id' => $usaha->id,
                    'nama' => $usaha->nama_umkm,
                    'url' => route('umkm.show', $usaha->id),
                ];
            });

        // Mencari produk berdasarkan nama
        $produk = Produk::query()
            ->where('nama_produk', 'like', '%' . $kataKunci . '%')
            ->orderBy('nama_produk', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama_produk,
                    'url' => route('produk.show', $item->id),
                ];
            });

        // Mengirim hasil pencarian tanpa reload
        return response()->json([
            'umkm' => $umkm,
            'produk' => $produk,
        ]);
    }

}
