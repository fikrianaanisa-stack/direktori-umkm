<style>
    /* Pengaturan Dasar Detail UMKM */
    .umkm-detail {
        font-family: Arial, sans-serif;
        color: #1e293b;
    }

    .umkm-detail * {
        box-sizing: border-box;
    }

    /* Kartu Informasi UMKM */
    .umkm-detail-card {
        min-width: 0;
        margin-bottom: 16px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    /* Judul Kartu Informasi */
    .umkm-detail-card h3 {
        margin: 0 0 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #ccfbf1;
        font-size: 18px;
        font-weight: 700;
        color: #0f766e;
    }

    /* Profil UMKM */
    .umkm-detail-profile {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        padding: 8px;
    }

    .umkm-detail-profile > div {
        flex: 1;
        min-width: 0;
    }

    /* Logo UMKM */
    .umkm-detail-logo {
        width: 120px;
        height: 120px;
        padding: 12px;
        flex-shrink: 0;
        object-fit: contain;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
    }

    /* Nama UMKM */
    .umkm-detail-profile h2 {
        margin: 0 0 10px;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.3;
        color: #0f172a;
        overflow-wrap: anywhere;
    }

    /* Kategori UMKM */
    .umkm-detail-category {
        display: inline-block;
        margin-bottom: 14px;
        padding: 5px 12px;
        background: #ccfbf1;
        color: #0f766e;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Deskripsi UMKM */
    .umkm-detail-description {
        margin: 0;
        color: #475569;
        font-size: 15px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    /* Foto Tempat Usaha */
    .umkm-detail-photo {
        display: block;
        width: 100%;
        max-width: 650px;
        max-height: 320px;
        height: auto;
        margin: 0 auto;
        object-fit: contain;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    /* Tata Letak Informasi Usaha dan Kontak */
    .umkm-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: start;
        gap: 20px;
    }

    .umkm-detail-grid .umkm-detail-card {
        margin-bottom: 0;
        min-width: 0;
    }

    /* Baris Informasi Usaha */
    .umkm-detail-info {
        display: grid;
        grid-template-columns: minmax(0, 38%) minmax(0, 1fr);
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
        line-height: 1.6;
    }

    .umkm-detail-info:last-child {
        border-bottom: none;
    }

    .umkm-detail-label,
    .umkm-detail-value {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    /* Label Informasi */
    .umkm-detail-label {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }

    /* Nilai Informasi */
    .umkm-detail-value {
        font-size: 14px;
        color: #1e293b;
        overflow-wrap: anywhere;
    }

    /* Tombol WhatsApp */
    .umkm-whatsapp-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 14px;
        background: #16a34a;
        color: #ffffff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .umkm-whatsapp-button:hover {
        background: #15803d;
        color: #ffffff;
    }

    /* Tautan Media Sosial */
    .umkm-social-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .umkm-social-link:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    /* Tautan Nomor Telepon */
    .umkm-telephone-link {
        color: #2563eb;
        font-weight: 500;
        text-decoration: none;
    }

    .umkm-telephone-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    /* Grid Katalog Produk UMKM */
    .umkm-produk-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    /* Kartu Produk */
    .umkm-produk-item {
        min-width: 0;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .umkm-produk-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.10);
    }

    /* Foto Produk */
    .umkm-produk-foto {
        display: block;
        width: 100%;
        height: 200px;
        object-fit: contain;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Informasi Produk */
    .umkm-produk-body {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 18px;
        min-width: 0;
    }

    /* Nama Produk */
    .umkm-produk-body h4 {
        margin: 0 0 8px;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.4;
        color: #0f172a;
        overflow-wrap: anywhere;
    }

    /* Harga Produk */
    .umkm-produk-harga {
        margin: 12px 0;
        color: #0f766e;
        font-size: 17px;
        font-weight: 700;
    }

    /* Tombol Detail Produk */
    .umkm-produk-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: flex-start;
        margin-top: auto;
        padding: 10px 16px;
        background: #0f766e;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .umkm-produk-link:hover {
        background: #115e59;
        color: #ffffff;
    }

    /* Tampilan Tablet dan HP */
    @media (max-width: 768px) {
        .umkm-detail-profile {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 0;
        }

        .umkm-detail-logo {
            width: 95px;
            height: 95px;
        }

        .umkm-detail-profile h2 {
            font-size: 22px;
        }

        .umkm-detail-grid {
            grid-template-columns: 1fr;
        }

        .umkm-detail-info {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .umkm-detail-card {
            padding: 16px;
        }

        .umkm-produk-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Tampilan HP dengan Layar Kecil */
    @media (max-width: 480px) {
        .umkm-produk-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="umkm-detail">
    {{-- Profil UMKM --}}
    <div class="umkm-detail-card">
        <div class="umkm-detail-profile">
            @if ($umkm->logo)
                <img
                    src="{{ asset('storage/' . $umkm->logo) }}"
                    alt="Logo {{ $umkm->nama_umkm }}"
                    class="umkm-detail-logo">
            @endif

            <div>
                <h2>{{ $umkm->nama_umkm }}</h2>
                <span class="umkm-detail-category">
                    {{ $kategori->nama_kategori ?? '-' }}
                </span>
                <p class="umkm-detail-description">
                    {{ $umkm->deskripsi ?: 'Deskripsi belum tersedia.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Foto Tempat Usaha --}}
    @if ($umkm->foto_usaha)
        <div class="umkm-detail-card">
            <h3>Foto Tempat Usaha</h3>
            <img
                src="{{ asset('storage/' . $umkm->foto_usaha) }}"
                alt="Foto tempat usaha {{ $umkm->nama_umkm }}"
                class="umkm-detail-photo">
        </div>
    @endif

    <div class="umkm-detail-grid">
        {{-- Informasi Usaha --}}
        <div class="umkm-detail-card">
            <h3>Informasi Usaha</h3>

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Alamat</span>
                <span class="umkm-detail-value">{{ $umkm->alamat ?: '-' }}</span>
            </div>

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Kecamatan</span>
                <span class="umkm-detail-value">{{ $wilayah->kecamatan ?? '-' }}</span>
            </div>

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Desa/Kelurahan/Negeri</span>
                <span class="umkm-detail-value">
                    {{ $wilayah->jenis_wilayah ?? '' }} {{ $wilayah->kelurahan_desa ?? '-' }}
                </span>
            </div>

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Tahun Berdiri</span>
                <span class="umkm-detail-value">{{ $umkm->tahun_berdiri ?: '-' }}</span>
            </div>

            
            {{-- Jam Operasional Ringkas --}}
            @php
                $daftarHari = [
                    'Senin', 'Selasa', 'Rabu', 'Kamis',
                    'Jumat', 'Sabtu', 'Minggu'
                ];

                $jadwalHarian = $umkm->jamOperasionalHarian->keyBy('hari');

                $kelompokJadwal = [];

                foreach ($daftarHari as $hari) {
                    $jadwal = $jadwalHarian->get($hari);

                    if (!$jadwal) {
                        $keterangan = 'Belum Diatur';
                    } elseif (!$jadwal->is_buka) {
                        $keterangan = 'Tutup';
                    } else {
                        $keterangan = substr($jadwal->jam_buka, 0, 5)
                            . '–'
                            . substr($jadwal->jam_tutup, 0, 5)
                            . ' WIT';
                    }

                    $jumlahKelompok = count($kelompokJadwal);

                    if (
                        $jumlahKelompok > 0 &&
                        $kelompokJadwal[$jumlahKelompok - 1]['keterangan'] === $keterangan
                    ) {
                        $kelompokJadwal[$jumlahKelompok - 1]['akhir'] = $hari;
                    } else {
                        $kelompokJadwal[] = [
                            'awal' => $hari,
                            'akhir' => $hari,
                            'keterangan' => $keterangan,
                        ];
                    }
                }
            @endphp

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Jam Operasional</span>

                <div class="umkm-detail-value">

                    @if ($jadwalHarian->isNotEmpty())

                        @foreach ($kelompokJadwal as $kelompok)
                            <div style="display: flex;
                                        justify-content: space-between;
                                        gap: 12px;
                                        padding: 5px 0;">

                                <span>
                                    {{ $kelompok['awal'] }}
                                    @if ($kelompok['awal'] !== $kelompok['akhir'])
                                        –{{ $kelompok['akhir'] }}
                                    @endif
                                </span>

                                <span style="
                                    color: {{
                                        $kelompok['keterangan'] === 'Tutup'
                                            ? '#dc2626'
                                            : ($kelompok['keterangan'] === 'Belum Diatur'
                                                ? '#64748b'
                                                : '#16a34a')
                                    }};
                                    text-align: right;
                                ">
                                    {{ $kelompok['keterangan'] }}
                                </span>

                            </div>
                        @endforeach

                    @else

                        {{ $umkm->jam_operasional ?: 'Belum tersedia' }}

                    @endif
                </div>
            </div>
        </div>

        {{-- Kontak dan Media Sosial --}}
        <div class="umkm-detail-card">
            <h3>Kontak &amp; Media Sosial</h3>

            @foreach ([
                'Telepon' => $umkm->telepon,
                'WhatsApp' => $umkm->whatsapp,
                'Instagram' => $umkm->instagram,
                'Facebook' => $umkm->facebook,
                'TikTok' => $umkm->tiktok,
                'Website' => $umkm->website,
            ] as $label => $nilai)
                <div class="umkm-detail-info">
                    <span class="umkm-detail-label">
                        {{ $label }}
                    </span>

                    <span class="umkm-detail-value">
                        @if ($label === 'Telepon' && $nilai)
                            <a
                                href="tel:{{ preg_replace('/[^\d+]/', '', $nilai) }}"
                                class="umkm-telephone-link">
                                {{ $nilai }}
                            </a>

                        @elseif ($label === 'WhatsApp' && $nilai)
                            @php
                                $nomorWa = preg_replace('/\D/', '', $nilai);

                                if (str_starts_with($nomorWa, '0')) {
                                    $nomorWa = '62' . substr($nomorWa, 1);
                                }

                                $pesanWa = 'Halo, saya melihat ' . $umkm->nama_umkm
                                    . ' di Direktori UMKM Kota Ambon. '
                                    . 'Saya ingin bertanya mengenai produk atau layanan yang tersedia.';

                                $linkWa = 'https://wa.me/' . $nomorWa
                                    . '?text=' . rawurlencode($pesanWa);
                            @endphp

                            <a
                                href="{{ $linkWa }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="umkm-whatsapp-button">
                                Chat WhatsApp
                            </a>

                        @elseif (
                            in_array($label, ['Instagram', 'Facebook', 'TikTok', 'Website'])
                            && $nilai
                            && filter_var($nilai, FILTER_VALIDATE_URL)
                            && in_array(parse_url($nilai, PHP_URL_SCHEME), ['http', 'https'])
                        )
                            <a
                                href="{{ $nilai }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="umkm-social-link">
                                Buka {{ $label }} ↗
                            </a>

                        @else
                            {{ $nilai ?: '-' }}
                        @endif
                    </span>
                </div>
            @endforeach

        </div>
    </div>

    
    {{-- Lokasi Usaha --}}
    @if ($umkm->google_maps_url ||
        ($umkm->latitude !== null && $umkm->longitude !== null))

        @php
            // Prioritaskan link Google Maps
            if ($umkm->google_maps_url) {
                $linkMaps = $umkm->google_maps_url;
            } else {
                $koordinat = $umkm->latitude . ',' . $umkm->longitude;
                $linkMaps = 'https://www.google.com/maps/search/?api=1&query='
                    . rawurlencode($koordinat);
            }
        @endphp

        <div class="umkm-detail-card" style="margin-top: 16px;">
            <h3>Lokasi Usaha</h3>

            <p style="color: #64748b; margin: 0 0 14px;">
                Lihat lokasi usaha melalui Google Maps.
            </p>

            <a href="{{ $linkMaps }}"
            target="_blank"
            rel="noopener noreferrer"
            class="umkm-social-link">
                Lihat di Google Maps ↗
            </a>
        </div>
    @endif

    
    {{-- Katalog Produk UMKM --}}
    <div class="umkm-detail-card" style="margin-top: 16px;">
        <h3>Katalog Produk</h3>

        @if ($umkm->produk->isNotEmpty())
            <div class="umkm-produk-grid">

                @foreach ($umkm->produk as $produk)
                    <div class="umkm-produk-item">

                        @if ($produk->foto)
                            <img
                                src="{{ asset('storage/' . $produk->foto) }}"
                                alt="{{ $produk->nama_produk }}"
                                class="umkm-produk-foto">
                        @else
                            <div class="umkm-produk-foto"
                                style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                Foto belum tersedia
                            </div>
                        @endif

                        <div class="umkm-produk-body">
                            <h4>{{ $produk->nama_produk }}</h4>

                            <p style="color: #64748b; font-size: 13px;">
                                {{ $produk->kategoriProduk->nama_kategori ?? '-' }}
                            </p>

                            <p class="umkm-produk-harga">
                                @if ($produk->harga === null)
                                    <span style="color:#64748b;font-size:14px;">
                                        Hubungi UMKM untuk informasi harga
                                    </span>
                                @else
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                @endif
                            </p>
                            
                            <a href="{{ route('produk.show', $produk->id) }}"
                            class="umkm-produk-link">
                                Lihat Detail Produk →
                            </a>

                        </div>

                    </div>
                @endforeach

            </div>
        @else
            <p style="color: #64748b;">
                Belum ada produk yang ditambahkan untuk UMKM ini.
            </p>
        @endif
    </div>


</div>
