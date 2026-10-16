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
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6fa;
            color: #1e293b;
        }

        header {
            background: #2344a8;
            color: white;
            padding: 22px 5%;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        header p {
            margin: 8px 0 0;
            color: #dbeafe;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h2 {
            margin: 0 0 8px;
        }

        .page-title p {
            margin: 0;
            color: #64748b;
        }

        .umkm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 22px;
        }

        .umkm-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px;
        }

        .umkm-name {
            font-size: 19px;
            margin: 0 0 16px;
            color: #1e293b;
        }

        .umkm-info {
            font-size: 14px;
            color: #475569;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .umkm-info strong {
            color: #334155;
        }

        .detail-link {
            display: inline-block;
            margin-top: 8px;
            padding: 10px 14px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        .detail-link:hover {
            background: #1d4ed8;
        }

        .empty-message {
            padding: 30px;
            background: white;
            border-radius: 10px;
            text-align: center;
            color: #64748b;
        }
    </style>
</head>
<body>

<header>
    <h1>Direktori UMKM Kota Ambon</h1>
    <p>Temukan berbagai usaha lokal di Kota Ambon.</p>
</header>

<main class="container">
    <div class="page-title">
        <h2>Daftar UMKM</h2>
        <p>Jelajahi informasi pelaku usaha mikro, kecil, dan menengah.</p>
    </div>

    <div class="umkm-grid">
        @forelse ($umkm as $item)
            <div class="umkm-card">
                <h3 class="umkm-name">
                    {{ $item->nama_umkm }}
                </h3>

                <div class="umkm-info">
                    <strong>Alamat:</strong>
                    {{ $item->alamat ?? '-' }}
                </div>

                <div class="umkm-info">
                    <strong>Telepon:</strong>
                    {{ $item->telepon ?? '-' }}
                </div>

                <a href="{{ route('umkm.show', $item->id) }}"
                   class="detail-link">
                    Lihat Detail
                </a>
            </div>
        @empty
            <div class="empty-message">
                Belum ada data UMKM yang tersedia.
            </div>
        @endforelse
    </div>
</main>

</body>
</html>
