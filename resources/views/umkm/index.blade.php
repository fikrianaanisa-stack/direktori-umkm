
@php
    $isPengelola = auth()->check()
        && in_array(auth()->user()->role, ['super_admin', 'admin']);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar UMKM - Direktori UMKM Ambon</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f5f6fa;
        }

        h1 {
            color: #243b53;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2563eb;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <h1>Daftar UMKM Kota Ambon</h1>
    @if (session('success'))
        <div style="
            background-color: #dcfce7;
            color: #166534;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #86efac;
            border-radius: 5px;
        ">
            {{ session('success') }}
        </div>
    @endif
    <p>Data Usaha Mikro, Kecil, dan Menengah</p>
    {{-- Tombol khusus Super Admin dan Admin --}}
    @auth
        @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
            <div style="margin-bottom: 20px;">
                <a href="{{ route('umkm.create') }}"
                style="
                    display: inline-block;
                    background-color: #2563eb;
                    color: white;
                    padding: 12px 18px;
                    border-radius: 6px;
                    text-decoration: none;
                ">
                    + Tambah UMKM
                </a>
            </div>
        @endif
    @endauth

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama UMKM</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($umkm as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_umkm }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->telepon ?? '-' }}</td>
                    
                    <td>
                        {{-- Detail dapat dilihat semua pengunjung --}}
                        <a href="{{ route('umkm.show', $item->id) }}">
                            Detail
                        </a>

                        {{-- Edit dan Hapus hanya untuk pengelola --}}
                        @auth
                            @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                                |
                                <a href="{{ route('umkm.edit', $item->id) }}">
                                    Edit
                                </a>

                                |
                                <form action="{{ route('umkm.destroy', $item->id) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus UMKM ini?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="
                                                background: none;
                                                border: none;
                                                color: red;
                                                cursor: pointer;
                                                font-size: 14px;
                                                padding: 0;
                                            ">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data UMKM.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
