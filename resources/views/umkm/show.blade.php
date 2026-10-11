<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail UMKM - {{ $umkm->nama_umkm }}</title>
</head>
<body>

    <h1>Detail UMKM</h1>

    <p>
        <a href="{{ route('umkm.index') }}">
            ← Kembali ke Daftar UMKM
        </a>
    </p>

    {{-- Menampilkan informasi UMKM dari partial --}}
    @include('umkm.partials.detail-content')

</body>
</html>
