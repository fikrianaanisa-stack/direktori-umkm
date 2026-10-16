@extends('layouts.admin')

@section('title', 'Tambah UMKM')

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

    <h1>Tambah Data UMKM</h1>
    <p>Lengkapi informasi usaha pada formulir berikut.</p>

    <a href="{{ route('admin.umkm.index') }}">← Kembali ke Daftar UMKM</a>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/umkm') }}" method="POST"
          enctype="multipart/form-data">
        @csrf

        <h3 class="section-title">A. Identitas UMKM</h3>

        <label>Nama UMKM *</label>
        <input type="text" name="nama_umkm"
               value="{{ old('nama_umkm') }}" required>

        <label>Kategori UMKM *</label>
        <select name="kategori_umkm_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $item)
                <option value="{{ $item->id }}"
                    @selected(old('kategori_umkm_id') == $item->id)>
                    {{ $item->nama_kategori }}
                </option>
            @endforeach
        </select>

        <label>Deskripsi Usaha</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>

        <label for="kecamatan">Kecamatan *</label>
        <select id="kecamatan" name="kecamatan" required>
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($wilayah->pluck('kecamatan')->unique()->sort() as $kecamatan)
                <option value="{{ $kecamatan }}"
                    @selected(old('kecamatan') == $kecamatan)>
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
                    @selected(old('wilayah_id') == $item->id)>
                    {{ $item->jenis_wilayah }} {{ $item->kelurahan_desa }}
                </option>
            @endforeach
        </select>

        <label>Alamat Lengkap *</label>
        <textarea name="alamat" rows="3" required>{{ old('alamat') }}</textarea>

        <h3 class="section-title">B. Lokasi Google Maps</h3>

        <label for="google_maps_url">Link Google Maps</label>
        <input type="url"
            id="google_maps_url"
            name="google_maps_url"
            value="{{ old('google_maps_url') }}"
            placeholder="https://maps.app.goo.gl/..."
            maxlength="2048">

        <small style="color: #64748b;">
            Buka profil usaha di Google Maps, klik Bagikan,
            lalu Salin link dan tempelkan di sini.
        </small>

        
        <label>Latitude</label>
        <input type="number" step="any" name="latitude"
               value="{{ old('latitude') }}"
               placeholder="Contoh: -3.6500000">

        <label>Longitude</label>
        <input type="number" step="any" name="longitude"
               value="{{ old('longitude') }}"
               placeholder="Contoh: 128.1800000">

        <p>
            Link Google Maps dapat digunakan tanpa mengisi koordinat.
            Latitude dan longitude bersifat opsional.
        </p>

        <h3 class="section-title">C. Kontak dan Media Sosial</h3>

        <label>Telepon</label>
        <input type="text" name="telepon" value="{{ old('telepon') }}">

        <label>WhatsApp</label>
        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}">

        <label>Instagram</label>
        <input type="text" name="instagram" value="{{ old('instagram') }}">

        <label>Facebook</label>
        <input type="text" name="facebook" value="{{ old('facebook') }}">

        <label>TikTok</label>
        <input type="text" name="tiktok" value="{{ old('tiktok') }}">

        <label>Website</label>
        <input type="text" name="website" value="{{ old('website') }}">

        <label>Jam Operasional</label>
        <input type="text" name="jam_operasional"
               value="{{ old('jam_operasional') }}"
               placeholder="Contoh: Senin-Sabtu, 08.00-17.00 WIT">

        <label>Tahun Berdiri</label>
        <input type="number" name="tahun_berdiri"
               min="1900" max="2100"
               value="{{ old('tahun_berdiri') }}">

        <h3 class="section-title">D. Foto Usaha</h3>

        <label>Logo UMKM</label>
        <input type="file" name="logo"
            accept=".jpg,.jpeg,.png,.webp">
        <small style="color: #64748b;">
            Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
        </small>

        <label>Foto Tempat Usaha</label>
        <input type="file" name="foto_usaha"
            accept=".jpg,.jpeg,.png,.webp">
        <small style="color: #64748b;">
            Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
        </small>

        <button type="submit"
        onclick="return confirm('Apakah Anda yakin data UMKM yang dimasukkan sudah benar?')">
            Simpan UMKM
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

    const wilayahLama = @json(old('wilayah_id'));

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

