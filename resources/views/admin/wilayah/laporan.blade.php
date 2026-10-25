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
            font-size: 11px;
            color: #222;
        }

        /* Judul laporan */
        h2 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .tanggal {
            text-align: center;
            font-size: 10px;
            margin-bottom: 20px;
        }

        /* Tabel laporan */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .nomor {
            width: 40px;
            text-align: center;
        }

        .kosong {
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

    <h2>{{ $judulLaporan }}</h2>

    <div class="tanggal">
        Tanggal Cetak: {{ $tanggalCetak }} WIT
    </div>

    <table>
        <thead>
            <tr>
                <th class="nomor">No.</th>
                <th>Kecamatan</th>
                <th>Desa/Kelurahan</th>
                <th>Jenis Wilayah</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($wilayah as $item)
                <tr>
                    <td class="nomor">{{ $loop->iteration }}</td>
                    <td>{{ $item->kecamatan }}</td>
                    <td>{{ $item->kelurahan_desa }}</td>
                    <td>{{ $item->jenis_wilayah ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="kosong">
                        Tidak ada data wilayah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
