@extends('layouts.public')

@section('title', 'Direktori UMKM Kota Ambon')

{{-- CSS khusus halaman Direktori UMKM --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/umkm-index.css') }}">
@endpush

@section('content')

<div class="container umkm-index-page">

    {{-- Judul halaman --}}
    <div class="umkm-page-title">
        <h2>Daftar UMKM</h2>
        <p>Jelajahi informasi pelaku usaha mikro, kecil, dan menengah.</p>
    </div>

    {{-- Formulir filter kategori dan pencarian --}}
    <form
        action="{{ route('umkm.index') }}"
        method="GET"
        class="umkm-filter-form"
    >
        <div class="row g-3 align-items-end">

            {{-- Pilihan kategori UMKM --}}
            <div class="col-12 col-md-4">
                <label for="kategori" class="form-label">
                    Kategori UMKM:
                </label>

                <select
                    name="kategori"
                    id="kategori"
                    class="form-select"
                >
                    <option value="">Semua Kategori</option>

                    @foreach ($kategoriUmkm as $kategori)
                        <option
                            value="{{ $kategori->id }}"
                            @selected(request('kategori') == $kategori->id)
                        >
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Pencarian berdasarkan nama UMKM --}}
            <div class="col-12 col-md-4">
                <label for="cari" class="form-label">
                    Cari UMKM:
                </label>

                <input
                    type="search"
                    name="cari"
                    id="cari"
                    class="form-control"
                    value="{{ request('cari') }}"
                    placeholder="Cari nama UMKM..."
                    aria-label="Cari nama UMKM"
                >
            </div>

            {{-- Tombol filter dan reset --}}
            <div class="col-12 col-md-4">
                <div class="d-flex flex-wrap gap-2">
                    <button
                        type="submit"
                        class="btn umkm-btn-primary"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('umkm.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Filter
                    </a>
                </div>
            </div>

        </div>
    </form>

    {{-- Jumlah hasil pencarian --}}
    <p
        id="jumlah-hasil"
        class="umkm-jumlah-hasil"
        aria-live="polite"
    >
        Menampilkan {{ $umkm->count() }} UMKM
    </p>

    {{-- Daftar kartu UMKM --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        @forelse ($umkm as $item)

            <div
                class="col umkm-list-item"
                data-nama="{{ $item->nama_umkm }}"
            >
                <article class="card umkm-list-card h-100">

                    <div class="card-body d-flex flex-column">

                        {{-- Nama UMKM --}}
                        <h3 class="umkm-list-name">
                            {{ $item->nama_umkm }}
                        </h3>

                        {{-- Alamat UMKM --}}
                        <p class="umkm-list-info">
                            <strong>Alamat:</strong>
                            {{ $item->alamat ?? '-' }}
                        </p>

                        {{-- Nomor telepon UMKM --}}
                        <p class="umkm-list-info">
                            <strong>Telepon:</strong>
                            {{ $item->telepon ?? '-' }}
                        </p>

                        {{-- Tautan halaman detail --}}
                        <div class="mt-auto pt-2">
                            <a
                                href="{{ route('umkm.show', $item->id) }}"
                                class="btn umkm-btn-primary"
                            >
                                Lihat Detail
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </div>

                </article>
            </div>

        @empty

            {{-- Pesan jika database belum memiliki data UMKM --}}
            <div class="col-12">
                <div class="umkm-empty-message">
                    Belum ada data UMKM yang tersedia.
                </div>
            </div>

        @endforelse

    </div>

    {{-- Pesan jika pencarian langsung tidak menemukan UMKM --}}
    <div
        id="pencarian-kosong"
        class="umkm-pencarian-kosong"
        role="status"
        hidden
    >
        <h3>UMKM Tidak Ditemukan</h3>

        <p>
            Tidak ada UMKM yang sesuai dengan kata kunci pencarian.
            Silakan coba nama usaha lainnya.
        </p>
    </div>

</div>

@endsection

{{-- JavaScript pencarian langsung pada kartu UMKM --}}
@push('scripts')
    <script src="{{ asset('js/umkm-index.js') }}" defer></script>
@endpush