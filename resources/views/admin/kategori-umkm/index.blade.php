@extends('layouts.admin')

@section('title', 'Kategori UMKM')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin-kategori-umkm.css') }}">

<div class="kategori-header">
    <div>
        <h1>Kategori UMKM</h1>
        <p>Pengelolaan kategori usaha UMKM Kota Ambon.</p>
    </div>
   
    <div class="kategori-header-actions">

        <a href="{{ route('admin.kategori-umkm.export.pdf') }}"
        target="_blank"
        class="kategori-btn kategori-btn-pdf">
            <i class="bi bi-file-earmark-pdf"></i>
            PDF Semua Data
        </a>

        <a href="{{ route('admin.kategori-umkm.export.pdf', ['mode' => 'filter', 'cari' => $cari ?? '']) }}"
        target="_blank"
        id="pdf-hasil-kategori"
        class="kategori-btn kategori-btn-filter">
            <i class="bi bi-file-earmark-pdf"></i>
            PDF Hasil Pencarian
        </a>

        <a href="{{ route('admin.kategori-umkm.create') }}"
        class="kategori-btn">
            <i class="bi bi-plus-lg"></i>
            Tambah Kategori
        </a>

    </div>

</div>

@if (session('success'))
    <div class="kategori-alert kategori-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="kategori-alert kategori-error">
        {{ session('error') }}
    </div>
@endif


<div class="kategori-card">

    {{-- Pencarian --}}
    <form method="GET"
          action="{{ route('admin.kategori-umkm.index') }}"
          class="kategori-search-form"
          id="form-cari-kategori">
        
    <div class="kategori-search">
        <i class="bi bi-search"></i>

        <input type="text"
            name="cari"
            id="cari-kategori"
            value="{{ $cari ?? '' }}"
            placeholder="Cari nama kategori atau deskripsi..."
            autocomplete="off">

        <button type="button"
                id="hapus-cari-kategori"
                class="kategori-search-clear"
                aria-label="Hapus pencarian"
                title="Hapus pencarian">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    </form>

    <div id="hasil-kategori">

        {{-- Tabel kategori --}}
        <div class="kategori-table-wrap">
            <table class="kategori-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah UMKM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kategoriUmkm as $kategori)
                        <tr>
                            <td>
                                {{ $kategoriUmkm->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $kategori->nama_kategori }}</td>

                            <td>{{ $kategori->deskripsi ?: '-' }}</td>

                            <td>{{ $kategori->umkm_count }}</td>

                            <td>
                                <div class="kategori-actions">
                                    <a href="{{ route('admin.kategori-umkm.edit', $kategori->id) }}">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.kategori-umkm.destroy', $kategori->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                @if ($kategori->umkm_count > 0)
                                                    disabled
                                                    title="Kategori masih digunakan"
                                                @endif>
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 25px;">
                                Belum ada kategori UMKM.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="kategori-pagination">
            {{ $kategoriUmkm->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cari-kategori');
    const form = document.getElementById('form-cari-kategori');
    const hasil = document.getElementById('hasil-kategori');
    const tombolHapus = document.getElementById('hapus-cari-kategori');
    const tombolPdfFilter = document.getElementById('pdf-hasil-kategori');

    function perbaruiLinkPdf() {
        const url = new URL(tombolPdfFilter.href);

        url.searchParams.set('mode', 'filter');
        url.searchParams.set('cari', input.value.trim());

        tombolPdfFilter.href = url.toString();
    }

    perbaruiLinkPdf();

    input.addEventListener('input', perbaruiLinkPdf);

    function tampilkanTombolHapus() {
        tombolHapus.style.display = input.value ? 'inline-flex' : 'none';
    }

    tampilkanTombolHapus();

    input.addEventListener('input', tampilkanTombolHapus);

    tombolHapus.addEventListener('click', function () {
        input.value = '';
        tampilkanTombolHapus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.focus();
    });

    let timer;
    let controller;

    // Mengambil hasil pencarian
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
                throw new Error('Gagal mengambil data');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(html, 'text/html');
            const hasilBaru = dokumen.getElementById('hasil-kategori');

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

    // Pencarian otomatis saat mengetik
    input.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            const url = new URL(form.action);

            url.searchParams.set('cari', input.value);

            cariKategori(url.toString());
        }, 350);
    });

    // Pagination tanpa refresh
    hasil.addEventListener('click', function (event) {
        const link = event.target.closest('.pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        clearTimeout(timer);

        cariKategori(link.href);
    });

    // Pencarian menggunakan Enter
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(timer);

        const url = new URL(form.action);

        url.searchParams.set('cari', input.value);

        cariKategori(url.toString());
    });
});
</script>

@endsection