@extends('layouts.public')

@section('title', 'Profil Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengunjung-profile.css') }}">
@endpush

@section('content')
<div class="container py-4">

    <h2 class="h4 fw-semibold mb-4">
        <i class="bi bi-person me-2"></i>
        Profil Saya
    </h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-4">Informasi Akun</h5>

            <form method="POST" action="{{ route('pengunjung.profile.update') }}">
                @csrf
                @method('PUT')

                {{-- Nama lengkap --}}
                <div class="mb-3">
                    <label for="name" class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        class="form-control"
                        value="{{ $user->email }}"
                        readonly
                    >
                </div>

                {{-- Status verifikasi email --}}
                <div class="mb-4">
                    <label class="form-label">
                        Status Verifikasi Email
                    </label>

                    <div>
                        @if ($user->email_verified_at)
                            <span class="badge bg-success">
                                Terverifikasi
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-floppy me-1"></i>
                    Simpan Perubahan
                </button>

            </form>

        </div>
    </div>

</div>
@endsection