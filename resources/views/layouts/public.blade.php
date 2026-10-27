<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Direktori UMKM Kota Ambon')</title>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    {{-- CSS utama halaman publik --}}
    <link rel="stylesheet" href="{{ asset('css/public-layout.css') }}">

    {{-- CSS tambahan setiap halaman --}}
    @stack('styles')
</head>

<body>

    {{-- Notifikasi global --}}
    @if (session('success') || session('error') || $errors->any())
        @php
            $toastType = session('success') ? 'success' : 'error';

            $toastTitle = $toastType === 'success'
                ? 'Berhasil'
                : 'Terjadi Kesalahan';

            $toastMessage = session('success')
                ?? session('error')
                ?? 'Periksa kembali data yang dimasukkan.';

            if ($errors->any()) {
                $toastType = 'error';
                $toastTitle = 'Validasi Gagal';
                $toastMessage = $errors->first();
            }
        @endphp

        <div class="toast-container position-fixed top-0 end-0 p-3">

            <div
                id="public-toast"
                class="toast public-toast public-toast-{{ $toastType }}"
                role="{{ $toastType === 'error' ? 'alert' : 'status' }}"
                aria-live="{{ $toastType === 'error' ? 'assertive' : 'polite' }}"
                aria-atomic="true"
                data-bs-autohide="{{ $toastType === 'success' ? 'true' : 'false' }}"
                data-bs-delay="5000"
            >

                <div class="toast-header">

                    <i class="bi {{ $toastType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} me-2"></i>

                    <strong class="me-auto">
                        {{ $toastTitle }}
                    </strong>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="toast"
                        aria-label="Tutup notifikasi"
                    ></button>

                </div>

                <div class="toast-body">
                    {{ $toastMessage }}
                </div>

            </div>

        </div>
    @endif

    {{-- Header dan navigasi publik --}}
    <header class="public-header">

        <nav class="navbar navbar-expand-lg public-navbar" aria-label="Navigasi utama">

            <div class="container public-container">

                {{-- Nama website --}}
                <a class="navbar-brand public-brand" href="{{ route('home') }}">
                    UMKM Kota Ambon
                </a>

                {{-- Tombol navigasi mobile --}}
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#publicNavbar"
                    aria-controls="publicNavbar"
                    aria-expanded="false"
                    aria-label="Buka navigasi"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                {{-- Menu navigasi --}}
                <div class="collapse navbar-collapse" id="publicNavbar">

                    <ul class="navbar-nav ms-auto align-items-lg-center public-nav-links">

                        <li class="nav-item">
                            <a
                                href="{{ route('home') }}"
                                class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                @if (request()->routeIs('home')) aria-current="page" @endif
                            >
                                Beranda
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                href="{{ route('umkm.index') }}"
                                class="nav-link {{ request()->routeIs('umkm.*') ? 'active' : '' }}"
                                @if (request()->routeIs('umkm.*')) aria-current="page" @endif
                            >
                                Direktori UMKM
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                href="{{ route('produk.index') }}"
                                class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}"
                                @if (request()->routeIs('produk.*')) aria-current="page" @endif
                            >
                                Katalog Produk
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                href="{{ route('kontak') }}"
                                class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}"
                                @if (request()->routeIs('kontak')) aria-current="page" @endif
                            >
                                Kontak
                            </a>
                        </li>

                        {{-- Menu pengguna belum login --}}
                        @guest

                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">
                                    Login
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">
                                    Daftar
                                </a>
                            </li>

                        @else

                            {{-- Dropdown akun pengguna --}}
                            <li class="nav-item dropdown public-account-dropdown">

                                <button
                                    type="button"
                                    class="btn public-account-toggle dropdown-toggle"
                                    id="publicAccountToggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <i class="bi bi-person-circle"></i>

                                    <span>{{ auth()->user()->name }}</span>
                                </button>

                                <ul
                                    class="dropdown-menu dropdown-menu-lg-end public-account-menu"
                                    aria-labelledby="publicAccountToggle"
                                >

                                    {{-- Menu khusus pengunjung --}}
                                    @if (auth()->user()->role === 'pengunjung')

                                        <li>
                                            <a
                                                href="{{ route('pengunjung.profile.index') }}"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-person me-2"></i>
                                                Profil Saya
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                href="{{ route('pengunjung.password.edit') }}"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-key me-2"></i>
                                                Ubah Password
                                            </a>
                                        </li>

                                    @endif

                                    {{-- Menu khusus admin --}}
                                    @if (auth()->user()->role === 'admin')

                                        <li>
                                            <a
                                                href="{{ route('admin.dashboard') }}"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-speedometer2 me-2"></i>
                                                Dashboard Admin
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                href="{{ route('admin.profile.edit') }}"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-person me-2"></i>
                                                Profil Saya
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                href="{{ route('admin.password.edit') }}"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-key me-2"></i>
                                                Ubah Password
                                            </a>
                                        </li>

                                    @endif

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    {{-- Logout pengguna --}}
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <button
                                                type="submit"
                                                class="dropdown-item public-account-logout"
                                            >
                                                <i class="bi bi-box-arrow-right me-2"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </li>

                                </ul>

                            </li>

                        @endguest

                    </ul>

                </div>

            </div>

        </nav>

    </header>

    {{-- Konten utama setiap halaman --}}
    <main class="public-content">
        @yield('content')
    </main>

    {{-- Footer publik --}}
    <footer class="public-footer">

        <div class="container public-container text-center">

            <div class="public-footer-brand">
                UMKM Kota Ambon
            </div>

            <p class="public-footer-description">
                Direktori usaha, produk, dan layanan UMKM di Kota Ambon.
            </p>

            {{-- Navigasi footer --}}
            <nav
                class="d-flex justify-content-center flex-wrap gap-3 public-footer-links"
                aria-label="Navigasi footer"
            >

                <a href="{{ route('home') }}">Beranda</a>

                <a href="{{ route('umkm.index') }}">
                    Direktori UMKM
                </a>

                <a href="{{ route('produk.index') }}">
                    Katalog Produk
                </a>

                <a href="{{ route('kontak') }}">Kontak</a>

            </nav>

            <p class="public-footer-copyright">
                &copy; {{ date('Y') }} Direktori UMKM Kota Ambon.
                Hak cipta dilindungi.
            </p>

        </div>

    </footer>

    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        defer
    ></script>

    {{-- JavaScript utama halaman publik --}}
    <script src="{{ asset('js/public-layout.js') }}" defer></script>

    {{-- Konfirmasi pengiriman ulasan --}}
    <script src="{{ asset('js/ulasan-konfirmasi.js') }}" defer></script>

    {{-- JavaScript tambahan setiap halaman --}}
    @stack('scripts')

</body>
</html>