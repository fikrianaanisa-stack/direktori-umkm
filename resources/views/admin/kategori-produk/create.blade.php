@extends('layouts.admin')

@section('title', 'Tambah Kategori Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-kategori-produk-create.css') }}">
@endpush

@section('content')

<div class="form-heading">
    <h1>Tambah Kategori Produk</h1>
    <p>Tambahkan kategori baru untuk mengelompokkan produk atau layanan UMKM.</p>
</div>

<div class="form-container">

    <form action="{{ route('admin.kategori-produk.store') }}"
          method="POST">

        @csrf

        <div class="form-group">
            <label for="nama_kategori">
                Nama Kategori <span class="form-required">*</span>
            </label>

            <input type="text"
                   id="nama_kategori"
                   name="nama_kategori"
                   class="form-control"
                   value="{{ old('nama_kategori') }}"
                   maxlength="100"
                   required
                   placeholder="Masukkan nama kategori produk">

            @error('nama_kategori')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>

            <textarea id="deskripsi"
                      name="deskripsi"
                      class="form-control"
                      rows="4"
                      placeholder="Masukkan deskripsi kategori (opsional)">{{ old('deskripsi') }}</textarea>

            @error('deskripsi')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">
                Simpan Kategori
            </button>

            <a href="{{ route('admin.kategori-produk.index') }}"
            class="btn-cancel">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-kategori-produk-create.js') }}"></script>
@endpush