@extends('layouts.admin')

@section('title', 'Data Wilayah')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-wilayah.css') }}">
@endpush

@section('content')

<div class="wilayah-header">
    <div>
        <h1>Data Wilayah</h1>
        <p>Pengelolaan kecamatan dan desa/kelurahan Kota Ambon.</p>
    </div>

    <div class="wilayah-header-actions">
        <a href="{{ route('admin.wilayah.exportPdf', ['mode' => 'semua']) }}"
           class="wilayah-btn-pdf"
           target="_blank"
           rel="noopener noreferrer">
            <i class="bi bi-file-earmark-pdf"></i>
            PDF Semua Data
        </a>

        <a href="{{ route('admin.wilayah.exportPdf', [
                'mode' => 'filter',
                'search' => request('search')
            ]) }}"
           class="wilayah-btn-pdf"
           id="pdf-hasil-wilayah"
           target="_blank"
           rel="noopener noreferrer">
            <i class="bi bi-file-earmark-pdf"></i>
            PDF Hasil Pencarian
        </a>

        <a href="{{ route('admin.wilayah.create') }}"
           class="wilayah-btn">
            <i class="bi bi-plus-lg"></i>
            Tambah Wilayah
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="wilayah-card">

    {{-- Pencarian wilayah --}}
    <div class="wilayah-search-wrapper">
        <div class="wilayah-search-box">
            <i class="bi bi-search"></i>

            <input
                type="search"
                id="cari-wilayah"
                aria-label="Cari wilayah"
                placeholder="Cari kecamatan, desa, atau jenis wilayah..."
                value="{{ request('search') }}"
                autocomplete="off"
            >

            <button
                type="button"
                id="hapus-cari-wilayah"
                aria-label="Hapus pencarian"
                title="Hapus pencarian"
                hidden
            >
                &times;
            </button>
        </div>
    </div>

    {{-- Hasil pencarian AJAX --}}
    <div id="hasil-wilayah">

        <div class="wilayah-table-wrap">
            <table class="wilayah-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kecamatan</th>
                        <th>Desa/Kelurahan</th>
                        <th>Jenis Wilayah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($wilayah as $item)
                        <tr>
                            <td>{{ $wilayah->firstItem() + $loop->index }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td>{{ $item->kelurahan_desa }}</td>
                            <td>{{ $item->jenis_wilayah ?? '-' }}</td>
                            <td>
                                <div class="wilayah-actions">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.wilayah.edit', $item->id) }}"
                                       class="btn-aksi-edit">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    {{-- Hapus --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.wilayah.destroy', $item->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus wilayah ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-aksi-hapus"
                                            @if ($item->umkm()->exists())
                                                disabled
                                                title="Wilayah masih digunakan oleh UMKM"
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
                        <tr>
                            <td colspan="5" class="wilayah-empty">
                                Belum ada data wilayah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="wilayah-pagination">
            {{ $wilayah->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCari = document.getElementById('cari-wilayah');
    const tombolPdfHasil = document.getElementById('pdf-hasil-wilayah');
    const hasilWilayah = document.getElementById('hasil-wilayah');
    const tombolHapus = document.getElementById('hapus-cari-wilayah');

    if (!inputCari || !tombolPdfHasil || !hasilWilayah || !tombolHapus) {
        return;
    }

    let timerPencarian;
    let permintaanAktif;
    let nomorPermintaan = 0;

    // Memperbarui link PDF hasil pencarian
    function perbaruiLinkPdf() {
        const kataKunci = inputCari.value.trim();
        const url = new URL(tombolPdfHasil.href);

        url.searchParams.set('mode', 'filter');

        if (kataKunci !== '') {
            url.searchParams.set('search', kataKunci);
        } else {
            url.searchParams.delete('search');
        }

        tombolPdfHasil.href = url.toString();
        tombolPdfHasil.classList.toggle('disabled', kataKunci === '');
        tombolPdfHasil.setAttribute(
            'aria-disabled',
            String(kataKunci === '')
        );
    }

    // Menampilkan tombol hapus pencarian
    function tampilkanTombolHapus() {
        tombolHapus.hidden = inputCari.value.trim() === '';
    }

    // Membuat URL pencarian
    function buatUrlPencarian() {
        const url = new URL(window.location.href);
        const kataKunci = inputCari.value.trim();

        if (kataKunci !== '') {
            url.searchParams.set('search', kataKunci);
        } else {
            url.searchParams.delete('search');
        }

        url.searchParams.delete('page');

        return url.toString();
    }

    // Memuat data tanpa refresh halaman
    async function muatData(url) {
        if (permintaanAktif) {
            permintaanAktif.abort();
        }

        const controller = new AbortController();
        permintaanAktif = controller;
        const nomorSaatIni = ++nomorPermintaan;

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data wilayah.');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const hasilBaru = dokumen.getElementById('hasil-wilayah');

            if (!hasilBaru) {
                throw new Error('Hasil pencarian tidak ditemukan.');
            }

            if (nomorSaatIni !== nomorPermintaan) {
                return;
            }

            hasilWilayah.innerHTML = hasilBaru.innerHTML;
            window.history.replaceState({}, '', url);

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                alert('Pencarian gagal. Silakan coba lagi.');
            }
        } finally {
            if (permintaanAktif === controller) {
                permintaanAktif = null;
            }
        }
    }

    // Pencarian otomatis saat mengetik
    inputCari.addEventListener('input', function () {
        tampilkanTombolHapus();
        perbaruiLinkPdf();

        clearTimeout(timerPencarian);

        timerPencarian = setTimeout(function () {
            muatData(buatUrlPencarian());
        }, 350);
    });

    // Menghapus kata kunci pencarian
    tombolHapus.addEventListener('click', function () {
        inputCari.value = '';

        inputCari.dispatchEvent(
            new Event('input', { bubbles: true })
        );

        inputCari.focus();
    });

    // Pagination tanpa refresh halaman
    hasilWilayah.addEventListener('click', function (event) {
        const link = event.target.closest('.wilayah-pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        clearTimeout(timerPencarian);

        muatData(link.href);
    });

    // Mencegah PDF hasil pencarian tanpa kata kunci
    tombolPdfHasil.addEventListener('click', function (event) {
        if (inputCari.value.trim() === '') {
            event.preventDefault();
        }
    });

    tampilkanTombolHapus();
    perbaruiLinkPdf();
});
</script>
@endpush
