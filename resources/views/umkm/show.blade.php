@extends('layouts.public')

@section('title', 'Detail UMKM - ' . $umkm->nama_umkm)

@section('content')

    <div class="umkm-detail-page">

        <nav class="breadcrumb" aria-label="Navigasi halaman">
            <a href="{{ route('home') }}">Beranda</a>
            <span> / </span>
            <a href="{{ route('umkm.index') }}">Direktori UMKM</a>
            <span> / </span>
            <span>{{ $umkm->nama_umkm }}</span>
        </nav>

        <h1 class="detail-title">
            {{ $umkm->nama_umkm }}
        </h1>

        {{-- Menampilkan komponen detail UMKM tanpa kartu pembungkus tambahan --}}
        @include('umkm.partials.detail-content')

    </div>

@endsection

@push('styles')
<style>
    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 22px;
        font-size: 14px;
        color: #64748b;
    }

    .breadcrumb a {
        color: #0f766e;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .detail-title {
        margin: 0 0 24px;
        font-size: 28px;
        color: #0f172a;
        overflow-wrap: anywhere;
    }

    @media (max-width: 600px) {
        .detail-title {
            font-size: 23px;
        }
    }
</style>
@endpush
