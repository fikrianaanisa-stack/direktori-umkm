@extends('layouts.admin')

@section('title', 'Edit Wilayah')

@push('styles')
<style>
    .wilayah-edit-container {
        max-width: 800px;
        width: 100%;
    }

    .wilayah-edit-header {
        margin-bottom: 25px;
    }

    .wilayah-edit-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px;
    }

    .wilayah-edit-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    .wilayah-edit-card {
        background: #ffffff;
        padding: 25px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .wilayah-edit-group {
        margin-bottom: 20px;
    }

    .wilayah-edit-label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
    }

    .wilayah-edit-required {
        color: #dc2626;
    }

    .wilayah-edit-input,
    .wilayah-edit-select {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        color: #334155;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .wilayah-edit-input:focus,
    .wilayah-edit-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .wilayah-edit-error-box {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-size: 14px;
    }

    .wilayah-edit-error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .wilayah-edit-error {
        color: #dc2626;
        font-size: 13px;
        margin-top: 6px;
    }

    .wilayah-edit-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .wilayah-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 6px;
        border: none;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .wilayah-edit-btn-save {
        background: #2563eb;
        color: #ffffff;
    }

    .wilayah-edit-btn-save:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .wilayah-edit-btn-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    .wilayah-edit-btn-cancel:hover {
        background: #cbd5e1;
        color: #334155;
    }

    @media (max-width: 576px) {
        .wilayah-edit-card {
            padding: 18px;
        }

        .wilayah-edit-header h1 {
            font-size: 21px;
        }

        .wilayah-edit-actions {
            flex-direction: column;
        }

        .wilayah-edit-btn {
            width: 100%;
        }
    }
</style>
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
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formEditWilayah');

    if (!form) return;

    const kecamatan = document.getElementById('kecamatan');
    const kelurahanDesa = document.getElementById('kelurahan_desa');
    const jenisWilayah = document.getElementById('jenis_wilayah');

    const nilaiAwal = {
        kecamatan: kecamatan.value,
        kelurahanDesa: kelurahanDesa.value,
        jenisWilayah: jenisWilayah.value
    };

    let sedangMenyimpan = false;

    // Memeriksa perubahan pada formulir.
    function adaPerubahan() {
        return (
            kecamatan.value !== nilaiAwal.kecamatan ||
            kelurahanDesa.value !== nilaiAwal.kelurahanDesa ||
            jenisWilayah.value !== nilaiAwal.jenisWilayah
        );
    }

    // Konfirmasi penyimpanan.
    form.addEventListener('submit', function (event) {

        if (!form.checkValidity()) return;

        const yakin = confirm(
            'Apakah Anda yakin ingin menyimpan perubahan data wilayah ini?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan ketika meninggalkan halaman
    // dengan perubahan yang belum disimpan.
    window.addEventListener('beforeunload', function (event) {

        if (sedangMenyimpan || !adaPerubahan()) return;

        event.preventDefault();
        event.returnValue = '';

    });

});
</script>
@endpush
