@extends('layouts.admin')

@section('title', 'Edit Kategori UMKM')

@push('styles')
<style>
    .kategori-edit-container {
        max-width: 800px;
        width: 100%;
    }

    .kategori-edit-header {
        margin-bottom: 25px;
    }

    .kategori-edit-header h1 {
        font-size: 24px;
        margin: 0 0 8px;
        color: #0f172a;
        font-weight: 700;
    }

    .kategori-edit-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    .kategori-edit-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .kategori-edit-group {
        margin-bottom: 20px;
    }

    .kategori-edit-group:last-of-type {
        margin-bottom: 25px;
    }

    .kategori-edit-label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
    }

    .kategori-edit-required {
        color: #dc2626;
    }

    .kategori-edit-input,
    .kategori-edit-textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        color: #334155;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .kategori-edit-input:focus,
    .kategori-edit-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .kategori-edit-textarea {
        resize: vertical;
        min-height: 110px;
    }

    .kategori-edit-error {
        color: #dc2626;
        margin-top: 6px;
        font-size: 13px;
    }

    .kategori-edit-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .kategori-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: background-color 0.2s;
        gap: 8px;
    }

    .kategori-edit-btn-save {
        background: #2563eb;
        color: #ffffff;
    }

    .kategori-edit-btn-save:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .kategori-edit-btn-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    .kategori-edit-btn-cancel:hover {
        background: #cbd5e1;
        color: #334155;
    }

    @media (max-width: 576px) {
        .kategori-edit-card {
            padding: 18px;
        }

        .kategori-edit-header h1 {
            font-size: 21px;
        }

        .kategori-edit-actions {
            flex-direction: column;
        }

        .kategori-edit-btn {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>
@endpush

@section('content')

<div class="kategori-edit-container">

    <div class="kategori-edit-header">
        <h1>Edit Kategori UMKM</h1>
        <p>Perbarui informasi kategori usaha UMKM.</p>
    </div>

    <div class="kategori-edit-card">

        <form
            id="formEditKategori"
            action="{{ route('admin.kategori-umkm.update', $kategori->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="kategori-edit-group">
                <label for="nama_kategori" class="kategori-edit-label">
                    Nama Kategori
                    <span class="kategori-edit-required">*</span>
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    class="kategori-edit-input"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    maxlength="100"
                    required
                >

                @error('nama_kategori')
                    <div class="kategori-edit-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="kategori-edit-group">
                <label for="deskripsi" class="kategori-edit-label">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="kategori-edit-textarea"
                >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

                @error('deskripsi')
                    <div class="kategori-edit-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="kategori-edit-actions">
                <button type="submit"
                        class="kategori-edit-btn kategori-edit-btn-save">
                    <i class="bi bi-floppy"></i>
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.kategori-umkm.index') }}"
                class="kategori-edit-btn kategori-edit-btn-cancel">
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
    const form = document.getElementById('formEditKategori');
    if (!form) return;

    const namaKategori = document.getElementById('nama_kategori');
    const deskripsi = document.getElementById('deskripsi');

    const nilaiAwal = {
        nama: namaKategori.value,
        deskripsi: deskripsi.value
    };

    let sedangMenyimpan = false;

    function adaPerubahan() {
        return namaKategori.value !== nilaiAwal.nama ||
               deskripsi.value !== nilaiAwal.deskripsi;
    }

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) return;

        if (!confirm(
            'Apakah Anda yakin ingin menyimpan perubahan kategori UMKM ini?'
        )) {
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
