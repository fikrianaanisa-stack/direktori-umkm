@extends('layouts.admin')

@section('title', 'Detail Produk')

@push('styles')
<style>
    .detail-produk-admin {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .detail-produk-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .detail-produk-header h1 {
        color: #243b53;
        font-size: 27px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0 0 6px;
    }

    .detail-produk-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    /* Tombol Kembali ke Data Produk */
    .detail-produk-back {
        display: inline-block;
        margin-top: 10px;
        padding: 11px 18px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
    }

    .detail-produk-back:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    /* Tombol Edit Produk */
    .detail-produk-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        margin-left: 10px;
        padding: 11px 18px;
        background: #f59e0b;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
    }

    .detail-produk-edit:hover {
        background: #d97706;
        color: #ffffff;
    }

    /* Menyamakan posisi tombol dengan kartu Detail Produk */
    .detail-produk-admin .produk-container {
        margin-bottom: 0;
    }

    .detail-produk-actions {
        width: 100%;
        max-width: 1000px;
        margin: 10px auto 0;
    }

</style>
@endpush

@section('content')

<div class="detail-produk-admin">

    <div class="detail-produk-header">
        <h1>Detail Produk</h1>
        <p>Informasi lengkap produk yang terdaftar dalam sistem.</p>
    </div>

    @include('produk.partials.detail-content', ['isAdminView' => true])

    {{-- Navigasi Detail Produk Admin --}}
    <div class="detail-produk-actions">

        {{-- Kembali ke daftar produk --}}
        <a href="{{ route('admin.produk.index') }}"
        class="detail-produk-back">
            ← Kembali ke Data Produk
        </a>

        {{-- Mengubah informasi produk --}}
        <a href="{{ route('produk.edit', $produk->id) }}"
        class="detail-produk-edit">
            ✎ Edit Produk
        </a>

    </div>

</div>

@endsection
