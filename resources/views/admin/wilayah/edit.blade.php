@extends('layouts.admin')

@section('title', 'Edit Wilayah')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-wilayah-edit.css') }}">
@endpush

@section('content')

<div class="wilayah-edit-container">

    <div class="wilayah-edit-header">
        <h1>Edit Wilayah</h1>
        <p>Perbarui data kecamatan dan desa/kelurahan Kota Ambon.</p>
    </div>

    <div class="wilayah-edit-card">

        {{-- Pesan kesalahan validasi --}}
        @if ($errors->any())
            <div class="wilayah-edit-error-box" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            id="formEditWilayah"
            action="{{ route('admin.wilayah.update', $wilayah->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            {{-- Kecamatan --}}
            <div class="wilayah-edit-group">
                <label for="kecamatan" class="wilayah-edit-label">
                    Kecamatan
                    <span class="wilayah-edit-required">*</span>
                </label>

                <input
                    type="text"
                    name="kecamatan"
                    id="kecamatan"
                    class="wilayah-edit-input"
                    value="{{ old('kecamatan', $wilayah->kecamatan) }}"
                    required
                >

                @error('kecamatan')
                    <div class="wilayah-edit-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Desa atau Kelurahan --}}
            <div class="wilayah-edit-group">
                <label for="kelurahan_desa" class="wilayah-edit-label">
                    Desa/Kelurahan
                    <span class="wilayah-edit-required">*</span>
                </label>

                <input
                    type="text"
                    name="kelurahan_desa"
                    id="kelurahan_desa"
                    class="wilayah-edit-input"
                    value="{{ old('kelurahan_desa', $wilayah->kelurahan_desa) }}"
                    required
                >

                @error('kelurahan_desa')
                    <div class="wilayah-edit-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Jenis Wilayah --}}
            <div class="wilayah-edit-group">
                <label for="jenis_wilayah" class="wilayah-edit-label">
                    Jenis Wilayah
                    <span class="wilayah-edit-required">*</span>
                </label>

                <select
                    name="jenis_wilayah"
                    id="jenis_wilayah"
                    class="wilayah-edit-select"
                    required
                >
                    <option value="">
                        -- Pilih Jenis Wilayah --
                    </option>

                    @foreach (['Desa', 'Kelurahan', 'Negeri'] as $jenis)
                        <option
                            value="{{ $jenis }}"
                            {{ old('jenis_wilayah', $wilayah->jenis_wilayah) === $jenis ? 'selected' : '' }}
                        >
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>

                @error('jenis_wilayah')
                    <div class="wilayah-edit-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Tombol Simpan dan Batal --}}
            <div class="wilayah-edit-actions">

                <button
                    type="submit"
                    class="wilayah-edit-btn wilayah-edit-btn-save"
                >
                    <i class="bi bi-floppy"></i>
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('admin.wilayah.index') }}"
                    class="wilayah-edit-btn wilayah-edit-btn-cancel"
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
    <script src="{{ asset('js/admin-wilayah-edit.js') }}"></script>
@endpush