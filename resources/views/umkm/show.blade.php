@extends('layouts.public')

@section('title', 'Detail UMKM - ' . $umkm->nama_umkm)

@section('content')

    @push('styles')
    <style>
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            color: #64748b;
        }

        .breadcrumb a {
            color: #0f766e;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .detail-title {
            margin: 0 0 24px;
            font-size: 28px;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        @media (max-width: 600px) {
            .detail-title {
                font-size: 23px;
            }
        }
    
        .ulasan-section {
            margin-top: 36px;
            padding: 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .ulasan-section h2 {
            margin: 0 0 18px;
            font-size: 22px;
            color: #0f172a;
        }

        .ulasan-ringkasan {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 24px;
            color: #475569;
        }

        .ulasan-ringkasan strong {
            color: #0f172a;
            font-size: 20px;
        }

        .ulasan-item {
            padding: 18px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .ulasan-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #334155;
        }

        .ulasan-header small {
            color: #64748b;
        }

        .ulasan-bintang {
            margin: 8px 0;
            font-size: 20px;
        }

        .ulasan-item p {
            margin: 8px 0 0;
            color: #475569;
            overflow-wrap: anywhere;
        }

        .ulasan-kosong {
            color: #64748b;
        }

        .ulasan-pagination {
            margin-top: 20px;
        }

        .ulasan-aksi {
            margin-top: 24px;
        }

        .ulasan-button {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .ulasan-button:hover {
            background: #115e59;
            color: white;
        }

        @media (max-width: 600px) {
            .ulasan-section {
                padding: 18px;
            }
        }

        
        /* Formulir rating dan ulasan */

        .form-ulasan {
            max-width: 650px;
        }

        .ulasan-aksi h3 {
            margin: 0 0 20px;
            font-size: 19px;
            color: #0f172a;
        }

        .ulasan-label {
            display: block;
            margin: 16px 0 10px;
            font-weight: 600;
            color: #334155;
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
            margin-bottom: 18px;
        }

        .rating-input input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
        }

        .rating-input label {
            font-size: 36px;
            line-height: 1;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #eab308;
        }

        .rating-input input:focus-visible + label {
            outline: 2px solid #2563eb;
            outline-offset: 3px;
            border-radius: 4px;
        }

        .form-ulasan textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            resize: vertical;
        }

        .form-ulasan textarea:focus {
            outline: 2px solid #0f766e;
            outline-offset: 1px;
        }

        .form-ulasan button.ulasan-button {
            border: none;
            cursor: pointer;
            font-size: 15px;
            margin-top: 16px;
        }

        .ulasan-catatan {
            margin-top: 12px;
            color: #64748b;
            font-size: 13px;
        }

        .ulasan-sukses,
        .ulasan-error {
            padding: 12px 16px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-size: 14px;
        }

        .ulasan-sukses {
            background: #dcfce7;
            color: #166534;
        }

        .ulasan-error {
            background: #fee2e2;
            color: #991b1b;
        }

    </style>
    @endpush

    <div class="umkm-detail-page">

        <nav class="breadcrumb" aria-label="Navigasi halaman">
            <a href="{{ route('home') }}">Beranda</a>
            <span> / </span>
            <a href="{{ route('umkm.index') }}">Direktori UMKM</a>
            <span> / </span>
            <span>{{ $umkm->nama_umkm }}</span>
        </nav>

        <h1 class="detail-title">
            {{ $umkm->nama_umkm }}
        </h1>

        {{-- Menampilkan komponen detail UMKM pada halaman publik --}}
        {{-- isAdminView = false agar tautan detail produk mengarah ke halaman publik --}}
        
        @include('umkm.partials.detail-content', ['isAdminView' => false])

        {{-- Ulasan pengunjung --}}
        <section class="ulasan-section">

            <h2>Ulasan Pengunjung</h2>

            <div class="ulasan-ringkasan">
                @if ($jumlahUlasan > 0)
                    <strong>⭐ {{ number_format($rataRating, 1, ',', '.') }} / 5</strong>
                    <span>({{ $jumlahUlasan }} ulasan)</span>
                @else
                    <p>Belum ada penilaian untuk UMKM ini.</p>
                @endif
            </div>

            <div class="ulasan-list">
                @forelse ($ulasan as $item)
                    <article class="ulasan-item">
                        <div class="ulasan-header">
                            <strong>{{ $item->user?->name ?? 'Pengunjung' }}</strong>

                            <small>
                                {{ $item->created_at->format('d/m/Y') }}
                            </small>
                        </div>

                        <div class="ulasan-bintang">
                            @for ($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= $item->rating ? '#eab308' : '#cbd5e1' }}">
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

            
            {{-- Bagian aksi ulasan UMKM --}}
            <div class="ulasan-aksi">

                {{-- Pengunjung belum login --}}
                @guest
                    <a href="{{ route('login', ['redirect' => route('umkm.show', $umkm->id)]) }}"
                    class="ulasan-button">
                        Login untuk Menulis Ulasan
                    </a>

                @else

                    {{-- Hanya akun pengunjung yang dapat memberikan ulasan --}}
                    @if (auth()->user()->role === 'pengunjung')

                        {{-- Pengunjung belum memverifikasi email --}}
                        @if (!auth()->user()->hasVerifiedEmail())

                            <div class="ulasan-verifikasi">
                                <h3>
                                    <i class="bi bi-envelope-exclamation"></i>
                                    Verifikasi Email Diperlukan
                                </h3>

                                <p>
                                    Untuk memberikan rating dan ulasan UMKM,
                                    silakan verifikasi alamat email Anda terlebih dahulu.
                                </p>

                                <a href="{{ route('pengunjung.verification.notice') }}"
                                class="ulasan-button">
                                    <i class="bi bi-envelope-check"></i>
                                    Verifikasi Email
                                </a>
                            </div>

                        @else

                            {{-- Formulir untuk pengunjung dengan email terverifikasi --}}
                            <h3>Tulis Ulasan Anda</h3>

                            <form method="POST"
                                action="{{ route('ulasan.store') }}"
                                class="form-ulasan">
                                @csrf

                                {{-- ID UMKM yang akan diulas --}}
                                <input type="hidden"
                                    name="umkm_id"
                                    value="{{ $umkm->id }}">

                                {{-- Rating UMKM --}}
                                <label class="ulasan-label">
                                    Berikan Rating
                                </label>

                                <div class="rating-input">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio"
                                            name="rating"
                                            id="rating-{{ $i }}"
                                            value="{{ $i }}"
                                            {{ old('rating') == $i ? 'checked' : '' }}
                                            required>

                                        <label for="rating-{{ $i }}"
                                            title="{{ $i }} bintang">★</label>
                                    @endfor
                                </div>

                                {{-- Komentar UMKM --}}
                                <label for="komentar" class="ulasan-label">
                                    Tulis Ulasan
                                </label>

                                <textarea id="komentar"
                                        name="komentar"
                                        rows="4"
                                        maxlength="2000"
                                        placeholder="Ceritakan pengalaman Anda dengan UMKM ini..."
                                        required>{{ old('komentar') }}</textarea>

                                {{-- Tombol pengiriman ulasan --}}
                                <button type="submit" class="ulasan-button">
                                    Kirim Ulasan
                                </button>

                                <p class="ulasan-catatan">
                                    Ulasan akan ditampilkan setelah disetujui admin.
                                </p>

                            </form>

                        @endif

                    @else

                        {{-- Admin tidak dapat memberikan ulasan --}}
                        <p class="ulasan-catatan">
                            Formulir ulasan hanya tersedia untuk akun pengunjung.
                        </p>

                    @endif

                @endguest

            </div>

        </section>

    </div>

@endsection



