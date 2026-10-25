@extends('layouts.public')

@section('title', 'Kontak - Direktori UMKM Kota Ambon')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
@endpush

@section('content')

<section class="kontak-section">

    {{-- Judul halaman --}}
    <div class="kontak-header">
        <h1>Hubungi Kami</h1>

        <p>
            Butuh informasi mengenai Direktori UMKM dan Katalog Produk Kota Ambon?
            Bagi pelaku UMKM yang ingin mendaftarkan usaha serta produk atau
            layanannya ke dalam direktori, silakan menghubungi admin melalui
            kontak yang tersedia.
        </p>
    </div>

    {{-- Informasi kontak --}}
    <div class="kontak-card">

        <div class="kontak-icon">
            <i class="bi bi-headset"></i>
        </div>

        <h2>Informasi Kontak</h2>

        <div class="kontak-info">

            <div class="kontak-info-item">
                <i class="bi bi-person-circle"></i>

                <div>
                    <strong>Pengelola</strong>
                    <p>Admin Direktori UMKM Kota Ambon</p>
                </div>
            </div>

            <div class="kontak-info-item">
                <i class="bi bi-whatsapp"></i>

                <div>
                    <strong>WhatsApp</strong>
                    <p>+62 821-3412-5373</p>
                </div>
            </div>

            <div class="kontak-info-item">
                <i class="bi bi-geo-alt"></i>

                <div>
                    <strong>Wilayah Layanan</strong>
                    <p>Kota Ambon, Maluku</p>
                </div>
            </div>

        </div>

        <a
            href="https://wa.me/6282134125373?text={{ rawurlencode('Halo Admin Direktori UMKM Kota Ambon, saya ingin mendapatkan informasi mengenai Direktori UMKM Kota Ambon.') }}"
            class="kontak-button"
            target="_blank"
            rel="noopener noreferrer"
        >
            <i class="bi bi-whatsapp"></i>
            Hubungi via WhatsApp
        </a>

    </div>

</section>

@endsection