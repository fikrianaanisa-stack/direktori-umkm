@extends('layouts.public')

@section('title', 'Login - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-login.css') }}">
@endpush

@section('content')
<section class="login-section d-flex align-items-center justify-content-center">

    <div class="card login-card border-0 shadow">
        <div class="card-body p-4 p-sm-5">

            {{-- Judul login --}}
            <h1 class="text-center fw-bold mb-2">
                Masuk ke Akun
            </h1>

            <p class="text-center text-secondary small mb-4">
                Direktori UMKM Kota Ambon
            </p>

            {{-- Formulir login --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                        Alamat Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                {{-- Tautan lupa password --}}
                <div class="text-end mb-3">
                    <a
                        href="{{ route('password.request') }}"
                        class="small text-decoration-none"
                    >
                        Lupa Password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100 py-2 fw-semibold"
                >
                    Masuk
                </button>
            </form>

            {{-- Tautan registrasi --}}
            <p class="text-center text-secondary small mt-4 mb-0">
                Belum punya akun?
                <a
                    href="{{ route('register') }}"
                    class="fw-semibold text-decoration-none"
                >
                    Daftar di sini
                </a>
            </p>

        </div>
    </div>

</section>
@endsection