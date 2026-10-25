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

    {{-- Notifikasi Berhasil --}}
    @if (session('success'))
        <div class="kategori-alert kategori-success">
            {{ session('success') }}
        </div>
    @endif

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
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori produk ini?');"
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

{{-- JavaScript Live Search AJAX Kategori Produk --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('kategoriSearch');
    const form = document.getElementById('kategoriSearchForm');
    const hasil = document.getElementById('hasil-kategori-produk');
    const tombolHapus = document.getElementById('kategoriSearchClear');
    const tombolPdfFilter = document.getElementById('pdf-hasil-kategori-produk');

    if (!input || !form || !hasil || !tombolHapus || !tombolPdfFilter) {
        return;
    }

    let timer;
    let controller;

    // Memperbarui link PDF sesuai kata kunci pencarian
    function perbaruiLinkPdf() {
        const url = new URL(tombolPdfFilter.href);

        url.searchParams.set('mode', 'filter');
        url.searchParams.set('cari', input.value.trim());

        tombolPdfFilter.href = url.toString();
    }

    // Menampilkan atau menyembunyikan tombol X
    function tampilkanTombolHapus() {
        tombolHapus.hidden = input.value === '';
    }

    // Mengambil hasil pencarian tanpa refresh halaman
    async function cariKategori(url) {
        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data kategori produk');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(html, 'text/html');
            const hasilBaru = dokumen.getElementById('hasil-kategori-produk');

            if (hasilBaru) {
                hasil.innerHTML = hasilBaru.innerHTML;
                window.history.replaceState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        }
    }

    // Pencarian otomatis setelah berhenti mengetik
    input.addEventListener('input', function () {
        perbaruiLinkPdf();
        tampilkanTombolHapus();

        clearTimeout(timer);

        timer = setTimeout(function () {
            const url = new URL(form.action);
            url.searchParams.set('cari', input.value);

            cariKategori(url.toString());
        }, 350);
    });

    // Menghapus pencarian
    tombolHapus.addEventListener('click', function () {
        input.value = '';
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.focus();
    });

    // Pagination tanpa refresh halaman
    hasil.addEventListener('click', function (event) {
        const link = event.target.closest('.pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        clearTimeout(timer);

        cariKategori(link.href);
    });

    // Pencarian menggunakan tombol Enter
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(timer);

        const url = new URL(form.action);
        url.searchParams.set('cari', input.value);

        cariKategori(url.toString());
    });

    // Pengaturan awal saat halaman dibuka
    perbaruiLinkPdf();
    tampilkanTombolHapus();
});
</script>
@endpush