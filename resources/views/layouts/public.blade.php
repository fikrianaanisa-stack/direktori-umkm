<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Direktori UMKM Kota Ambon')</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS layout publik --}}
    <link rel="stylesheet" href="{{ asset('css/public-layout.css') }}">

    {{-- CSS tambahan setiap halaman --}}
    @stack('styles')

</head>
<body>

    {{-- Notifikasi global halaman publik --}}
    @if (session('success') || session('error') || $errors->any())
        @php
            $toastType = session('success') ? 'success' : 'error';

            $toastTitle = $toastType === 'success'
                ? 'Berhasil'
                : 'Validasi Gagal';

            $toastMessage = session('success')
                ?? session('error')
                ?? 'Periksa kembali data yang dimasukkan.';

            if ($errors->any()) {
                $toastType = 'error';
                $toastTitle = 'Validasi Gagal';
                $toastMessage = $errors->first();
            }
        @endphp

        <div id="public-toast"
             class="public-toast {{ $toastType === 'success' ? 'public-toast-success' : 'public-toast-error' }}"
             data-type="{{ $toastType }}"
             role="{{ $toastType === 'error' ? 'alert' : 'status' }}">

            <div class="public-toast-content">
                <div>
                    <strong>{{ $toastTitle }}</strong>

                    <p class="public-toast-message">
                        {{ $toastMessage }}
                    </p>
                </div>

                <button type="button"
                        class="public-toast-close"
                        aria-label="Tutup notifikasi">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <!-- Header dan navigasi -->

    <header class="public-header">
        <nav class="public-navbar" aria-label="Navigasi utama">

            <a href="{{ route('home') }}" class="public-brand">
                UMKM Kota Ambon
            </a>
            
            {{-- Menu Navigasi Publik --}}
            <div class="public-nav-links">

                <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}"
                @if(request()->routeIs('home')) aria-current="page" @endif>
                    Beranda
                </a>

                <a href="{{ route('umkm.index') }}"
                class="{{ request()->routeIs('umkm.*') ? 'active' : '' }}"
                @if(request()->routeIs('umkm.*')) aria-current="page" @endif>
                    Direktori UMKM
                </a>

                <a href="{{ route('produk.index') }}"
                class="{{ request()->routeIs('produk.*') ? 'active' : '' }}"
                @if(request()->routeIs('produk.*')) aria-current="page" @endif>
                    Katalog Produk
                </a>

                <a href="{{ route('kontak') }}"
                class="{{ request()->routeIs('kontak') ? 'active' : '' }}"
                @if(request()->routeIs('kontak')) aria-current="page" @endif>
                    Kontak
                </a>
                
                {{-- Menu akun berdasarkan status login --}}
                @guest
                    <a href="{{ route('login') }}">
                        Login
                    </a>

                    <a href="{{ route('register') }}">
                        Daftar
                    </a>
                @else
                    {{-- Dropdown akun pengguna --}}
                    <div class="public-account-dropdown">

                        {{-- Tombol nama pengguna --}}
                        <button type="button"
                                class="public-account-toggle"
                                id="publicAccountToggle"
                                aria-expanded="false"
                                aria-controls="publicAccountMenu">

                            <i class="bi bi-person-circle"></i>

                            <span>{{ auth()->user()->name }}</span>

                            <i class="bi bi-chevron-down"></i>
                        </button>

                        {{-- Daftar menu akun --}}
                        <div class="public-account-menu"
                            id="publicAccountMenu">

                            {{-- Menu khusus pengunjung --}}
                            @if (auth()->user()->role === 'pengunjung')

                                <a href="{{ route('pengunjung.profile.index') }}">
                                    <i class="bi bi-person"></i>
                                    Profil Saya
                                </a>

                                {{-- Akan diaktifkan setelah fitur ubah password dibuat --}}
                                <a href="{{ route('pengunjung.password.edit') }}">
                                    <i class="bi bi-key"></i> Ubah Password
                                </a>

                            @endif

                            {{-- Menu khusus admin --}}
                            @if (auth()->user()->role === 'admin')

                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i>
                                    Dashboard Admin
                                </a>

                                <a href="{{ route('admin.profile.edit') }}">
                                    <i class="bi bi-person"></i>
                                    Profil Saya
                                </a>

                                <a href="{{ route('admin.password.edit') }}">
                                    <i class="bi bi-key"></i>
                                    Ubah Password
                                </a>

                            @endif

                            <div class="public-account-divider"></div>

                            {{-- Logout --}}
                            <form method="POST"
                                action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="public-account-logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>

                        </div>
                    </div>
                @endguest

            </div>
        </nav>
    </header>

    <!-- Konten setiap halaman -->
    <main class="public-content">
        @yield('content')
    </main>
    
    {{-- Footer Publik --}}
    <footer class="public-footer">

        <div class="public-footer-brand">
            UMKM Kota Ambon
        </div>

        <p class="public-footer-description">
            Direktori usaha, produk, dan layanan UMKM di Kota Ambon.
        </p>

        <nav class="public-footer-links" aria-label="Navigasi footer">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('umkm.index') }}">Direktori UMKM</a>
            <a href="{{ route('produk.index') }}">Katalog Produk</a>
            <a href="{{ route('kontak') }}">Kontak</a>
        </nav>

        <p class="public-footer-copyright">
            &copy; {{ date('Y') }} Direktori UMKM Kota Ambon.
            Hak cipta dilindungi.
        </p>
    </footer>

{{-- JavaScript layout publik --}}
<script src="{{ asset('js/public-layout.js') }}" defer></script>

{{-- JavaScript tambahan setiap halaman --}}
@stack('scripts')
</body>
</html>