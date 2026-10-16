
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

    <form id="form-edit-umkm"
        action="{{ route('umkm.update', $umkm->id) }}"
        method="POST"
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
        
        <label for="google_maps_url">Link Google Maps</label>
        <input type="url"
            id="google_maps_url"
            name="google_maps_url"
            value="{{ old('google_maps_url', $umkm->google_maps_url) }}"
            placeholder="https://maps.app.goo.gl/..."
            maxlength="2048">

        <small style="color: #64748b;">
            Buka profil usaha di Google Maps, klik Bagikan,
            lalu Salin link dan tempelkan di sini.
        </small>


        <label>Latitude</label>
        <input type="number" step="any" name="latitude"
               value="{{ old('latitude', $umkm->latitude) }}"
               placeholder="Contoh: -3.6500000">

        <label>Longitude</label>
        <input type="number" step="any" name="longitude"
               value="{{ old('longitude', $umkm->longitude) }}"
               placeholder="Contoh: 128.1800000">

        <p>
            Link Google Maps dapat digunakan tanpa mengisi koordinat.
            Latitude dan longitude bersifat opsional.
        </p>

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

        {{-- Pengaturan jam operasional per hari --}}
        <div style="margin-top: 20px;">
            <h3>Jam Operasional Harian</h3>
            
            {{-- Menampilkan kesalahan validasi jadwal --}}
            @if ($errors->has('jadwal'))
                <div style="background: #fee2e2; color: #991b1b;
                            padding: 12px; border-radius: 8px;
                            margin: 10px 0;">
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

            @foreach ($hariList as $hari)
                @php
                    $jadwal = $jadwalTersimpan->get($hari);
                    $isBuka = old(
                        "jadwal.$hari.is_buka",
                        $jadwal ? (int) $jadwal->is_buka : 0
                    );
                @endphp

                <div style="padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <strong>{{ $hari }}</strong>

                    {{-- Pilihan status operasional harian --}}
                    @php
                        $statusJadwal = old(
                            "jadwal.$hari.status",
                            $jadwal ? ($jadwal->is_buka ? 'buka' : 'tutup') : 'belum_diatur'
                        );
                    @endphp

                    <div style="margin-top: 10px;">
                        <label for="status-{{ $hari }}"
                            style="display: block; margin-bottom: 5px;">
                            Status Operasional
                        </label>

                        <select name="jadwal[{{ $hari }}][status]"
                                id="status-{{ $hari }}"
                                style="width: 100%; max-width: 220px; padding: 8px;">
                            <option value="belum_diatur"
                                {{ $statusJadwal == 'belum_diatur' ? 'selected' : '' }}>
                                Belum Diatur
                            </option>

                            <option value="buka"
                                {{ $statusJadwal == 'buka' ? 'selected' : '' }}>
                                Buka
                            </option>

                            <option value="tutup"
                                {{ $statusJadwal == 'tutup' ? 'selected' : '' }}>
                                Tutup
                            </option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <div>
                            <label>Jam Buka</label>
                            <input type="time"
                                name="jadwal[{{ $hari }}][jam_buka]"
                                value="{{ old("jadwal.$hari.jam_buka", $jadwal?->jam_buka ? substr($jadwal->jam_buka, 0, 5) : '') }}">
                        </div>

                        <div>
                            <label>Jam Tutup</label>
                            <input type="time"
                                name="jadwal[{{ $hari }}][jam_tutup]"
                                value="{{ old("jadwal.$hari.jam_tutup", $jadwal?->jam_tutup ? substr($jadwal->jam_tutup, 0, 5) : '') }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

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

        <button type="submit">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulir = document.getElementById('form-edit-umkm');

    if (!formulir) return;

    formulir.addEventListener('submit', async function (event) {
        event.preventDefault();

        // Konfirmasi sebelum menyimpan
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan data UMKM ini?')) {
            return;
        }

        const tombolSimpan = formulir.querySelector('button[type="submit"]');

        if (tombolSimpan?.disabled) return;

        // Siapkan data termasuk logo, foto, dan jadwal harian
        const dataFormulir = new FormData(formulir);

        if (tombolSimpan) {
            tombolSimpan.disabled = true;
            tombolSimpan.textContent = 'Menyimpan...';
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

            if (!response.ok) {
                // Ambil pesan kesalahan dari Laravel
                const semuaError = hasil.errors
                    ? Object.values(hasil.errors).flat()
                    : [];

                const pesan = semuaError.length
                    ? semuaError.join('\n')
                    : (hasil.message || 'Data UMKM gagal disimpan.');

                // Siapkan toast kesalahan
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
                    background: #fef2f2;
                    border: 1px solid #fca5a5;
                    color: #991b1b;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                    white-space: pre-line;
                `;

                toast.replaceChildren();

                const judul = document.createElement('strong');
                judul.textContent = 'Validasi Gagal';

                const keterangan = document.createElement('p');
                keterangan.style.margin = '8px 0 0';
                keterangan.textContent = pesan;

                toast.append(judul, keterangan);

                return;
            }

            // Tampilkan notifikasi keberhasilan
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
                background: #f0fdf4;
                border: 1px solid #86efac;
                color: #166534;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            `;

            toast.replaceChildren();

            const judul = document.createElement('strong');
            judul.textContent = 'Berhasil';

            const keterangan = document.createElement('p');
            keterangan.style.margin = '8px 0 0';
            keterangan.textContent =
                hasil.message || 'Data UMKM berhasil diperbarui.';

            toast.append(judul, keterangan);

            // Beri waktu membaca notifikasi sebelum berpindah halaman
            if (hasil.redirect) {
                setTimeout(function () {
                    window.location.href = hasil.redirect;
                }, 1500);
            }

        } catch (error) {
            console.error('Kesalahan AJAX Edit UMKM:', error);

            // Tampilkan toast kesalahan koneksi atau respons server
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
                background: #fef2f2;
                border: 1px solid #fca5a5;
                color: #991b1b;
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            `;

            toast.replaceChildren();

            const judul = document.createElement('strong');
            judul.textContent = 'Terjadi Kesalahan';

            const keterangan = document.createElement('p');
            keterangan.style.margin = '8px 0 0';
            keterangan.textContent =
                'Koneksi atau respons server bermasalah. ' +
                'Periksa status penyimpanan sebelum mencoba lagi.';

            toast.append(judul, keterangan);

        } finally {
            if (tombolSimpan) {
                tombolSimpan.disabled = false;
                tombolSimpan.textContent = 'Simpan Perubahan';
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
@endpush
