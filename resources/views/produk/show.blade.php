<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Direktori UMKM Kota Ambon</title>

    <style>
        body {
            margin: 0;
            padding: 30px 20px;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .produk-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .produk-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 25px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .produk-foto {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            background: #f8fafc;
            border-radius: 10px;
        }

        .produk-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            background: #f1f5f9;
            color: #94a3b8;
            border-radius: 10px;
        }

        .produk-info h1 {
            margin-top: 0;
            font-size: 26px;
        }

        .produk-kategori {
            color: #64748b;
            font-size: 14px;
        }

        .produk-harga {
            color: #2563eb;
            font-size: 25px;
            font-weight: bold;
            margin: 20px 0;
        }

        .produk-deskripsi {
            line-height: 1.7;
            color: #475569;
            white-space: pre-line;
        }

        .produk-link {
            display: inline-block;
            margin-top: 18px;
            padding: 11px 16px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .produk-link:hover {
            background: #1d4ed8;
        }

        .produk-kembali {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .produk-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="produk-container">

    <a href="{{ route('produk.index') }}" class="produk-kembali">
        ← Kembali ke Katalog Produk
    </a>

    <div class="produk-card">

        <div>
            @if ($produk->foto)
                <img src="{{ asset('storage/' . $produk->foto) }}"
                     alt="{{ $produk->nama_produk }}"
                     class="produk-foto">
            @else
                <div class="produk-placeholder">
                    Foto produk belum tersedia
                </div>
            @endif
        </div>

        <div class="produk-info">

            <h1>{{ $produk->nama_produk }}</h1>

            <p class="produk-kategori">
                Kategori:
                {{ $produk->kategoriProduk->nama_kategori ?? '-' }}
            </p>

            <!-- Daftar pilihan harga produk -->
            <div class="produk-harga" style="font-size:16px;font-weight:normal;">
                @forelse ($produk->hargaProduk->sortBy('harga_min') as $pilihan)
                    <div style="padding:12px 14px;margin-bottom:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">

                        <div style="color:#2563eb;font-size:20px;font-weight:bold;">
                            Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                            @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                            @endif
                        </div>

                        <div style="color:#64748b;font-size:14px;margin-top:5px;">
                            @if ($pilihan->satuan)
                                Untuk
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                {{ $pilihan->satuan }}
                            @else
                                Jumlah:
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                            @endif
                        </div>

                    </div>
                @empty
                    <span style="color:#64748b;font-size:15px;">
                        Hubungi UMKM untuk informasi harga
                    </span>
                @endforelse

                <p style="font-size:12px;color:#64748b;font-weight:normal;margin-top:10px;">
                    Harga dapat berubah sewaktu-waktu.
                </p>
            </div>

            <h3>Deskripsi Produk</h3>

            <p class="produk-deskripsi">{{ $produk->deskripsi ?: 'Deskripsi produk belum tersedia.' }}</p>

            <h3>UMKM Penyedia</h3>

            <p>{{ $produk->umkm->nama_umkm ?? '-' }}</p>

            @if ($produk->umkm)
                <a href="{{ route('umkm.show', $produk->umkm->id) }}"
                   class="produk-link">
                    Lihat Profil UMKM →
                </a>
            @endif

        </div>

    </div>

</div>

</body>
</html>
