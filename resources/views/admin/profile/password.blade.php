@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-password.css') }}">
@endpush

@section('content')
<div class="container py-4 admin-password-page">

    <h2 class="mb-4">
        <i class="bi bi-shield-lock"></i> Ubah Password
    </h2>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <h5 class="mb-3">Keamanan Akun</h5>

            <p class="text-muted">
                Gunakan password yang kuat untuk menjaga keamanan akun admin.
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="formPassword"
                  action="{{ route('admin.password.update') }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">
                        Password Saat Ini
                    </label>

                    <div class="input-group">
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               autocomplete="current-password"
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary toggle-password"
                                data-target="current_password"
                                aria-label="Tampilkan password saat ini">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('current_password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password Baru
                    </label>

                    <div class="input-group">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="8"
                               autocomplete="new-password"
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary toggle-password"
                                data-target="password"
                                aria-label="Tampilkan password baru">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Password baru minimal 8 karakter.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        Konfirmasi Password Baru
                    </label>

                    <div class="input-group">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               minlength="8"
                               autocomplete="new-password"
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary toggle-password"
                                data-target="password_confirmation"
                                aria-label="Tampilkan konfirmasi password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.profile.edit') }}"
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
    <script src="{{ asset('js/admin-password.js') }}" defer></script>
@endpush