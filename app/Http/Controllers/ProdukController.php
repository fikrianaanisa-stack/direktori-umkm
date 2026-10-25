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

    public function index(Request $request)
    {
        // Menyiapkan query produk beserta relasinya
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

        // Mengurutkan produk terbaru
        $produk = $query->orderBy('id', 'desc')->get();

        // Mengambil kategori untuk dropdown filter
        $kategoriProduk = KategoriProduk::orderBy('nama_kategori', 'asc')
            ->get();

        return view('produk.index', compact('produk', 'kategoriProduk'));
    }

    // Menampilkan detail produk berdasarkan ID
    public function show($id)
    {
        // Mengambil produk beserta relasi yang sudah digunakan
        $produk = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ])->findOrFail($id);

        // Mengambil ulasan produk yang sudah disetujui admin
        $ulasan = $produk->ulasanDisetujui()
            ->with('user:id,name')
            ->latest()
            ->paginate(5);

        // Menghitung jumlah ulasan yang disetujui
        $jumlahUlasan = $produk->ulasanDisetujui()->count();

        // Menghitung rata-rata rating produk
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



    /**
     * Menampilkan detail produk dalam dashboard admin.
     */
    public function adminShow($id)
    {
        $produk = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ])->findOrFail($id);

        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Menampilkan daftar produk untuk admin.
     * Mendukung pencarian dan pagination 10 data per halaman.
     */
    public function adminIndex(Request $request)
    {
        /// Menyiapkan query produk beserta relasinya
        $query = Produk::with([
            'umkm',
            'kategoriProduk',
            'fotoProduk'
        ]);

        // Melakukan pencarian jika ada kata kunci
        if ($request->filled('cari')) {
            $kataKunci = trim($request->query('cari'));

            $query->where(function ($q) use ($kataKunci) {

                // Pencarian berdasarkan nama produk
                $q->where('nama_produk', 'like', '%' . $kataKunci . '%')

                // Pencarian berdasarkan nama UMKM penyedia
                ->orWhereHas('umkm', function ($umkm) use ($kataKunci) {
                    $umkm->where('nama_umkm', 'like', '%' . $kataKunci . '%');
                })

                // Pencarian berdasarkan kategori produk
                ->orWhereHas('kategoriProduk', function ($kategori) use ($kataKunci) {
                    $kategori->where('nama_kategori', 'like', '%' . $kataKunci . '%');
                });
            });
        }

        // Mengurutkan produk terbaru dan membatasi 10 data per halaman
        $produk = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Menampilkan halaman admin Data Produk
        return view('admin.produk.index', compact('produk'));
    }

    // Export laporan data produk ke PDF
    public function exportPdf(Request $request)
    {
        $query = Produk::with([
            'umkm',
            'kategoriProduk',
            'hargaProduk'
        ]);

        // Filter sesuai pencarian pada halaman admin
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

        // Mengambil seluruh hasil tanpa pagination
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

        // Menyimpan data produk baru
        public function store(Request $request)
        {
            // Validasi data formulir
            $validated = $request->validate([
                'umkm_id' => 'required|exists:umkm,id',
                'kategori_produk_id' => 'required|exists:kategori_produk,id',
                'nama_produk' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',

                // Validasi beberapa pilihan harga
                'harga_produk' => 'sometimes|array',
                'harga_produk.*.harga_min' => 'required|numeric|min:0',
                'harga_produk.*.harga_max' => [
                    'nullable',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        preg_match('/^harga_produk\.(\d+)\.harga_max$/', $attribute, $matches);

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

                // Tetap mendukung foto tunggal dari formulir lama
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

                // Mendukung beberapa foto dari formulir baru
                'foto_tambahan' => 'nullable|array',
                'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $fotoTersimpan = [];

            try {
                // Upload foto utama ke penyimpanan file
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

                // Pisahkan pilihan harga dari data produk
                $dataHarga = $validated['harga_produk'] ?? [];

                unset(
                    $validated['harga_produk'],
                    $validated['foto'],
                    $validated['foto_tambahan']
                );

                // Simpan produk, harga, dan foto dalam satu transaksi
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

                        // Simpan referensi semua foto produk
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

                // Hapus file baru jika penyimpanan database gagal
                foreach ($fotoTersimpan as $path) {
                    \Illuminate\Support\Facades\Storage::disk('public')
                        ->delete($path);
                }

                throw $e;
            }

            // Kembali ke halaman daftar produk
            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        }

        // Menampilkan formulir edit produk
        public function edit($id)
        {
            // Ambil produk beserta pilihan harga dan seluruh fotonya
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

            // Validasi data formulir
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

                // Foto utama baru (opsional)
                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

                // Foto tambahan baru (opsional)
                'foto_tambahan' => 'nullable|array',
                'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $fotoBaruTersimpan = [];

            try {
                // Upload foto utama baru, jika dipilih
                $fotoUtamaBaru = null;

                if ($request->hasFile('foto')) {
                    $fotoUtamaBaru = $request->file('foto')->store('produk', 'public');

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

                // Simpan kondisi produk, harga, dan foto sebelum diperbarui
                $dataSebelum = $produk->load([
                    'hargaProduk',
                    'fotoProduk',
                ])->toArray();

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
                        // Perbarui data utama produk
                        $produk->update($validated);

                        // Hapus pilihan harga yang tidak dipertahankan
                        $idDipertahankan = collect($dataHarga)
                            ->pluck('id')
                            ->filter()
                            ->values()
                            ->all();

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

                        // Tentukan urutan berikutnya untuk foto baru
                        $urutan = (int) $produk->fotoProduk()->max('urutan');

                        // Simpan referensi foto baru
                        foreach ($fotoBaruTersimpan as $path) {
                            $urutan++;

                            $produk->fotoProduk()->create([
                                'foto' => $path,
                                'urutan' => $urutan,
                            ]);
                        }

                        // Jika admin mengganti foto utama,
                        // jadikan foto baru sebagai foto pertama di slider
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
                // Jika gagal, hapus hanya file baru dari proses ini
                foreach ($fotoBaruTersimpan as $path) {
                    \Illuminate\Support\Facades\Storage::disk('public')
                        ->delete($path);
                }

                throw $e;
            }

            // Respons untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil diperbarui.',
                    'redirect' => route('admin.produk.index'),
                ]);
            }

            // Kembali ke daftar produk
            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil diperbarui.');
        }

        // Menghapus satu foto dari galeri produk
        public function destroyFoto(Request $request, $id, $fotoId)
        {
            $produk = Produk::findOrFail($id);

            // Pastikan foto benar-benar milik produk ini
            $foto = $produk->fotoProduk()
                ->whereKey($fotoId)
                ->firstOrFail();

            $pathFoto = $foto->foto;

            \Illuminate\Support\Facades\DB::transaction(
                function () use ($produk, $foto, $pathFoto) {

                    // Hapus satu data foto dari galeri
                    $foto->delete();

                    // Tentukan foto utama berdasarkan urutan terkecil
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

            // Periksa apakah file masih digunakan dalam galeri produk
            $dipakaiGaleri = \App\Models\FotoProduk::where(
                'foto',
                $pathFoto
            )->exists();

            // Hapus file fisik jika tidak digunakan lagi
            if (!$dipakaiGaleri) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($pathFoto);
            }

            // Respons untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto produk berhasil dihapus.',
                    'redirect' => route('produk.edit', $produk->id),
                ]);
            }

            // Respons untuk permintaan biasa
            return redirect()
                ->route('produk.edit', $produk->id)
                ->with('success', 'Foto produk berhasil dihapus.');
        }

        // Menghapus data produk
        public function destroy($id)
        {
            // Cari produk beserta pilihan harga dan galeri foto
            $produk = Produk::with(['hargaProduk', 'fotoProduk'])->findOrFail($id);

            // Simpan data sebelum dihapus
            $dataSebelum = $produk->toArray();
            $namaProduk = $produk->nama_produk;

            // Kumpulkan seluruh foto produk tanpa duplikasi
            $semuaFoto = $produk->fotoProduk
                ->pluck('foto')
                ->filter()
                ->unique()
                ->values();

            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                // Hapus produk dari database
                $produk->delete();

                // Catat aktivitas penghapusan
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

            // Bersihkan file setelah transaksi berhasil
            foreach ($semuaFoto as $pathFoto) {
                // Periksa apakah foto masih digunakan produk lain
                $dipakaiGaleri = \App\Models\FotoProduk::where(
                    'foto',
                    $pathFoto
                )->exists();

                // Hapus file jika tidak digunakan lagi
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