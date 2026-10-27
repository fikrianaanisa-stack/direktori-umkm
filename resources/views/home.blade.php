@extends('layouts.public')

@section('title', 'Beranda - Direktori UMKM Kota Ambon')

{{-- CSS khusus halaman Beranda --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<div class="home-content">

    {{-- Banner utama Beranda --}}
    <section class="home-hero" aria-label="Promosi UMKM Kota Ambon">
        <img
            src="{{ asset('images/banner-hero-umkm-ambon.png') }}"
            alt="Temukan UMKM dan produk lokal Kota Ambon serta informasi bagi pelaku UMKM yang ingin bergabung"
            class="home-hero-image img-fluid"
        >
    </section>

    {{-- Informasi pendaftaran UMKM --}}
    <div class="home-daftar-umkm text-center">
        <p>
            Ayo bergabung dan tampilkan usaha, produk, serta layanan Anda
            di Direktori UMKM Kota Ambon.
            <a href="{{ route('kontak') }}" class="home-daftar-link">
                Hubungi kami.
            </a>
        </p>
    </div>

    {{-- Pencarian dan navigasi Beranda --}}
    <section class="home-explore text-center">

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
                    class="form-control home-search-input"
                    placeholder="Cari nama UMKM atau produk lokal..."
                    aria-label="Cari UMKM atau produk"
                    aria-controls="home-search-results"
                    aria-expanded="false"
                    autocomplete="off"
                    maxlength="100"
                    data-search-url="{{ route('search.index') }}"
                >

            </div>

            {{-- Hasil pencarian langsung --}}
            <div
                id="home-search-results"
                class="home-search-results"
                role="status"
                aria-live="polite"
                hidden
            ></div>

        </div>

        {{-- Tombol navigasi Beranda --}}
        <div class="d-flex justify-content-center flex-wrap gap-3">

            <a href="{{ route('umkm.index') }}" class="btn home-btn-primary">
                Jelajahi UMKM
            </a>

            <a href="{{ route('produk.index') }}" class="btn home-btn-outline">
                Lihat Katalog Produk
            </a>

        </div>

    </section>

    {{-- Statistik UMKM dan produk --}}
    <section class="statistik">

        <div class="row g-4 justify-content-center">

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="statistik-card h-100">
                    <h2>{{ $jumlahUmkm }}</h2>
                    <p>UMKM Terdaftar</p>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="statistik-card h-100">
                    <h2>{{ $jumlahProduk }}</h2>
                    <p>Produk dan Layanan</p>
                </div>
            </div>

        </div>

    </section>

    {{-- Kategori UMKM --}}
    <section class="kategori-section">

        <div class="section-heading">
            <h2>Jelajahi Kategori UMKM</h2>
            <p>
                Temukan berbagai usaha lokal berdasarkan kategori
                yang sesuai dengan kebutuhan Anda.
            </p>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">

            @forelse ($kategoriUmkm as $kategori)

                @php
                    // Menentukan ikon berdasarkan kategori UMKM
                    $ikonKategori = match ($kategori->nama_kategori) {
                        'Makanan & Minuman' => '🍽️',
                        'Fashion' => '👕',
                        'Kerajinan & Souvenir' => '🎨',
                        'Jasa' => '🛠️',
                        'Perdagangan' => '🛍️',
                        default => '🏪',
                    };
                @endphp

                <div class="col">

                    <a
                        href="{{ route('umkm.index', ['kategori' => $kategori->id]) }}"
                        class="kategori-card d-block h-100"
                    >

                        <div class="kategori-icon">
                            {{ $ikonKategori }}
                        </div>

                        <h3>{{ $kategori->nama_kategori }}</h3>

                        <p>{{ $kategori->umkm_count }} UMKM</p>

                    </a>

                </div>

            @empty

                <div class="col-12">
                    <p class="text-center text-muted">
                        Belum ada kategori UMKM yang tersedia.
                    </p>
                </div>

            @endforelse

        </div>

    </section>

    {{-- Daftar UMKM terbaru --}}
    <section class="umkm-section">

        <div class="section-heading">
            <h2>Jelajahi UMKM Kota Ambon</h2>
            <p>
                Kenali berbagai usaha lokal yang terdaftar
                dalam direktori kami.
            </p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            @forelse ($umkmTerbaru as $umkm)

                @php
                    // Menggunakan foto usaha atau logo sebagai gambar utama
                    $fotoUmkm = $umkm->foto_usaha ?: $umkm->logo;
                @endphp

                <div class="col">

                    <article class="card umkm-card h-100">

                        @if ($fotoUmkm)

                            <img
                                src="{{ asset('storage/' . $fotoUmkm) }}"
                                alt="{{ $umkm->nama_umkm }}"
                                class="card-img-top umkm-card-image"
                                loading="lazy"
                            >

                        @else

                            <div class="umkm-placeholder">
                                Foto UMKM Belum Tersedia
                            </div>

                        @endif

                        <div class="card-body umkm-card-body d-flex flex-column">

                            <h3>{{ $umkm->nama_umkm }}</h3>

                            <p>
                                {{ $umkm->alamat ?: 'Alamat belum tersedia' }}
                            </p>

                            <a
                                href="{{ route('umkm.show', $umkm->id) }}"
                                class="umkm-detail-link mt-auto"
                            >
                                Lihat Detail UMKM &rarr;
                            </a>

                        </div>

                    </article>

                </div>

            @empty

                <div class="col-12">
                    <p class="text-center text-muted">
                        Belum ada UMKM yang terdaftar.
                    </p>
                </div>

            @endforelse

        </div>

        <div class="umkm-all-link">

            <a href="{{ route('umkm.index') }}" class="btn home-btn-primary">
                Lihat Semua UMKM
            </a>

        </div>

    </section>

    {{-- Produk dan layanan terbaru --}}
    <section class="produk-section">

        <div class="section-heading">
            <h2>Produk dan Layanan Terbaru</h2>
            <p>
                Temukan berbagai produk dan layanan
                dari UMKM lokal Kota Ambon.
            </p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            @forelse ($produkTerbaru as $produk)

                @php
                    // Mengambil foto pertama sebagai gambar utama produk
                    $fotoUtama = $produk->fotoProduk->first();
                @endphp

                <div class="col">

                    <article class="card produk-card h-100">

                        @if ($fotoUtama)

                            <img
                                src="{{ asset('storage/' . $fotoUtama->foto) }}"
                                alt="{{ $produk->nama_produk }}"
                                class="card-img-top produk-card-image"
                                loading="lazy"
                            >

                        @else

                            <div class="produk-placeholder">
                                Foto Produk Belum Tersedia
                            </div>

                        @endif

                        <div class="card-body produk-card-body d-flex flex-column">

                            <h3>{{ $produk->nama_produk }}</h3>

                            <p class="produk-umkm">
                                Oleh:
                                {{ $produk->umkm?->nama_umkm ?? 'UMKM tidak tersedia' }}
                            </p>

                            <a
                                href="{{ route('produk.show', $produk->id) }}"
                                class="produk-detail-link mt-auto"
                            >
                                Lihat Detail Produk &rarr;
                            </a>

                        </div>

                    </article>

                </div>

            @empty

                <div class="col-12">
                    <p class="text-center text-muted">
                        Belum ada produk atau layanan yang tersedia.
                    </p>
                </div>

            @endforelse

        </div>

        <div class="produk-all-link">

            <a href="{{ route('produk.index') }}" class="btn home-btn-primary">
                Lihat Semua Produk
            </a>

        </div>

    </section>

</div>

@endsection

{{-- JavaScript khusus Live Search --}}
@push('scripts')
    <script src="{{ asset('js/home-search.js') }}" defer></script>
@endpush