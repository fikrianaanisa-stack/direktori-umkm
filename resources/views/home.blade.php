@extends('layouts.public')

@section('title', 'Beranda - Direktori UMKM Kota Ambon')

@push('styles')
<style>

    <>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .hero {
            padding: 90px 8%;
            text-align: center;
        }

        .hero h1 {
            font-size: 38px;
            margin-bottom: 18px;
        }

        .hero p {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #0f766e;
            color: white;
            text-decoration: none;
            padding: 13px 24px;
            border-radius: 8px;
            margin: 5px;
        }

        .btn-outline {
            background: white;
            color: #0f766e;
            border: 1px solid #0f766e;
        }

        @media (max-width: 650px) {
            .hero {
                padding: 60px 6%;
            }

            .hero h1 {
                font-size: 28px;
            }
        }

        
        /* Statistik halaman utama */
        .statistik {
            display: flex;
            justify-content: center;
            gap: 24px;
            padding: 20px 8% 70px;
            flex-wrap: wrap;
        }

        .statistik-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px 40px;
            text-align: center;
            min-width: 220px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .statistik-card h2 {
            color: #0f766e;
            font-size: 36px;
            margin: 0 0 10px;
        }

        .statistik-card p {
            color: #64748b;
            margin: 0;
        }

        /* Daftar UMKM terbaru */
        .umkm-section {
            padding: 60px 8%;
            background: #ffffff;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-heading h2 {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: #64748b;
        }

        .umkm-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .umkm-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .umkm-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .umkm-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .umkm-card-body {
            padding: 20px;
        }

        .umkm-card-body h3 {
            margin: 0 0 10px;
        }

        .umkm-card-body p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .umkm-detail-link {
            display: inline-block;
            margin-top: 12px;
            color: #0f766e;
            font-weight: bold;
            text-decoration: none;
        }

        .umkm-detail-link:hover {
            text-decoration: underline;
        }

        .umkm-all-link {
            text-align: center;
            margin-top: 35px;
        }

        @media (max-width: 900px) {
            .umkm-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .umkm-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Katalog produk homepage */
        .produk-section {
            padding: 60px 8%;
            background: #f8fafc;
        }

        .produk-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .produk-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .produk-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .produk-card img,
        .produk-placeholder {
            width: 100%;
            height: 190px;
            object-fit: cover;
        }

        .produk-placeholder {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #e2e8f0;
            color: #64748b;
            font-size: 13px;
            text-align: center;
            padding: 15px;
        }

        .produk-card-body {
            padding: 18px;
        }

        .produk-card-body h3 {
            font-size: 17px;
            margin: 0 0 10px;
        }

        .produk-umkm {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .produk-detail-link {
            display: inline-block;
            margin-top: 12px;
            color: #0f766e;
            font-weight: bold;
            text-decoration: none;
        }

        .produk-detail-link:hover {
            text-decoration: underline;
        }

        .produk-all-link {
            text-align: center;
            margin-top: 35px;
        }

        @media (max-width: 1000px) {
            .produk-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .produk-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Kategori UMKM */
        .kategori-section {
            padding: 60px 8%;
            background: #f8fafc;
        }

        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 18px;
        }

        .kategori-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 28px 14px;
            text-align: center;
            text-decoration: none;
            color: #1e293b;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .kategori-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-color: #0f766e;
        }

        .kategori-icon {
            font-size: 36px;
            margin-bottom: 14px;
        }

        .kategori-card h3 {
            font-size: 16px;
            margin: 0 0 10px;
        }

        .kategori-card p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        @media (max-width: 1000px) {
            .kategori-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .kategori-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

    </style>
@endpush

@section('content')

    <div class="home-content">
        <section class="hero">
            <h1>Temukan UMKM dan Produk Lokal Kota Ambon</h1>

            <p>
                Jelajahi informasi usaha, produk, dan layanan
                dari berbagai UMKM di Kota Ambon.
            </p>

            <a href="{{ route('umkm.index') }}" class="btn">
                Jelajahi UMKM
            </a>

            <a href="{{ route('produk.index') }}" class="btn btn-outline">
                Lihat Katalog Produk
            </a>
        </section>

        <!-- Statistik UMKM dan Produk -->
        <section class="statistik">
            <div class="statistik-card">
                <h2>{{ $jumlahUmkm }}</h2>
                <p>UMKM Terdaftar</p>
            </div>

            <div class="statistik-card">
                <h2>{{ $jumlahProduk }}</h2>
                <p>Produk dan Layanan</p>
            </div>
        </section>
        
        <!-- Kategori UMKM -->
        <section class="kategori-section">
            <div class="section-heading">
                <h2>Jelajahi Kategori UMKM</h2>
                <p>
                    Temukan berbagai usaha lokal berdasarkan
                    kategori yang sesuai dengan kebutuhan Anda.
                </p>
            </div>

            <div class="kategori-grid">
                @forelse ($kategoriUmkm as $kategori)
                    @php
                        $ikonKategori = match ($kategori->nama_kategori) {
                            'Makanan & Minuman' => '🍽️',
                            'Fashion' => '👕',
                            'Kerajinan & Souvenir' => '🎨',
                            'Jasa' => '🛠️',
                            'Perdagangan' => '🛍️',
                            default => '🏪',
                        };
                    @endphp

                    <a href="{{ route('umkm.index', ['kategori' => $kategori->id]) }}"
                        class="kategori-card">
                        <div class="kategori-icon">
                            {{ $ikonKategori }}
                        </div>

                        <h3>{{ $kategori->nama_kategori }}</h3>

                        <p>
                            {{ $kategori->umkm_count }} UMKM
                        </p>
                    </a>
                @empty
                    <p>Belum ada kategori UMKM yang tersedia.</p>
                @endforelse
            </div>
        </section>
        
        <!-- UMKM Terbaru -->
        <section class="umkm-section">
            <div class="section-heading">
                <h2>Jelajahi UMKM Kota Ambon</h2>
                <p>Kenali berbagai usaha lokal yang terdaftar dalam direktori kami.</p>
            </div>

            <div class="umkm-grid">
                @forelse ($umkmTerbaru as $umkm)
                    <article class="umkm-card">

                        @php
                            $fotoUmkm = $umkm->foto_usaha ?: $umkm->logo;
                        @endphp

                        @if ($fotoUmkm)
                            <img
                                src="{{ asset('storage/' . $fotoUmkm) }}"
                                alt="{{ $umkm->nama_umkm }}"
                                loading="lazy"
                            >
                        @else
                            <div style="height:200px;background:#e2e8f0;
                                        display:flex;align-items:center;
                                        justify-content:center;color:#64748b;">
                                Foto UMKM Belum Tersedia
                            </div>
                        @endif

                        <div class="umkm-card-body">
                            <h3>{{ $umkm->nama_umkm }}</h3>

                            <p>
                                {{ $umkm->alamat ?: 'Alamat belum tersedia' }}
                            </p>

                            <a
                                href="{{ route('umkm.show', $umkm->id) }}"
                                class="umkm-detail-link"
                            >
                                Lihat Detail UMKM &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <p>Belum ada UMKM yang terdaftar.</p>
                @endforelse
            </div>

            <div class="umkm-all-link">
                <a href="{{ route('umkm.index') }}" class="btn">
                    Lihat Semua UMKM
                </a>
            </div>
        </section>
        
        <!-- Produk dan Layanan Terbaru -->
        <section class="produk-section">
            <div class="section-heading">
                <h2>Produk dan Layanan Terbaru</h2>
                <p>
                    Temukan berbagai produk dan layanan
                    dari UMKM lokal Kota Ambon.
                </p>
            </div>

            <div class="produk-grid">
                @forelse ($produkTerbaru as $produk)
                    <article class="produk-card">

                        @if ($produk->foto)
                            <img
                                src="{{ asset('storage/' . $produk->foto) }}"
                                alt="{{ $produk->nama_produk }}"
                                loading="lazy"
                            >
                        @else
                            <div class="produk-placeholder">
                                Foto Produk Belum Tersedia
                            </div>
                        @endif

                        <div class="produk-card-body">
                            <h3>{{ $produk->nama_produk }}</h3>

                            <p class="produk-umkm">
                                Oleh:
                                {{ $produk->umkm?->nama_umkm ?? 'UMKM tidak tersedia' }}
                            </p>

                            <a
                                href="{{ route('produk.show', $produk->id) }}"
                                class="produk-detail-link"
                            >
                                Lihat Detail Produk &rarr;
                            </a>
                        </div>
                    </article>
                @empty
                    <p>Belum ada produk atau layanan yang tersedia.</p>
                @endforelse
            </div>

            <div class="produk-all-link">
                <a href="{{ route('produk.index') }}" class="btn">
                    Lihat Semua Produk
                </a>
            </div>
        </section>
    
    </div>

@endsection
