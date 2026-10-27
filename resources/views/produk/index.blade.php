@extends('layouts.public')

@section('title', 'Katalog Produk UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk-index.css') }}">
@endpush

@section('content')

<main class="container produk-index-page">

    {{-- Judul halaman --}}
    <div class="produk-page-header">
        <h1>Katalog Produk UMKM</h1>
        <p>Jelajahi produk yang tersedia dari berbagai UMKM.</p>
    </div>

    {{-- Filter kategori dan pencarian produk --}}
    <form
        action="{{ route('produk.index') }}"
        method="GET"
        class="produk-filter"
    >
        <div class="row g-3 align-items-end">

            <div class="col-12 col-md-6 col-lg-4">
                <label for="kategori" class="form-label">
                    Kategori Produk
                </label>

                <select
                    name="kategori"
                    id="kategori"
                    class="form-select"
                >
                    <option value="">Semua Kategori</option>

                    @foreach ($kategoriProduk as $kategori)
                        <option
                            value="{{ $kategori->id }}"
                            @selected(request('kategori') == $kategori->id)
                        >
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <label for="cari" class="form-label">
                    Cari Produk
                </label>

                <input
                    type="search"
                    name="cari"
                    id="cari"
                    class="form-control"
                    value="{{ request('cari') }}"
                    placeholder="Masukkan nama produk..."
                >
            </div>

            <div class="col-12 col-lg-4">
                <div class="d-flex flex-wrap gap-2">
                    <button
                        type="submit"
                        class="btn produk-filter-button"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('produk.index') }}"
                        class="btn produk-reset-button"
                    >
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset Filter
                    </a>
                </div>
            </div>

        </div>
    </form>

    {{-- Informasi jumlah produk --}}
    <p
        id="jumlah-hasil"
        class="produk-jumlah-hasil"
        aria-live="polite"
    >
        Menampilkan {{ $produk->count() }} produk
    </p>

    {{-- Daftar produk --}}
    <div id="produk-grid" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

        @foreach ($produk as $item)

            @php
                $fotoUtama = $item->fotoProduk->first();

                $pilihanHarga = $item->hargaProduk;
                $jumlahPilihan = $pilihanHarga->count();
                $hargaTerendah = $pilihanHarga->min('harga_min');
            @endphp

            <div
                class="col produk-list-item"
                data-nama="{{ Str::lower($item->nama_produk) }}"
            >
                <article class="card produk-card h-100">

                    {{-- Foto produk --}}
                    @if ($fotoUtama)
                        <img
                            src="{{ asset('storage/' . $fotoUtama->foto) }}"
                            alt="{{ $item->nama_produk }}"
                            class="card-img-top produk-image"
                        >
                    @else
                        <div class="produk-image produk-no-image">
                            <i class="bi bi-image"></i>
                            <span>Foto belum tersedia</span>
                        </div>
                    @endif

                    <div class="card-body produk-card-body">

                        {{-- Kategori produk --}}
                        <p class="produk-category">
                            {{ $item->kategoriProduk?->nama_kategori ?? 'Tanpa Kategori' }}
                        </p>

                        {{-- Nama produk --}}
                        <h2 class="produk-name">
                            {{ $item->nama_produk }}
                        </h2>

                        {{-- Harga produk --}}
                        <div class="produk-price">

                            @if ($jumlahPilihan === 0 || $hargaTerendah === null)

                                <span class="produk-price-empty">
                                    Hubungi UMKM untuk informasi harga
                                </span>

                            @elseif ($jumlahPilihan === 1)

                                @php
                                    $pilihan = $pilihanHarga->first();
                                @endphp

                                Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                                @if (
                                    $pilihan->harga_max !== null &&
                                    $pilihan->harga_max > $pilihan->harga_min
                                )
                                    – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                                @endif

                                @if ($pilihan->satuan)
                                    <span class="produk-price-unit">
                                        / {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                        {{ $pilihan->satuan }}
                                    </span>
                                @endif

                            @else

                                Mulai Rp {{ number_format($hargaTerendah, 0, ',', '.') }}

                                <span class="produk-price-options">
                                    {{ $jumlahPilihan }} pilihan harga tersedia
                                </span>

                            @endif

                        </div>

                        {{-- Nama UMKM penyedia --}}
                        <p class="produk-business-name">
                            <i class="bi bi-shop me-1"></i>
                            UMKM: {{ $item->umkm?->nama_umkm ?? '-' }}
                        </p>

                        {{-- Tombol navigasi --}}
                        <div class="produk-card-actions">

                            <a
                                href="{{ route('produk.show', $item->id) }}"
                                class="btn produk-detail-button"
                            >
                                <i class="bi bi-eye me-1"></i>
                                Detail Produk
                            </a>

                            @if ($item->umkm)
                                <a
                                    href="{{ route('umkm.show', $item->umkm->id) }}"
                                    class="btn produk-umkm-button"
                                >
                                    <i class="bi bi-shop me-1"></i>
                                    Lihat UMKM
                                </a>
                            @endif

                        </div>

                    </div>

                </article>
            </div>

        @endforeach

    </div>

    {{-- Pesan jika belum ada produk --}}
    @if ($produk->isEmpty())
        <div class="produk-empty-message">
            <i class="bi bi-box-seam"></i>
            <p>Belum ada produk yang tersedia.</p>
        </div>
    @endif

    {{-- Pesan jika pencarian langsung tidak menemukan produk --}}
    <div
        id="pencarian-kosong"
        class="produk-pencarian-kosong"
        role="status"
        hidden
    >
        <i class="bi bi-search"></i>
        <h3>Produk Tidak Ditemukan</h3>
        <p>Tidak ada produk yang sesuai dengan kata kunci pencarian.</p>
    </div>

</main>

@endsection

@push('scripts')
    <script src="{{ asset('js/produk-index.js') }}" defer></script>
@endpush