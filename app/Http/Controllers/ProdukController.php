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
        
        $produk = Produk::with(['umkm', 'kategoriProduk', 'hargaProduk'])
            ->orderBy('id', 'desc')
            ->get();

        return view('produk.index', compact('produk'));
    }
    
    // Menampilkan detail produk
    public function show($id)
    {
        
    $produk = Produk::with(['umkm', 'kategoriProduk', 'hargaProduk'])
        ->orderBy('id', 'desc')
        ->get();


        return view('produk.show', compact('produk'));
    }


    // Menampilkan daftar produk untuk admin
    public function adminIndex()
    {
        $produk = Produk::with(['umkm', 'kategoriProduk'])
            ->orderBy('id', 'desc')
            ->get();

        return view('produk.admin-index', compact('produk'));
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
                
                'harga' => 'nullable|numeric|min:0',

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

                        if (is_numeric($value) && is_numeric($minimum)
                            && (float) $value < (float) $minimum) {
                            $fail('Harga maksimum harus lebih besar atau sama dengan harga minimum.');
                        }
                    },
                ],
                'harga_produk.*.jumlah_satuan' => 'required|numeric|gt:0',
                'harga_produk.*.satuan' => 'nullable|string|max:50',

                'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            ]);

            // Upload foto produk jika tersedia
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')
                    ->store('produk', 'public');
            }

            // Pisahkan pilihan harga dari data produk
            $dataHarga = $validated['harga_produk'] ?? [];
            unset($validated['harga_produk']);

            // Sinkronkan harga lama dengan harga terendah
            $validated['harga'] = !empty($dataHarga)
                ? collect($dataHarga)->min('harga_min')
                : null;

            // Simpan produk dan pilihan harga dalam satu transaksi
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $dataHarga) {
                $produk = Produk::create($validated);

                foreach ($dataHarga as $pilihan) {
                    $produk->hargaProduk()->create([
                        'harga_min' => $pilihan['harga_min'],
                        'harga_max' => $pilihan['harga_max'] ?? null,
                        'jumlah_satuan' => $pilihan['jumlah_satuan'],
                        'satuan' => $pilihan['satuan'] ?? null,
                    ]);
                }
            });

            // Kembali ke halaman daftar produk
            return redirect()->route('admin.produk.index')
                    ->with('success', 'Produk berhasil ditambahkan.');
                        }

        // Menampilkan formulir edit produk
        public function edit($id)
        {
            // Ambil produk beserta pilihan harganya
            $produk = Produk::with('hargaProduk')->findOrFail($id);

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
                'harga' => 'nullable|numeric|min:0',

                // Validasi beberapa pilihan harga
                'harga_produk' => 'sometimes|array',
                'harga_produk.*.id' => 'nullable|integer|exists:harga_produk,id',
                'harga_produk.*.harga_min' => 'required|numeric|min:0',
                
                
                // Bandingkan harga maksimum dengan minimum pada pilihan yang sama
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

                        if (is_numeric($value) && is_numeric($minimum)
                            && (float) $value < (float) $minimum) {
                            $fail('Harga maksimum harus lebih besar atau sama dengan harga minimum.');
                        }
                    },
                ],

                'harga_produk.*.jumlah_satuan' => 'required|numeric|gt:0',
                'harga_produk.*.satuan' => 'nullable|string|max:50',

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

            // Daftar harga kosong berarti seluruh pilihan harga dihapus
            $dataHarga = $validated['harga_produk'] ?? [];
            unset($validated['harga_produk']);

            // Sinkronkan harga lama dengan pilihan harga yang tersedia
            if (!empty($dataHarga)) {
                $hargaMinimum = collect($dataHarga)->min('harga_min');
                $validated['harga'] = $hargaMinimum;
            } else {
                // Jika semua pilihan harga dihapus, kosongkan harga lama
                $validated['harga'] = null;
            }
           
            // Mulai transaksi database
            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                // Perbarui data utama produk
                $produk->update($validated);

                
                // Hapus pilihan harga lama yang tidak lagi ada di formulir
                if ($dataHarga !== null) {
                    $idDipertahankan = collect($dataHarga)
                        ->pluck('id')
                        ->filter()
                        ->values()
                        ->all();

                    $produk->hargaProduk()
                        ->whereNotIn('id', $idDipertahankan)
                        ->delete();
                }

                
                // Simpan atau perbarui pilihan harga
                if ($dataHarga !== null) {
                    foreach ($dataHarga as $pilihan) {

                        $nilaiHarga = [
                            'harga_min' => $pilihan['harga_min'] ?? null,
                            'harga_max' => $pilihan['harga_max'] ?? null,
                            'jumlah_satuan' => $pilihan['jumlah_satuan'] ?? 1,
                            'satuan' => $pilihan['satuan'] ?? null,
                        ];

                        if (!empty($pilihan['id'])) {
                            // Perbarui harga milik produk ini
                            $produk->hargaProduk()
                                ->whereKey($pilihan['id'])
                                ->firstOrFail()
                                ->update($nilaiHarga);
                        } else {
                            // Tambahkan pilihan harga baru
                            $produk->hargaProduk()->create($nilaiHarga);
                        }
                    }
                }

                // Simpan seluruh perubahan jika berhasil
                \Illuminate\Support\Facades\DB::commit();

            } catch (\Throwable $e) {
                // Batalkan perubahan database jika terjadi kesalahan
                \Illuminate\Support\Facades\DB::rollBack();

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
            return redirect()->route('admin.produk.index')
                    ->with('success', 'Produk berhasil dihapus.');
        }
}