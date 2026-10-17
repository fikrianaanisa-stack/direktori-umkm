@extends('layouts.public')

@section('title', 'Katalog Produk UMKM Kota Ambon')

@push('styles')

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
        
        /* Navigasi kembali ke beranda */
        .home-link {
            display: inline-block;
            margin-top: 16px;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .home-link:hover {
            text-decoration: underline;
        }
        
        /* Formulir filter dan pencarian */
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 15px;
            padding: 20px;
            margin-bottom: 25px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1 1 220px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: #ffffff;
            color: #1e293b;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        .filter-button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-button:hover {
            background: #1d4ed8;
        }
        
        /* Informasi jumlah hasil pencarian */
        .jumlah-hasil {
            margin: 0 0 20px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        
        /* Pesan hasil pencarian kosong */
        .pencarian-kosong {
            padding: 30px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            color: #64748b;
        }

        .pencarian-kosong h3 {
            margin: 0 0 10px;
            color: #334155;
        }

        .pencarian-kosong p {
            margin: 0;
            font-size: 14px;
        }
        
        /* Tombol reset filter */
        .reset-filter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .reset-filter:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

    </style>
@endpush

@section('content')

<main class="container">
    <div class="page-title">
        <h2>Katalog Produk UMKM</h2>
        <p>Jelajahi produk yang tersedia dari berbagai UMKM.</p>
    </div>
    
    <!-- Filter kategori dan pencarian produk -->
    <form action="{{ route('produk.index') }}" method="GET" class="filter-container">

        <div class="filter-group">
            <label for="kategori">Kategori Produk</label>

            <select name="kategori" id="kategori">
                <option value="">Semua Kategori</option>

                @foreach ($kategoriProduk as $kategori)
                    <option
                        value="{{ $kategori->id }}"
                        {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="cari">Cari Produk</label>

            <input
                type="search"
                name="cari"
                id="cari"
                value="{{ request('cari') }}"
                placeholder="Masukkan nama produk...">
        </div>

        <button type="submit" class="filter-button">
            Terapkan Filter
        </button>

        <a href="{{ route('produk.index') }}" class="reset-filter">
            Reset Filter
        </a>

    </form>
    <!-- Informasi jumlah produk -->
    <p id="jumlah-hasil" class="jumlah-hasil" aria-live="polite">
        Menampilkan {{ $produk->count() }} produk
    </p>

    <div class="product-grid">
        @forelse ($produk as $item)
            <div class="product-card"
                data-nama="{{ Str::lower($item->nama_produk) }}">

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

    <!-- Pesan ketika pencarian langsung tidak menemukan produk -->
    <div id="pencarian-kosong"
         class="pencarian-kosong"
         style="display: none;"
         role="status">

        <h3>Produk Tidak Ditemukan</h3>
        <p>Tidak ada produk yang sesuai dengan kata kunci pencarian.</p>
    </div>
</main>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputCari = document.getElementById('cari');
    const kartuProduk = document.querySelectorAll('.product-card');
    const jumlahHasil = document.getElementById('jumlah-hasil');
    const pencarianKosong = document.getElementById('pencarian-kosong');

    if (!inputCari) return;

    function filterProduk() {

        const kataKunci = inputCari.value.toLowerCase().trim();
        let jumlahDitemukan = 0;

        kartuProduk.forEach(function (kartu) {

            const namaProduk = kartu.dataset.nama || '';
            const cocok = namaProduk.includes(kataKunci);

            kartu.style.display = cocok ? '' : 'none';

            if (cocok) {
                jumlahDitemukan++;
            }

        });

        // Memperbarui jumlah produk yang ditemukan
        if (jumlahHasil) {
            jumlahHasil.textContent =
                'Ditemukan ' + jumlahDitemukan + ' produk';
        }

        // Menampilkan pesan jika tidak ditemukan produk
        if (pencarianKosong) {
            pencarianKosong.style.display =
                jumlahDitemukan === 0 && kartuProduk.length > 0
                    ? 'block'
                    : 'none';
        }

    }

    // Pencarian langsung saat mengetik
    inputCari.addEventListener('input', filterProduk);

    // Menyesuaikan tampilan saat halaman pertama dibuka
    filterProduk();

});
</script>
@endpush