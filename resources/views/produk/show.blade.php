
@extends('layouts.public')

@section('title', $produk->nama_produk . ' - Direktori UMKM Kota Ambon')

@section('content')


<div class="produk-container">

    {{-- Navigasi Breadcrumb --}}
    <nav class="produk-breadcrumb" aria-label="Navigasi halaman">
        <a href="{{ route('home') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route('produk.index') }}">Katalog Produk</a>
        <span>/</span>
        <span aria-current="page">{{ $produk->nama_produk }}</span>
    </nav>

    <div class="produk-card">

        <div>
            @if ($produk->foto)
                <img src="{{ asset('storage/' . $produk->foto) }}"
                     alt="{{ $produk->nama_produk }}"
                     class="produk-foto">
            @else
                <div class="produk-placeholder">
                    Foto produk belum tersedia
                </div>
            @endif
        </div>

        <div class="produk-info">

            <h1>{{ $produk->nama_produk }}</h1>

            <p class="produk-kategori">
                Kategori:
                {{ $produk->kategoriProduk->nama_kategori ?? '-' }}
            </p>
            
            {{-- Daftar Pilihan Harga Produk --}}
            <div class="produk-harga">

                @forelse ($produk->hargaProduk->sortBy('harga_min') as $pilihan)

                    <div class="produk-harga-item">

                        {{-- Nilai Harga Produk --}}
                        <div class="produk-harga-nilai">
                            Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                            @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                            @endif
                        </div>

                        {{-- Jumlah dan Satuan Harga --}}
                        <div class="produk-harga-satuan">
                            @if ($pilihan->satuan)
                                Untuk
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                {{ $pilihan->satuan }}
                            @else
                                Jumlah:
                                {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                            @endif
                        </div>

                    </div>

                @empty

                    <span class="produk-harga-kosong">
                        Hubungi UMKM untuk informasi harga
                    </span>

                @endforelse

                {{-- Catatan Harga --}}
                <p class="produk-harga-catatan">
                    Harga dapat berubah sewaktu-waktu.
                </p>

            </div>

            {{-- Deskripsi Produk --}}
            <section class="produk-section">
                <h3>Deskripsi Produk</h3>

                <p class="produk-deskripsi">
                    {{ $produk->deskripsi ?: 'Deskripsi produk belum tersedia.' }}
                </p>
            </section>
            
            {{-- Informasi UMKM Penyedia --}}
            <section class="produk-section">
                <h3>UMKM Penyedia</h3>

                @if ($produk->umkm)
                    <p class="produk-umkm-nama">
                        {{ $produk->umkm->nama_umkm }}
                    </p>

                    <a href="{{ route('umkm.show', $produk->umkm->id) }}"
                    class="produk-link">
                        Lihat Profil UMKM →
                    </a>
                @else
                    <p class="produk-umkm-nama produk-umkm-kosong">
                        Informasi UMKM belum tersedia.
                    </p>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
@push('styles')
 <style>
        .produk-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* Kartu Detail Produk */
        .produk-card {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 30px;
            padding: 25px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        /* Mencegah konten melebar keluar dari kolom */
        .produk-card > div {
            min-width: 0;
        }

        /* Menyesuaikan teks informasi produk */
        .produk-info {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        /* Foto Detail Produk */
        .produk-foto {
            display: block;
            width: 100%;
            height: 350px;
            max-height: 350px;
            object-fit: contain;
            object-position: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }

        .produk-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 350px;
            background: #f1f5f9;
            color: #94a3b8;
            border-radius: 10px;
        }

        .produk-info h1 {
            margin-top: 0;
            font-size: 26px;
        }

        .produk-kategori {
            color: #64748b;
            font-size: 14px;
        }

        
        /* Daftar Harga Produk */
        .produk-harga {
            margin: 20px 0;
            color: #0f766e;
            font-size: 16px;
            font-weight: normal;
        }

        /* Nilai Harga pada Setiap Pilihan */
        .produk-harga-nilai {
            color: #0f766e;
            font-size: 20px;
            font-weight: 700;
        }

        /* Kartu Pilihan Harga */
        .produk-harga-item {
            padding: 14px 16px;
            margin-bottom: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.2s ease;
        }

        /* Efek saat kursor diarahkan ke kartu harga */
        .produk-harga-item:hover {
            border-color: #5eead4;
        }

        /* Keterangan Jumlah dan Satuan Harga */
        .produk-harga-satuan {
            margin-top: 6px;
            color: #64748b;
            font-size: 14px;
        }

        /* Informasi Jika Harga Belum Tersedia */
        .produk-harga-kosong {
            color: #64748b;
            font-size: 15px;
        }

        /* Catatan Perubahan Harga */
        .produk-harga-catatan {
            margin-top: 10px;
            color: #64748b;
            font-size: 12px;
            font-weight: normal;
        }

        .produk-deskripsi {
            line-height: 1.7;
            color: #475569;
            white-space: pre-line;
        }

        /* Tombol Lihat Profil UMKM */
        .produk-link {
            display: inline-block;
            margin-top: 18px;
            padding: 11px 16px;
            background: #0f766e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .produk-link:hover {
            background: #115e59;
            color: #ffffff;
        }
        
        /* Tampilan Tablet dan HP */
        @media (max-width: 768px) {
            .produk-card {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 20px;
            }

            .produk-foto {
                height: 300px;
                max-height: 300px;
            }

            .produk-placeholder {
                min-height: 300px;
            }

            .produk-info h1 {
                font-size: 24px;
            }
        }

        /* Tampilan HP dengan Layar Kecil */
        @media (max-width: 480px) {
            .produk-card {
                gap: 20px;
                padding: 16px;
            }

            .produk-foto {
                height: 240px;
                max-height: 240px;
            }

            .produk-placeholder {
                min-height: 240px;
            }

            .produk-info h1 {
                font-size: 22px;
            }

            .produk-harga-nilai {
                font-size: 18px;
            }

            .produk-harga-item {
                padding: 12px;
            }

            .produk-link {
                max-width: 100%;
                text-align: center;
            }
        }
        
        /* Kartu Pilihan Harga */
        .produk-harga-item {
            padding: 14px 16px;
            margin-bottom: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: border-color 0.2s ease;
        }

        /* Efek Hover Kartu Harga */
        .produk-harga-item:hover {
            border-color: #5eead4;
        }

        /* Keterangan Jumlah dan Satuan */
        .produk-harga-satuan {
            margin-top: 6px;
            color: #64748b;
            font-size: 14px;
        }

        /* Informasi Harga Belum Tersedia */
        .produk-harga-kosong {
            color: #64748b;
            font-size: 15px;
        }

        /* Catatan Perubahan Harga */
        .produk-harga-catatan {
            margin-top: 10px;
            color: #64748b;
            font-size: 12px;
            font-weight: normal;
        }
        
        /* Bagian Informasi Produk */
        .produk-section {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        /* Judul Bagian Informasi */
        .produk-section h3 {
            margin: 0 0 14px;
            font-size: 18px;
            font-weight: 700;
            color: #0f766e;
        }

        /* Nama UMKM Penyedia */
        .produk-umkm-nama {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Keterangan Jika UMKM Tidak Tersedia */
        .produk-umkm-kosong {
            color: #64748b;
            font-weight: 400;
        }

        
        /* Navigasi Breadcrumb Detail Produk */
        .produk-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 14px;
            color: #64748b;
        }

        /* Tautan Breadcrumb */
        .produk-breadcrumb a {
            color: #0f766e;
            text-decoration: none;
        }

        /* Efek Hover Breadcrumb */
        .produk-breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Nama Produk pada Breadcrumb */
        .produk-breadcrumb [aria-current="page"] {
            overflow-wrap: anywhere;
        }
    </style>
@endpush

