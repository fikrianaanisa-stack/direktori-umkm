@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}">
@endpush

@section('content')
<div class="container py-4 admin-profile-page">

    <h2 class="mb-4">
        <i class="bi bi-person-circle"></i> Profil Saya
    </h2>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h5 class="mb-3">Informasi Akun</h5>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="formProfil"
                  action="{{ route('admin.profile.update') }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $admin->name) }}"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $admin->email) }}"
                           required>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text"
                           class="form-control"
                           value="Admin"
                           readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tanggal Akun Dibuat</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $admin->created_at?->format('d-m-Y H:i') ?? '-' }}"
                           readonly>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                       id="btnBatal"
                       class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-profile.js') }}" defer></script>
@endpush