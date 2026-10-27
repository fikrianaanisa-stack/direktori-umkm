@extends('layouts.public')

@section('title', 'Registrasi - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-register.css') }}">
@endpush

@section('content')
<section class="register-section d-flex align-items-center justify-content-center">

    <div class="card register-card border-0 shadow">
        <div class="card-body p-4 p-sm-5">

            <h1 class="text-center fw-bold mb-2">
                Daftar Akun
            </h1>

            <p class="text-center text-secondary small mb-4">
                Direktori UMKM Kota Ambon
            </p>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- Nama lengkap --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                        maxlength="255"
                        required
                        autofocus
                    >
                </div>

                {{-- Email --}}
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
                        placeholder="Masukkan alamat email"
                        autocomplete="email"
                        required
                    >
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                {{-- Konfirmasi password --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100 py-2 fw-semibold"
                >
                    Daftar Sekarang
                </button>

            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Sudah punya akun?
                <a
                    href="{{ route('login') }}"
                    class="fw-semibold text-decoration-none"
                >
                    Masuk di sini
                </a>
            </p>

        </div>
    </div>

</section>
@endsection