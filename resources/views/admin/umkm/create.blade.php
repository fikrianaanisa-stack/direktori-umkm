@extends('layouts.admin')

@section('title', 'Tambah UMKM')

@push('styles')

<style>
  /* Kontainer formulir */
    .umkm-form-container {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        padding: 24px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Judul halaman */
    .umkm-form-container h1 {
        color: #243b53;
        font-size: 27px;
        font-weight: 700;
        margin: 0 0 6px;
        line-height: 1.3;
        text-align: center;
    }

    /* Caption di bawah judul */
    .umkm-form-container h1 + p {
        text-align: center;
        color: #64748b;
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Judul bagian formulir */
    .umkm-form-container .section-title {
        font-size: 19px;
        font-weight: 600;
        color: #243b53;
        margin-top: 26px;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #2563eb;
    }

    /* Label input */
    .umkm-form-container label {
        display: block;
        margin-top: 12px;
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    /* Input, select, textarea */
    .umkm-form-container input,
    .umkm-form-container select,
    .umkm-form-container textarea {
        width: 100%;
        padding: 8px 11px;
        margin-top: 3px;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 14px;
        color: #1e293b;
        background-color: #ffffff;
    }

    /* Tinggi input dan select */
    .umkm-form-container input:not([type="file"]),
    .umkm-form-container select {
        min-height: 40px;
    }

    /* Fokus input */
    .umkm-form-container input:focus,
    .umkm-form-container select:focus,
    .umkm-form-container textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    /* Deskripsi dan alamat */
    .umkm-form-container textarea {
        min-height: 75px;
        resize: vertical;
    }

    /* Teks bantuan */
    .umkm-form-container small {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Tombol simpan */
    .umkm-form-container button[type="submit"] {
        margin-top: 20px;
        padding: 10px 18px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .umkm-form-container button[type="submit"]:hover {
        background: #1d4ed8;
    }

    /* Pesan kesalahan */
    .umkm-form-container .error {
        color: #dc2626;
    }

    /* Tanda kolom wajib */
    .umkm-form-container .required {
        color: #dc2626;
        font-weight: 600;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .umkm-form-container {
            padding: 18px;
        }

        .umkm-form-container h1 {
            font-size: 23px;
        }

        .umkm-form-container .section-title {
            font-size: 18px;
        }
    }

    .umkm-form-container .form-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
    }

    .umkm-form-container .form-actions .btn-simpan,
    .umkm-form-container .form-actions .btn-batal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        margin-top: 0;
        border-radius: 7px;
        font-size: 16px;
        font-weight: 400;
        text-decoration: none;
    }

    .umkm-form-container .form-actions .btn-batal {
        background: #e2e8f0;
        color: #334155;
        border: none;
    }

    .umkm-form-container .form-actions .btn-batal:hover {
        background: #cbd5e1;
    }

    
    /* Tata letak formulir dua kolom */
    .umkm-form-container .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 20px;
        row-gap: 12px;
    }

    .umkm-form-container .form-field {
        min-width: 0;
    }

    .umkm-form-container .form-field label {
        margin-top: 0;
    }

    /* Tampilan satu kolom pada HP */
    @media (max-width: 768px) {
        .umkm-form-container .form-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Jam operasional harian */
    .umkm-form-container .jadwal-operasional {
        margin-top: 24px;
    }

    .umkm-form-container .jadwal-keterangan {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 14px;
    }

    /* Tabel jadwal */
    .umkm-form-container .jadwal-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .umkm-form-container .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .umkm-form-container .jadwal-table th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        text-align: left;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }

    .umkm-form-container .jadwal-table td {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .umkm-form-container .jadwal-table .nama-hari {
        font-weight: 600;
        color: #334155;
    }

    /* Input khusus tabel */
    .umkm-form-container .jadwal-table select,
    .umkm-form-container .jadwal-table input[type="time"] {
        width: 100%;
        min-height: 38px;
        margin: 0;
        padding: 6px 8px;
        font-size: 13px;
    }

    /* Tampilan input nonaktif */
    .umkm-form-container .jadwal-table input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .umkm-form-container .jadwal-table {
            min-width: 620px;
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

    <form id="form-umkm"
        action="{{ route('umkm.store') }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf

        <h3 class="section-title">A. Identitas UMKM</h3>

        <div class="form-grid">
            <div class="form-field">
                <label>Nama UMKM <span class="required">*</span></label>
                <input type="text" name="nama_umkm"
                    value="{{ old('nama_umkm') }}" required>
            </div>

            <div class="form-field">
                <label>Kategori UMKM <span class="required">*</span></label>
                <select name="kategori_umkm_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('kategori_umkm_id') == $item->id)>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label>Deskripsi Usaha</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>

        <label>Tahun Berdiri (Opsional)</label>
        <input type="number"
            name="tahun_berdiri"
            min="1900"
            max="2100"
            value="{{ old('tahun_berdiri') }}">
        
        <div class="form-grid">
            <div class="form-field">
                <label for="kecamatan">
                    Kecamatan <span class="required">*</span>
                </label>

                <select id="kecamatan" name="kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>

                    @foreach ($wilayah->pluck('kecamatan')->unique()->sort() as $kecamatan)
                        <option value="{{ $kecamatan }}"
                            @selected(old('kecamatan') == $kecamatan)>
                            {{ $kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="wilayah_id">
                    Desa / Kelurahan / Negeri <span class="required">*</span>
                </label>

                <select id="wilayah_id" name="wilayah_id" required>
                    <option value="">
                        -- Pilih Kecamatan Terlebih Dahulu --
                    </option>

                    @foreach ($wilayah as $item)
                        <option
                            value="{{ $item->id }}"
                            data-kecamatan="{{ $item->kecamatan }}"
                            @selected(old('wilayah_id') == $item->id)>
                            {{ $item->jenis_wilayah }} {{ $item->kelurahan_desa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label>Alamat Lengkap <span class="required">*</span></label>
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
        
        <div class="form-grid">
            <div class="form-field">
                <label>Latitude</label>
                <input type="number"
                    step="any"
                    name="latitude"
                    value="{{ old('latitude') }}"
                    placeholder="Contoh: -3.6500000">
            </div>

            <div class="form-field">
                <label>Longitude</label>
                <input type="number"
                    step="any"
                    name="longitude"
                    value="{{ old('longitude') }}"
                    placeholder="Contoh: 128.1800000">
            </div>
        </div>
            Link Google Maps dapat digunakan tanpa mengisi koordinat.
            Latitude dan longitude bersifat opsional.
        </p>

        <h3 class="section-title">C. Kontak dan Media Sosial</h3>
        
        <div class="form-grid">
            <div class="form-field">
                <label>Telepon</label>
                <input type="text" name="telepon"
                    value="{{ old('telepon') }}">
            </div>

            <div class="form-field">
                <label>WhatsApp</label>
                <input type="text" name="whatsapp"
                    value="{{ old('whatsapp') }}">
            </div>

            <div class="form-field">
                <label>Instagram</label>
                <input type="text" name="instagram"
                    value="{{ old('instagram') }}">
            </div>

            <div class="form-field">
                <label>Facebook</label>
                <input type="text" name="facebook"
                    value="{{ old('facebook') }}">
            </div>

            <div class="form-field">
                <label>TikTok</label>
                <input type="text" name="tiktok"
                    value="{{ old('tiktok') }}">
            </div>

            <div class="form-field">
                <label>Website</label>
                <input type="text" name="website"
                    value="{{ old('website') }}">
            </div>
        </div>
        
        {{-- Pengaturan jam operasional harian --}}
        <div class="jadwal-operasional">
            <h3 class="section-title">D. Jam Operasional Harian</h3>
            <p class="jadwal-keterangan">
                Atur status dan jam operasional untuk setiap hari.
            </p>

            @php
                $hariList = [
                    'Senin', 'Selasa', 'Rabu', 'Kamis',
                    'Jumat', 'Sabtu', 'Minggu'
                ];
            @endphp

            <div class="jadwal-table-wrapper">
                <table class="jadwal-table">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Status Operasional</th>
                            <th>Jam Buka</th>
                            <th>Jam Tutup</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($hariList as $hari)
                            @php
                                $statusJadwal = old(
                                    "jadwal.$hari.status",
                                    'belum_diatur'
                                );
                            @endphp

                            <tr>
                                <td class="nama-hari">
                                    {{ $hari }}
                                </td>

                                <td>
                                    <select
                                        name="jadwal[{{ $hari }}][status]"
                                        id="status-{{ $hari }}">

                                        <option value="belum_diatur"
                                            @selected($statusJadwal === 'belum_diatur')>
                                            Belum Diatur
                                        </option>

                                        <option value="buka"
                                            @selected($statusJadwal === 'buka')>
                                            Buka
                                        </option>

                                        <option value="tutup"
                                            @selected($statusJadwal === 'tutup')>
                                            Tutup
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="jadwal[{{ $hari }}][jam_buka]"
                                        value="{{ old("jadwal.$hari.jam_buka") }}"
                                        @disabled($statusJadwal !== 'buka')>
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="jadwal[{{ $hari }}][jam_tutup]"
                                        value="{{ old("jadwal.$hari.jam_tutup") }}"
                                        @disabled($statusJadwal !== 'buka')>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>

            <div class="form-field">
                <label for="keterangan_operasional">
                    Keterangan Operasional (Opsional)
                </label>

                <textarea
                    id="keterangan_operasional"
                    name="keterangan_operasional"
                    rows="3"
                    maxlength="100"
                    placeholder="Contoh: Libur pada tanggal merah dan hari besar keagamaan.">{{ old('keterangan_operasional') }}</textarea>

                <small>
                    Informasi tambahan mengenai operasional usaha.
                    Maksimal 100 karakter.
                </small>

                @error('keterangan_operasional')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <h3 class="section-title">E. Foto Usaha</h3>
        
        <div class="form-grid">
            <div class="form-field">
                <label for="logo">Logo UMKM</label>

                <input type="file"
                    id="logo"
                    name="logo"
                    accept=".jpg,.jpeg,.png,.webp">

                <small style="color: #64748b;">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                </small>
            </div>

            <div class="form-field">
                <label for="foto_usaha">Foto Tempat Usaha</label>

                <input type="file"
                    id="foto_usaha"
                    name="foto_usaha"
                    accept=".jpg,.jpeg,.png,.webp">

                <small style="color: #64748b;">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                </small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-simpan">
                <i class="bi bi-floppy me-2"></i>
                Simpan UMKM
            </button>

            <a href="{{ route('admin.umkm.index') }}" class="btn-batal">
                <i class="bi bi-x-circle me-2"></i>
                Batal
            </a>
        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pengaturan jam operasional harian
    const hariList = [
        'Senin', 'Selasa', 'Rabu', 'Kamis',
        'Jumat', 'Sabtu', 'Minggu'
    ];

    hariList.forEach(function (hari) {
        const status = document.getElementById('status-' + hari);

        const jamBuka = document.querySelector(
            `[name="jadwal[${hari}][jam_buka]"]`
        );

        const jamTutup = document.querySelector(
            `[name="jadwal[${hari}][jam_tutup]"]`
        );

        if (!status || !jamBuka || !jamTutup) return;

        function perbaruiKolomJam() {
            const isBuka = status.value === 'buka';

            // Aktifkan kolom jam hanya jika status Buka
            jamBuka.disabled = !isBuka;
            jamTutup.disabled = !isBuka;

            // Bedakan tampilan kolom aktif dan tidak aktif
            jamBuka.style.backgroundColor = isBuka ? '#ffffff' : '#f1f5f9';
            jamTutup.style.backgroundColor = isBuka ? '#ffffff' : '#f1f5f9';

            jamBuka.style.cursor = isBuka ? 'text' : 'not-allowed';
            jamTutup.style.cursor = isBuka ? 'text' : 'not-allowed';
        }

        // Jalankan ketika halaman pertama kali dibuka
        perbaruiKolomJam();

        // Jalankan setiap kali status diubah
        status.addEventListener('change', perbaruiKolomJam);
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-umkm');
    if (!form) return;

    let adaPerubahan = false;
    let sedangMenyimpan = false;

    // Mendeteksi perubahan data formulir
    form.addEventListener('input', function () {
        adaPerubahan = true;
    });

    form.addEventListener('change', function () {
        adaPerubahan = true;
    });

    // Menangani konfirmasi penyimpanan
    form.addEventListener('submit', function (event) {
        const yakin = confirm(
            'Apakah Anda yakin data UMKM yang dimasukkan sudah benar?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan ketika meninggalkan halaman
    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan && !sedangMenyimpan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});
</script>

@endpush

