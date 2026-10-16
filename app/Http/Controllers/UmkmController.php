<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    /**
     * Menampilkan daftar UMKM untuk pengunjung.
     */
    public function index()
    {
        $umkm = Umkm::orderBy('nama_umkm', 'asc')->get();

        return view('umkm.index', compact('umkm'));
    }

    /**
     * Menampilkan halaman pengelolaan UMKM untuk Admin.
     */
    public function adminIndex()
    {
        $umkm = Umkm::orderBy('nama_umkm', 'asc')->get();

        return view('admin.umkm.index', compact('umkm'));
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
            'jam_operasional' => 'nullable|string|max:255',
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

        // Memastikan hari berstatus Buka memiliki jam lengkap
        foreach ($data['jadwal'] ?? [] as $hari => $jadwal) {
            if (($jadwal['status'] ?? '') !== 'buka') {
                continue;
            }

            if (empty($jadwal['jam_buka']) || empty($jadwal['jam_tutup'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jadwal' => "Jam buka dan jam tutup hari {$hari} wajib diisi.",
                ]);
            }
        }

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

        return redirect()
            ->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail UMKM.
     */
    public function show(string $id)
    {
        $umkm = Umkm::with(['produk.kategoriProduk'])->findOrFail($id);

        $kategori = DB::table('kategori_umkm')
            ->where('id', $umkm->kategori_umkm_id)
            ->first();

        $wilayah = DB::table('wilayah')
            ->where('id', $umkm->wilayah_id)
            ->first();

        return view('umkm.show', compact('umkm', 'kategori', 'wilayah'));
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
            'jam_operasional' => 'nullable|string|max:255',
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

        // Memastikan hari berstatus Buka memiliki jam lengkap
        foreach ($data['jadwal'] ?? [] as $hari => $jadwal) {
            if (($jadwal['status'] ?? '') !== 'buka') {
                continue;
            }

            if (empty($jadwal['jam_buka']) || empty($jadwal['jam_tutup'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jadwal' => "Jam buka dan jam tutup hari {$hari} wajib diisi.",
                ]);
            }
        }

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

        // Respons JSON untuk penyimpanan melalui AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data UMKM dan jadwal operasional berhasil diperbarui.',
                'redirect' => route('admin.umkm.index'),
            ]);
        }

        // Respons untuk penyimpanan formulir biasa
        return redirect()->route('admin.umkm.index')
            ->with('success', 'Data UMKM dan jadwal operasional berhasil diperbarui.');
    }

    /**
     * Menghapus data UMKM beserta file gambarnya.
     */
    public function destroy(string $id)
    {
        $umkm = Umkm::findOrFail($id);

        // Menghapus data dari database
        $umkm->delete();

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
