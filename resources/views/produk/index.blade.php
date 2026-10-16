<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk UMKM Kota Ambon</title>

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

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 22px;
        }

        .product-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 210px;
            object-fit: cover;
            background: #e2e8f0;
        }

        .no-image {
            height: 210px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            color: #64748b;
        }

        .product-info {
            padding: 18px;
        }

        .category {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 12px;
        }

        .price {
            color: #2563eb;
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .business-name {
            color: #475569;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .detail-link {
            display: inline-block;
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
    <p>Temukan berbagai produk dari pelaku UMKM Kota Ambon.</p>
</header>

<main class="container">
    <div class="page-title">
        <h2>Katalog Produk UMKM</h2>
        <p>Jelajahi produk yang tersedia dari berbagai UMKM.</p>
    </div>

    <div class="product-grid">
        @forelse ($produk as $item)
            <div class="product-card">
                @if ($item->foto)
                    <img
                        src="{{ asset('storage/' . $item->foto) }}"
                        alt="{{ $item->nama_produk }}"
                        class="product-image">
                @else
                    <div class="no-image">Tidak ada foto</div>
                @endif

                <div class="product-info">
                    <div class="category">
                        {{ $item->kategoriProduk?->nama_kategori ?? 'Tanpa Kategori' }}
                    </div>

                    <h3 class="product-name">
                        {{ $item->nama_produk }}
                    </h3>

                    <div class="price">
                        @php
                            // Ambil pilihan harga produk
                            $pilihanHarga = $item->hargaProduk;
                            $jumlahPilihan = $pilihanHarga->count();
                            $hargaTerendah = $pilihanHarga->min('harga_min');
                        @endphp

                        @if ($jumlahPilihan === 0)
                            <span style="color:#64748b;font-size:14px;">
                                Hubungi UMKM untuk informasi harga
                            </span>

                        @elseif ($jumlahPilihan === 1)
                            @php
                                $pilihan = $pilihanHarga->first();
                            @endphp

                            Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                            @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                            @endif

                            @if ($pilihan->satuan)
                                <span style="font-size:14px;">
                                    / {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                    {{ $pilihan->satuan }}
                                </span>
                            @endif

                        @else
                            Mulai Rp {{ number_format($hargaTerendah, 0, ',', '.') }}

                            <div style="font-size:13px;color:#64748b;font-weight:normal;margin-top:5px;">
                                {{ $jumlahPilihan }} pilihan harga tersedia
                            </div>
                        @endif
                    </div>

                    <div class="business-name">
                        UMKM: {{ $item->umkm?->nama_umkm ?? '-' }}
                    </div>

                    <a href="{{ route('produk.show', $item->id) }}"
                    class="detail-link">
                        Detail Produk
                    </a>

                    @if ($item->umkm)
                        <a href="{{ route('umkm.show', $item->umkm->id) }}"
                        class="detail-link">
                            Lihat UMKM
                        </a>
                    @endif

                </div>
            </div>
        @empty
            <div class="empty-message">
                Belum ada produk yang tersedia.
            </div>
        @endforelse
    </div>
</main>

</body>
</html>
