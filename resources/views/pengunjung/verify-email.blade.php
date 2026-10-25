@extends('layouts.public')

@section('title', 'Verifikasi Email - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengunjung-verify-email.css') }}">
@endpush

@section('content')

<div class="verify-email-container">

    <div class="verify-email-card">

        <div class="verify-email-icon">
            <i class="bi bi-envelope-check"></i>
        </div>

        <h1 class="verify-email-title">
            Verifikasi Email
        </h1>

        <p class="verify-email-description">
            Kami telah mengirimkan tautan verifikasi ke alamat email:
        </p>

        <p class="verify-email-address">
            {{ auth()->user()->email }}
        </p>

        <p class="verify-email-description">
            Silakan periksa email Anda dan klik tautan verifikasi
            untuk mengaktifkan fitur ulasan dan rating.
        </p>

        <div class="verify-email-actions">

            {{-- Kirim ulang email verifikasi --}}
            <form method="POST"
                  action="{{ route('pengunjung.verification.send') }}">
                @csrf

                <button type="submit"
                        class="verify-email-btn verify-email-btn-primary">
                    <i class="bi bi-arrow-repeat"></i>
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Kembali ke beranda --}}
            <a href="{{ route('home') }}"
               class="verify-email-btn verify-email-btn-secondary">
                <i class="bi bi-house"></i>
                Kembali ke Beranda
            </a>

        </div>

    </div>

</div>

@endsection