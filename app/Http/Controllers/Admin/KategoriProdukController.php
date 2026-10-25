<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ActivityLogger;

class KategoriProdukController extends Controller
{

    // Menampilkan dan mencari kategori produk
    public function index(Request $request)
    {
        $cari = trim($request->input('cari', ''));

        $kategoriProduk = KategoriProduk::withCount('produk')
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($q) use ($cari) {
                    $q->where('nama_kategori', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama_kategori')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-produk.index', compact('kategoriProduk', 'cari'));
    }

    // Mengekspor laporan kategori produk ke PDF
    public function exportPdf(Request $request)
    {
        $cari = trim($request->input('cari', ''));
        $mode = $request->input('mode', 'semua');

        $kategoriProduk = KategoriProduk::withCount('produk')
            ->when($mode === 'filter' && $cari !== '', function ($query) use ($cari) {
                $query->where(function ($q) use ($cari) {
                    $q->where('nama_kategori', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama_kategori')
            ->get();

        $pdf = Pdf::loadView('admin.kategori-produk.laporan', [
            'kategoriProduk' => $kategoriProduk,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
            'judulLaporan' => $mode === 'filter'
                ? 'Laporan Kategori Produk - Hasil Pencarian'
                : 'Laporan Seluruh Kategori Produk',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-kategori-produk.pdf');
    }

    // Form tambah kategori
    public function create()
    {
        return view('admin.kategori-produk.create');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_produk', 'nama_kategori'),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kategori = KategoriProduk::create($validated);

        // Catat aktivitas tambah kategori produk
        ActivityLogger::log(
            'kategori_produk',
            'create',
            $kategori,
            null,
            $kategori->toArray(),
            'Menambahkan kategori produk: ' . $kategori->nama_kategori
        );

        return redirect()
            ->route('admin.kategori-produk.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    // Form edit kategori
    public function edit(KategoriProduk $kategori_produk)
    {
        return view('admin.kategori-produk.edit', [
            'kategori' => $kategori_produk,
        ]);
    }

    // Memperbarui kategori
    public function update(Request $request, KategoriProduk $kategori_produk)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_produk', 'nama_kategori')
                    ->ignore($kategori_produk->id),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        // Simpan data sebelum diperbarui
        $dataSebelum = $kategori_produk->toArray();

        // Perbarui kategori produk
        $kategori_produk->update($validated);

        // Catat aktivitas edit kategori produk
        ActivityLogger::log(
            'kategori_produk',
            'update',
            $kategori_produk,
            $dataSebelum,
            $kategori_produk->fresh()->toArray(),
            'Memperbarui kategori produk: ' . $kategori_produk->nama_kategori
        );

        return redirect()
            ->route('admin.kategori-produk.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy(KategoriProduk $kategori_produk)
    {
        if ($kategori_produk->produk()->exists()) {
            return redirect()
                ->route('admin.kategori-produk.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        // Simpan data sebelum dihapus
        $dataSebelum = $kategori_produk->toArray();
        $namaKategori = $kategori_produk->nama_kategori;

        // Hapus kategori produk
        $kategori_produk->delete();

        // Catat aktivitas hapus kategori produk
        ActivityLogger::log(
            'kategori_produk',
            'delete',
            $kategori_produk,
            $dataSebelum,
            null,
            'Menghapus kategori produk: ' . $namaKategori
        );

        return redirect()
            ->route('admin.kategori-produk.index')
            ->with('success', 'Kategori produk berhasil dihapus.');
    }
}