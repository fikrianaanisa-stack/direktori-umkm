@extends('layouts.admin')

@section('title', 'Detail UMKM')

@push('styles')

<style>
    .detail-umkm {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Judul dan caption Detail UMKM */
    .detail-umkm-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .detail-umkm-header h1 {
        color: #243b53;
        font-size: 27px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0 0 6px;
    }

    .detail-umkm-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }

    .detail-umkm-back {
        display: inline-block;
        margin-top: 10px;
        padding: 11px 18px;
        background: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
    }

    .detail-umkm-back:hover {
        background: #1d4ed8;
        
    }
    
.detail-umkm-edit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    background: #f59e0b;
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    margin-top: 10px;
    margin-left: 10px;
}

.detail-umkm-edit:hover {
    background: #d97706;
}

</style>
@endpush

@section('content')

<div class="detail-umkm">

    <div class="detail-umkm-header">
        <h1>Detail UMKM</h1>
        <p>Informasi lengkap UMKM dan katalog produk di Kota Ambon.</p>
    </div>

    {{-- Menampilkan informasi UMKM dalam konteks dashboard admin --}}
    {{-- Mengarahkan tautan detail produk ke halaman admin --}}
    @include('umkm.partials.detail-content', ['isAdminView' => true])

    <a href="{{ route('admin.umkm.index') }}"
       class="detail-umkm-back">
        ← Kembali ke Data UMKM
    </a>

    <a href="{{ route('umkm.edit', $umkm->id) }}"
       class="detail-umkm-edit">
        ✎ Edit UMKM
    </a>

</div>

@endsection

