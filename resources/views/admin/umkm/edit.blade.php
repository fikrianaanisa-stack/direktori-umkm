@extends('layouts.admin')

@section('title', 'Edit UMKM')

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

    /* Caption */
    .umkm-form-container h1 + p {
        text-align: center;
        color: #64748b;
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Judul bagian */
    .umkm-form-container .section-title {
        font-size: 19px;
        font-weight: 600;
        color: #243b53;
        margin-top: 26px;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #2563eb;
    }

    /* Label */
    .umkm-form-container label {
        display: block;
        margin-top: 12px;
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    /* Input */
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

    .umkm-form-container input:not([type="file"]),
    .umkm-form-container select {
        min-height: 40px;
    }

    .umkm-form-container textarea {
        min-height: 75px;
        resize: vertical;
    }

    /* Fokus input */
    .umkm-form-container input:focus,
    .umkm-form-container select:focus,
    .umkm-form-container textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    /* Tata letak dua kolom */
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

    /* Tombol simpan */
    .umkm-form-container button[type="submit"] {
        margin-top: 20px;
        padding: 10px 18px;
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .umkm-form-container button[type="submit"]:hover {
        background: #1d4ed8;
    }

    /* Pesan error */
    .umkm-form-container .error {
        color: #dc2626;
    }

    /* Kolom wajib */
    .umkm-form-container .required {
        color: #dc2626;
        font-weight: 600;
    }
    
    /* Area pratinjau logo dan foto usaha */
    .umkm-form-container .foto-preview {
        width: 100%;
        height: 180px;
        padding: 10px;
        margin-bottom: 12px;
        box-sizing: border-box;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Gambar tetap proporsional */
    .umkm-form-container .foto-preview img {
        display: block;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        margin: 0;
    }

    /* Keterangan foto */
    .umkm-form-container .foto-keterangan {
        font-size: 13px;
        color: #64748b;
        margin: 8px 0;
    }

    /* Keterangan format file */
    .umkm-form-container .foto-bantuan {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
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

        .umkm-form-container .form-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Tombol aksi formulir */
    .umkm-form-container .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 24px;
    }

    .umkm-form-container .form-actions .btn-simpan,
    .umkm-form-container .form-actions .btn-batal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        line-height: 1.4;
        cursor: pointer;
    }

    /* Tombol Simpan Perubahan */
    .umkm-form-container .form-actions .btn-simpan {
        margin-top: 0;
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
    }

    .umkm-form-container .form-actions .btn-simpan:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    /* Tombol Batal */
    .umkm-form-container .form-actions .btn-batal {
        background: #e2e8f0;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .umkm-form-container .form-actions .btn-batal:hover {
        background: #cbd5e1;
        color: #1e293b;
        border-color: #cbd5e1;
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

    
    <form id="form-edit-umkm"
        action="{{ route('umkm.update', $umkm->id) }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3 class="section-title">A. Identitas UMKM</h3>
        
        <div class="form-grid">
            <div class="form-field">
                <label>Nama UMKM <span class="required">*</span></label>
                <input type="text"
                    name="nama_umkm"
                    value="{{ old('nama_umkm', $umkm->nama_umkm) }}"
                    required>
            </div>

            <div class="form-field">
                <label>Kategori UMKM <span class="required">*</span></label>
                <select name="kategori_umkm_id" required>
                    <option value="">-- Pilih Kategori --</option>

                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('kategori_umkm_id', $umkm->kategori_umkm_id) == $item->id)>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label>Deskripsi Usaha</label>
        <textarea name="deskripsi" rows="3">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>

        <label>Tahun Berdiri (Opsional)</label>
        <input type="number"
            name="tahun_berdiri"
            min="1900"
            max="2100"
            value="{{ old('tahun_berdiri', $umkm->tahun_berdiri) }}">
        
        <div class="form-grid">
            <div class="form-field">
                <label for="kecamatan">
                    Kecamatan <span class="required">*</span>
                </label>

                <select id="kecamatan" name="kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>

                    @foreach ($wilayah->pluck('kecamatan')->unique()->sort() as $kecamatan)
                        <option value="{{ $kecamatan }}"
                            @selected(old('kecamatan', $wilayah->firstWhere('id', $umkm->wilayah_id)?->kecamatan) == $kecamatan)>
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
                    <option value="">-- Pilih Kecamatan Terlebih Dahulu --</option>

                    @foreach ($wilayah as $item)
                        <option value="{{ $item->id }}"
                            data-kecamatan="{{ $item->kecamatan }}"
                            @selected(old('wilayah_id', $umkm->wilayah_id) == $item->id)>
                            {{ $item->jenis_wilayah }} {{ $item->kelurahan_desa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label>Alamat Lengkap <span class="required">*</span></label>
        <textarea name="alamat" rows="3" required>{{ old('alamat', $umkm->alamat) }}</textarea>

        <h3 class="section-title">B. Lokasi Google Maps</h3>

        <label for="google_maps_url">Link Google Maps</label>
        <input type="url"
            id="google_maps_url"
            name="google_maps_url"
            value="{{ old('google_maps_url', $umkm->google_maps_url) }}"
            placeholder="https://maps.app.goo.gl/..."
            maxlength="2048">

        <small>
            Buka profil usaha di Google Maps, klik Bagikan,
            lalu Salin link dan tempelkan di sini.
        </small>
        
        <div class="form-grid">
            <div class="form-field">
                <label>Latitude</label>
                <input type="number"
                    step="any"
                    name="latitude"
                    value="{{ old('latitude', $umkm->latitude) }}"
                    placeholder="Contoh: -3.6500000">
            </div>

            <div class="form-field">
                <label>Longitude</label>
                <input type="number"
                    step="any"
                    name="longitude"
                    value="{{ old('longitude', $umkm->longitude) }}"
                    placeholder="Contoh: 128.1800000">
            </div>
        </div>

        <p>
            Link Google Maps dapat digunakan tanpa mengisi koordinat.
            Latitude dan longitude bersifat opsional.
        </p>

        <h3 class="section-title">C. Kontak dan Media Sosial</h3>
        
        <div class="form-grid">
            <div class="form-field">
                <label>Telepon</label>
                <input type="text"
                    name="telepon"
                    value="{{ old('telepon', $umkm->telepon) }}">
            </div>

            <div class="form-field">
                <label>WhatsApp</label>
                <input type="text"
                    name="whatsapp"
                    value="{{ old('whatsapp', $umkm->whatsapp) }}">
            </div>

            <div class="form-field">
                <label>Instagram</label>
                <input type="text"
                    name="instagram"
                    value="{{ old('instagram', $umkm->instagram) }}">
            </div>

            <div class="form-field">
                <label>Facebook</label>
                <input type="text"
                    name="facebook"
                    value="{{ old('facebook', $umkm->facebook) }}">
            </div>

            <div class="form-field">
                <label>TikTok</label>
                <input type="text"
                    name="tiktok"
                    value="{{ old('tiktok', $umkm->tiktok) }}">
            </div>

            <div class="form-field">
                <label>Website</label>
                <input type="text"
                    name="website"
                    value="{{ old('website', $umkm->website) }}">
            </div>
        </div>

        
        {{-- Pengaturan jam operasional per hari --}}
        <div class="jadwal-operasional">
            <h3 class="section-title">D. Jam Operasional Harian</h3>

            @if ($errors->has('jadwal'))
                <div class="error">
                    {{ $errors->first('jadwal') }}
                </div>
            @endif

            <p>Atur jadwal buka dan tutup untuk setiap hari.</p>

            @php
                $hariList = [
                    'Senin', 'Selasa', 'Rabu', 'Kamis',
                    'Jumat', 'Sabtu', 'Minggu'
                ];

                $jadwalTersimpan = $umkm->jamOperasionalHarian->keyBy('hari');
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
                                $jadwal = $jadwalTersimpan->get($hari);

                                $statusJadwal = old(
                                    "jadwal.$hari.status",
                                    $jadwal
                                        ? ($jadwal->is_buka ? 'buka' : 'tutup')
                                        : 'belum_diatur'
                                );
                            @endphp

                            <tr>
                                <td><strong>{{ $hari }}</strong></td>

                                <td>
                                    <select
                                        name="jadwal[{{ $hari }}][status]"
                                        id="status-{{ $hari }}">

                                        <option value="belum_diatur"
                                            @selected($statusJadwal == 'belum_diatur')>
                                            Belum Diatur
                                        </option>

                                        <option value="buka"
                                            @selected($statusJadwal == 'buka')>
                                            Buka
                                        </option>

                                        <option value="tutup"
                                            @selected($statusJadwal == 'tutup')>
                                            Tutup
                                        </option>
                                    </select>
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="jadwal[{{ $hari }}][jam_buka]"
                                        value="{{ old("jadwal.$hari.jam_buka", $jadwal?->jam_buka ? substr($jadwal->jam_buka, 0, 5) : '') }}">
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="jadwal[{{ $hari }}][jam_tutup]"
                                        value="{{ old("jadwal.$hari.jam_tutup", $jadwal?->jam_tutup ? substr($jadwal->jam_tutup, 0, 5) : '') }}">
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
                    placeholder="Contoh: Libur pada tanggal merah dan hari besar keagamaan.">{{ old('keterangan_operasional', $umkm->keterangan_operasional) }}</textarea>

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

            {{-- Logo UMKM --}}
            <div class="form-field">
                <label>Logo UMKM</label>

                @if ($umkm->logo)
                    <p class="foto-keterangan">Logo saat ini:</p>

                    <div class="foto-preview">
                        <img src="{{ asset('storage/' . $umkm->logo) }}"
                            alt="Logo {{ $umkm->nama_umkm }}">
                    </div>
                @else
                    <p class="foto-keterangan">Logo belum tersedia.</p>
                @endif

                <input type="file"
                    name="logo"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="foto-bantuan">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                    Kosongkan jika tidak ingin mengganti logo.
                </small>
            </div>

            {{-- Foto Tempat Usaha --}}
            <div class="form-field">
                <label>Foto Tempat Usaha</label>

                @if ($umkm->foto_usaha)
                    <p class="foto-keterangan">Foto tempat usaha saat ini:</p>

                    <div class="foto-preview">
                        <img src="{{ asset('storage/' . $umkm->foto_usaha) }}"
                            alt="Foto {{ $umkm->nama_umkm }}">
                    </div>
                @else
                    <p class="foto-keterangan">Foto tempat usaha belum tersedia.</p>
                @endif

                <input type="file"
                    name="foto_usaha"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="foto-bantuan">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                    Kosongkan jika tidak ingin mengganti foto.
                </small>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn-simpan">
                <i class="bi bi-floppy me-2"></i>
                Simpan Perubahan
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
    let penyimpananBerhasil = false;
</script>

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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulir = document.getElementById('form-edit-umkm');

    if (!formulir) return;

    formulir.addEventListener('submit', async function (event) {
        event.preventDefault();

        const tombolSimpan = formulir.querySelector('button[type="submit"]');

        if (tombolSimpan?.disabled) return;

        // Konfirmasi sebelum menyimpan
        const yakin = window.confirm(
            'Apakah Anda yakin data UMKM sudah benar dan ingin menyimpan perubahan?'
        );

        if (!yakin) return;

        // Siapkan seluruh data, termasuk foto dan jadwal
        const dataFormulir = new FormData(formulir);

        const tombolAwal = tombolSimpan?.innerHTML;

        if (tombolSimpan) {
            tombolSimpan.disabled = true;
            tombolSimpan.innerHTML = 'Menyimpan...';
        }

        // Fungsi untuk menampilkan notifikasi
        function tampilkanToast(judul, pesan, berhasil) {
            let toast = document.getElementById('admin-toast');

            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'admin-toast';
                document.body.appendChild(toast);
            }

            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                width: calc(100% - 40px);
                max-width: 360px;
                box-sizing: border-box;
                padding: 16px;
                border-radius: 10px;
                background: ${berhasil ? '#f0fdf4' : '#fef2f2'};
                border: 1px solid ${berhasil ? '#86efac' : '#fca5a5'};
                color: ${berhasil ? '#166534' : '#991b1b'};
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                white-space: pre-line;
            `;

            toast.replaceChildren();

            const judulToast = document.createElement('strong');
            judulToast.textContent = judul;

            const isiToast = document.createElement('p');
            isiToast.style.margin = '8px 0 0';
            isiToast.textContent = pesan;

            toast.append(judulToast, isiToast);
        }

        try {
            const response = await fetch(formulir.action, {
                method: 'POST',
                body: dataFormulir,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const hasil = await response.json();

            if (!response.ok || hasil.success === false) {
                const semuaError = hasil.errors
                    ? Object.values(hasil.errors).flat()
                    : [];

                const pesan = semuaError.length
                    ? semuaError.join('\n')
                    : (hasil.message || 'Data UMKM gagal disimpan.');

                tampilkanToast('Validasi Gagal', pesan, false);
                return;
            }

            // Penyimpanan berhasil
            penyimpananBerhasil = true;

            // Nonaktifkan peringatan perubahan belum disimpan
            window.nonaktifkanPeringatanUmkm?.();

            tampilkanToast(
                'Berhasil',
                hasil.message || 'Data UMKM berhasil diperbarui.',
                true
            );

            // Berpindah halaman setelah notifikasi ditampilkan
            if (hasil.redirect) {
                setTimeout(function () {
                    window.location.href = hasil.redirect;
                }, 1500);
            }

        } catch (error) {
            console.error('Kesalahan AJAX Edit UMKM:', error);

            tampilkanToast(
                'Terjadi Kesalahan',
                'Koneksi atau respons server bermasalah. Periksa status penyimpanan sebelum mencoba lagi.',
                false
            );

        } finally {
            if (tombolSimpan) {
                tombolSimpan.disabled = false;
                tombolSimpan.innerHTML = tombolAwal;
            }
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Daftar hari operasional
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

            jamBuka.disabled = !isBuka;
            jamTutup.disabled = !isBuka;

            jamBuka.style.backgroundColor = isBuka ? '#ffffff' : '#f1f5f9';
            jamTutup.style.backgroundColor = isBuka ? '#ffffff' : '#f1f5f9';

            jamBuka.style.cursor = isBuka ? 'text' : 'not-allowed';
            jamTutup.style.cursor = isBuka ? 'text' : 'not-allowed';
        }

        // Atur kondisi ketika halaman pertama kali dibuka
        perbaruiKolomJam();

        // Atur kondisi setiap kali status berubah
        status.addEventListener('change', perbaruiKolomJam);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-edit-umkm');

    if (!form) return;

    // Mengambil nilai seluruh input formulir
    function ambilDataForm() {
        const data = [];

        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (!field.name) return;

            if (field.type === 'file') {
                const files = Array.from(field.files).map(function (file) {
                    return [file.name, file.size, file.lastModified];
                });

                data.push([field.name, files]);
            } else if (field.type === 'checkbox' || field.type === 'radio') {
                data.push([field.name, field.checked, field.value]);
            } else {
                data.push([field.name, field.value]);
            }
        });

        return JSON.stringify(data);
    }

    // Menyimpan kondisi awal formulir setelah halaman selesai dimuat
    const dataAwal = ambilDataForm();

    function adaPerubahan() {
        return ambilDataForm() !== dataAwal;
    }

    
    // Peringatan hanya muncul jika ada perubahan yang belum berhasil disimpan
    function peringatanPerubahan(event) {
        if (penyimpananBerhasil) {
            return;
        }

        if (adaPerubahan()) {
            event.preventDefault();
            event.returnValue = '';
        }
    }

    // Aktifkan peringatan selama data belum disimpan
    window.addEventListener('beforeunload', peringatanPerubahan);

    // Nonaktifkan peringatan setelah penyimpanan berhasil
    window.nonaktifkanPeringatanUmkm = function () {
        window.removeEventListener('beforeunload', peringatanPerubahan);
    };

</script>

@endpush
