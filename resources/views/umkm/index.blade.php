@extends('layouts.public')

@section('title', 'Direktori UMKM Kota Ambon')

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

        .umkm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 22px;
        }

        .umkm-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px;
        }

        .umkm-name {
            font-size: 19px;
            margin: 0 0 16px;
            color: #1e293b;
        }

        .umkm-info {
            font-size: 14px;
            color: #475569;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .umkm-info strong {
            color: #334155;
        }
        
        /* Tombol Detail UMKM */
        .detail-link {
            display: inline-block;
            margin-top: 8px;
            padding: 10px 14px;
            background: #0f766e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        /* Efek Hover Tombol */
        .detail-link:hover {
            background: #115e59;
        }

        .empty-message {
            padding: 30px;
            background: white;
            border-radius: 10px;
            text-align: center;
            color: #64748b;
        }
        
        /* Filter kategori UMKM */
        .filter-container {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
            padding: 18px 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .filter-container label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .filter-container select {
            flex: 1;
            min-width: 180px;
            max-width: 320px;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #1e293b;
            font-size: 14px;
        }

        .filter-container button {
            padding: 11px 20px;
            border: none;
            border-radius: 8px;
            background: #0f766e;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-container button:hover {
            background: #115e59;
        }
        
        /* Kotak pencarian UMKM */
        .filter-container input[type="search"] {
            flex: 1;
            min-width: 180px;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #1e293b;
            font-size: 14px;
        }

        .filter-container input[type="search"]:focus,
        .filter-container select:focus {
            outline: 2px solid #0f766e;
            outline-offset: 1px;
        }

        @media (max-width: 600px) {
            .filter-container input[type="search"],
            .filter-container select,
            .filter-container button {
                width: 100%;
                max-width: none;
            }
        }

        .jumlah-hasil {
            margin: 0 0 18px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        /* Pesan hasil pencarian kosong */
        .pencarian-kosong {
            padding: 35px 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            margin-top: 15px;
        }

        .pencarian-kosong h3 {
            margin: 0 0 10px;
            color: #334155;
            font-size: 20px;
        }

        .pencarian-kosong p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Tombol Reset Filter */
        .reset-filter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .reset-filter:hover {
            background: #e2e8f0;
        }

        @media (max-width: 600px) {
            .reset-filter {
                width: 100%;
            }
        }

        /* Tautan Beranda */
        .home-link {
            display: inline-block;
            margin-top: 16px;
            color: #0f766e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .home-link:hover {
            text-decoration: underline;
        }

    </style>
@endpush

@section('content')

<div class="container">
    <div class="page-title">
        <h2>Daftar UMKM</h2>
        <p>Jelajahi informasi pelaku usaha mikro, kecil, dan menengah.</p>
    </div>
    
    <!-- Filter Kategori UMKM -->
    <form action="{{ route('umkm.index') }}"
        method="GET"
        class="filter-container">

        <label for="kategori">Kategori UMKM:</label>

        <select name="kategori" id="kategori">
            <option value="">Semua Kategori</option>

            @foreach ($kategoriUmkm as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
        
        <!-- Pencarian nama UMKM -->
        <input
            type="search"
            name="cari"
            id="cari"
            value="{{ request('cari') }}"
            placeholder="Cari nama UMKM..."
            aria-label="Cari nama UMKM"
        >

        <button type="submit">
            Terapkan Filter
        </button>

        <a href="{{ route('umkm.index') }}" class="reset-filter">
            Reset Filter
        </a>
    </form>
    
    <p id="jumlah-hasil" class="jumlah-hasil" aria-live="polite">
        Menampilkan {{ $umkm->count() }} UMKM
    </p>

    <div class="umkm-grid">
       
        @forelse ($umkm as $item)
            <div class="umkm-card"
                data-nama="{{ Str::lower($item->nama_umkm) }}">

                <h3 class="umkm-name">
                    {{ $item->nama_umkm }}
                </h3>

                <div class="umkm-info">
                    <strong>Alamat:</strong>
                    {{ $item->alamat ?? '-' }}
                </div>

                <div class="umkm-info">
                    <strong>Telepon:</strong>
                    {{ $item->telepon ?? '-' }}
                </div>

                <a href="{{ route('umkm.show', $item->id) }}"
                   class="detail-link">
                    Lihat Detail
                </a>
            </div>
        @empty
            <div class="empty-message">
                Belum ada data UMKM yang tersedia.
            </div>
        @endforelse
    </div>
    
    <!-- Pesan ketika hasil pencarian kosong -->
    <div id="pencarian-kosong" class="pencarian-kosong"
        style="display: none;" role="status">
        <h3>UMKM Tidak Ditemukan</h3>
        <p>
            Tidak ada UMKM yang sesuai dengan kata kunci pencarian.
            Silakan coba nama usaha lainnya.
        </p>
    </div>

</div>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCari = document.getElementById('cari');
    const kartuUmkm = document.querySelectorAll('.umkm-card');
    const jumlahHasil = document.getElementById('jumlah-hasil');
    const pencarianKosong = document.getElementById('pencarian-kosong');

    if (!inputCari) return;

    function filterUmkm() {
        const kataKunci = inputCari.value.toLowerCase().trim();
        let jumlahDitemukan = 0;

        kartuUmkm.forEach(function (kartu) {
            const namaUmkm = kartu.dataset.nama || '';
            const cocok = namaUmkm.includes(kataKunci);

            kartu.style.display = cocok ? '' : 'none';

            if (cocok) {
                jumlahDitemukan++;
            }
        });

        if (jumlahHasil) {
            jumlahHasil.textContent =
                'Ditemukan ' + jumlahDitemukan + ' UMKM';
        }
        
        if (pencarianKosong) {
            pencarianKosong.style.display =
                jumlahDitemukan === 0 && kartuUmkm.length > 0
                    ? 'block'
                    : 'none';
        }

    }

    inputCari.addEventListener('input', filterUmkm);

    // Menyesuaikan hasil saat halaman pertama kali dibuka
    filterUmkm();
});
</script>

@endpush