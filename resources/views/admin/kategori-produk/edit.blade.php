@extends('layouts.admin')

@section('title', 'Edit Kategori Produk')

@push('styles')
<style>
    .kategori-produk-edit {
        max-width: 800px;
        width: 100%;
    }

    .kategori-produk-edit .form-heading {
        margin-bottom: 25px;
    }

    .kategori-produk-edit .form-heading h1 {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }

    .kategori-produk-edit .form-heading p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .kategori-produk-edit .form-container {
        background: #ffffff;
        padding: 25px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .kategori-produk-edit .form-group {
        margin-bottom: 20px;
    }

    .kategori-produk-edit .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
    }

    .kategori-produk-edit .form-control {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        color: #334155;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .kategori-produk-edit .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .kategori-produk-edit textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .kategori-produk-edit .form-required,
    .kategori-produk-edit .form-error {
        color: #dc2626;
    }

    .kategori-produk-edit .form-error {
        font-size: 13px;
        margin-top: 6px;
    }

    .kategori-produk-edit .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
    }

    .kategori-produk-edit .form-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        font-family: inherit;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .kategori-produk-edit .btn-save {
        background: #2563eb;
        color: #ffffff;
    }

    .kategori-produk-edit .btn-save:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .kategori-produk-edit .btn-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    .kategori-produk-edit .btn-cancel:hover {
        background: #cbd5e1;
        color: #334155;
    }

    @media (max-width: 576px) {
        .kategori-produk-edit .form-container {
            padding: 18px;
        }

        .kategori-produk-edit .form-heading h1 {
            font-size: 21px;
        }

        .kategori-produk-edit .form-actions {
            flex-direction: column;
        }

        .kategori-produk-edit .form-btn {
            width: 100%;
        }
    }
</style>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditKategoriProduk');

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

    // Konfirmasi sebelum menyimpan perubahan.
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) return;

        const yakin = confirm(
            'Apakah Anda yakin data perubahan kategori produk sudah benar dan ingin disimpan?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan jika pengguna meninggalkan halaman
    // saat ada perubahan yang belum disimpan.
    window.addEventListener('beforeunload', function (event) {
        if (sedangMenyimpan || !adaPerubahan()) return;

        event.preventDefault();
        event.returnValue = '';
    });
});
</script>
@endpush
