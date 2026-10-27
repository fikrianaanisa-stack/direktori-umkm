@extends('layouts.public')

@section('title', 'Kontak - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
@endpush

@section('content')
<section class="kontak-section">

    {{-- Judul halaman --}}
    <div class="kontak-header text-center mb-4">
        <h1 class="fw-bold mb-3">Hubungi Kami</h1>

        <p class="text-secondary mx-auto mb-0">
            Butuh informasi mengenai Direktori UMKM dan Katalog Produk Kota Ambon?
            Bagi pelaku UMKM yang ingin mendaftarkan usaha serta produk atau
            layanannya ke dalam direktori, silakan menghubungi admin melalui
            kontak yang tersedia.
        </p>
    </div>

    {{-- Informasi kontak --}}
    <div class="card kontak-card shadow-sm mx-auto">
        <div class="card-body p-4 p-md-5">

            <div class="kontak-icon text-center mb-3">
                <i class="bi bi-headset"></i>
            </div>

            <h2 class="text-center fw-bold mb-4">Informasi Kontak</h2>

            <div class="d-flex flex-column gap-4 mb-4">

                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-person-circle kontak-info-icon"></i>

                    <div>
                        <strong class="d-block mb-1">Pengelola</strong>
                        <p class="text-secondary mb-0">
                            Admin Direktori UMKM Kota Ambon
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-whatsapp kontak-info-icon"></i>

                    <div>
                        <strong class="d-block mb-1">WhatsApp</strong>
                        <p class="text-secondary mb-0">
                            +62 821-3412-5373
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-geo-alt kontak-info-icon"></i>

                    <div>
                        <strong class="d-block mb-1">Wilayah Layanan</strong>
                        <p class="text-secondary mb-0">
                            Kota Ambon, Maluku
                        </p>
                    </div>
                </div>

            </div>

            <div class="text-center">
                <a
                    href="https://wa.me/6282134125373?text={{ rawurlencode('Halo Admin Direktori UMKM Kota Ambon, saya ingin mendapatkan informasi mengenai Direktori UMKM Kota Ambon.') }}"
                    class="btn kontak-button d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="bi bi-whatsapp"></i>
                    Hubungi via WhatsApp
                </a>
            </div>

        </div>
    </div>

</section>
@endsection