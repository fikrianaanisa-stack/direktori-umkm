<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk UMKM</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f6fa;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        /* Tampilan tombol aksi */
        .btn-edit,
        .btn-hapus {
            display: inline-block;
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-hapus {
            background-color: #dc3545;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-hapus:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <h2>Daftar Produk UMKM</h2>
    
    {{-- Tombol Tambah Produk --}}
    <a href="{{ route('produk.create') }}" class="btn-tambah">
        + Tambah Produk
    </a>

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Foto</th>
                <th>Nama Produk</th>
                <th>UMKM</th>
                <th>Kategori Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($produk as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        @if ($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}"
                                 alt="{{ $item->nama_produk }}">
                        @else
                            Tidak ada foto
                        @endif
                    </td>

                    <td>{{ $item->nama_produk }}</td>

                    <td>{{ $item->umkm?->nama_umkm ?? '-' }}</td>

                    <td>{{ $item->kategoriProduk?->nama_kategori ?? '-' }}</td>

                    <td>
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>

                    {{-- Kolom Aksi --}}
                    <td>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('produk.edit', $item->id) }}"
                        class="btn-edit">
                            Edit
                        </a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('produk.destroy', $item->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-hapus">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        Belum ada data produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>