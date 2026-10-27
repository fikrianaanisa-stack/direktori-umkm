<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Umkm;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ActivityLogger;

class ProdukController extends Controller
{
    // Menampilkan katalog produk publik
    public function index(Request $request)
    {
        $query = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk',
            'fotoProduk'
        ]);

        // Filter berdasarkan kategori produk
        if ($request->filled('kategori')) {
            $kategoriId = $request->query('kategori');

            if (ctype_digit((string) $kategoriId)) {
                $query->where('kategori_produk_id', $kategoriId);
            }
        }

        // Pencarian berdasarkan nama produk
        if ($request->filled('cari')) {
            $kataKunci = trim($request->query('cari'));

            $query->where('nama_produk', 'like', '%' . $kataKunci . '%');
        }

        $produk = $query->orderBy('id', 'desc')->get();

        $kategoriProduk = KategoriProduk::orderBy('nama_kategori', 'asc')
            ->get();

        return view('produk.index', compact('produk', 'kategoriProduk'));
    }

    // Menampilkan detail produk publik
    public function show($id)
    {
        $produk = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ])->findOrFail($id);

        $ulasan = $produk->ulasanDisetujui()
            ->with('user:id,name')
            ->latest()
            ->paginate(5);

        $jumlahUlasan = $produk->ulasanDisetujui()->count();

        $rataRating = round(
            (float) $produk->ulasanDisetujui()->avg('rating'),
            1
        );

        return view('produk.show', compact(
            'produk',
            'ulasan',
            'jumlahUlasan',
            'rataRating'
        ));
    }

    // Menampilkan detail produk dalam dashboard admin
    public function adminShow($id)
    {
        $produk = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ])->findOrFail($id);

        return view('admin.produk.show', compact('produk'));
    }

    // Menampilkan daftar produk admin
    public function adminIndex(Request $request)
    {
        $query = Produk::with([
            'umkm',
            'kategoriProduk',
            'fotoProduk'
        ]);

        // Pencarian produk, UMKM, dan kategori
        if ($request->filled('cari')) {
            $kataKunci = trim($request->query('cari'));

            $query->where(function ($q) use ($kataKunci) {
                $q->where('nama_produk', 'like', '%' . $kataKunci . '%')
                    ->orWhereHas('umkm', function ($umkm) use ($kataKunci) {
                        $umkm->where('nama_umkm', 'like', '%' . $kataKunci . '%');
                    })
                    ->orWhereHas('kategoriProduk', function ($kategori) use ($kataKunci) {
                        $kategori->where('nama_kategori', 'like', '%' . $kataKunci . '%');
                    });
            });
        }

        $produk = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.produk.index', compact('produk'));
    }

    // Export laporan produk ke PDF
    public function exportPdf(Request $request)
    {
        $query = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ]);

        // Filter berdasarkan hasil pencarian
        if ($request->query('mode') === 'filter' && $request->filled('cari')) {
            $kataKunci = trim($request->query('cari'));

            $query->where(function ($q) use ($kataKunci) {
                $q->where('nama_produk', 'like', '%' . $kataKunci . '%')
                    ->orWhereHas('umkm', function ($umkm) use ($kataKunci) {
                        $umkm->where('nama_umkm', 'like', '%' . $kataKunci . '%');
                    })
                    ->orWhereHas('kategoriProduk', function ($kategori) use ($kataKunci) {
                        $kategori->where('nama_kategori', 'like', '%' . $kataKunci . '%');
                    });
            });
        }

        $produk = $query->orderBy('id', 'desc')->get();

        $pdf = Pdf::loadView('admin.produk.laporan', [
            'produk' => $produk,
            'tanggalCetak' => now()->format('d-m-Y H:i'),
        ])->setPaper('a4', 'landscape');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-data-produk.pdf"',
        ]);
    }

    // Menampilkan formulir tambah produk
    public function create()
    {
        $umkm = Umkm::orderBy('nama_umkm')->get();

        $kategoriProduk = KategoriProduk::orderBy('nama_kategori')->get();

        return view('admin.produk.create', compact('umkm', 'kategoriProduk'));
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'umkm_id' => 'required|exists:umkm,id',
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

            // Validasi pilihan harga
            'harga_produk' => 'sometimes|array',
            'harga_produk.*.harga_min' => 'required|numeric|min:0',
            'harga_produk.*.harga_max' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    preg_match(
                        '/^harga_produk\.(\d+)\.harga_max$/',
                        $attribute,
                        $matches
                    );

                    $index = $matches[1] ?? null;

                    $minimum = $index !== null
                        ? $request->input("harga_produk.$index.harga_min")
                        : null;

                    if (
                        is_numeric($value) &&
                        is_numeric($minimum) &&
                        (float) $value < (float) $minimum
                    ) {
                        $fail('Harga maksimum harus lebih besar atau sama dengan harga minimum.');
                    }
                },
            ],
            'harga_produk.*.jumlah_satuan' => 'required|numeric|gt:0',
            'harga_produk.*.satuan' => 'nullable|string|max:50',

            // Foto produk
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_tambahan' => 'nullable|array',
            'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoTersimpan = [];

        try {
            // Upload foto utama
            if ($request->hasFile('foto')) {
                $fotoTersimpan[] = $request->file('foto')
                    ->store('produk', 'public');
            }

            // Upload foto tambahan
            if ($request->hasFile('foto_tambahan')) {
                foreach ($request->file('foto_tambahan') as $file) {
                    $fotoTersimpan[] = $file->store('produk', 'public');
                }
            }

            // Pisahkan pilihan harga dari data utama
            $dataHarga = $validated['harga_produk'] ?? [];

            unset(
                $validated['harga_produk'],
                $validated['foto'],
                $validated['foto_tambahan']
            );

            // Simpan dalam transaksi database
            \Illuminate\Support\Facades\DB::transaction(
                function () use ($validated, $dataHarga, $fotoTersimpan) {
                    $produk = Produk::create($validated);

                    // Simpan pilihan harga
                    foreach ($dataHarga as $pilihan) {
                        $produk->hargaProduk()->create([
                            'harga_min' => $pilihan['harga_min'],
                            'harga_max' => $pilihan['harga_max'] ?? null,
                            'jumlah_satuan' => $pilihan['jumlah_satuan'],
                            'satuan' => $pilihan['satuan'] ?? null,
                        ]);
                    }

                    // Simpan foto produk
                    foreach ($fotoTersimpan as $index => $path) {
                        $produk->fotoProduk()->create([
                            'foto' => $path,
                            'urutan' => $index + 1,
                        ]);
                    }

                    // Catat aktivitas admin
                    ActivityLogger::log(
                        'produk',
                        'create',
                        $produk,
                        null,
                        $produk->toArray(),
                        'Menambahkan produk: ' . $produk->nama_produk
                    );
                }
            );

        } catch (\Throwable $e) {
            // Hapus foto baru jika transaksi gagal
            foreach ($fotoTersimpan as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($path);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan formulir edit produk
    public function edit($id)
    {
        $produk = Produk::with([
            'hargaProduk',
            'fotoProduk'
        ])->findOrFail($id);

        $umkm = Umkm::orderBy('nama_umkm')->get();

        $kategoriProduk = KategoriProduk::orderBy('nama_kategori')->get();

        return view('admin.produk.edit', compact(
            'produk',
            'umkm',
            'kategoriProduk'
        ));
    }

    // Memperbarui data produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'umkm_id' => 'required|exists:umkm,id',
            'kategori_produk_id' => 'required|exists:kategori_produk,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

            // Validasi pilihan harga
            'harga_produk' => 'sometimes|array',
            'harga_produk.*.id' => 'nullable|integer|exists:harga_produk,id',
            'harga_produk.*.harga_min' => 'required|numeric|min:0',
            'harga_produk.*.harga_max' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    preg_match(
                        '/^harga_produk\.(\d+)\.harga_max$/',
                        $attribute,
                        $matches
                    );

                    $index = $matches[1] ?? null;

                    $minimum = $index !== null
                        ? $request->input("harga_produk.$index.harga_min")
                        : null;

                    if (
                        is_numeric($value) &&
                        is_numeric($minimum) &&
                        (float) $value < (float) $minimum
                    ) {
                        $fail(
                            'Harga maksimum harus lebih besar atau sama dengan harga minimum.'
                        );
                    }
                },
            ],
            'harga_produk.*.jumlah_satuan' => 'required|numeric|gt:0',
            'harga_produk.*.satuan' => 'nullable|string|max:50',

            // Foto baru
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_tambahan' => 'nullable|array',
            'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoBaruTersimpan = [];

        try {
            // Upload foto utama baru
            $fotoUtamaBaru = null;

            if ($request->hasFile('foto')) {
                $fotoUtamaBaru = $request->file('foto')
                    ->store('produk', 'public');

                $fotoBaruTersimpan[] = $fotoUtamaBaru;
            }

            // Upload foto tambahan
            if ($request->hasFile('foto_tambahan')) {
                foreach ($request->file('foto_tambahan') as $file) {
                    $fotoBaruTersimpan[] = $file->store('produk', 'public');
                }
            }

            // Pisahkan pilihan harga
            $dataHarga = $validated['harga_produk'] ?? [];

            unset(
                $validated['harga_produk'],
                $validated['foto'],
                $validated['foto_tambahan']
            );

            // Simpan kondisi sebelum perubahan
            $dataSebelum = $produk->load([
                'hargaProduk',
                'fotoProduk',
            ])->toArray();

            // Perbarui data dalam transaksi
            \Illuminate\Support\Facades\DB::transaction(
                function () use (
                    $produk,
                    $validated,
                    $dataHarga,
                    $fotoBaruTersimpan,
                    $fotoUtamaBaru,
                    $dataSebelum,
                    $request
                ) {
                    // Perbarui data utama
                    $produk->update($validated);

                    // Tentukan pilihan harga yang dipertahankan
                    $idDipertahankan = collect($dataHarga)
                        ->pluck('id')
                        ->filter()
                        ->values()
                        ->all();

                    // Hapus pilihan harga yang tidak dipertahankan
                    $produk->hargaProduk()
                        ->whereNotIn('id', $idDipertahankan)
                        ->delete();

                    // Simpan atau perbarui pilihan harga
                    foreach ($dataHarga as $pilihan) {
                        $nilaiHarga = [
                            'harga_min' => $pilihan['harga_min'],
                            'harga_max' => $pilihan['harga_max'] ?? null,
                            'jumlah_satuan' => $pilihan['jumlah_satuan'],
                            'satuan' => $pilihan['satuan'] ?? null,
                        ];

                        if (!empty($pilihan['id'])) {
                            $produk->hargaProduk()
                                ->whereKey($pilihan['id'])
                                ->firstOrFail()
                                ->update($nilaiHarga);
                        } else {
                            $produk->hargaProduk()->create($nilaiHarga);
                        }
                    }

                    // Tentukan urutan foto berikutnya
                    $urutan = (int) $produk->fotoProduk()->max('urutan');

                    // Simpan foto baru
                    foreach ($fotoBaruTersimpan as $path) {
                        $urutan++;

                        $produk->fotoProduk()->create([
                            'foto' => $path,
                            'urutan' => $urutan,
                        ]);
                    }

                    // Jadikan foto utama baru sebagai foto pertama
                    if ($fotoUtamaBaru !== null) {
                        $produk->fotoProduk()
                            ->where('foto', $fotoUtamaBaru)
                            ->update(['urutan' => 0]);
                    }

                    // Catat aktivitas admin
                    $dataSesudah = $produk->fresh([
                        'hargaProduk',
                        'fotoProduk',
                    ])->toArray();

                    ActivityLogger::log(
                        'produk',
                        'update',
                        $produk,
                        $dataSebelum,
                        $dataSesudah,
                        'Memperbarui produk: ' . $produk->nama_produk
                    );
                }
            );

        } catch (\Throwable $e) {
            // Hapus foto baru jika transaksi gagal
            foreach ($fotoBaruTersimpan as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($path);
            }

            throw $e;
        }

        // Respons untuk permintaan AJAX
        if ($request->expectsJson()) {
            session()->flash('success', 'Produk berhasil diperbarui.');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'redirect' => route('admin.produk.index'),
            ]);
        }

        // Respons untuk permintaan biasa
        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus satu foto produk
    public function destroyFoto(Request $request, $id, $fotoId)
    {
        $produk = Produk::findOrFail($id);

        // Pastikan foto milik produk ini
        $foto = $produk->fotoProduk()
            ->whereKey($fotoId)
            ->firstOrFail();

        $pathFoto = $foto->foto;

        // Hapus data foto dalam transaksi
        \Illuminate\Support\Facades\DB::transaction(
            function () use ($produk, $foto, $pathFoto) {
                $foto->delete();

                // Tentukan foto utama berikutnya
                $fotoUtama = $produk->fotoProduk()
                    ->orderBy('urutan')
                    ->orderBy('id')
                    ->first();

                // Catat aktivitas penghapusan foto
                ActivityLogger::log(
                    'produk',
                    'update',
                    $produk,
                    ['foto_dihapus' => $pathFoto],
                    ['foto_utama' => $fotoUtama?->foto],
                    'Menghapus foto produk: ' . $produk->nama_produk
                );
            }
        );

        // Periksa apakah foto masih digunakan
        $dipakaiGaleri = \App\Models\FotoProduk::where(
            'foto',
            $pathFoto
        )->exists();

        // Hapus file jika tidak digunakan lagi
        if (!$dipakaiGaleri) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($pathFoto);
        }

        // Respons AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto produk berhasil dihapus.',
                'redirect' => route('produk.edit', $produk->id),
            ]);
        }

        // Respons biasa
        return redirect()
            ->route('produk.edit', $produk->id)
            ->with('success', 'Foto produk berhasil dihapus.');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $produk = Produk::with([
            'hargaProduk',
            'fotoProduk'
        ])->findOrFail($id);

        // Simpan data sebelum penghapusan
        $dataSebelum = $produk->toArray();
        $namaProduk = $produk->nama_produk;

        // Kumpulkan foto tanpa duplikasi
        $semuaFoto = $produk->fotoProduk
            ->pluck('foto')
            ->filter()
            ->unique()
            ->values();

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Hapus produk
            $produk->delete();

            // Catat aktivitas admin
            ActivityLogger::log(
                'produk',
                'delete',
                $produk,
                $dataSebelum,
                null,
                'Menghapus produk: ' . $namaProduk
            );

            \Illuminate\Support\Facades\DB::commit();

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            throw $e;
        }

        // Bersihkan foto setelah transaksi berhasil
        foreach ($semuaFoto as $pathFoto) {
            $dipakaiGaleri = \App\Models\FotoProduk::where(
                'foto',
                $pathFoto
            )->exists();

            if (!$dipakaiGaleri) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($pathFoto);
            }
        }

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}