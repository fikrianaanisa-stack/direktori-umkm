<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produk</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin: 0 0 8px;
            font-size: 16px;
        }

        .informasi {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #555;
            padding: 7px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #e5e7eb;
            text-align: center;
        }

        .nomor {
            width: 35px;
            text-align: center;
        }

        .harga {
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <h2>LAPORAN DATA PRODUK</h2>

    <div class="informasi">
        Tanggal Cetak: {{ $tanggalCetak }} WIT
        <br>
        Jumlah Produk: {{ $produk->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="nomor">No.</th>
                <th>Nama Produk</th>
                <th>UMKM</th>
                <th>Kategori Produk</th>
                <th>Harga</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($produk as $item)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>

                    <td>{{ $item->nama_produk }}</td>

                    <td>{{ $item->umkm?->nama_umkm ?? '-' }}</td>

                    <td>{{ $item->kategoriProduk?->nama_kategori ?? '-' }}</td>

                    <td class="harga">
                        @forelse ($item->hargaProduk as $pilihan)
                            Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                            @if (
                                $pilihan->harga_max !== null &&
                                $pilihan->harga_max > $pilihan->harga_min
                            )
                                – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                            @endif

                            @if ($pilihan->satuan)
                                / {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                {{ $pilihan->satuan }}
                            @endif

                            @unless ($loop->last)
                                <br>
                            @endunless

                        @empty
                            Hubungi UMKM
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Tidak ada data produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
