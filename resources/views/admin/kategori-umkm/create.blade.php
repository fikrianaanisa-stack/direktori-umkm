@extends('layouts.admin')

@section('title', 'Tambah Kategori UMKM')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-kategori-umkm-create.css') }}">
@endpush

@section('content')
<div class="container-fluid admin-kategori-umkm-create">

    <div class="mb-4">
        <h2 class="mb-2">Tambah Kategori UMKM</h2>
        <p class="text-muted mb-0">
            Tambahkan kategori baru untuk mengelompokkan usaha UMKM.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <form id="formKategoriUmkm"
                  action="{{ route('admin.kategori-umkm.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">
                        Nama Kategori <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           id="nama_kategori"
                           name="nama_kategori"
                           class="form-control @error('nama_kategori') is-invalid @enderror"
                           value="{{ old('nama_kategori') }}"
                           maxlength="100"
                           required>

                    @error('nama_kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="form-label">
                        Deskripsi
                    </label>

                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="4"
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Kategori
                    </button>

                    <a href="{{ route('admin.kategori-umkm.index') }}"
                       id="btnBatalKategoriUmkm"
                       class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-kategori-umkm-create.js') }}" defer></script>
@endpush