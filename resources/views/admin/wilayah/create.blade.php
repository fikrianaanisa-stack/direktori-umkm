@extends('layouts.admin')

@section('title', 'Tambah Wilayah')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-wilayah-create.css') }}">
@endpush

@section('content')

<h2>Tambah Wilayah</h2>
<p class="wilayah-form-description">
    Tambahkan data kecamatan dan desa/kelurahan Kota Ambon.
</p>

<div class="wilayah-form-card">

    {{-- Menampilkan pesan kesalahan validasi --}}
    @if ($errors->any())
        <div class="wilayah-form-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulir tambah wilayah --}}
    <form action="{{ route('admin.wilayah.store') }}"
        method="POST"
        id="formTambahWilayah">

        @csrf

        {{-- Input nama kecamatan --}}
        <div class="wilayah-form-group">
            <label for="kecamatan">
                Kecamatan <span class="wilayah-required">*</span>
            </label>
            <input type="text"
                   name="kecamatan"
                   id="kecamatan"
                   value="{{ old('kecamatan') }}"
                   required>
        </div>

        {{-- Input nama desa atau kelurahan --}}
        <div class="wilayah-form-group">
            <label for="kelurahan_desa">
                Desa/Kelurahan <span class="wilayah-required">*</span>
            </label>
            <input type="text"
                   name="kelurahan_desa"
                   id="kelurahan_desa"
                   value="{{ old('kelurahan_desa') }}"
                   required>
        </div>

        {{-- Pilihan jenis wilayah --}}
        <div class="wilayah-form-group">
            <label for="jenis_wilayah">
                Jenis Wilayah <span class="wilayah-required">*</span>
            </label>
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
            id="batalTambahWilayah">
                Batal
            </a>
        </div>

    </form>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-wilayah-create.js') }}"></script>
@endpush
