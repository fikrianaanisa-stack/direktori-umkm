@extends('layouts.public')

@section('title', '404 - Halaman Tidak Ditemukan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/error-pages.css') }}">
@endpush

@section('content')
    <section class="error-page container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="error-page-card text-center">

                    <div class="error-page-icon mb-3">
                        <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                    </div>

                    <h1 class="error-page-code mb-2">404</h1>

                    <h2 class="error-page-title mb-3">
                        Halaman Tidak Ditemukan
                    </h2>

                    <p class="error-page-description mb-4">
                        Maaf, halaman yang Anda cari tidak ditemukan
                        atau sudah tidak tersedia.
                        Silakan kembali ke beranda atau jelajahi
                        daftar UMKM yang tersedia.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-house-door me-2"></i>
                            Kembali ke Beranda
                        </a>

                        <a href="{{ url('/umkm') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="bi bi-shop me-2"></i>
                            Lihat Daftar UMKM
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection