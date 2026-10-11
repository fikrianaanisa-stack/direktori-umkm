
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrasi') - Direktori UMKM Kota Ambon</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            color: #1e293b;
        }

        .app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #1e40af;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .brand {
            font-size: 18px;
            font-weight: bold;
        }

        .user-menu {
            position: relative;
        }

        .user-menu summary {
            cursor: pointer;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-menu summary::-webkit-details-marker {
            display: none;
        }

        .user-menu summary::after {
            content: "⌄";
            font-size: 18px;
        }

        .user-name {
            font-weight: bold;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.85;
            margin-top: 4px;
        }

        .dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 12px);
            background: white;
            color: #1e293b;
            width: 220px;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
            z-index: 100;
        }

        .dropdown a,
        .dropdown button,
        .dropdown .disabled-item {
            display: block;
            width: 100%;
            text-align: left;
            padding: 11px;
            border: none;
            background: none;
            font-size: 14px;
            color: #334155;
            text-decoration: none;
        }

        .dropdown button {
            cursor: pointer;
            color: #dc2626;
        }

        .dropdown a:hover,
        .dropdown button:hover {
            background: #f1f5f9;
            border-radius: 5px;
        }

        .dropdown .disabled-item {
            color: #94a3b8;
        }

        .main-layout {
            display: flex;
            flex: 1;
            min-height: 0;
        }

        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: white;
            border-right: 1px solid #e2e8f0;
            padding: 25px 15px;
        }

        .sidebar-title {
            font-size: 12px;
            color: #64748b;
            font-weight: bold;
            margin: 0 12px 15px;
        }

        .sidebar a,
        .sidebar .menu-disabled {
            display: block;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            text-decoration: none;
            color: #334155;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #eff6ff;
        }

        .sidebar a.active {
                background-color: #dbeafe;
                color: #1d4ed8;
                font-weight: 600;
                border-left: 4px solid #2563eb;
        }

        .sidebar .menu-disabled {
            color: #94a3b8;
            cursor: default;
        }

        .content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .footer {
            background: #1e293b;
            color: white;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 13px;
        }

        .footer-description {
            color: #cbd5e1;
            margin-top: 5px;
        }
        
        /* Header bagian kiri */
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Tombol hamburger disembunyikan pada desktop */
        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: white;
            font-size: 25px;
            cursor: pointer;
            padding: 5px;
        }

        /* Tampilan browser 1024px atau kurang */
        @media (max-width: 1024px) {

            .menu-toggle {
                display: block;
            }

            .main-layout {
                flex-direction: column;
            }

            
            .sidebar {
                display: block;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 0 12px;
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                visibility: hidden;
                transition:
                    max-height 0.45s ease-in-out,
                    opacity 0.3s ease,
                    visibility 0s linear 0.45s;
            }

            .sidebar.show {
                max-height: 700px;
                opacity: 1;
                visibility: visible;
                padding: 12px;
                transition:
                    max-height 0.55s ease-in-out,
                    opacity 0.3s ease,
                    visibility 0s;
            }


            .content {
                padding: 20px 15px;
            }
        }

        /* Tampilan smartphone */
        @media (max-width: 768px) {

            .header {
                padding: 15px;
            }

            .brand {
                font-size: 15px;
            }

            .user-name {
                font-size: 12px;
            }

            .user-role {
                font-size: 11px;
            }
        }

        
        /* Animasi bergelombang untuk menu hamburger */
        @keyframes menuWave {
            0% {
                opacity: 0;
                transform: translateY(-18px) scale(0.96);
            }

            65% {
                opacity: 1;
                transform: translateY(5px) scale(1.02);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Hanya aktif pada tampilan responsive */
        @media (max-width: 1024px) {

            .sidebar.show > a,
            .sidebar.show > .menu-disabled {
                animation: menuWave 0.5s ease-out both;
            }

            .sidebar.show > :nth-child(2) {
                animation-delay: 0.05s;
            }

            .sidebar.show > :nth-child(3) {
                animation-delay: 0.10s;
            }

            .sidebar.show > :nth-child(4) {
                animation-delay: 0.15s;
            }

            .sidebar.show > :nth-child(5) {
                animation-delay: 0.20s;
            }

            .sidebar.show > :nth-child(6) {
                animation-delay: 0.25s;
            }

            .sidebar.show > :nth-child(7) {
                animation-delay: 0.30s;
            }

            .sidebar.show > :nth-child(8) {
                animation-delay: 0.35s;
            }

            .sidebar.show > :nth-child(9) {
                animation-delay: 0.40s;
            }
        }

        /* Aksesibilitas animasi */
        @media (prefers-reduced-motion: reduce) {
            .sidebar.show > a,
            .sidebar.show > .menu-disabled {
                animation: none;
            }
        }

        
        /* Animasi bergelombang saat menu ditutup */
        @keyframes menuWaveOut {
            0% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-15px);
            }
        }

        @media (max-width: 1024px) {
            .sidebar.closing > a,
            .sidebar.closing > .menu-disabled {
                animation: menuWaveOut 0.2s ease-in both;
            }

            .sidebar.closing > :nth-child(9) { animation-delay: 0s; }
            .sidebar.closing > :nth-child(8) { animation-delay: 0.04s; }
            .sidebar.closing > :nth-child(7) { animation-delay: 0.08s; }
            .sidebar.closing > :nth-child(6) { animation-delay: 0.12s; }
            .sidebar.closing > :nth-child(5) { animation-delay: 0.16s; }
            .sidebar.closing > :nth-child(4) { animation-delay: 0.20s; }
            .sidebar.closing > :nth-child(3) { animation-delay: 0.24s; }
            .sidebar.closing > :nth-child(2) { animation-delay: 0.28s; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sidebar.closing > a,
            .sidebar.closing > .menu-disabled {
                animation: none;
            }
        }

    </style>

    @stack('styles')
</head>

<body>
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
                        {{ auth()->user()->role === 'super_admin'
                            ? 'Super Admin'
                            : 'Admin' }}
                    </div>
                </div>
            </summary>

            <div class="dropdown">
                <div class="disabled-item">
                    Profil Saya (segera hadir)
                </div>

                <div class="disabled-item">
                    Ubah Password (segera hadir)
                </div>

                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>

                <hr>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </details>
    </header>

    <div class="main-layout">

        {{-- SIDEBAR --}}
        <aside class="sidebar" id="adminSidebar">
            <p class="sidebar-title">MENU UTAMA</p>

            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.umkm.index') }}"
            class="{{ request()->routeIs('admin.umkm.*', 'umkm.create', 'umkm.edit', 'umkm.show') ? 'active' : '' }}">
                Data UMKM
            </a>

            <a href="{{ route('produk.index') }}"
               class="{{ request()->routeIs('produk.*') ? 'active' : '' }}">
                Data Produk
            </a>

            {{-- Menu berikut akan diaktifkan setelah CRUD dibuat --}}
            <div class="menu-disabled">Kategori UMKM</div>
            <div class="menu-disabled">Kategori Produk</div>
            <div class="menu-disabled">Data Wilayah</div>
            <div class="menu-disabled">Ulasan</div>

            @if (auth()->user()->role === 'super_admin')
                <div class="menu-disabled">Kelola Admin</div>
            @endif
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


@stack('scripts')


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('adminSidebar');
        const userMenu = document.querySelector('.user-menu');

        // Membuka dan menutup sidebar
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', function () {

                
        if (sidebar.classList.contains('closing')) {
            return;
        }

        if (sidebar.classList.contains('show')) {
            sidebar.classList.add('closing');

            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Buka menu navigasi');

            
            sidebar.classList.remove('show');

            setTimeout(function () {
                sidebar.classList.remove('closing');
            }, 550);

        } else {
            sidebar.classList.add('show');

            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.setAttribute('aria-label', 'Tutup menu navigasi');
        }

            });
        }

        // Menutup dropdown profil ketika klik di luar
        document.addEventListener('click', function (event) {
            if (userMenu && !userMenu.contains(event.target)) {
                userMenu.removeAttribute('open');
            }
        });

        // Mengembalikan sidebar saat ukuran browser berubah
        window.addEventListener('resize', function () {
            if (window.innerWidth > 1024 && sidebar && menuToggle) {
                sidebar.classList.remove('show');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'Buka menu navigasi');
            }
        });

    });
</script>
</body>
</html>

