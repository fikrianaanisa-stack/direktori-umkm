<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Direktori UMKM Kota Ambon')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        /* Header Publik */
        .public-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Navigasi Utama */
        .public-navbar {
            max-width: 1150px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* Nama Website */
        .public-brand {
            color: #0f766e;
            font-size: 21px;
            font-weight: 700;
            text-decoration: none;
        }

        /* Menu Navigasi */
        .public-nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        /* Tautan Navigasi */
        .public-nav-links a {
            color: #334155;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        /* Efek Hover Navigasi */
        .public-nav-links a:hover {
            color: #0f766e;
        }

        /* Menu Navigasi Aktif */
        .public-nav-links a.active {
            color: #0f766e;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 6px;
            text-decoration-thickness: 2px;
        }
        
        /* Penanda Menu Navigasi Aktif */
        .public-nav-links a.active {
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 6px;
            text-decoration-thickness: 2px;
        }

        /* Area konten */
        .public-content {
            max-width: 1150px;
            margin: 35px auto;
            padding: 0 24px;
            min-height: 60vh;
        }
        
        /* Footer Publik */
        .public-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 35px 24px;
            text-align: center;
        }

        /* Nama Website */
        .public-footer-brand {
            color: #0f766e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        /* Deskripsi Footer */
        .public-footer-description {
            color: #64748b;
            font-size: 14px;
            margin: 0 0 16px;
        }

        /* Navigasi Footer */
        .public-footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .public-footer-links a {
            color: #334155;
            font-size: 14px;
            text-decoration: none;
        }

        .public-footer-links a:hover {
            color: #0f766e;
        }

        /* Hak Cipta */
        .public-footer-copyright {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        @media (max-width: 600px) {
            .public-navbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .public-nav-links {
                gap: 14px;
            }

            .public-content {
                margin: 24px auto;
                padding: 0 16px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

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
                
                <a href="{{ route('login') }}">
                    Login
                </a>

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
        </nav>

        <p class="public-footer-copyright">
            &copy; {{ date('Y') }} Direktori UMKM Kota Ambon.
            Hak cipta dilindungi.
        </p>

    </footer>
</body>
</html>
