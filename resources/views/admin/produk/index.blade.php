@extends('layouts.admin')

@section('title', 'Data Produk')

{{-- CSS Data Produk --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-produk.css') }}">
@endpush

@section('content')

    {{-- Header Halaman --}}
    <div class="produk-header">
        <div>
            <h1>Data Produk</h1>
            <p>Kelola katalog produk UMKM Kota Ambon.</p>
        </div>

        {{-- Tombol Header --}}
        <div class="produk-header-actions">

            {{-- PDF Semua Data --}}
            <a href="{{ route('admin.produk.export.pdf', ['mode' => 'semua']) }}"
               target="_blank"
               rel="noopener"
               class="produk-btn produk-btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Semua Data
            </a>

            {{-- PDF Hasil Pencarian --}}
            <a href="{{ route('admin.produk.export.pdf', ['mode' => 'filter']) }}"
               id="pdf-hasil-produk"
               target="_blank"
               rel="noopener"
               class="produk-btn produk-btn-filter">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Hasil Pencarian
            </a>

            {{-- Tambah Produk --}}
            <a href="{{ route('produk.create') }}"
               class="produk-btn produk-btn-tambah">
                <i class="bi bi-plus-lg"></i>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- Kartu Data Produk --}}
    <div class="produk-table-wrapper">

        {{-- Form Live Search --}}
        <form action="{{ route('admin.produk.index') }}"
              method="GET"
              id="form-cari-produk"
              class="produk-search-form">

            <div class="produk-search">
                <i class="bi bi-search"></i>

                <input type="text"
                       name="cari"
                       id="cari-produk"
                       value="{{ request('cari') }}"
                       placeholder="Cari nama produk, UMKM, atau kategori..."
                       autocomplete="off"
                       aria-label="Cari data produk">

                {{-- Tombol Hapus Pencarian --}}
                <button type="button"
                        id="hapus-cari-produk"
                        class="produk-search-clear"
                        aria-label="Hapus pencarian"
                        title="Hapus pencarian">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </form>

        {{-- Area Hasil Pencarian AJAX --}}
        <div id="hasil-produk">

            {{-- Tabel Data Produk --}}
            <table class="produk-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>UMKM</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($produk as $item)
                        <tr>
                            <td>{{ $produk->firstItem() + $loop->index }}</td>

                            {{-- Foto Produk --}}
                            <td>
                                @php
                                    $fotoUtama = $item->fotoProduk->first();
                                @endphp

                                @if ($fotoUtama)
                                    <img src="{{ asset('storage/' . $fotoUtama->foto) }}"
                                        alt="{{ $item->nama_produk }}"
                                        class="produk-foto">
                                @else
                                    <span class="produk-teks-kosong">-</span>
                                @endif
                            </td>
                            
                            <td>
                                <a href="{{ route('admin.produk.show', $item->id) }}"
                                class="produk-nama-link">
                                    {{ $item->nama_produk }}
                                </a>
                            </td>
                            <td>{{ $item->umkm?->nama_umkm ?? '-' }}</td>
                            <td>{{ $item->kategoriProduk?->nama_kategori ?? '-' }}</td>

                            {{-- Harga Produk --}}
                            <td>
                                @php
                                    // Mengambil pilihan harga produk
                                    $pilihanHarga = $item->hargaProduk;
                                    $jumlahPilihan = $pilihanHarga->count();
                                    $hargaTerendah = $pilihanHarga->min('harga_min');
                                @endphp

                                @if ($jumlahPilihan === 0)
                                    <span class="produk-teks-muted">
                                        Hubungi UMKM
                                    </span>

                                @elseif ($jumlahPilihan === 1)
                                    @php
                                        $pilihan = $pilihanHarga->first();
                                    @endphp

                                    <strong>
                                        Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                                        @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                            – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                                        @endif
                                    </strong>

                                    @if ($pilihan->satuan)
                                        <div class="produk-harga-info">
                                            Untuk
                                            {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                            {{ $pilihan->satuan }}
                                        </div>
                                    @endif

                                @else
                                    <strong class="produk-harga-mulai">
                                        Mulai Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                                    </strong>

                                    <div class="produk-harga-info">
                                        {{ $jumlahPilihan }} pilihan harga
                                    </div>
                                @endif
                            </td>

                            {{-- Tombol Aksi --}}
                            <td>
                                <div class="produk-aksi">

                                    {{-- Edit Produk --}}
                                    <a href="{{ route('produk.edit', $item->id) }}"
                                       class="produk-aksi-edit">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    {{-- Hapus Produk --}}
                                    <form action="{{ route('produk.destroy', $item->id) }}"
                                    method="POST"
                                    data-confirm-delete>
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="produk-aksi-hapus">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="produk-empty">
                                Belum ada produk yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

           {{-- Pagination --}}
            <div class="produk-pagination">
                {{ $produk->links('pagination::bootstrap-5') }}
            </div>

        </div>
        {{-- Akhir Area Hasil Pencarian AJAX --}}

    </div>
    {{-- Akhir Kartu Data Produk --}}

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-produk.js') }}"></script>
@endpush
