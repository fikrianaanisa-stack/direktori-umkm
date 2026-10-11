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

        return view('umkm.admin-index', compact('umkm'));
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

        return view('umkm.create', compact('kategori', 'wilayah'));
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

            'telepon' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'jam_operasional' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|integer|between:1900,2100',

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

        // Menyimpan data ke database
        Umkm::create($data);

        return redirect()
            ->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail UMKM.
     */
    public function show(string $id)
    {
        $umkm = Umkm::findOrFail($id);

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
        $umkm = Umkm::findOrFail($id);

        $kategori = DB::table('kategori_umkm')
            ->where('id', $umkm->kategori_umkm_id)
            ->first();

        $wilayah = DB::table('wilayah')
            ->where('id', $umkm->wilayah_id)
            ->first();

        return view('umkm.admin-show', compact(
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

        return view('umkm.edit', compact('umkm', 'kategori', 'wilayah'));
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

            'telepon' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'jam_operasional' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|integer|between:1900,2100',

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

        // Memperbarui data di database
        $umkm->update($data);

        return redirect()
            ->route('admin.umkm.index')
            ->with('success', 'Data UMKM berhasil diperbarui.');
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
