@extends('layouts.admin')

@section('title', 'Detail UMKM')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/umkm-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-show.css') }}">
@endpush

@section('content')
<div class="detail-umkm">

    {{-- Judul halaman --}}
    <div class="text-center mb-4">
        <h1 class="fw-bold mb-2">Detail UMKM</h1>
        <p class="text-secondary small mb-0">
            Informasi lengkap UMKM dan katalog produk di Kota Ambon.
        </p>
    </div>

    {{-- Komponen detail UMKM --}}
    @include('umkm.partials.detail-content', ['isAdminView' => true])

    {{-- Tombol navigasi --}}
    <div class="d-flex flex-wrap gap-2 mt-3">
        <a href="{{ route('admin.umkm.index') }}"
           class="btn btn-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Data UMKM
        </a>

        <a href="{{ route('umkm.edit', $umkm->id) }}"
           class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i>
            Edit UMKM
        </a>
    </div>

</div>
@endsection