@extends('layouts.admin')

@section('title', 'Tambah Kategori UMKM')

@section('content')

<div style="max-width: 800px;">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 24px; margin-bottom: 8px;">
            Tambah Kategori UMKM
        </h1>

        <p style="color: #64748b; font-size: 14px;">
            Tambahkan kategori baru untuk mengelompokkan usaha UMKM.
        </p>
    </div>

    <div style="background: white; padding: 25px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <form action="{{ route('admin.kategori-umkm.store') }}"
              method="POST"
              onsubmit="return confirm('Apakah data kategori UMKM yang Anda isi sudah benar dan ingin disimpan?');">

            @csrf

            <div style="margin-bottom: 20px;">
                <label for="nama_kategori"
                       style="display: block; margin-bottom: 8px;">
                    Nama Kategori <span style="color: red;">*</span>
                </label>

                <input type="text"
                       id="nama_kategori"
                       name="nama_kategori"
                       value="{{ old('nama_kategori') }}"
                       maxlength="100"
                       required
                       style="width: 100%; padding: 12px;
                              border: 1px solid #cbd5e1;
                              border-radius: 6px;">

                @error('nama_kategori')
                    <div style="color: #dc2626; margin-top: 6px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label for="deskripsi"
                       style="display: block; margin-bottom: 8px;">
                    Deskripsi
                </label>

                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="4"
                          style="width: 100%; padding: 12px;
                                 border: 1px solid #cbd5e1;
                                 border-radius: 6px;">{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <div style="color: #dc2626; margin-top: 6px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div style="display: flex; gap: 12px;">

                <button type="submit"
                        style="background: #2563eb; color: white;
                               border: none; padding: 12px 20px;
                               border-radius: 6px; cursor: pointer;">
                    Simpan Kategori
                </button>

                <a href="{{ route('admin.kategori-umkm.index') }}"
                   style="background: #e2e8f0; color: #334155;
                          padding: 12px 20px; border-radius: 6px;
                          text-decoration: none;"
                    onclick="return confirm('Apakah Anda yakin ingin membatalkan penambahan kategori UMKM? Data yang sudah diisi tidak akan disimpan.');">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection