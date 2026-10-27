@extends('layouts.public')

@section('title', 'Lupa Password - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-forgot-password.css') }}">
@endpush

@section('content')
<section class="forgot-password-section d-flex align-items-center justify-content-center">

    <div class="card forgot-password-card border-0 shadow-sm">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <h1 class="h4 fw-bold mb-2">
                    Lupa Password?
                </h1>

                <p class="text-secondary small mb-0">
                    Masukkan alamat email akun Anda.
                    Kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>
            </div>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

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
                        value="{{ old('email') }}"
                        placeholder="Masukkan alamat email"
                        autocomplete="email"
                        required
                        autofocus
                    >

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    Kirim Tautan Reset Password
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