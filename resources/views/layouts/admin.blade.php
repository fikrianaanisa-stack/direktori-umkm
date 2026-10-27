<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Panel Administrasi') - Direktori UMKM Kota Ambon</title>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    {{-- CSS utama admin --}}
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    {{-- CSS khusus halaman --}}
    @stack('styles')
</head>

<body>
    
    {{-- Notifikasi global Admin --}}
    @if (session('success') || session('error') || $errors->any())
        @php
            $toastType = session('success') ? 'success' : 'error';
            $toastTitle = $toastType === 'success' ? 'Berhasil' : 'Validasi Gagal';

            $toastMessage = session('success')
                ?? session('error')
                ?? 'Periksa kembali data yang dimasukkan.';

            if ($errors->any()) {
                // Ambil pesan kesalahan validasi pertama
                $toastMessage = $errors->first();
            }
        @endphp

        <div id="admin-toast"
            class="admin-toast {{ $toastType === 'success' ? 'admin-toast-success' : 'admin-toast-error' }}"
            data-type="{{ $toastType }}"
            role="{{ $toastType === 'error' ? 'alert' : 'status' }}">

            <div class="admin-toast-content">
                <div>
                    <strong>{{ $toastTitle }}</strong>

                    <p class="admin-toast-message">
                        {{ $toastMessage }}
                    </p>
                </div>

                <button type="button"
                        class="admin-toast-close"
                        aria-label="Tutup notifikasi">
                    &times;
                </button>
            </div>
        </div>
    @endif

<div class="app">

    
    {{-- HEADER --}}
    <header class="header">
        <div class="header-left">

            <button
                type="button"
                class="menu-toggle"
                id="menuToggle"
                aria-label="Buka menu navigasi"
                aria-controls="adminSidebar"
                aria-expanded="false"
            >
                ☰
            </button>

            <div class="brand">
                Direktori UMKM Kota Ambon
            </div>

        </div>

        <details class="user-menu">
            <summary>
                <div>
                    <div class="user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="user-role">
                        Admin
                    </div>
                </div>
            </summary>

            <div class="dropdown">
            <a href="{{ route('admin.profile.edit') }}">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>

            <a href="{{ route('admin.password.edit') }}">
                <i class="bi bi-shield-lock"></i> Ubah Password
            </a>

            <hr>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
        </details>
    </header>

    <div class="main-layout">

        {{-- SIDEBAR --}}
        <aside class="sidebar" id="adminSidebar">
            <p class="sidebar-title">MENU UTAMA</p>

            {{-- Menu Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>Dashboard
            </a>

            {{-- Menu Data UMKM --}}
            <a href="{{ route('admin.umkm.index') }}"
            class="{{ request()->routeIs('admin.umkm.*', 'umkm.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>Data UMKM
            </a>

            {{-- Menu Data Produk --}}
            <a href="{{ route('admin.produk.index') }}"
            class="{{ request()->routeIs('admin.produk.*', 'produk.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>Data Produk
            </a>

            {{-- Menu Kategori UMKM --}}
            <a href="{{ route('admin.kategori-umkm.index') }}"
            class="{{ request()->routeIs('admin.kategori-umkm.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>Kategori UMKM
            </a>

            {{-- Menu Kategori Produk --}}
            <a href="{{ route('admin.kategori-produk.index') }}"
            class="{{ request()->routeIs('admin.kategori-produk.*') ? 'active' : '' }}">
                <i class="bi bi-collection"></i>Kategori Produk
            </a>

           {{-- Menu Data Wilayah --}}
            <a href="{{ route('admin.wilayah.index') }}"
            class="{{ request()->routeIs('admin.wilayah.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>Data Wilayah
            </a>

            {{-- Menu Data Pengunjung --}}
            <a href="{{ route('admin.pengunjung.index') }}"
            class="{{ request()->routeIs('admin.pengunjung.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i>Data Pengunjung
            </a>

            {{-- Moderasi ulasan pengunjung --}}
            <a href="{{ route('admin.ulasan.index') }}"
            class="{{ request()->routeIs('admin.ulasan.*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text"></i>Ulasan
            </a>

            {{-- Riwayat aktivitas admin --}}
            <a href="{{ route('admin.activity-logs.index') }}"
            class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>Riwayat Aktivitas
            </a>

            {{-- Daftar pengelola admin --}}
            <a href="{{ route('admin.pengelola.index') }}"
            class="{{ request()->routeIs('admin.pengelola.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>Daftar Pengelola Admin
            </a>
            
        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="content">
            @yield('content')
        </main>

    </div>

    {{-- FOOTER --}}
    <footer class="footer">
        <div>
            <strong>Direktori UMKM Kota Ambon</strong>
            <div class="footer-description">
                Sistem Informasi Direktori UMKM dan Katalog Produk
            </div>
        </div>

        <div>
            &copy; {{ date('Y') }} Capstone Project
        </div>
    </footer>

</div>

{{-- JavaScript utama admin --}}
<script src="{{ asset('js/admin-layout.js') }}" defer></script>

{{-- JavaScript khusus halaman --}}
@stack('scripts')

</body>
</html>