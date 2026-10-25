<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UlasanController extends Controller
{
    // Menyimpan rating dan ulasan pengunjung
    public function store(Request $request)
    {
        // Hanya pengunjung yang boleh memberikan ulasan
        if (!Auth::check() || Auth::user()->role !== 'pengunjung') {
            abort(403, 'Hanya pengunjung yang dapat memberikan ulasan.');
        }

        // Validasi rating, komentar, dan objek ulasan
        $data = $request->validate([
            'umkm_id' => [
                'nullable',
                'integer',
                'exists:umkm,id',
                'required_without:produk_id',
                'prohibits:produk_id',
            ],
            'produk_id' => [
                'nullable',
                'integer',
                'exists:produk,id',
                'required_without:umkm_id',
                'prohibits:umkm_id',
            ],
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['required', 'string', 'max:2000'],
        ], [
            'umkm_id.required_without' =>
                'Harap pilih jenis yang ingin diulas, yaitu UMKM atau Produk.',
            'produk_id.required_without' =>
                'Harap pilih jenis yang ingin diulas, yaitu UMKM atau Produk.',
            'umkm_id.prohibits' =>
                'Ulasan hanya boleh ditujukan kepada satu UMKM atau satu produk.',
            'produk_id.prohibits' =>
                'Ulasan hanya boleh ditujukan kepada satu UMKM atau satu produk.',
            'rating.required' => 'Silakan pilih rating terlebih dahulu.',
            'rating.between' => 'Rating harus antara 1 sampai 5 bintang.',
            'komentar.required' => 'Silakan tulis ulasan terlebih dahulu.',
            'komentar.max' => 'Ulasan maksimal 2000 karakter.',
        ]);

        // Pemeriksaan tambahan agar tepat satu objek dipilih
        $adaUmkm = !empty($data['umkm_id']);
        $adaProduk = !empty($data['produk_id']);

        if ($adaUmkm === $adaProduk) {
            throw ValidationException::withMessages([
                'objek_ulasan' =>
                    'Harap pilih satu UMKM atau produk yang ingin diulas.',
            ]);
        }

        // Menyimpan ulasan dengan status menunggu persetujuan
        Ulasan::create([
            'user_id' => Auth::id(),
            'umkm_id' => $data['umkm_id'] ?? null,
            'produk_id' => $data['produk_id'] ?? null,
            'rating' => $data['rating'],
            'komentar' => $data['komentar'],
            'status' => 'pending',
        ]);

        // Kembali ke halaman detail objek yang diulas
        if ($adaUmkm) {
            return redirect()
                ->route('umkm.show', $data['umkm_id'])
                ->with('success', 'Ulasan berhasil dikirim dan menunggu persetujuan admin.');
        }

        return redirect()
            ->route('produk.show', $data['produk_id'])
            ->with('success', 'Ulasan berhasil dikirim dan menunggu persetujuan admin.');
    }
}
