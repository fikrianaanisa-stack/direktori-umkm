@extends('layouts.public')

@section('title', 'Reset Password - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-reset-password.css') }}">
@endpush

@section('content')
<section class="reset-password-section d-flex align-items-center justify-content-center">

    <div class="card reset-password-card border-0 shadow-sm">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <h1 class="h4 fw-bold mb-2">
                    Reset Password
                </h1>

                <p class="text-secondary small mb-0">
                    Silakan buat password baru untuk akun Anda.
                </p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                {{-- Token reset password --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Alamat email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                        Alamat Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $email) }}"
                        autocomplete="email"
                        required
                    >
                </div>

                {{-- Password baru --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
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
                        Konfirmasi Password Baru
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password baru"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    Simpan Password Baru
                </button>

            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    Kembali ke Login
                </a>
            </div>

        </div>
    </div>

</section>
@endsection