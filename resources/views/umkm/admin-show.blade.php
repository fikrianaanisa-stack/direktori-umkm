@extends('layouts.admin')

@section('title', 'Detail UMKM')

@push('styles')
<style>
    .detail-umkm {
        max-width: 1400px;
        margin: 0 auto;
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

<div style="margin-bottom: 25px;">
    <h1 style="color: #1e293b; margin-bottom: 8px;">
        Detail UMKM
    </h1>

    <p style="color: #64748b;">
        Informasi lengkap UMKM Kota Ambon.
    </p>
</div>

<div class="detail-umkm">

    {{-- Memanggil informasi UMKM dari Blade Partial --}}
    @include('umkm.partials.detail-content')

    
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
