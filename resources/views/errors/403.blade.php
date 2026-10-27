@extends('layouts.public')

@section('title', '403 - Akses Ditolak')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/error-pages.css') }}">
@endpush

@section('content')
    <section class="error-page container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="error-page-card text-center">

                    <div class="error-page-icon mb-3">
                        <i class="bi bi-shield-lock" aria-hidden="true"></i>
                    </div>

                    <h1 class="error-page-code mb-2">403</h1>

                    <h2 class="error-page-title mb-3">
                        Akses Ditolak
                    </h2>

                    <p class="error-page-description mb-4">
                        Maaf, Anda tidak memiliki izin untuk mengakses
                        halaman ini. Silakan kembali ke beranda atau
                        masuk menggunakan akun yang memiliki hak akses.
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-house-door me-2"></i>
                            Kembali ke Beranda
                        </a>

                        <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Halaman Login
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection