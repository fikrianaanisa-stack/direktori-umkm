@extends('layouts.admin')

@section('title', 'Detail Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-produk-show.css') }}">
@endpush

@section('content')

<div class="detail-produk-admin">

    <div class="text-center mb-4">
        <h1 class="detail-produk-title">Detail Produk</h1>
        <p class="text-secondary small mb-0">
            Informasi lengkap produk yang terdaftar dalam sistem.
        </p>
    </div>

    @include('produk.partials.detail-content', ['isAdminView' => true])

    <div class="detail-produk-actions d-flex flex-wrap gap-2 mt-3">

        <a href="{{ route('admin.produk.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Data Produk
        </a>

        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i>
            Edit Produk
        </a>

    </div>

</div>

@endsection