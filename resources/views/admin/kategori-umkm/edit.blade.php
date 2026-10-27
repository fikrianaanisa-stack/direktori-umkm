@extends('layouts.admin')

@section('title', 'Edit Kategori UMKM')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-kategori-umkm-edit.css') }}">
@endpush

@section('content')
<div class="container-fluid admin-kategori-umkm-edit">

    <div class="mb-4">
        <h2 class="mb-2">Edit Kategori UMKM</h2>
        <p class="text-muted mb-0">
            Perbarui informasi kategori usaha UMKM.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">

            <form id="formEditKategori"
                  action="{{ route('admin.kategori-umkm.update', $kategori->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">
                        Nama Kategori <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           id="nama_kategori"
                           name="nama_kategori"
                           class="form-control @error('nama_kategori') is-invalid @enderror"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
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
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-1"></i>
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.kategori-umkm.index') }}"
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
    <script src="{{ asset('js/admin-kategori-umkm-edit.js') }}" defer></script>
@endpush