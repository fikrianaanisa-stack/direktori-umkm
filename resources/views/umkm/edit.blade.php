
@extends('layouts.admin')

@section('title', 'Edit UMKM')

@push('styles')
<style>
    .umkm-form-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .umkm-form-container h1 {
        color: #243b53;
        margin-top: 0;
    }

    .umkm-form-container label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    .umkm-form-container input,
    .umkm-form-container select,
    .umkm-form-container textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
    }

    .umkm-form-container button[type="submit"] {
        margin-top: 20px;
        padding: 12px 20px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .umkm-form-container button[type="submit"]:hover {
        background: #1d4ed8;
    }

    .umkm-form-container .section-title {
        margin-top: 30px;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 8px;
    }

    .umkm-form-container .error {
        color: #dc2626;
    }

    .umkm-form-container img {
        display: block;
        max-width: 100%;
        margin: 10px 0;
    }

    @media (max-width: 768px) {
        .umkm-form-container {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')

<div class="umkm-form-container">

<div class="container">

    <h1>Edit UMKM</h1>
    <p>Perbarui informasi usaha pada formulir berikut.</p>

    <a href="{{ route('admin.umkm.index') }}">← Kembali ke Data UMKM</a>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('umkm.update', $umkm->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3 class="section-title">A. Identitas UMKM</h3>

        <label>Nama UMKM *</label>
        <input type="text" name="nama_umkm"
               value="{{ old('nama_umkm', $umkm->nama_umkm) }}" required>

        <label>Kategori UMKM *</label>
        <select name="kategori_umkm_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $item)
                <option value="{{ $item->id }}"
                    @selected(old('kategori_umkm_id', $umkm->kategori_umkm_id) == $item->id)>
                    {{ $item->nama_kategori }}
                </option>
            @endforeach
        </select>

        <label>Deskripsi Usaha</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>

        <label for="kecamatan">Kecamatan *</label>
        <select id="kecamatan" name="kecamatan" required>
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($wilayah->pluck('kecamatan')->unique()->sort() as $kecamatan)
                <option value="{{ $kecamatan }}"
                    @selected(old('kecamatan', $wilayah->firstWhere('id', $umkm->wilayah_id)?->kecamatan) == $kecamatan)>
                    {{ $kecamatan }}
                </option>
            @endforeach
        </select>

        <label for="wilayah_id">Desa / Kelurahan / Negeri *</label>
        <select id="wilayah_id" name="wilayah_id" required>
            <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>
            @foreach ($wilayah as $item)
                <option
                    value="{{ $item->id }}"
                    data-kecamatan="{{ $item->kecamatan }}"
                    @selected(old('wilayah_id', $umkm->wilayah_id) == $item->id)>
                    {{ $item->jenis_wilayah }} {{ $item->kelurahan_desa }}
                </option>
            @endforeach
        </select>

        <label>Alamat Lengkap *</label>
        <textarea name="alamat" rows="3" required>{{ old('alamat', $umkm->alamat) }}</textarea>

        <h3 class="section-title">B. Lokasi Google Maps</h3>

        <label>Latitude</label>
        <input type="number" step="any" name="latitude"
               value="{{ old('latitude', $umkm->latitude) }}"
               placeholder="Contoh: -3.6500000">

        <label>Longitude</label>
        <input type="number" step="any" name="longitude"
               value="{{ old('longitude', $umkm->longitude) }}"
               placeholder="Contoh: 128.1800000">

        <p>Koordinat dapat dilengkapi kemudian melalui fitur Edit UMKM.</p>

        <h3 class="section-title">C. Kontak dan Media Sosial</h3>

        <label>Telepon</label>
        <input type="text" name="telepon"
            value="{{ old('telepon', $umkm->telepon) }}">

        <label>WhatsApp</label>
        <input type="text" name="whatsapp"
            value="{{ old('whatsapp', $umkm->whatsapp) }}">

        <label>Instagram</label>
        <input type="text" name="instagram"
            value="{{ old('instagram', $umkm->instagram) }}">

        <label>Facebook</label>
        <input type="text" name="facebook"
            value="{{ old('facebook', $umkm->facebook) }}">

        <label>TikTok</label>
        <input type="text" name="tiktok"
            value="{{ old('tiktok', $umkm->tiktok) }}">

        <label>Website</label>
        <input type="text" name="website"
            value="{{ old('website', $umkm->website) }}">

        <label>Jam Operasional</label>
        <input type="text" name="jam_operasional"
            value="{{ old('jam_operasional', $umkm->jam_operasional) }}"
            placeholder="Contoh: Senin-Sabtu, 08.00-17.00 WIT">

        <label>Tahun Berdiri</label>
        <input type="number" name="tahun_berdiri"
            min="1900" max="2100"
            value="{{ old('tahun_berdiri', $umkm->tahun_berdiri) }}">

        <h3 class="section-title">D. Foto Usaha</h3>

        <label>Logo UMKM</label>

        @if ($umkm->logo)
            <p>Logo saat ini:</p>
            <img src="{{ asset('storage/' . $umkm->logo) }}"
                alt="Logo {{ $umkm->nama_umkm }}"
                style="max-width: 150px; height: auto; border-radius: 5px;">
        @else
            <p>Logo belum tersedia.</p>
        @endif

        <input type="file" name="logo"
            accept=".jpg,.jpeg,.png,.webp">

        <small style="color: #64748b;">
            Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
            Kosongkan jika tidak ingin mengganti logo.
        </small>


        <label>Foto Tempat Usaha</label>

        @if ($umkm->foto_usaha)
            <p>Foto tempat usaha saat ini:</p>
            <img src="{{ asset('storage/' . $umkm->foto_usaha) }}"
                alt="Foto {{ $umkm->nama_umkm }}"
                style="max-width: 350px; height: auto; border-radius: 5px;">
        @else
            <p>Foto tempat usaha belum tersedia.</p>
        @endif

        <input type="file" name="foto_usaha"
            accept=".jpg,.jpeg,.png,.webp">

        <small style="color: #64748b;">
            Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
            Kosongkan jika tidak ingin mengganti foto.
        </small>

        <button type="submit"
        onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan data UMKM ini?')">
            Simpan Perubahan
        </button>
        </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kecamatanSelect = document.getElementById('kecamatan');
    const wilayahSelect = document.getElementById('wilayah_id');

    const semuaWilayah = Array.from(wilayahSelect.options)
        .filter(option => option.value !== '')
        .map(option => option.cloneNode(true));

    const wilayahLama = @json(old('wilayah_id', $umkm->wilayah_id));

    function tampilkanWilayah() {
        const kecamatanDipilih = kecamatanSelect.value;

        wilayahSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = kecamatanDipilih
            ? '-- Pilih Desa / Kelurahan / Negeri --'
            : '-- Pilih Kecamatan Terlebih Dahulu --';

        wilayahSelect.appendChild(placeholder);

        semuaWilayah.forEach(function (option) {
            if (option.dataset.kecamatan === kecamatanDipilih) {
                wilayahSelect.appendChild(option.cloneNode(true));
            }
        });
    }

    kecamatanSelect.addEventListener('change', function () {
        tampilkanWilayah();
    });

    tampilkanWilayah();

    if (wilayahLama) {
        wilayahSelect.value = wilayahLama;
    }
});
</script>
@endpush
