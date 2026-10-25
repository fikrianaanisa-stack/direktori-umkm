<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data UMKM Kota Ambon</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 18mm 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 22px;
        }

        .judul-laporan h2 {
            margin: 0 0 6px;
            font-size: 17px;
        }

        .judul-laporan p {
            margin: 0;
            font-size: 12px;
        }

        .informasi {
            margin-bottom: 14px;
            line-height: 1.7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #555;
            padding: 8px 7px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #e2e8f0;
            font-weight: bold;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        .nomor {
            width: 5%;
            text-align: center;
        }

        .nama {
            width: 25%;
        }

        .alamat {
            width: 48%;
        }

        .telepon {
            width: 22%;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }

        @media print {
            .tidak-dicetak {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="judul-laporan">
        <h2>LAPORAN DATA UMKM</h2>
        <p>Sistem Informasi Direktori UMKM dan Katalog Produk Kota Ambon</p>
    </div>

    <div class="informasi">
        <strong>Tanggal Cetak:</strong> {{ $tanggalCetak }}<br>
        <strong>Jumlah UMKM:</strong> {{ $umkm->count() }} UMKM
    </div>

    <table>
        <thead>
            <tr>
                <th class="nomor">No.</th>
                <th class="nama">Nama UMKM</th>
                <th class="alamat">Alamat</th>
                <th class="telepon">Telepon</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($umkm as $item)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_umkm }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->telepon ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">
                        Tidak ada data UMKM.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan dihasilkan melalui Sistem Informasi Direktori UMKM
        dan Katalog Produk Kota Ambon.
    </div>

</body>
</html>
