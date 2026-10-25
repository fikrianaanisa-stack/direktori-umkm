<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    /**
     * Menampilkan daftar data wilayah pada halaman Admin.
     */

/* Menampilkan daftar wilayah dan pencarian */
public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $wilayah = \App\Models\Wilayah::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kecamatan', 'like', '%' . $search . '%')
                    ->orWhere('kelurahan_desa', 'like', '%' . $search . '%')
                    ->orWhere('jenis_wilayah', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('kecamatan', 'asc')
            ->orderBy('kelurahan_desa', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.wilayah.index', compact('wilayah'));
    }

    /**
     * Menampilkan formulir tambah data wilayah.
     */
    public function create()
    {
        // Membuka halaman formulir tambah wilayah
        return view('admin.wilayah.create');
    }

    /**
     * Memvalidasi dan menyimpan data wilayah baru ke database.
     */
    public function store(Request $request)
    {
        // Memvalidasi data yang dikirim dari formulir
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:100',
            'kelurahan_desa' => 'required|string|max:100',
            'jenis_wilayah' => 'required|in:Desa,Kelurahan,Negeri',
        ]);

        // Menyimpan data wilayah baru ke tabel wilayah
            DB::transaction(function () use ($validated) {
        // Simpan data wilayah baru
        $wilayah = Wilayah::create($validated);

        // Catat aktivitas tambah wilayah
        ActivityLogger::log(
            'wilayah',
            'create',
            $wilayah,
            null,
            $wilayah->toArray(),
            'Menambahkan wilayah: ' . $wilayah->kelurahan_desa .
            ' - Kecamatan ' . $wilayah->kecamatan
        );
    });

        // Mengarahkan kembali ke halaman daftar wilayah
        // serta menampilkan pesan bahwa data berhasil disimpan
        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', 'Data wilayah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan formulir edit data wilayah.
     */
    public function edit(string $id)
    {
        // Mencari data wilayah berdasarkan ID
        $wilayah = \App\Models\Wilayah::findOrFail($id);

        // Mengirimkan data wilayah ke formulir edit
        return view('admin.wilayah.edit', compact('wilayah'));
    }

    /**
     * Memvalidasi dan memperbarui data wilayah.
     */
    public function update(Request $request, string $id)
    {
        // Mencari data wilayah yang akan diperbarui
        $wilayah = \App\Models\Wilayah::findOrFail($id);

        // Memvalidasi data yang dikirim dari formulir
        $validated = $request->validate([
            'kecamatan' => 'required|string|max:100',
            'kelurahan_desa' => 'required|string|max:100',
            'jenis_wilayah' => 'required|in:Desa,Kelurahan,Negeri',
        ]);

        // Memperbarui data wilayah di database
            DB::transaction(function () use ($wilayah, $validated) {
        // Simpan data sebelum diperbarui
        $dataSebelum = $wilayah->toArray();

        // Perbarui data wilayah
        $wilayah->update($validated);

        // Simpan data setelah diperbarui
        $dataSesudah = $wilayah->fresh()->toArray();

        // Catat aktivitas edit wilayah
        ActivityLogger::log(
            'wilayah',
            'update',
            $wilayah,
            $dataSebelum,
            $dataSesudah,
            'Memperbarui wilayah: ' . $wilayah->kelurahan_desa .
            ' - Kecamatan ' . $wilayah->kecamatan
        );
    });

        // Mengarahkan kembali ke daftar wilayah
        // dan menampilkan pesan keberhasilan
        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', 'Data wilayah berhasil diperbarui.');
    }

    /**
     * Menghapus data wilayah dari database.
     * Wilayah yang masih digunakan oleh UMKM tidak boleh dihapus.
     */
    public function destroy(string $id)
    {
        // Mencari data wilayah berdasarkan ID
        $wilayah = \App\Models\Wilayah::findOrFail($id);

        // Memeriksa apakah wilayah masih digunakan oleh UMKM
        if ($wilayah->umkm()->exists()) {
            return redirect()
                ->route('admin.wilayah.index')
                ->with('error', 'Wilayah tidak dapat dihapus karena masih digunakan oleh UMKM.');
        }

        // Menghapus wilayah apabila tidak digunakan oleh UMKM
            DB::transaction(function () use ($wilayah) {
        // Simpan data sebelum dihapus
        $dataSebelum = $wilayah->toArray();

        $namaWilayah = $wilayah->kelurahan_desa;
        $namaKecamatan = $wilayah->kecamatan;

        // Hapus wilayah
        $wilayah->delete();

        // Catat aktivitas hapus wilayah
        ActivityLogger::log(
            'wilayah',
            'delete',
            $wilayah,
            $dataSebelum,
            null,
            'Menghapus wilayah: ' . $namaWilayah .
            ' - Kecamatan ' . $namaKecamatan
        );
    });

        // Kembali ke daftar wilayah dengan pesan keberhasilan
        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', 'Data wilayah berhasil dihapus.');
    }


    /* Mengekspor laporan data wilayah ke PDF */
    public function exportPdf(Request $request)
    {
        $search = trim($request->input('search', ''));
        $mode = $request->input('mode', 'semua');

        $wilayah = Wilayah::query()
            ->when($mode === 'filter' && $search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kecamatan', 'like', "%{$search}%")
                    ->orWhere('kelurahan_desa', 'like', "%{$search}%")
                    ->orWhere('jenis_wilayah', 'like', "%{$search}%");
                });
            })
            ->orderBy('kecamatan', 'asc')
            ->orderBy('kelurahan_desa', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.wilayah.laporan', [
            'wilayah' => $wilayah,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
            'judulLaporan' => $mode === 'filter'
                ? 'Laporan Data Wilayah - Hasil Pencarian'
                : 'Laporan Seluruh Data Wilayah',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-data-wilayah.pdf');
}

}
