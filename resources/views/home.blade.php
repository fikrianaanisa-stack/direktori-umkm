@extends('layouts.public')

@section('title', 'Beranda - Direktori UMKM Kota Ambon')

{{-- CSS khusus Beranda Publik --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

    <div class="home-content">
        {{-- Hero Banner UMKM Kota Ambon --}}
        <section class="home-hero" aria-label="Promosi UMKM Kota Ambon">

            <img
                src="{{ asset('images/banner-hero-umkm-ambon.png') }}"
                alt="Temukan UMKM dan produk lokal Kota Ambon serta informasi bagi pelaku UMKM yang ingin bergabung"
                class="home-hero-image"
            >

        </section>

        {{-- Informasi pendaftaran UMKM --}}
        <div class="home-daftar-umkm">
            <p>
                Ayo bergabung dan promosikan UMKM serta produk Anda.
                <a href="{{ route('kontak') }}" class="home-daftar-link">
                    Hubungi kami.
                </a>
            </p>
        </div>

        {{-- Pencarian dan Navigasi Beranda --}}
        <section class="home-explore">

            {{-- Live Search UMKM dan produk --}}
            <div class="home-search-wrapper">

                <div class="home-search-form">

                    <span class="home-search-icon" aria-hidden="true">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="search"
                        id="home-live-search"
                        name="cari"
                        placeholder="Cari nama UMKM atau produk lokal..."
                        aria-label="Cari UMKM atau produk"
                        aria-controls="home-search-results"
                        aria-expanded="false"
                        autocomplete="off"
                        maxlength="100"
                        data-search-url="{{ route('search.index') }}"
                    >

                </div>

                {{-- Dropdown hasil Live Search --}}
                <div
                    id="home-search-results"
                    class="home-search-results"
                    role="status"
                    aria-live="polite"
                    hidden
                ></div>

            </div>

            <div class="home-explore-actions">

                <a href="{{ route('umkm.index') }}" class="btn">
                    Jelajahi UMKM
                </a>

                <a href="{{ route('produk.index') }}" class="btn btn-outline">
                    Lihat Katalog Produk
                </a>

            </div>

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

                        @php
                            $fotoUtama = $produk->fotoProduk->first();
                        @endphp

                        @if ($fotoUtama)
                            <img
                                src="{{ asset('storage/' . $fotoUtama->foto) }}"
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

@push('scripts')
    <script src="{{ asset('js/home-search.js') }}" defer></script>
@endpush
