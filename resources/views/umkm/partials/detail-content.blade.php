{{-- Komponen detail UMKM untuk halaman publik dan admin --}}
<div class="umkm-detail">

    {{-- Profil UMKM --}}
    <section class="umkm-detail-card">
        <div class="d-flex flex-column flex-md-row align-items-start gap-4">

            @if ($umkm->logo)
                <img
                    src="{{ asset('storage/' . $umkm->logo) }}"
                    alt="Logo {{ $umkm->nama_umkm }}"
                    class="umkm-detail-logo"
                >
            @endif

            <div class="flex-grow-1 umkm-detail-profile-content">
                <h2 class="umkm-detail-name">
                    {{ $umkm->nama_umkm }}
                </h2>

                <span class="badge rounded-pill umkm-detail-category">
                    {{ $kategori->nama_kategori ?? '-' }}
                </span>

                <p class="umkm-detail-description">
                    {{ $umkm->deskripsi ?: 'Deskripsi belum tersedia.' }}
                </p>
            </div>

        </div>
    </section>

    {{-- Foto tempat usaha --}}
    @if ($umkm->foto_usaha)
        <section class="umkm-detail-card">
            <h3 class="umkm-detail-section-title">Foto Tempat Usaha</h3>

            <img
                src="{{ asset('storage/' . $umkm->foto_usaha) }}"
                alt="Foto tempat usaha {{ $umkm->nama_umkm }}"
                class="umkm-detail-photo"
            >
        </section>
    @endif

    {{-- Informasi usaha dan kontak --}}
    <div class="row g-3 mb-3">

        <div class="col-12 col-lg-6">
            <section class="umkm-detail-card h-100 mb-0">
                <h3 class="umkm-detail-section-title">Informasi Usaha</h3>

                <div class="umkm-detail-info">
                    <span class="umkm-detail-label">Alamat</span>
                    <span class="umkm-detail-value">
                        {{ $umkm->alamat ?: '-' }}
                    </span>
                </div>

                <div class="umkm-detail-info">
                    <span class="umkm-detail-label">Kecamatan</span>
                    <span class="umkm-detail-value">
                        {{ $wilayah->kecamatan ?? '-' }}
                    </span>
                </div>

                <div class="umkm-detail-info">
                    <span class="umkm-detail-label">Desa/Kelurahan/Negeri</span>
                    <span class="umkm-detail-value">
                        {{ $wilayah->jenis_wilayah ?? '' }}
                        {{ $wilayah->kelurahan_desa ?? '-' }}
                    </span>
                </div>

                <div class="umkm-detail-info">
                    <span class="umkm-detail-label">Tahun Berdiri</span>
                    <span class="umkm-detail-value">
                        {{ $umkm->tahun_berdiri ?: '-' }}
                    </span>
                </div>

                {{-- Mengelompokkan hari dengan jam operasional yang sama --}}
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
                        } elseif ($jadwal->jam_buka && $jadwal->jam_tutup) {
                            $keterangan = substr($jadwal->jam_buka, 0, 5)
                                . '–'
                                . substr($jadwal->jam_tutup, 0, 5)
                                . ' WIT';
                        } else {
                            $keterangan = 'Buka (jam tidak ditentukan)';
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
                                @php
                                    $statusJadwal = match ($kelompok['keterangan']) {
                                        'Tutup' => 'tutup',
                                        'Belum Diatur' => 'belum-diatur',
                                        default => 'buka',
                                    };
                                @endphp

                                <div class="umkm-operasional-row">
                                    <span>
                                        {{ $kelompok['awal'] }}

                                        @if ($kelompok['awal'] !== $kelompok['akhir'])
                                            –{{ $kelompok['akhir'] }}
                                        @endif
                                    </span>

                                    <span class="umkm-operasional-status umkm-operasional-{{ $statusJadwal }}">
                                        {{ $kelompok['keterangan'] }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <span>Belum tersedia</span>
                        @endif
                    </div>
                </div>

                @if (filled($umkm->keterangan_operasional))
                    <div class="umkm-detail-info">
                        <span class="umkm-detail-label">
                            Keterangan Operasional
                        </span>

                        <span class="umkm-detail-value">
                            {{ $umkm->keterangan_operasional }}
                        </span>
                    </div>
                @endif

            </section>
        </div>

        {{-- Kontak dan media sosial --}}
        <div class="col-12 col-lg-6">
            <section class="umkm-detail-card h-100 mb-0">
                <h3 class="umkm-detail-section-title">
                    Kontak &amp; Media Sosial
                </h3>

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
                                    class="umkm-telephone-link"
                                >
                                    {{ $nilai }}
                                </a>

                            @elseif ($label === 'WhatsApp' && $nilai)
                                @php
                                    $nomorWa = preg_replace('/\D/', '', $nilai);

                                    if (str_starts_with($nomorWa, '0')) {
                                        $nomorWa = '62' . substr($nomorWa, 1);
                                    }

                                    $pesanWa = 'Halo, saya melihat '
                                        . $umkm->nama_umkm
                                        . ' di Direktori UMKM Kota Ambon. '
                                        . 'Saya ingin bertanya mengenai produk atau layanan yang tersedia.';

                                    $linkWa = 'https://wa.me/' . $nomorWa
                                        . '?text=' . rawurlencode($pesanWa);
                                @endphp

                                <a
                                    href="{{ $linkWa }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn umkm-whatsapp-button"
                                >
                                    <i class="bi bi-whatsapp me-1"></i>
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
                                    class="btn umkm-social-link"
                                >
                                    Buka {{ $label }}
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>

                            @else
                                {{ $nilai ?: '-' }}
                            @endif

                        </span>
                    </div>

                @endforeach

            </section>
        </div>

    </div>

    {{-- Lokasi usaha --}}
    @if (
        $umkm->google_maps_url ||
        ($umkm->latitude !== null && $umkm->longitude !== null)
    )
        @php
            if ($umkm->google_maps_url) {
                $linkMaps = $umkm->google_maps_url;
            } else {
                $koordinat = $umkm->latitude . ',' . $umkm->longitude;

                $linkMaps = 'https://www.google.com/maps/search/?api=1&query='
                    . rawurlencode($koordinat);
            }
        @endphp

        <section class="umkm-detail-card">
            <h3 class="umkm-detail-section-title">Lokasi Usaha</h3>

            <p class="umkm-detail-muted">
                Lihat lokasi usaha melalui Google Maps.
            </p>

            <a
                href="{{ $linkMaps }}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn umkm-social-link"
            >
                <i class="bi bi-geo-alt me-1"></i>
                Lihat di Google Maps
                <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
        </section>
    @endif

    {{-- Katalog produk UMKM --}}
    <section class="umkm-detail-card">
        <h3 class="umkm-detail-section-title">Katalog Produk</h3>

        @if ($umkm->produk->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">

                @foreach ($umkm->produk as $produk)
                    @php
                        $fotoUtama = $produk->fotoProduk->first();
                        $hargaTerendah = $produk->hargaProduk->min('harga_min');
                    @endphp

                    <div class="col">
                        <article class="card umkm-produk-item h-100">

                            @if ($fotoUtama)
                                <img
                                    src="{{ asset('storage/' . $fotoUtama->foto) }}"
                                    alt="{{ $produk->nama_produk }}"
                                    class="umkm-produk-foto"
                                >
                            @else
                                <div class="umkm-produk-foto umkm-produk-placeholder">
                                    Foto belum tersedia
                                </div>
                            @endif

                            <div class="card-body umkm-produk-body">
                                <h4>{{ $produk->nama_produk }}</h4>

                                <p class="umkm-produk-kategori">
                                    {{ $produk->kategoriProduk->nama_kategori ?? '-' }}
                                </p>

                                <p class="umkm-produk-harga">
                                    @if ($hargaTerendah === null)
                                        <span class="umkm-produk-harga-kosong">
                                            Hubungi UMKM untuk informasi harga
                                        </span>
                                    @else
                                        Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                                    @endif
                                </p>

                                {{-- Rute detail produk mengikuti halaman pemanggil --}}
                                <a
                                    href="{{ ($isAdminView ?? false)
                                        ? route('admin.produk.show', $produk->id)
                                        : route('produk.show', $produk->id) }}"
                                    class="btn umkm-produk-link"
                                >
                                    Lihat Detail Produk
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                        </article>
                    </div>
                @endforeach

            </div>
        @else
            <p class="umkm-detail-muted mb-0">
                Belum ada produk yang ditambahkan untuk UMKM ini.
            </p>
        @endif

    </section>

</div>