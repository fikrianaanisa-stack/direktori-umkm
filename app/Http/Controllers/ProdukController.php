<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Umkm;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with(['umkm', 'kategoriProduk'])
            ->orderBy('id', 'desc')
            ->get();

        return view('produk.index', compact('produk'));
    }

    // Menampilkan formulir tambah produk
    public function create()
    {
        $umkm = Umkm::orderBy('nama_umkm')->get();

        $kategoriProduk = KategoriProduk::orderBy('nama_kategori')->get();

        return view('produk.create', compact('umkm', 'kategoriProduk'));
    }

        // Menyimpan data produk baru
        public function store(Request $request)
        {
            // Validasi data formulir
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'kategori_produk_id' => 'required|exists:kategori_produk,id',
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // Upload foto produk jika tersedia
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')
                    ->store('produk', 'public');
            }

            // Simpan produk ke database
            Produk::create($validated);

            // Kembali ke halaman daftar produk
            return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        }

        // Menampilkan formulir edit produk
        public function edit($id)
        {
            $produk = Produk::findOrFail($id);

            $umkm = Umkm::orderBy('nama_umkm')->get();

            $kategoriProduk = KategoriProduk::orderBy('nama_kategori')->get();

            return view('produk.edit', compact(
                'produk',
                'umkm',
                'kategoriProduk'
            ));
        }

        // Memperbarui data produk
        public function update(Request $request, $id)
        {
            // Cari produk berdasarkan ID
            $produk = Produk::findOrFail($id);

            // Validasi data formulir
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'kategori_produk_id' => 'required|exists:kategori_produk,id',
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // Jika ada foto baru, ganti foto lama
            if ($request->hasFile('foto')) {
                $fotoBaru = $request->file('foto')->store('produk', 'public');

                // Hapus foto lama jika tersedia
                if ($produk->foto) {
                    \Illuminate\Support\Facades\Storage::disk('public')
                        ->delete($produk->foto);
                }

                $validated['foto'] = $fotoBaru;
            }

            // Perbarui data produk
            $produk->update($validated);

            // Kembali ke halaman daftar produk
            return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil diperbarui.');
        }

        // Menghapus data produk
        public function destroy($id)
        {
            // Cari produk berdasarkan ID
            $produk = Produk::findOrFail($id);

            // Hapus foto produk jika tersedia
            if ($produk->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($produk->foto);
            }

            // Hapus data produk dari database
            $produk->delete();

            // Kembali ke halaman daftar produk
            return redirect()
                ->route('produk.index')
                ->with('success', 'Produk berhasil dihapus.');
        }
}