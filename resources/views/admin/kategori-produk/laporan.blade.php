<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judulLaporan }}</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
        }

        .laporan-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .laporan-header h2 {
            font-size: 15px;
            margin: 0 0 6px;
        }

        .laporan-header p {
            font-size: 10px;
            margin: 0;
            color: #64748b;
        }

        .laporan-info {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .laporan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .laporan-table th,
        .laporan-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }

        .laporan-table th {
            background: #e2e8f0;
            font-weight: bold;
            text-align: center;
        }

        .laporan-table .text-center {
            text-align: center;
        }

        .laporan-table thead {
            display: table-header-group;
        }

        .laporan-table tr {
            page-break-inside: avoid;
        }

        .laporan-footer {
            margin-top: 20px;
            font-size: 9px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="laporan-header">
        <h2>{{ $judulLaporan }}</h2>
        <p>Sistem Informasi Direktori UMKM dan Katalog Produk Kota Ambon</p>
    </div>

    <div class="laporan-info">
        Tanggal Cetak: {{ $tanggalCetak }} WIT
    </div>

    <table class="laporan-table">
        <thead>
            <tr>
                <th width="7%">No.</th>
                <th width="25%">Nama Kategori</th>
                <th width="53%">Deskripsi</th>
                <th width="15%">Jumlah Produk</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($kategoriProduk as $kategori)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    <td>{{ $kategori->deskripsi ?: '-' }}</td>
                    <td class="text-center">{{ $kategori->produk_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Tidak ada data kategori produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="laporan-footer">
        Total Kategori: {{ $kategoriProduk->count() }}
    </div>
</body>
</html>