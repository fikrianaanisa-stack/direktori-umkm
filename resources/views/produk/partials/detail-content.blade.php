@push('styles')
 <style>
    .produk-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    /* Kartu Detail Produk */
    .produk-card {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: start;
        gap: 30px;
        padding: 25px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    /* Mencegah konten melebar keluar dari kolom */
    .produk-card > div {
        min-width: 0;
    }

    /* Menyesuaikan teks informasi produk */
    .produk-info {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    /* Foto Detail Produk */
    .produk-foto {
        display: block;
        width: 100%;
        height: 350px;
        max-height: 350px;
        object-fit: contain;
        object-position: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }

    /* Slider Foto Produk */
    .produk-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 10px;
    }

    /* Tombol navigasi kiri dan kanan */
    .produk-slider-prev,
    .produk-slider-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: center;

        width: 38px;
        height: 38px;
        padding: 0;

        border: none;
        border-radius: 50%;

        background: rgba(15, 23, 42, 0.65);
        color: #ffffff;

        font-size: 22px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .produk-slider-prev {
        left: 12px;
    }

    .produk-slider-next {
        right: 12px;
    }

    .produk-slider-prev:hover,
    .produk-slider-next:hover {
        background: rgba(15, 23, 42, 0.9);
    }

    /* Indikator nomor foto */
    .produk-slider-indikator {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);

        z-index: 2;
        padding: 6px 14px;

        border-radius: 20px;
        background: rgba(15, 23, 42, 0.7);
        color: #ffffff;

        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Penyesuaian layar HP */
    @media (max-width: 600px) {
        .produk-slider-prev,
        .produk-slider-next {
            width: 34px;
            height: 34px;
            font-size: 18px;
        }

        .produk-slider-prev {
            left: 8px;
        }

        .produk-slider-next {
            right: 8px;
        }

        .produk-slider-indikator {
            bottom: 10px;
            font-size: 12px;
        }
    }

    .produk-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 350px;
        background: #f1f5f9;
        color: #94a3b8;
        border-radius: 10px;
    }

    .produk-info h1 {
        margin-top: 0;
        font-size: 26px;
    }

    .produk-kategori {
        color: #64748b;
        font-size: 14px;
    }

    
    /* Daftar Harga Produk */
    .produk-harga {
        margin: 20px 0;
        color: #0f766e;
        font-size: 16px;
        font-weight: normal;
    }

    /* Nilai Harga pada Setiap Pilihan */
    .produk-harga-nilai {
        color: #0f766e;
        font-size: 20px;
        font-weight: 700;
    }

    /* Kartu Pilihan Harga */
    .produk-harga-item {
        padding: 14px 16px;
        margin-bottom: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: border-color 0.2s ease;
    }

    /* Efek saat kursor diarahkan ke kartu harga */
    .produk-harga-item:hover {
        border-color: #5eead4;
    }

    /* Keterangan Jumlah dan Satuan Harga */
    .produk-harga-satuan {
        margin-top: 6px;
        color: #64748b;
        font-size: 14px;
    }

    /* Informasi Jika Harga Belum Tersedia */
    .produk-harga-kosong {
        color: #64748b;
        font-size: 15px;
    }

    /* Catatan Perubahan Harga */
    .produk-harga-catatan {
        margin-top: 10px;
        color: #64748b;
        font-size: 12px;
        font-weight: normal;
    }

    .produk-deskripsi {
        line-height: 1.7;
        color: #475569;
        white-space: pre-line;
    }

    /* Tombol Lihat Profil UMKM */
    .produk-link {
        display: inline-block;
        margin-top: 18px;
        padding: 11px 16px;
        background: #0f766e;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.2s ease;
    }

    .produk-link:hover {
        background: #115e59;
        color: #ffffff;
    }
    
    /* Tampilan Tablet dan HP */
    @media (max-width: 768px) {
        .produk-card {
            grid-template-columns: 1fr;
            gap: 24px;
            padding: 20px;
        }

        .produk-foto {
            height: 300px;
            max-height: 300px;
        }

        .produk-placeholder {
            min-height: 300px;
        }

        .produk-info h1 {
            font-size: 24px;
        }
    }

    /* Tampilan HP dengan Layar Kecil */
    @media (max-width: 480px) {
        .produk-card {
            gap: 20px;
            padding: 16px;
        }

        .produk-foto {
            height: 240px;
            max-height: 240px;
        }

        .produk-placeholder {
            min-height: 240px;
        }

        .produk-info h1 {
            font-size: 22px;
        }

        .produk-harga-nilai {
            font-size: 18px;
        }

        .produk-harga-item {
            padding: 12px;
        }

        .produk-link {
            max-width: 100%;
            text-align: center;
        }
    }
    
    /* Kartu Pilihan Harga */
    .produk-harga-item {
        padding: 14px 16px;
        margin-bottom: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: border-color 0.2s ease;
    }

    /* Efek Hover Kartu Harga */
    .produk-harga-item:hover {
        border-color: #5eead4;
    }

    /* Keterangan Jumlah dan Satuan */
    .produk-harga-satuan {
        margin-top: 6px;
        color: #64748b;
        font-size: 14px;
    }

    /* Informasi Harga Belum Tersedia */
    .produk-harga-kosong {
        color: #64748b;
        font-size: 15px;
    }

    /* Catatan Perubahan Harga */
    .produk-harga-catatan {
        margin-top: 10px;
        color: #64748b;
        font-size: 12px;
        font-weight: normal;
    }
    
    /* Bagian Informasi Produk */
    .produk-section {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    /* Judul Bagian Informasi */
    .produk-section h3 {
        margin: 0 0 14px;
        font-size: 18px;
        font-weight: 700;
        color: #0f766e;
    }

    /* Nama UMKM Penyedia */
    .produk-umkm-nama {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
    }

    /* Keterangan Jika UMKM Tidak Tersedia */
    .produk-umkm-kosong {
        color: #64748b;
        font-weight: 400;
    }

    
    /* Navigasi Breadcrumb Detail Produk */
    .produk-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
        font-size: 14px;
        color: #64748b;
    }

    /* Tautan Breadcrumb */
    .produk-breadcrumb a {
        color: #0f766e;
        text-decoration: none;
    }

    /* Efek Hover Breadcrumb */
    .produk-breadcrumb a:hover {
        text-decoration: underline;
    }

    /* Nama Produk pada Breadcrumb */
    .produk-breadcrumb [aria-current="page"] {
        overflow-wrap: anywhere;
    }
</style>
@endpush

<div class="produk-container">

    <div class="produk-card">

        {{-- Foto Produk --}}
        @php
            // Ambil seluruh foto dari tabel foto_produk
            $daftarFoto = $produk->fotoProduk;

            $jumlahFoto = $daftarFoto->count();
            $sliderId = 'slider-produk-' . $produk->id;
        @endphp

        <div>
            @if ($jumlahFoto > 0)
                <div class="produk-slider" id="{{ $sliderId }}">

                    @foreach ($daftarFoto as $index => $foto)
                        <img
                            src="{{ asset('storage/' . $foto->foto) }}"
                            alt="{{ $produk->nama_produk }} - Foto {{ $index + 1 }}"
                            class="produk-foto produk-slide"
                            data-slide="{{ $index }}"
                            style="{{ $index === 0 ? '' : 'display:none;' }}"
                        >
                    @endforeach

                    @if ($jumlahFoto > 1)
                        <button
                            type="button"
                            class="produk-slider-prev"
                            aria-label="Foto sebelumnya"
                            onclick="geserFotoProduk('{{ $sliderId }}', -1)">
                            &#10094;
                        </button>

                        <button
                            type="button"
                            class="produk-slider-next"
                            aria-label="Foto berikutnya"
                            onclick="geserFotoProduk('{{ $sliderId }}', 1)">
                            &#10095;
                        </button>

                        <div class="produk-slider-indikator">
                            <span class="produk-slide-aktif">1</span>
                            / {{ $jumlahFoto }}
                        </div>
                    @endif

                </div>
            @else
                <div class="produk-placeholder">
                    Foto produk belum tersedia
                </div>
            @endif
        </div>

        <div class="produk-info">

            <h1>{{ $produk->nama_produk }}</h1>

            <p class="produk-kategori">
                Kategori:
                {{ $produk->kategoriProduk->nama_kategori ?? '-' }}
            </p>

            {{-- Pilihan Harga --}}
            <div class="produk-harga">

                @forelse ($produk->hargaProduk->sortBy('harga_min') as $pilihan)

                    <div class="produk-harga-item">

                        <div class="produk-harga-nilai">
                            Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                            @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                            @endif
                        </div>

                        <div class="produk-harga-satuan">
                            @if ($pilihan->satuan)
                                Untuk
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                {{ $pilihan->satuan }}
                            @else
                                Jumlah:
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                            @endif
                        </div>

                    </div>

                @empty

                    <span class="produk-harga-kosong">
                        Hubungi UMKM untuk informasi harga
                    </span>

                @endforelse

                <p class="produk-harga-catatan">
                    Harga dapat berubah sewaktu-waktu.
                </p>

            </div>

            {{-- Deskripsi Produk --}}
            <section class="produk-section">
                <h3>Deskripsi Produk</h3>

                <p class="produk-deskripsi">
                    {{ $produk->deskripsi ?: 'Deskripsi produk belum tersedia.' }}
                </p>
            </section>

            {{-- Informasi UMKM Penyedia --}}
            <section class="produk-section">
                <h3>UMKM Penyedia</h3>

                @if ($produk->umkm)

                    <p class="produk-umkm-nama">
                        {{ $produk->umkm->nama_umkm }}
                    </p>

                    <a href="{{ ($isAdminView ?? false)
                        ? route('admin.umkm.show', $produk->umkm->id)
                        : route('umkm.show', $produk->umkm->id) }}"
                       class="produk-link">
                        Lihat Profil UMKM →
                    </a>

                @else

                    <p class="produk-umkm-nama produk-umkm-kosong">
                        Informasi UMKM belum tersedia.
                    </p>

                @endif
            </section>

        </div>

    </div>

</div>

<script>
    function geserFotoProduk(sliderId, arah) {
        const slider = document.getElementById(sliderId);

        if (!slider) return;

        const foto = slider.querySelectorAll('.produk-slide');
        const indikator = slider.querySelector('.produk-slide-aktif');

        if (foto.length <= 1) return;

        let posisiAktif = Array.from(foto).findIndex(
            item => item.style.display !== 'none'
        );

        if (posisiAktif < 0) {
            posisiAktif = 0;
        }

        const posisiBaru =
            (posisiAktif + arah + foto.length) % foto.length;

        foto.forEach((item, index) => {
            item.style.display =
                index === posisiBaru ? 'block' : 'none';
        });

        if (indikator) {
            indikator.textContent = posisiBaru + 1;
        }
    }
</script>