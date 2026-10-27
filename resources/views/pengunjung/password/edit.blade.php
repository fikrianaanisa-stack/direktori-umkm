@extends('layouts.public')

@section('title', 'Ubah Password')

{{-- CSS khusus halaman Ubah Password --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengunjung-password.css') }}">
@endpush

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">

                {{-- Judul formulir dengan ikon yang konsisten --}}
                <div class="card-header bg-white py-3">
                    <h4 class="h5 mb-0 fw-semibold">
                        <i class="bi bi-key me-2"></i>
                        Ubah Password
                    </h4>
                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Silakan masukkan password saat ini dan password baru
                        untuk menjaga keamanan akun Anda.
                    </p>

                    <form method="POST" action="{{ route('pengunjung.password.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Password saat ini --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Password Saat Ini
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                            >

                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="8"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Minimal 8 karakter.
                            </small>
                        </div>

                        {{-- Konfirmasi password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                minlength="8"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        {{-- Tombol aksi perubahan password --}}
                        <div class="d-flex flex-wrap gap-2">

                            <button type="submit" class="btn btn-brand">
                                <i class="bi bi-floppy me-1"></i>
                                Simpan Perubahan
                            </button>

                            <a href="{{ route('pengunjung.profile.index') }}"
                            class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Batal
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection