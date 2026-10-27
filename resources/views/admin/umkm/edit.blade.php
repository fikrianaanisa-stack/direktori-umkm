@extends('layouts.admin')

@section('title', 'Edit UMKM')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-form.css') }}">
@endpush

@section('content')
<div class="umkm-form-container p-4 p-md-5">

    <h1 class="text-center fw-bold mb-2">Edit UMKM</h1>
    <p class="text-center text-secondary mb-4">
        Perbarui informasi usaha pada formulir berikut.
    </p>

    <a href="{{ route('admin.umkm.index') }}"
       class="btn btn-outline-secondary btn-sm mb-4">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali ke Data UMKM
    </a>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mb-0 mt-2">
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

        <h3 class="section-title pb-2 mb-3">
            A. Identitas UMKM
        </h3>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="nama_umkm" class="form-label">
                    Nama UMKM <span class="text-danger">*</span>
                </label>

                <input type="text"
                       id="nama_umkm"
                       name="nama_umkm"
                       class="form-control"
                       value="{{ old('nama_umkm', $umkm->nama_umkm) }}"
                       required>
            </div>

            <div class="col-md-6">
                <label for="kategori_umkm_id" class="form-label">
                    Kategori UMKM <span class="text-danger">*</span>
                </label>

                <select id="kategori_umkm_id"
                        name="kategori_umkm_id"
                        class="form-select"
                        required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('kategori_umkm_id', $umkm->kategori_umkm_id) == $item->id)>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label for="deskripsi" class="form-label">
                    Deskripsi Usaha
                </label>

                <textarea id="deskripsi"
                          name="deskripsi"
                          class="form-control"
                          rows="3">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
            </div>

            <div class="col-md-6">
                <label for="tahun_berdiri" class="form-label">
                    Tahun Berdiri (Opsional)
                </label>

                <input type="number"
                       id="tahun_berdiri"
                       name="tahun_berdiri"
                       class="form-control"
                       min="1900"
                       max="2100"
                       value="{{ old('tahun_berdiri', $umkm->tahun_berdiri) }}">
            </div>

            <div class="col-md-6">
                <label for="kecamatan" class="form-label">
                    Kecamatan <span class="text-danger">*</span>
                </label>

                <select id="kecamatan"
                        name="kecamatan"
                        class="form-select"
                        required>

                    <option value="">-- Pilih Kecamatan --</option>

                    @foreach ($wilayah->pluck('kecamatan')->unique()->sort() as $kecamatan)
                        <option value="{{ $kecamatan }}"
                            @selected(old('kecamatan', $wilayah->firstWhere('id', $umkm->wilayah_id)?->kecamatan) == $kecamatan)>
                            {{ $kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="wilayah_id" class="form-label">
                    Desa / Kelurahan / Negeri
                    <span class="text-danger">*</span>
                </label>

                <select id="wilayah_id"
                        name="wilayah_id"
                        class="form-select"
                        required>

                    <option value="">
                        -- Pilih Kecamatan Terlebih Dahulu --
                    </option>

                    @foreach ($wilayah as $item)
                        <option value="{{ $item->id }}"
                                data-kecamatan="{{ $item->kecamatan }}"
                                @selected(old('wilayah_id', $umkm->wilayah_id) == $item->id)>
                            {{ $item->jenis_wilayah }} {{ $item->kelurahan_desa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label for="alamat" class="form-label">
                    Alamat Lengkap <span class="text-danger">*</span>
                </label>

                <textarea id="alamat"
                          name="alamat"
                          class="form-control"
                          rows="3"
                          required>{{ old('alamat', $umkm->alamat) }}</textarea>
            </div>
        </div>

        <h3 class="section-title pb-2 mt-4 mb-3">
            B. Lokasi Google Maps
        </h3>

        <div class="mb-3">
            <label for="google_maps_url" class="form-label">
                Link Google Maps
            </label>

            <input type="url"
                   id="google_maps_url"
                   name="google_maps_url"
                   class="form-control"
                   value="{{ old('google_maps_url', $umkm->google_maps_url) }}"
                   placeholder="https://maps.app.goo.gl/..."
                   maxlength="2048">

            <div class="form-text">
                Buka profil usaha di Google Maps, klik Bagikan,
                lalu Salin link dan tempelkan di sini.
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="latitude" class="form-label">
                    Latitude
                </label>

                <input type="number"
                       id="latitude"
                       name="latitude"
                       class="form-control"
                       step="any"
                       value="{{ old('latitude', $umkm->latitude) }}"
                       placeholder="Contoh: -3.6500000">
            </div>

            <div class="col-md-6">
                <label for="longitude" class="form-label">
                    Longitude
                </label>

                <input type="number"
                       id="longitude"
                       name="longitude"
                       class="form-control"
                       step="any"
                       value="{{ old('longitude', $umkm->longitude) }}"
                       placeholder="Contoh: 128.1800000">
            </div>
        </div>

        <p class="form-text mt-2">
            Link Google Maps dapat digunakan tanpa mengisi koordinat.
            Latitude dan longitude bersifat opsional.
        </p>

        <h3 class="section-title pb-2 mt-4 mb-3">
            C. Kontak dan Media Sosial
        </h3>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="text"
                       id="telepon"
                       name="telepon"
                       class="form-control"
                       value="{{ old('telepon', $umkm->telepon) }}">
            </div>

            <div class="col-md-6">
                <label for="whatsapp" class="form-label">WhatsApp</label>
                <input type="text"
                       id="whatsapp"
                       name="whatsapp"
                       class="form-control"
                       value="{{ old('whatsapp', $umkm->whatsapp) }}">
            </div>

            <div class="col-md-6">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text"
                       id="instagram"
                       name="instagram"
                       class="form-control"
                       value="{{ old('instagram', $umkm->instagram) }}">
            </div>

            <div class="col-md-6">
                <label for="facebook" class="form-label">Facebook</label>
                <input type="text"
                       id="facebook"
                       name="facebook"
                       class="form-control"
                       value="{{ old('facebook', $umkm->facebook) }}">
            </div>

            <div class="col-md-6">
                <label for="tiktok" class="form-label">TikTok</label>
                <input type="text"
                       id="tiktok"
                       name="tiktok"
                       class="form-control"
                       value="{{ old('tiktok', $umkm->tiktok) }}">
            </div>

            <div class="col-md-6">
                <label for="website" class="form-label">Website</label>
                <input type="text"
                       id="website"
                       name="website"
                       class="form-control"
                       value="{{ old('website', $umkm->website) }}">
            </div>
        </div>

        <h3 class="section-title pb-2 mt-4 mb-3">
            D. Jam Operasional Harian
        </h3>

        @if ($errors->has('jadwal'))
            <div class="alert alert-danger">
                {{ $errors->first('jadwal') }}
            </div>
        @endif

        <p class="text-secondary small">
            Atur jadwal buka dan tutup untuk setiap hari.
        </p>

        @php
            $hariList = [
                'Senin', 'Selasa', 'Rabu', 'Kamis',
                'Jumat', 'Sabtu', 'Minggu'
            ];

            $jadwalTersimpan = $umkm->jamOperasionalHarian->keyBy('hari');
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered align-middle jadwal-table">
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
                            <td class="nama-hari">
                                {{ $hari }}
                            </td>

                            <td>
                                <select name="jadwal[{{ $hari }}][status]"
                                        id="status-{{ $hari }}"
                                        class="form-select form-select-sm">

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
                                <input type="time"
                                       name="jadwal[{{ $hari }}][jam_buka]"
                                       class="form-control form-control-sm"
                                       value="{{ old("jadwal.$hari.jam_buka", $jadwal?->jam_buka ? substr($jadwal->jam_buka, 0, 5) : '') }}">
                            </td>

                            <td>
                                <input type="time"
                                       name="jadwal[{{ $hari }}][jam_tutup]"
                                       class="form-control form-control-sm"
                                       value="{{ old("jadwal.$hari.jam_tutup", $jadwal?->jam_tutup ? substr($jadwal->jam_tutup, 0, 5) : '') }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <label for="keterangan_operasional" class="form-label">
                Keterangan Operasional (Opsional)
            </label>

            <textarea id="keterangan_operasional"
                      name="keterangan_operasional"
                      class="form-control"
                      rows="3"
                      maxlength="100"
                      placeholder="Contoh: Libur pada tanggal merah dan hari besar keagamaan.">{{ old('keterangan_operasional', $umkm->keterangan_operasional) }}</textarea>

            <div class="form-text">
                Informasi tambahan mengenai operasional usaha.
                Maksimal 100 karakter.
            </div>

            @error('keterangan_operasional')
                <div class="text-danger small mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <h3 class="section-title pb-2 mt-4 mb-3">
            E. Foto Usaha
        </h3>

        <div class="row g-4">
            <div class="col-md-6">
                <label for="logo" class="form-label">
                    Logo UMKM
                </label>

                @if ($umkm->logo)
                    <p class="foto-keterangan">
                        Logo saat ini:
                    </p>

                    <div class="foto-preview">
                        <img src="{{ asset('storage/' . $umkm->logo) }}"
                             alt="Logo {{ $umkm->nama_umkm }}">
                    </div>
                @else
                    <p class="foto-keterangan">
                        Logo belum tersedia.
                    </p>
                @endif

                <input type="file"
                       id="logo"
                       name="logo"
                       class="form-control"
                       accept=".jpg,.jpeg,.png,.webp">

                <button type="button"
                        class="btn btn-outline-danger btn-sm mt-2 d-none"
                        data-clear-file="logo">
                    <i class="bi bi-x-circle me-1"></i>
                    Hapus Pilihan
                </button>

                <div class="form-text">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                    Kosongkan jika tidak ingin mengganti logo.
                </div>
            </div>

            <div class="col-md-6">
                <label for="foto_usaha" class="form-label">
                    Foto Tempat Usaha
                </label>

                @if ($umkm->foto_usaha)
                    <p class="foto-keterangan">
                        Foto tempat usaha saat ini:
                    </p>

                    <div class="foto-preview">
                        <img src="{{ asset('storage/' . $umkm->foto_usaha) }}"
                             alt="Foto {{ $umkm->nama_umkm }}">
                    </div>
                @else
                    <p class="foto-keterangan">
                        Foto tempat usaha belum tersedia.
                    </p>
                @endif

                <input type="file"
                       id="foto_usaha"
                       name="foto_usaha"
                       class="form-control"
                       accept=".jpg,.jpeg,.png,.webp">

                <button type="button"
                        class="btn btn-outline-danger btn-sm mt-2 d-none"
                        data-clear-file="foto_usaha">
                    <i class="bi bi-x-circle me-1"></i>
                    Hapus Pilihan
                </button>

                <div class="form-text">
                    Format: JPG, JPEG, PNG, WebP. Maksimal 2 MB.
                    Kosongkan jika tidak ingin mengganti foto.
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-floppy me-1"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('admin.umkm.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i>
                Batal
            </a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-umkm-edit.js') }}"></script>
@endpush