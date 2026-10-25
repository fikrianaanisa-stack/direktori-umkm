<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUmkm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ActivityLogger;

class KategoriUmkmController extends Controller
{
    // Menampilkan daftar kategori UMKM
    public function index(Request $request)
    {
        $cari = trim($request->input('cari', ''));

        $kategoriUmkm = KategoriUmkm::withCount('umkm')
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($q) use ($cari) {
                    $q->where('nama_kategori', 'like', "%{$cari}%")
                    ->orWhere('deskripsi', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama_kategori')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori-umkm.index', compact(
            'kategoriUmkm',
            'cari'
        ));
    }

    // Mengekspor laporan kategori UMKM ke PDF
    public function exportPdf(Request $request)
    {
        $cari = trim($request->input('cari', ''));
        $mode = $request->input('mode', 'semua');

        $kategoriUmkm = KategoriUmkm::withCount('umkm')
            ->when($mode === 'filter' && $cari !== '', function ($query) use ($cari) {
                $query->where(function ($q) use ($cari) {
                    $q->where('nama_kategori', 'like', "%{$cari}%")
                      ->orWhere('deskripsi', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama_kategori')
            ->get();

        $pdf = Pdf::loadView('admin.kategori-umkm.laporan', [
            'kategoriUmkm' => $kategoriUmkm,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
            'judulLaporan' => $mode === 'filter'
                ? 'Laporan Kategori UMKM - Hasil Pencarian'
                : 'Laporan Seluruh Kategori UMKM',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-kategori-umkm.pdf');
    }


    // Menampilkan formulir tambah kategori
    public function create()
    {
        return view('admin.kategori-umkm.create');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_umkm', 'nama_kategori'),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kategori = KategoriUmkm::create($validated);

        // Catat aktivitas tambah kategori UMKM
        ActivityLogger::log(
            'kategori_umkm',
            'create',
            $kategori,
            null,
            $kategori->toArray(),
            'Menambahkan kategori UMKM: ' . $kategori->nama_kategori
        );

        return redirect()
            ->route('admin.kategori-umkm.index')
            ->with('success', 'Kategori UMKM berhasil ditambahkan.');
    }

    // Menampilkan formulir edit kategori
    public function edit(KategoriUmkm $kategori_umkm)
    {
        return view('admin.kategori-umkm.edit', [
            'kategori' => $kategori_umkm,
        ]);
    }

    // Memperbarui kategori
    public function update(Request $request, KategoriUmkm $kategori_umkm)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_umkm', 'nama_kategori')
                    ->ignore($kategori_umkm->id),
            ],
            'deskripsi' => ['nullable', 'string'],
        ]);

        // Simpan data sebelum diperbarui
        $dataSebelum = $kategori_umkm->toArray();

        // Perbarui kategori UMKM
        $kategori_umkm->update($validated);

        // Catat aktivitas edit kategori UMKM
        ActivityLogger::log(
            'kategori_umkm',
            'update',
            $kategori_umkm,
            $dataSebelum,
            $kategori_umkm->fresh()->toArray(),
            'Memperbarui kategori UMKM: ' . $kategori_umkm->nama_kategori
        );

        return redirect()
            ->route('admin.kategori-umkm.index')
            ->with('success', 'Kategori UMKM berhasil diperbarui.');
    }

    // Menghapus kategori yang tidak digunakan
    public function destroy(KategoriUmkm $kategori_umkm)
    {
        if ($kategori_umkm->umkm()->exists()) {
            return redirect()
                ->route('admin.kategori-umkm.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh UMKM.');
        }

        // Simpan data sebelum kategori dihapus
        $dataSebelum = $kategori_umkm->toArray();
        $namaKategori = $kategori_umkm->nama_kategori;

        // Hapus kategori UMKM
        $kategori_umkm->delete();

        // Catat aktivitas hapus kategori UMKM
        ActivityLogger::log(
            'kategori_umkm',
            'delete',
            $kategori_umkm,
            $dataSebelum,
            null,
            'Menghapus kategori UMKM: ' . $namaKategori
        );

        return redirect()
            ->route('admin.kategori-umkm.index')
            ->with('success', 'Kategori UMKM berhasil dihapus.');
    }
}