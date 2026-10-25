@extends('layouts.admin')

@section('title', 'Tambah Kategori Produk')

@section('content')

<style>
    .form-container {
        max-width: 800px;
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .form-heading {
        margin-bottom: 25px;
    }

    .form-heading h1 {
        margin: 0 0 8px;
        font-size: 24px;
        color: #1e293b;
    }

    .form-heading p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
    }

    .form-error {
        color: #dc2626;
        font-size: 13px;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-save {
        background: #2563eb;
        color: white;
        padding: 11px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #334155;
        padding: 11px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }
</style>

<div class="form-heading">
    <h1>Tambah Kategori Produk</h1>
    <p>Tambahkan kategori baru untuk mengelompokkan produk atau layanan UMKM.</p>
</div>

<div class="form-container">

    <form action="{{ route('admin.kategori-produk.store') }}"
          method="POST"
          onsubmit="return confirm('Apakah data kategori produk yang Anda isi sudah benar dan ingin disimpan?');">

        @csrf

        <div class="form-group">
            <label for="nama_kategori">
                Nama Kategori <span style="color:red;">*</span>
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
               class="btn-cancel"
               onclick="return confirm('Apakah Anda yakin ingin membatalkan pengisian data kategori produk? Data yang sudah diisi tidak akan disimpan.');">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection