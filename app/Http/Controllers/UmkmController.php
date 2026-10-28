<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\KategoriUmkm;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    /**
     * Menampilkan daftar UMKM untuk pengunjung.
     */

    public function index(Request $request)
    {
        // Menyiapkan query daftar UMKM
        $query = Umkm::query();

        // Filter berdasarkan kategori UMKM
        if ($request->filled('kategori')) {
            $kategoriId = $request->query('kategori');

            if (ctype_digit((string) $kategoriId)) {
                $query->where('kategori_umkm_id', $kategoriId);
            }
        }

        // Pencarian berdasarkan nama UMKM
        if ($request->filled('cari')) {
            $kataKunci = trim($request->query('cari'));

            $query->where('nama_umkm', 'like', '%' . $kataKunci . '%');
        }

        // Mengurutkan nama UMKM secara alfabetis
        $umkm = $query->orderBy('nama_umkm', 'asc')->get();

        // Mengambil daftar kategori untuk pilihan filter
        $kategoriUmkm = KategoriUmkm::orderBy('nama_kategori', 'asc')->get();

        return view('umkm.index', compact('umkm', 'kategoriUmkm'));
    }

    /**
     * Menampilkan daftar UMKM untuk Admin.
     * Mendukung pencarian dan pagination.
     */
    public function adminIndex(Request $request)
    {
        // Menyiapkan query data UMKM
        $query = Umkm::query();

        // Melakukan pencarian jika ada kata kunci
        if ($request->filled('cari')) {

            $kataKunci = trim($request->query('cari'));

            $query->where(function ($q) use ($kataKunci) {
                $q->where('nama_umkm', 'like', '%' . $kataKunci . '%')
                ->orWhere('alamat', 'like', '%' . $kataKunci . '%')
                ->orWhere('telepon', 'like', '%' . $kataKunci . '%');
            });
        }

        // Mengurutkan data dan menampilkan 10 UMKM per halaman
        $umkm = $query
            ->orderBy('nama_umkm', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.umkm.index', compact('umkm'));
    }

    /**
     * Mengekspor laporan data UMKM ke PDF.
     * Mendukung pilihan semua data atau hasil pencarian.
     */
    public function exportPdf(Request $request)
    {
        // Menyiapkan query data UMKM
        $query = Umkm::query();

        // Filter hanya diterapkan jika memilih hasil pencarian
        if ($request->query('mode') === 'filter' && $request->filled('cari')) {

            $kataKunci = trim($request->query('cari'));

            $query->where(function ($q) use ($kataKunci) {
                $q->where('nama_umkm', 'like', '%' . $kataKunci . '%')
                ->orWhere('alamat', 'like', '%' . $kataKunci . '%')
                ->orWhere('telepon', 'like', '%' . $kataKunci . '%');
            });
        }

        // Mengambil seluruh data sesuai pilihan, tanpa pagination
        $umkm = $query->orderBy('nama_umkm', 'asc')->get();

        // Membuat dokumen PDF dari template laporan
        $pdf = Pdf::loadView('admin.umkm.laporan', [
            'umkm' => $umkm,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
        ])->setPaper('a4', 'landscape');

        // Mengirim laporan PDF langsung ke browser
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-data-umkm.pdf"',
        ]);
    }

    /**
     * Menampilkan formulir tambah UMKM.
     */
    public function create()
    {
        $kategori = DB::table('kategori_umkm')
            ->orderBy('nama_kategori')
            ->get();

        $wilayah = DB::table('wilayah')
            ->orderBy('kecamatan')
            ->orderBy('kelurahan_desa')
            ->get();

        return view('admin.umkm.create', compact('kategori', 'wilayah'));
    }

    /**
     * Menyimpan data UMKM baru.
     */
    public function store(Request $request)
    {
        // Validasi data formulir
        $data = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'kategori_umkm_id' => 'required|exists:kategori_umkm,id',
            'wilayah_id' => 'required|exists:wilayah,id',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',

            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2048',

            'telepon' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'keterangan_operasional' => 'nullable|string|max:100',
            'tahun_berdiri' => 'nullable|integer|between:1900,2100',

            // Validasi jadwal operasional harian
            'jadwal' => 'sometimes|array',
            'jadwal.*' => 'array',
            'jadwal.*.status' => 'required|in:belum_diatur,buka,tutup',
            'jadwal.*.jam_buka' => 'nullable|date_format:H:i',
            'jadwal.*.jam_tutup' => 'nullable|date_format:H:i',


            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_usaha' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'logo.max' => 'Ukuran logo UMKM maksimal 2 MB.',
            'foto_usaha.max' => 'Ukuran foto usaha maksimal 2 MB.',
            'logo.image' => 'Logo UMKM harus berupa gambar.',
            'foto_usaha.image' => 'Foto usaha harus berupa gambar.',
            'logo.mimes' => 'Format logo harus JPG, JPEG, PNG, atau WebP.',
            'foto_usaha.mimes' => 'Format foto usaha harus JPG, JPEG, PNG, atau WebP.',
            'logo.uploaded' => 'Logo gagal diunggah. Pastikan ukuran file kurang dari 2 MB.',
            'foto_usaha.uploaded' => 'Foto usaha gagal diunggah. Pastikan ukuran file kurang dari 2 MB.',
        ]);

        // Menyimpan logo UMKM
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')
                ->store('umkm/logo', 'public');
        }

        // Menyimpan foto usaha
        if ($request->hasFile('foto_usaha')) {
            $data['foto_usaha'] = $request->file('foto_usaha')
                ->store('umkm/foto-usaha', 'public');
        }

        // Pisahkan jadwal harian dari data utama UMKM
        $jadwalHarian = $data['jadwal'] ?? [];
        unset($data['jadwal']);

        // Menyimpan data utama UMKM
        $umkm = Umkm::create($data);

        // Menyimpan jadwal operasional harian
        foreach ($jadwalHarian as $hari => $jadwal) {

            // Pastikan nama hari sesuai
            if (!in_array($hari, [
                'Senin', 'Selasa', 'Rabu', 'Kamis',
                'Jumat', 'Sabtu', 'Minggu'
            ], true)) {
                continue;
            }

            $status = $jadwal['status'] ?? 'belum_diatur';

            // Hari yang belum diatur tidak disimpan
            if ($status === 'belum_diatur') {
                continue;
            }

            $isBuka = $status === 'buka';

            // Simpan jadwal yang berstatus Buka atau Tutup
            $umkm->jamOperasionalHarian()->create([
                'hari' => $hari,
                'is_buka' => $isBuka,
                'jam_buka' => $isBuka ? ($jadwal['jam_buka'] ?? null) : null,
                'jam_tutup' => $isBuka ? ($jadwal['jam_tutup'] ?? null) : null,
            ]);
        }

        // Mencatat aktivitas penambahan UMKM
        ActivityLogger::log(
            'UMKM',
            'create',
            $umkm,
            null,
            $umkm->getAttributes(),
            'Menambahkan UMKM: ' . $umkm->nama_umkm
        );

        return redirect()
            ->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail UMKM.
     */
    public function show(string $id)
    {
        $umkm = Umkm::with([
            'produk.kategoriProduk',
            'produk.fotoProduk',
            'produk.hargaProduk',
        ])->findOrFail($id);

        $kategori = DB::table('kategori_umkm')
            ->where('id', $umkm->kategori_umkm_id)
            ->first();

        $wilayah = DB::table('wilayah')
            ->where('id', $umkm->wilayah_id)
            ->first();


        // Mengambil ulasan UMKM yang sudah disetujui admin
        $ulasan = $umkm->ulasanDisetujui()
            ->with('user:id,name')
            ->latest()
            ->paginate(5);

        // Menghitung jumlah dan rata-rata rating UMKM
        $jumlahUlasan = $umkm->ulasanDisetujui()->count();

        $rataRating = round(
            (float) $umkm->ulasanDisetujui()->avg('rating'),
            1
        );

        return view('umkm.show', compact(
            'umkm',
            'kategori',
            'wilayah',
            'ulasan',
            'jumlahUlasan',
            'rataRating'
        ));

    }

    /**
     * Menampilkan detail UMKM khusus Admin.
     */
    public function adminShow(string $id)
    {
        $umkm = Umkm::with(['produk.kategoriProduk'])->findOrFail($id);

        $kategori = DB::table('kategori_umkm')
            ->where('id', $umkm->kategori_umkm_id)
            ->first();

        $wilayah = DB::table('wilayah')
            ->where('id', $umkm->wilayah_id)
            ->first();

        return view('admin.umkm.show', compact(
            'umkm',
            'kategori',
            'wilayah'
        ));
    }

    /**
     * Menampilkan formulir edit UMKM.
     */
    public function edit(string $id)
    {
        $umkm = Umkm::findOrFail($id);

        $kategori = DB::table('kategori_umkm')
            ->orderBy('nama_kategori')
            ->get();

        $wilayah = DB::table('wilayah')
            ->orderBy('kecamatan')
            ->orderBy('kelurahan_desa')
            ->get();

        return view('admin.umkm.edit', compact('umkm', 'kategori', 'wilayah'));
    }

    /**
     * Memperbarui data UMKM.
     */
    public function update(Request $request, string $id)
    {
        $umkm = Umkm::findOrFail($id);

        // Menyimpan kondisi data sebelum diperbarui
        $oldValues = $umkm->getAttributes();

        // Validasi data formulir
        $data = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'kategori_umkm_id' => 'required|exists:kategori_umkm,id',
            'wilayah_id' => 'required|exists:wilayah,id',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',

            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2048',

            'telepon' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'keterangan_operasional' => 'nullable|string|max:100',
            'tahun_berdiri' => 'nullable|integer|between:1900,2100',

            // Validasi jadwal operasional harian
            'jadwal' => 'sometimes|array',
            'jadwal.*' => 'array',
            'jadwal.*.status' => 'required|in:belum_diatur,buka,tutup',
            'jadwal.*.jam_buka' => 'nullable|date_format:H:i',
            'jadwal.*.jam_tutup' => 'nullable|date_format:H:i',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_usaha' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'logo.max' => 'Ukuran logo UMKM maksimal 2 MB.',
            'foto_usaha.max' => 'Ukuran foto usaha maksimal 2 MB.',
            'logo.image' => 'Logo UMKM harus berupa gambar.',
            'foto_usaha.image' => 'Foto usaha harus berupa gambar.',
            'logo.mimes' => 'Format logo harus JPG, JPEG, PNG, atau WebP.',
            'foto_usaha.mimes' => 'Format foto usaha harus JPG, JPEG, PNG, atau WebP.',
            'logo.uploaded' => 'Logo gagal diunggah. Pastikan ukuran file kurang dari 2 MB.',
            'foto_usaha.uploaded' => 'Foto usaha gagal diunggah. Pastikan ukuran file kurang dari 2 MB.',
        ]);

        // Mengganti logo UMKM
        if ($request->hasFile('logo')) {
            $logoBaru = $request->file('logo')
                ->store('umkm/logo', 'public');

            // Menghapus logo lama
            if ($umkm->logo && Storage::disk('public')->exists($umkm->logo)) {
                Storage::disk('public')->delete($umkm->logo);
            }

            $data['logo'] = $logoBaru;
        }

        // Mengganti foto usaha
        if ($request->hasFile('foto_usaha')) {
            $fotoBaru = $request->file('foto_usaha')
                ->store('umkm/foto-usaha', 'public');

            // Menghapus foto lama
            if ($umkm->foto_usaha && Storage::disk('public')->exists($umkm->foto_usaha)) {
                Storage::disk('public')->delete($umkm->foto_usaha);
            }

            $data['foto_usaha'] = $fotoBaru;
        }

        // Pisahkan jadwal harian dari data utama UMKM
        $jadwalHarian = $data['jadwal'] ?? [];
        unset($data['jadwal']);

        // Memperbarui data utama UMKM
        $umkm->update($data);

        // Menyimpan jadwal operasional harian
        foreach ($jadwalHarian as $hari => $jadwal) {

            // Hanya menerima nama hari yang valid
            if (!in_array($hari, [
                'Senin', 'Selasa', 'Rabu', 'Kamis',
                'Jumat', 'Sabtu', 'Minggu'
            ], true)) {
                continue;
            }

            $status = $jadwal['status'] ?? 'belum_diatur';

            // Hapus jadwal harian jika status Belum Diatur
            if ($status === 'belum_diatur') {
                $umkm->jamOperasionalHarian()
                    ->where('hari', $hari)
                    ->delete();

                continue;
            }

            // Menentukan status buka atau tutup
            $isBuka = $status === 'buka';

            // Simpan atau perbarui jadwal tanpa duplikasi
            $umkm->jamOperasionalHarian()->updateOrCreate(
                ['hari' => $hari],
                [
                    'is_buka' => $isBuka,
                    'jam_buka' => $isBuka ? ($jadwal['jam_buka'] ?? null) : null,
                    'jam_tutup' => $isBuka ? ($jadwal['jam_tutup'] ?? null) : null,
                ]
            );
        }

        // Mengambil data UMKM setelah diperbarui
        $newValues = $umkm->fresh()->getAttributes();

        // Mencatat aktivitas perubahan UMKM
        ActivityLogger::log(
            'UMKM',
            'update',
            $umkm,
            $oldValues,
            $newValues,
            'Memperbarui UMKM: ' . $umkm->nama_umkm
        );

        // Respons JSON untuk penyimpanan melalui AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data UMKM berhasil diperbarui.',
                'redirect' => route('admin.umkm.index'),
            ]);
        }

        // Respons untuk penyimpanan formulir biasa
        return redirect()->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil diperbarui.');
    }

    /**
     * Menghapus data UMKM beserta file gambarnya.
     */
    public function destroy(string $id)
    {
        $umkm = Umkm::findOrFail($id);

        if ($umkm->produk()->exists()) {
            return redirect()
                ->route('admin.umkm.index')
                ->with('error', 'UMKM tidak dapat dihapus karena masih memiliki produk. Hapus seluruh produk terkait terlebih dahulu.');
        }

        // Menyimpan data UMKM sebelum dihapus
        $oldValues = $umkm->getAttributes();
        $namaUmkm = $umkm->nama_umkm;

        // Menghapus data UMKM dari database
        $umkm->delete();

        // Mencatat aktivitas penghapusan UMKM
        ActivityLogger::log(
            'UMKM',
            'delete',
            $umkm,
            $oldValues,
            null,
            'Menghapus UMKM: ' . $namaUmkm
        );

        // Menghapus logo UMKM
        if ($umkm->logo && Storage::disk('public')->exists($umkm->logo)) {
            Storage::disk('public')->delete($umkm->logo);
        }

        // Menghapus foto usaha
        if ($umkm->foto_usaha && Storage::disk('public')->exists($umkm->foto_usaha)) {
            Storage::disk('public')->delete($umkm->foto_usaha);
        }

        return redirect()
            ->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil dihapus.');
    }
}
