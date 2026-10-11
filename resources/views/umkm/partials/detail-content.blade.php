<style>
    .umkm-detail {
        font-family: Arial, sans-serif;
        color: #1e293b;
    }

    .umkm-detail * {
        box-sizing: border-box;
    }

    .umkm-detail-card {
        min-width: 0;
        margin-bottom: 16px;
        padding: 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .umkm-detail-card h3 {
        margin: 0 0 12px;
        font-size: 17px;
        color: #1e293b;
    }

    .umkm-detail-profile {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .umkm-detail-logo {
        width: 110px;
        height: 110px;
        padding: 10px;
        flex-shrink: 0;
        object-fit: contain;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .umkm-detail-profile h2 {
        margin: 0 0 6px;
        font-size: 24px;
        line-height: 1.3;
    }

    .umkm-detail-category {
        display: inline-block;
        margin-bottom: 8px;
        padding: 4px 10px;
        background: #dbeafe;
        color: #1d4ed8;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .umkm-detail-description {
        margin: 0;
        color: #64748b;
        line-height: 1.5;
    }

    .umkm-detail-photo {
        display: block;
        width: 100%;
        max-height: 350px;
        object-fit: contain;
        background: #f8fafc;
        border-radius: 10px;
    }

    .umkm-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .umkm-detail-grid .umkm-detail-card {
        margin-bottom: 0;
    }

    .umkm-detail-info {
        display: grid;
        grid-template-columns: minmax(0, 42%) minmax(0, 1fr);
        gap: 12px;
        padding: 9px 0;
        border-bottom: 1px solid #f1f5f9;
        line-height: 1.5;
    }

    .umkm-detail-info:last-child {
        border-bottom: none;
    }

    .umkm-detail-label,
    .umkm-detail-value {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .umkm-detail-label {
        font-weight: 600;
        color: #475569;
    }

    .umkm-whatsapp-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 14px;
        background: #16a34a;
        color: #fff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .umkm-whatsapp-button:hover {
        background: #15803d;
        color: #fff;
    }

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

    .umkm-telephone-link {
        color: #2563eb;
        font-weight: 500;
        text-decoration: none;
    }

    .umkm-telephone-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .umkm-detail-profile {
            flex-direction: column;
            align-items: flex-start;
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

            <div class="umkm-detail-info">
                <span class="umkm-detail-label">Jam Operasional</span>
                <span class="umkm-detail-value">{{ $umkm->jam_operasional ?: '-' }}</span>
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
    @if ($umkm->latitude !== null && $umkm->longitude !== null)
        @php
            $koordinat = $umkm->latitude . ',' . $umkm->longitude;
            $linkMaps = 'https://www.google.com/maps/search/?api=1&query='
                . rawurlencode($koordinat);
        @endphp

        <div class="umkm-detail-card" style="margin-top: 16px;">
            <h3>Lokasi Usaha</h3>
            <p style="color: #64748b; margin: 0 0 14px;">
                Lihat lokasi usaha melalui Google Maps.
            </p>
            <a
                href="{{ $linkMaps }}"
                target="_blank"
                rel="noopener noreferrer"
                class="umkm-social-link">
                Lihat di Google Maps ↗
            </a>
        </div>
    @endif
</div>
