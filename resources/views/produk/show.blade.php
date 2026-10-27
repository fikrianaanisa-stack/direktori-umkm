@extends('layouts.public')

@section('title', $produk->nama_produk . ' - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk-show.css') }}">
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <nav class="produk-breadcrumb" aria-label="Navigasi halaman">
        <a href="{{ route('home') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('produk.index') }}">Katalog Produk</a>
        <span>/</span>
        <span aria-current="page">{{ $produk->nama_produk }}</span>
    </nav>

    {{-- Detail produk --}}
    @include('produk.partials.detail-content', ['isAdminView' => false])

    {{-- Ulasan produk --}}
    <section class="ulasan-section">

        <h2>Ulasan Produk</h2>

        <div class="ulasan-ringkasan">
            @if ($jumlahUlasan > 0)
                <strong>
                    ⭐ {{ number_format($rataRating, 1, ',', '.') }} / 5
                </strong>
                <span>({{ $jumlahUlasan }} ulasan)</span>
            @else
                <p>Belum ada penilaian untuk produk ini.</p>
            @endif
        </div>

        <div class="ulasan-list">
            @forelse ($ulasan as $item)
                <article class="ulasan-item">

                    <div class="ulasan-header">
                        <strong>{{ $item->user?->name ?? 'Pengunjung' }}</strong>
                        <small>{{ $item->created_at->format('d/m/Y') }}</small>
                    </div>

                    <div class="ulasan-bintang" aria-label="Rating {{ $item->rating }} dari 5 bintang">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $item->rating ? 'bintang-aktif' : 'bintang-nonaktif' }}">
                                ★
                            </span>
                        @endfor
                    </div>

                    <p>{{ $item->komentar }}</p>

                </article>
            @empty
                <p class="ulasan-kosong">
                    Belum ada ulasan. Jadilah yang pertama memberikan ulasan.
                </p>
            @endforelse
        </div>

        @if ($ulasan->hasPages())
            <div class="ulasan-pagination">
                {{ $ulasan->links() }}
            </div>
        @endif

        {{-- Aksi ulasan --}}
        <div class="ulasan-aksi">

            @guest
                <a
                    href="{{ route('login', ['redirect' => route('produk.show', $produk->id)]) }}"
                    class="btn ulasan-button"
                >
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login untuk Menulis Ulasan
                </a>
            @else

                @if (auth()->user()->role === 'pengunjung')

                    @if (!auth()->user()->hasVerifiedEmail())

                        <div class="ulasan-verifikasi">
                            <h3>
                                <i class="bi bi-envelope-exclamation me-1"></i>
                                Verifikasi Email Diperlukan
                            </h3>

                            <p>
                                Untuk memberikan rating dan ulasan produk,
                                silakan verifikasi alamat email Anda terlebih dahulu.
                            </p>

                            <a
                                href="{{ route('pengunjung.verification.notice') }}"
                                class="btn ulasan-button"
                            >
                                <i class="bi bi-envelope-check me-1"></i>
                                Verifikasi Email
                            </a>
                        </div>

                    @else

                        <h3>Tulis Ulasan Anda</h3>

                        <form
                            method="POST"
                            action="{{ route('ulasan.store') }}"
                            class="form-ulasan js-konfirmasi-ulasan"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="produk_id"
                                value="{{ $produk->id }}"
                            >

                            {{-- Rating produk --}}
                            <label class="ulasan-label">
                                Berikan Rating
                            </label>

                            <div class="rating-input">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input
                                        type="radio"
                                        name="rating"
                                        id="rating-produk-{{ $i }}"
                                        value="{{ $i }}"
                                        @checked(old('rating') == $i)
                                        required
                                    >

                                    <label
                                        for="rating-produk-{{ $i }}"
                                        title="{{ $i }} bintang"
                                    >★</label>
                                @endfor
                            </div>

                            {{-- Komentar --}}
                            <label
                                for="komentar-produk"
                                class="ulasan-label"
                            >
                                Tulis Ulasan
                            </label>

                            <textarea
                                id="komentar-produk"
                                name="komentar"
                                rows="4"
                                maxlength="2000"
                                placeholder="Ceritakan pengalaman Anda dengan produk ini..."
                                required
                            >{{ old('komentar') }}</textarea>

                            <button type="submit" class="btn ulasan-button">
                                <i class="bi bi-send me-1"></i>
                                Kirim Ulasan
                            </button>

                            <p class="ulasan-catatan">
                                Ulasan akan ditampilkan setelah disetujui admin.
                            </p>
                        </form>

                    @endif

                @else

                    <p class="ulasan-catatan">
                        Formulir ulasan hanya tersedia untuk akun pengunjung.
                    </p>

                @endif

            @endguest

        </div>

    </section>

@endsection