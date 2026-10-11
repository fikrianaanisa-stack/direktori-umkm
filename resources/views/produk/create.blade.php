<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk UMKM</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 30px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Produk UMKM</h2>

    <form action="{{ route('produk.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <label>UMKM Pemilik Produk</label>
        <select name="umkm_id" required>
            <option value="">-- Pilih UMKM --</option>
            @foreach ($umkm as $item)
                <option value="{{ $item->id }}"
                    @selected(old('umkm_id') == $item->id)>
                    {{ $item->nama_umkm }}
                </option>
            @endforeach
        </select>
        @error('umkm_id')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Kategori Produk</label>
        <select name="kategori_produk_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoriProduk as $kategori)
                <option value="{{ $kategori->id }}"
                    @selected(old('kategori_produk_id') == $kategori->id)>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_produk_id')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Nama Produk</label>
        <input type="text" name="nama_produk"
               value="{{ old('nama_produk') }}" required>
        @error('nama_produk')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Deskripsi Produk</label>
        <textarea name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Harga (Rp)</label>
        <input type="number" name="harga"
               min="0" step="1"
               value="{{ old('harga') }}" required>
        @error('harga')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Foto Produk</label>
        <input type="file" name="foto" accept="image/*">
        @error('foto')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Simpan Produk</button>
        <a href="{{ route('produk.index') }}">Kembali</a>
    </form>
</div>

</body>
</html>