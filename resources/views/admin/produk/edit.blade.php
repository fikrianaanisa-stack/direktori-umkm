@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-produk-edit.css') }}">
@endpush

@section('content')
<div class="edit-produk-page">

    <div class="edit-produk-header">
        <h2>Edit Produk</h2>
        <p>Perbarui informasi produk UMKM Kota Ambon.</p>
    </div>

    <div class="edit-produk-card">

        <form id="form-edit-produk"
              action="{{ route('produk.update', $produk->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="edit-produk-grid">

                <!-- KOLOM KIRI -->
                <div class="edit-produk-kiri">

                    <div class="edit-produk-field">
                        <label for="umkm_id" class="edit-produk-label">
                            UMKM Pemilik Produk
                        </label>

                        <select id="umkm_id"
                                name="umkm_id"
                                class="edit-produk-input"
                                required>
                            <option value="">-- Pilih UMKM --</option>

                            @foreach ($umkm as $item)
                                <option value="{{ $item->id }}"
                                    @selected(old('umkm_id', $produk->umkm_id) == $item->id)>
                                    {{ $item->nama_umkm }}
                                </option>
                            @endforeach
                        </select>

                        @error('umkm_id')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="edit-produk-field">
                        <label for="kategori_produk_id" class="edit-produk-label">
                            Kategori Produk
                        </label>

                        <select id="kategori_produk_id"
                                name="kategori_produk_id"
                                class="edit-produk-input"
                                required>
                            <option value="">-- Pilih Kategori --</option>

                            @foreach ($kategoriProduk as $kategori)
                                <option value="{{ $kategori->id }}"
                                    @selected(old('kategori_produk_id', $produk->kategori_produk_id) == $kategori->id)>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>

                        @error('kategori_produk_id')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="edit-produk-field">
                        <label for="nama_produk" class="edit-produk-label">
                            Nama Produk
                        </label>

                        <input type="text"
                            id="nama_produk"
                            name="nama_produk"
                            class="edit-produk-input"
                            value="{{ old('nama_produk', $produk->nama_produk) }}"
                            required>

                        @error('nama_produk')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="edit-produk-field">
                        <label for="deskripsi" class="edit-produk-label">
                            Deskripsi Produk
                        </label>

                        <textarea id="deskripsi"
                                name="deskripsi"
                                rows="4"
                                class="edit-produk-input">{{ old('deskripsi', $produk->deskripsi) }}</textarea>

                        @error('deskripsi')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Galeri Foto Produk --}}
                    <div class="edit-produk-field-lg">

                        <label class="edit-produk-galeri-label">
                            Foto Produk Saat Ini
                        </label>

                        @php
                            // Ambil seluruh foto produk yang tersimpan
                            $daftarFoto = $produk->fotoProduk;
                        @endphp

                        @if ($daftarFoto->isNotEmpty())
                            <div class="edit-produk-galeri">

                                @foreach ($daftarFoto as $index => $foto)
                                    <div class="edit-produk-foto-item">

                                        <img
                                            src="{{ asset('storage/' . $foto->foto) }}"
                                            alt="Foto {{ $index + 1 }}"
                                            class="edit-produk-foto"
                                        >

                                        <small class="edit-produk-foto-caption">
                                            Foto {{ $index + 1 }}
                                        </small>

                                        @if (isset($foto->id))
                                            <button
                                                type="button"
                                                class="btn-hapus-foto-produk"
                                                data-url="{{ route('produk.foto.destroy', ['id' => $produk->id, 'fotoId' => $foto->id]) }}"
                                            >
                                                Hapus Foto
                                            </button>
                                        @endif

                                    </div>
                                @endforeach

                            </div>
                        @else
                            <p class="edit-produk-help">
                                Belum ada foto produk.
                            </p>
                        @endif

                        {{-- Mengganti foto utama --}}
                        <label for="foto" class="edit-produk-label">
                            Ganti Foto Utama (Opsional)
                        </label>

                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            class="edit-produk-input"
                            accept=".jpg,.jpeg,.png,.webp"
                        >
                        <div id="preview-foto-utama" class="mt-3 d-none">
                            <img
                                id="gambar-preview-foto-utama"
                                alt="Pratinjau foto utama"
                                class="edit-produk-foto d-block mb-2"
                            >

                            <button
                                type="button"
                                id="hapus-preview-foto-utama"
                                class="btn btn-danger btn-sm"
                            >
                                <i class="bi bi-trash me-1"></i>
                                Hapus
                            </button>
                        </div>

                        <small class="edit-produk-help">
                            Kosongkan jika tidak ingin mengganti foto utama.
                            Foto sebelumnya tetap tersimpan. Maksimal 2 MB.
                        </small>

                        @error('foto')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror

                        {{-- Menambahkan foto baru --}}
                        <label for="foto_tambahan"
                            class="edit-produk-label edit-produk-label-tambahan">
                            Tambah Foto Produk (Opsional)
                        </label>

                        <input
                            type="file"
                            id="foto_tambahan"
                            name="foto_tambahan[]"
                            class="edit-produk-input"
                            accept=".jpg,.jpeg,.png,.webp"
                            multiple
                        >

                        <button
                            type="button"
                            id="hapus-pilihan-foto-tambahan"
                            class="btn btn-danger btn-sm mt-2 d-none"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Hapus Pilihan Foto
                        </button>

                        <small class="edit-produk-help">
                            Pilih beberapa foto sekaligus. Foto lama tidak akan terhapus.
                            Maksimal 2 MB per foto.
                        </small>

                        @error('foto_tambahan')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror

                        @error('foto_tambahan.*')
                            <small class="edit-produk-error">{{ $message }}</small>
                        @enderror

                    </div>
                </div>
                <!-- AKHIR KOLOM KIRI -->

                <!-- KOLOM KANAN -->
                <div class="edit-produk-kanan">

                    <div class="edit-produk-harga">

                    <h3>Pilihan Harga Produk</h3>

                    <p>Berikut pilihan harga yang tersimpan untuk produk ini.</p>

                    @php
                        // Gunakan input sebelumnya jika validasi gagal
                        $pilihanHargaForm = old('harga_produk');

                        // Jika tidak ada input sebelumnya, gunakan data database
                        if ($pilihanHargaForm === null) {
                            $pilihanHargaForm = $produk->hargaProduk->toArray();
                        }

                        // Tentukan indeks berikutnya untuk pilihan harga baru
                        $indeksBerikutnya = count($pilihanHargaForm)
                            ? max(array_keys($pilihanHargaForm)) + 1
                            : 0;
                    @endphp

                    <div id="daftar-pilihan-harga"
                        data-next-index="{{ $indeksBerikutnya }}">

                        @forelse ($pilihanHargaForm as $index => $pilihan)

                            <div class="pilihan-harga-blade">

                                <div class="edit-produk-harga-header">

                                    <strong>
                                        Pilihan Harga {{ $loop->iteration }}
                                    </strong>

                                    <button type="button"
                                            class="hapus-pilihan-harga">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>

                                </div>

                                <input type="hidden"
                                    name="harga_produk[{{ $index }}][id]"
                                    value="{{ $pilihan['id'] ?? '' }}">

                                <div class="edit-produk-harga-grid">

                                    <div>
                                        <label>Harga Minimum (Rp)</label>

                                        <input type="number"
                                            name="harga_produk[{{ $index }}][harga_min]"
                                            class="edit-produk-harga-input"
                                            min="0"
                                            step="0.01"
                                            value="{{ $pilihan['harga_min'] ?? '' }}"
                                            required>
                                    </div>

                                    <div>
                                        <label>Harga Maksimum (Rp)</label>

                                        <input type="number"
                                            name="harga_produk[{{ $index }}][harga_max]"
                                            class="edit-produk-harga-input"
                                            min="0"
                                            step="0.01"
                                            value="{{ $pilihan['harga_max'] ?? '' }}"
                                            placeholder="Opsional">
                                    </div>

                                </div>

                                <div class="edit-produk-harga-grid mt-3">

                                    <div>
                                        <label>Jumlah</label>

                                        <input type="number"
                                            name="harga_produk[{{ $index }}][jumlah_satuan]"
                                            class="edit-produk-harga-input"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ $pilihan['jumlah_satuan'] ?? 1 }}"
                                            required>
                                    </div>

                                    <div>
                                        <label>Satuan</label>

                                        <input type="text"
                                            name="harga_produk[{{ $index }}][satuan]"
                                            class="edit-produk-harga-input"
                                            value="{{ $pilihan['satuan'] ?? '' }}"
                                            placeholder="Contoh: kg, buah, botol"
                                            maxlength="50">
                                    </div>

                                </div>

                            </div>

                        @empty

                            <p class="text-secondary small">
                                Belum ada pilihan harga yang tersimpan.
                            </p>

                        @endforelse

                    </div>

                    <button type="button"
                            id="tambah-pilihan-harga"
                            class="edit-produk-tambah-harga">
                        + Tambah Pilihan Harga
                    </button>

                </div>

                </div>
                <!-- AKHIR KOLOM KANAN -->

            </div>
            <!-- AKHIR GRID DUA KOLOM -->
            
            <!-- TOMBOL AKSI -->
            <div class="form-actions-produk">

                <button type="submit" class="btn-simpan-produk">
                    <i class="bi bi-floppy me-2"></i>
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.produk.index') }}"
                class="btn-batal-produk">
                    <i class="bi bi-x-circle me-2"></i>
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin-produk-edit.js') }}"></script>
@endpush