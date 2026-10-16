<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori UMKM Kota Ambon</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 18px 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            color: #0f766e;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            color: #334155;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: #0f766e;
        }

        .hero {
            padding: 90px 8%;
            text-align: center;
        }

        .hero h1 {
            font-size: 38px;
            margin-bottom: 18px;
        }

        .hero p {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #0f766e;
            color: white;
            text-decoration: none;
            padding: 13px 24px;
            border-radius: 8px;
            margin: 5px;
        }

        .btn-outline {
            background: white;
            color: #0f766e;
            border: 1px solid #0f766e;
        }

        @media (max-width: 650px) {
            .navbar {
                flex-direction: column;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .hero {
                padding: 60px 6%;
            }

            .hero h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <header class="navbar">
        <a href="{{ route('home') }}" class="brand">
            UMKM Kota Ambon
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('umkm.index') }}">Direktori UMKM</a>
            <a href="{{ route('produk.index') }}">Katalog Produk</a>
            <a href="{{ route('login') }}">Login Admin</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Temukan UMKM dan Produk Lokal Kota Ambon</h1>

            <p>
                Jelajahi informasi usaha, produk, dan layanan
                dari berbagai UMKM di Kota Ambon.
            </p>

            <a href="{{ route('umkm.index') }}" class="btn">
                Jelajahi UMKM
            </a>

            <a href="{{ route('produk.index') }}" class="btn btn-outline">
                Lihat Katalog Produk
            </a>
        </section>
    </main>
</body>
</html>