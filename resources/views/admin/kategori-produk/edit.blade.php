@extends('layouts.admin')

@section('title', 'Edit Kategori Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-kategori-produk-edit.css') }}">
@endpush

@section('content')

<div class="kategori-produk-edit">

    <div class="form-heading">
        <h1>Edit Kategori Produk</h1>
        <p>Perbarui informasi kategori produk atau layanan UMKM.</p>
    </div>

    <div class="form-container">

        <form
            id="formEditKategoriProduk"
            action="{{ route('admin.kategori-produk.update', $kategori->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_kategori">
                    Nama Kategori
                    <span class="form-required">*</span>
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    class="form-control"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    maxlength="100"
                    required
                >

                @error('nama_kategori')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    class="form-control"
                    rows="4"
                >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

                @error('deskripsi')
                    <div class="form-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    class="form-btn btn-save"
                >
                    <i class="bi bi-floppy"></i>
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('admin.kategori-produk.index') }}"
                    class="form-btn btn-cancel"
                >
                    <i class="bi bi-x-circle"></i>
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-kategori-produk-edit.js') }}"></script>
@endpush
