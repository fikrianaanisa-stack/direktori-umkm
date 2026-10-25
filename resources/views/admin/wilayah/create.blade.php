@extends('layouts.admin')

@section('title', 'Tambah Wilayah')

@section('content')

<style>
    .wilayah-form-card {
        max-width: 650px;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .wilayah-form-group {
        margin-bottom: 20px;
    }

    .wilayah-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #334155;
    }

    .wilayah-form-group input,
    .wilayah-form-group select {
        width: 100%;
        padding: 11px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
    }

    .wilayah-form-actions {
        display: flex;
        gap: 12px;
    }

    .wilayah-form-actions button,
    .wilayah-form-actions a {
        padding: 11px 18px;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
    }

    .wilayah-form-actions button {
        background: #2563eb;
        color: white;
        border: none;
    }

    .wilayah-form-actions a {
        background: #e2e8f0;
        color: #334155;
    }
</style>

<h2>Tambah Wilayah</h2>
<p style="color:#64748b;margin-bottom:25px;">
    Tambahkan data kecamatan dan desa/kelurahan Kota Ambon.
</p>

<div class="wilayah-form-card">

    {{-- Menampilkan pesan kesalahan validasi --}}
    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:12px;margin-bottom:20px;border-radius:6px;">
            <ul style="margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulir tambah wilayah --}}
    <form action="{{ route('admin.wilayah.store') }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin menyimpan data wilayah ini?');">

        @csrf

        {{-- Input nama kecamatan --}}
        <div class="wilayah-form-group">
            <label for="kecamatan">Kecamatan</label>
            <input type="text"
                   name="kecamatan"
                   id="kecamatan"
                   value="{{ old('kecamatan') }}"
                   required>
        </div>

        {{-- Input nama desa atau kelurahan --}}
        <div class="wilayah-form-group">
            <label for="kelurahan_desa">Desa/Kelurahan</label>
            <input type="text"
                   name="kelurahan_desa"
                   id="kelurahan_desa"
                   value="{{ old('kelurahan_desa') }}"
                   required>
        </div>

        {{-- Pilihan jenis wilayah --}}
        <div class="wilayah-form-group">
            <label for="jenis_wilayah">Jenis Wilayah</label>
            <select name="jenis_wilayah" id="jenis_wilayah" required>
                <option value="">-- Pilih Jenis Wilayah --</option>
                @foreach (['Desa', 'Kelurahan', 'Negeri'] as $jenis)
                    <option value="{{ $jenis }}"
                        {{ old('jenis_wilayah') === $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol simpan dan kembali --}}
        <div class="wilayah-form-actions">
            <button type="submit">Simpan Wilayah</button>

            <a href="{{ route('admin.wilayah.index') }}"
               onclick="return confirm('Batalkan penambahan wilayah?');">
                Batal
            </a>
        </div>

    </form>
</div>

@endsection
