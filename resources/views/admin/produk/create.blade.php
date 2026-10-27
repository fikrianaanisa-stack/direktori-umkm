@extends('layouts.admin')

@section('title', 'Tambah Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-produk-create.css') }}">
@endpush

@section('content')
<div class="produk-create">

    {{-- Header halaman --}}
    <div class="produk-create-header">
        <h2>Tambah Produk</h2>
        <p>Tambahkan produk ke katalog UMKM Kota Ambon.</p>
    </div>

    {{-- Form tambah produk --}}
    <div class="produk-create-card">
        <form id="form-tambah-produk"
            action="{{ route('produk.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

                {{-- UMKM pemilik produk --}}
                <div class="produk-create-field">
                    <label for="umkm_id" class="produk-create-label">
                        UMKM Pemilik Produk
                    </label>

                    <select id="umkm_id"
                            name="umkm_id"
                            class="produk-create-input"
                            required>
                        <option value="">-- Pilih UMKM --</option>

                        @foreach ($umkm as $item)
                            <option value="{{ $item->id }}"
                                    @selected(old('umkm_id') == $item->id)>
                                {{ $item->nama_umkm }}
                            </option>
                        @endforeach
                    </select>

                    @error('umkm_id')
                        <small class="produk-create-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Kategori produk --}}
                <div class="produk-create-field">
                    <label for="kategori_produk_id" class="produk-create-label">
                        Kategori Produk
                    </label>

                    <select id="kategori_produk_id"
                            name="kategori_produk_id"
                            class="produk-create-input"
                            required>
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($kategoriProduk as $kategori)
                            <option value="{{ $kategori->id }}"
                                    @selected(old('kategori_produk_id') == $kategori->id)>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    @error('kategori_produk_id')
                        <small class="produk-create-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Nama produk --}}
                <div class="produk-create-field">
                    <label for="nama_produk" class="produk-create-label">
                        Nama Produk
                    </label>

                    <input type="text"
                        id="nama_produk"
                        name="nama_produk"
                        class="produk-create-input"
                        value="{{ old('nama_produk') }}"
                        required>

                    @error('nama_produk')
                        <small class="produk-create-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Deskripsi produk --}}
                <div class="produk-create-field">
                    <label for="deskripsi" class="produk-create-label">
                        Deskripsi Produk
                    </label>

                    <textarea id="deskripsi"
                            name="deskripsi"
                            rows="4"
                            class="produk-create-input">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <small class="produk-create-error">{{ $message }}</small>
                    @enderror
                </div>

                    {{-- Pilihan harga produk --}}
                    <div class="produk-create-harga">
                        <h3>Pilihan Harga Produk</h3>

                        <p>
                            Tambahkan satu atau beberapa pilihan harga.
                            Jika harga belum diketahui, bagian ini boleh dikosongkan.
                        </p>

                        <div id="daftar-pilihan-harga"></div>

                        <button type="button"
                                id="tambah-pilihan-harga"
                                class="produk-create-tambah-harga">
                            + Tambah Pilihan Harga
                        </button>
                    </div>

                    {{-- Foto utama produk --}}
                    <div class="produk-create-field-lg">
                        <label for="foto" class="produk-create-label">
                            Foto Utama Produk
                        </label>

                        <input type="file"
                            id="foto"
                            name="foto"
                            class="produk-create-input"
                            accept=".jpg,.jpeg,.png,.webp">

                        <button type="button"
                                class="btn btn-outline-danger btn-sm mt-2 d-none"
                                data-clear-file="foto">
                            <i class="bi bi-trash"></i>
                            Hapus Foto
                        </button>

                        <small class="produk-create-help">
                            Foto utama yang ditampilkan pertama kali.
                            Format JPG, PNG, atau WebP. Maksimal 2 MB.
                        </small>

                        @error('foto')
                            <small class="produk-create-error">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Foto tambahan produk --}}
                    <div class="produk-create-field-lg">
                        <label for="foto_tambahan" class="produk-create-label">
                            Foto Tambahan Produk
                        </label>

                        <input type="file"
                            id="foto_tambahan"
                            name="foto_tambahan[]"
                            class="produk-create-input"
                            accept=".jpg,.jpeg,.png,.webp"
                            multiple>

                        <button type="button"
                                class="btn btn-outline-danger btn-sm mt-2 d-none"
                                data-clear-file="foto_tambahan">
                            <i class="bi bi-trash"></i>
                            Hapus Foto
                        </button>

                        <small class="produk-create-help">
                            Opsional. Bisa memilih beberapa foto sekaligus.
                            Setiap foto maksimal 2 MB.
                            Tahan Ctrl untuk memilih beberapa file.
                        </small>

                        @error('foto_tambahan')
                            <small class="produk-create-error">{{ $message }}</small>
                        @enderror

                        @error('foto_tambahan.*')
                            <small class="produk-create-error">{{ $message }}</small>
                        @enderror
                    </div>

            {{-- Tombol simpan dan batal --}}
            <div class="produk-create-actions d-flex flex-wrap gap-3">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-floppy me-2"></i>
                    Simpan Produk
                </button>

                <a href="{{ route('admin.produk.index') }}"
                id="btn-batal-produk"
                class="btn btn-secondary px-4 py-2">
                    <i class="bi bi-x-circle me-2"></i>
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-produk-create.js') }}"></script>
@endpush