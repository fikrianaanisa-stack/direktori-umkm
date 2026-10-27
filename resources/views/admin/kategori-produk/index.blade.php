@extends('layouts.admin')

@section('title', 'Kategori Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-kategori-produk.css') }}">
@endpush

@section('content')

    {{-- Header Halaman --}}
    <div class="kategori-header">
        <div>
            <h1>Kategori Produk</h1>
            <p>Pengelolaan kategori produk dan layanan UMKM Kota Ambon.</p>
        </div>

        {{-- Tombol Aksi Header --}}
        <div class="kategori-header-actions">

            {{-- PDF Semua Data --}}
            <a href="{{ route('admin.kategori-produk.export.pdf', ['mode' => 'semua']) }}"
               class="kategori-btn kategori-btn-pdf"
               target="_blank">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Semua Data
            </a>

            {{-- PDF Hasil Pencarian --}}
            <a href="{{ route('admin.kategori-produk.export.pdf', [
                    'mode' => 'filter',
                    'cari' => $cari
                ]) }}"
               id="pdf-hasil-kategori-produk"
               class="kategori-btn kategori-btn-filter"
               target="_blank">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Hasil Pencarian
            </a>

            {{-- Tambah Kategori --}}
            <a href="{{ route('admin.kategori-produk.create') }}"
               class="kategori-btn">
                <i class="bi bi-plus-lg"></i>
                Tambah Kategori
            </a>

        </div>
    </div>

    {{-- Notifikasi Kesalahan --}}
    @if (session('error'))
        <div class="kategori-alert kategori-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kontainer Data Kategori Produk --}}
    <div class="kategori-card">

        {{-- Form Pencarian Kategori Produk --}}
        <div class="kategori-search-wrapper">
            <form
                id="kategoriSearchForm"
                action="{{ route('admin.kategori-produk.index') }}"
                method="GET"
                class="kategori-search-box"
                role="search"
            >
                <i class="bi bi-search"></i>

                <input
                    type="search"
                    id="kategoriSearch"
                    name="cari"
                    value="{{ $cari }}"
                    placeholder="Cari nama atau deskripsi kategori..."
                    autocomplete="off"
                >

                {{-- Tombol Hapus Pencarian --}}
                <button
                    type="button"
                    id="kategoriSearchClear"
                    aria-label="Hapus pencarian"
                    @if ($cari === '') hidden @endif
                >
                    &times;
                </button>
            </form>
        </div>

        {{-- Area Hasil Pencarian AJAX --}}
        <div id="hasil-kategori-produk">

            {{-- Tabel Kategori Produk --}}
            <div class="kategori-table-wrap">
                <table class="kategori-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($kategoriProduk as $kategori)
                            <tr>
                                <td>
                                    {{ $kategoriProduk->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $kategori->nama_kategori }}
                                </td>

                                <td>
                                    {{ $kategori->deskripsi ?: '-' }}
                                </td>

                                <td>
                                    {{ $kategori->produk_count }}
                                </td>

                                <td>
                                    {{-- Tombol Edit dan Hapus --}}
                                    <div class="kategori-actions">

                                        {{-- Edit Kategori --}}
                                        <a href="{{ route('admin.kategori-produk.edit', $kategori->id) }}"
                                           class="kategori-action-edit">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        {{-- Hapus Kategori --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.kategori-produk.destroy', $kategori->id) }}"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="kategori-action-delete"
                                                @if ($kategori->produk_count > 0)
                                                    disabled
                                                    title="Kategori masih digunakan oleh produk"
                                                @endif
                                            >
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Tampilan Jika Data Kosong --}}
                            <tr>
                                <td colspan="5" class="kategori-empty">
                                    Belum ada kategori produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="kategori-pagination">
                {{ $kategoriProduk->links('pagination::bootstrap-5') }}
            </div>

        </div>
        {{-- Akhir Area Hasil Pencarian AJAX --}}

    </div>
    {{-- Akhir Kontainer Data Kategori Produk --}}

@endsection
{{-- JavaScript Kategori Produk --}}
@push('scripts')
    <script src="{{ asset('js/admin-kategori-produk.js') }}"></script>
@endpush