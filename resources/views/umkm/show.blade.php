@extends('layouts.public')

@section('title', 'Detail UMKM - ' . $umkm->nama_umkm)

{{-- CSS khusus halaman Detail UMKM --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/umkm-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/umkm-show.css') }}">
@endpush

@section('content')

<div class="container umkm-show-page">

    {{-- Navigasi halaman --}}
    <nav aria-label="Navigasi halaman">
        <ol class="breadcrumb umkm-show-breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Beranda</a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('umkm.index') }}">Direktori UMKM</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                {{ $umkm->nama_umkm }}
            </li>
        </ol>
    </nav>

    {{-- Judul halaman --}}
    <h1 class="umkm-show-title">
        {{ $umkm->nama_umkm }}
    </h1>

    {{-- Komponen detail UMKM --}}
    @include('umkm.partials.detail-content', ['isAdminView' => false])

    {{-- Ulasan pengunjung --}}
    <section class="umkm-ulasan-section">

        <h2 class="umkm-ulasan-title">
            Ulasan Pengunjung
        </h2>

        {{-- Ringkasan rating --}}
        <div class="umkm-ulasan-ringkasan">

            @if ($jumlahUlasan > 0)
                <strong>
                    <i class="bi bi-star-fill umkm-ulasan-star-active"></i>
                    {{ number_format($rataRating, 1, ',', '.') }} / 5
                </strong>

                <span>({{ $jumlahUlasan }} ulasan)</span>
            @else
                <p class="mb-0">
                    Belum ada penilaian untuk UMKM ini.
                </p>
            @endif

        </div>

        {{-- Daftar ulasan --}}
        <div class="umkm-ulasan-list">

            @forelse ($ulasan as $item)

                <article class="umkm-ulasan-item">

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <strong>
                            {{ $item->user?->name ?? 'Pengunjung' }}
                        </strong>

                        <small class="text-secondary">
                            {{ $item->created_at->format('d/m/Y') }}
                        </small>
                    </div>

                    {{-- Rating bintang --}}
                    <div
                        class="umkm-ulasan-bintang"
                        aria-label="Rating {{ $item->rating }} dari 5 bintang"
                    >
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                class="{{ $i <= $item->rating
                                    ? 'umkm-ulasan-star-active'
                                    : 'umkm-ulasan-star-inactive' }}"
                                aria-hidden="true"
                            >
                                ★
                            </span>
                        @endfor
                    </div>

                    <p class="umkm-ulasan-komentar">
                        {{ $item->komentar }}
                    </p>

                </article>

            @empty

                <p class="umkm-ulasan-kosong">
                    Belum ada ulasan. Jadilah yang pertama memberikan ulasan.
                </p>

            @endforelse

        </div>

        {{-- Pagination ulasan --}}
        @if ($ulasan->hasPages())
            <div class="umkm-ulasan-pagination">
                {{ $ulasan->links() }}
            </div>
        @endif

        {{-- Aksi dan formulir ulasan --}}
        <div class="umkm-ulasan-aksi">

            @guest

                {{-- Pengunjung harus login sebelum menulis ulasan --}}
                <a
                    href="{{ route('login', ['redirect' => route('umkm.show', $umkm->id)]) }}"
                    class="btn umkm-ulasan-button"
                >
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login untuk Menulis Ulasan
                </a>

            @else

                @if (auth()->user()->role === 'pengunjung')

                    @if (!auth()->user()->hasVerifiedEmail())

                        {{-- Pengunjung harus memverifikasi email --}}
                        <div class="alert alert-warning umkm-ulasan-verifikasi">

                            <h3>
                                <i class="bi bi-envelope-exclamation me-1"></i>
                                Verifikasi Email Diperlukan
                            </h3>

                            <p>
                                Untuk memberikan rating dan ulasan UMKM,
                                silakan verifikasi alamat email Anda terlebih dahulu.
                            </p>

                            <a
                                href="{{ route('pengunjung.verification.notice') }}"
                                class="btn umkm-ulasan-button"
                            >
                                <i class="bi bi-envelope-check me-1"></i>
                                Verifikasi Email
                            </a>

                        </div>

                    @else

                        {{-- Formulir ulasan untuk pengunjung terverifikasi --}}
                        <h3 class="umkm-ulasan-form-title">
                            Tulis Ulasan Anda
                        </h3>

                        <form
                            method="POST"
                            action="{{ route('ulasan.store') }}"
                            class="umkm-form-ulasan js-konfirmasi-ulasan"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="umkm_id"
                                value="{{ $umkm->id }}"
                            >

                            {{-- Pilihan rating --}}
                            <fieldset class="umkm-rating-fieldset">
                                <legend class="umkm-ulasan-label">
                                    Berikan Rating
                                </legend>

                                <div class="umkm-rating-input">

                                    @for ($i = 5; $i >= 1; $i--)

                                        <input
                                            type="radio"
                                            name="rating"
                                            id="umkm-rating-{{ $i }}"
                                            value="{{ $i }}"
                                            @checked(old('rating') == $i)
                                            required
                                        >

                                        <label
                                            for="umkm-rating-{{ $i }}"
                                            title="{{ $i }} bintang"
                                            aria-label="{{ $i }} bintang"
                                        >
                                            ★
                                        </label>

                                    @endfor

                                </div>
                            </fieldset>

                            {{-- Komentar pengunjung --}}
                            <div class="mb-3">
                                <label
                                    for="komentar"
                                    class="form-label umkm-ulasan-label"
                                >
                                    Tulis Ulasan
                                </label>

                                <textarea
                                    id="komentar"
                                    name="komentar"
                                    class="form-control umkm-ulasan-textarea"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Ceritakan pengalaman Anda dengan UMKM ini..."
                                    required
                                >{{ old('komentar') }}</textarea>
                            </div>

                            {{-- Tombol kirim ulasan --}}
                            <button
                                type="submit"
                                class="btn umkm-ulasan-button"
                            >
                                <i class="bi bi-send me-1"></i>
                                Kirim Ulasan
                            </button>

                            <p class="umkm-ulasan-catatan">
                                Ulasan akan ditampilkan setelah disetujui admin.
                            </p>

                        </form>

                    @endif

                @else

                    {{-- Admin tidak dapat memberikan ulasan --}}
                    <p class="umkm-ulasan-catatan">
                        Formulir ulasan hanya tersedia untuk akun pengunjung.
                    </p>

                @endif

            @endguest

        </div>

    </section>

</div>

@endsection