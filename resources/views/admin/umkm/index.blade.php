@extends('layouts.admin')

@section('title', 'Data UMKM')

{{-- CSS Data UMKM --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm.css') }}">
@endpush

@section('content')

    {{-- Header Halaman --}}
    <div class="umkm-header">
        <div>
            <h1>Data UMKM</h1>
            <p>Kelola data UMKM Kota Ambon.</p>
        </div>

        {{-- Tombol Aksi Header --}}
        <div class="umkm-header-actions">

            {{-- PDF Semua Data --}}
            <a href="{{ route('admin.umkm.export.pdf', ['mode' => 'semua']) }}"
               target="_blank"
               rel="noopener"
               class="umkm-btn umkm-btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Semua Data
            </a>

            {{-- PDF Hasil Pencarian --}}
            <a href="{{ route('admin.umkm.export.pdf', [
                    'mode' => 'filter',
                    'cari' => request('cari')
                ]) }}"
               id="pdf-hasil-pencarian"
               target="_blank"
               rel="noopener"
               class="umkm-btn umkm-btn-filter">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Hasil Pencarian
            </a>

            {{-- Tambah UMKM --}}
            <a href="{{ route('umkm.create') }}"
               class="umkm-btn">
                <i class="bi bi-plus-lg"></i>
                Tambah UMKM
            </a>

        </div>
    </div>

    {{-- Notifikasi Berhasil --}}
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Kontainer Data UMKM --}}
    <div class="umkm-card">

        {{-- Live Search UMKM --}}
        <div class="umkm-toolbar">
            <div class="umkm-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="cari-umkm"
                    placeholder="Cari nama UMKM, alamat, atau telepon..."
                    value="{{ request('cari') }}"
                    autocomplete="off"
                >

                {{-- Tombol Hapus Pencarian --}}
                <button
                    type="button"
                    id="hapus-cari-umkm"
                    class="umkm-search-clear"
                    aria-label="Hapus pencarian"
                    title="Hapus pencarian"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        {{-- Area Hasil Pencarian AJAX --}}
        <div id="hasil-umkm">

            {{-- Tabel Data UMKM --}}
            <div class="table-responsive">
                <table class="umkm-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama UMKM</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($umkm as $item)
                            <tr>
                                <td>
                                    {{ $umkm->firstItem() + $loop->index }}
                                </td>

                                <td>{{ $item->nama_umkm }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->telepon ?? '-' }}</td>

                                <td>
                                    {{-- Tombol Aksi UMKM --}}
                                    <div class="aksi">

                                        {{-- Detail UMKM --}}
                                        <a href="{{ route('admin.umkm.show', $item->id) }}"
                                           class="aksi-detail">
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>

                                        {{-- Edit UMKM --}}
                                        <a href="{{ route('umkm.edit', $item->id) }}"
                                           class="aksi-edit">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>
                                        
                                        {{-- Hapus UMKM --}}
                                        @if ($item->produk()->exists())
                                            <span title="UMKM masih memiliki produk">
                                                <button
                                                    type="button"
                                                    class="aksi-hapus"
                                                    disabled
                                                    style="background-color: #adb5bd; border-color: #adb5bd; color: #fff; cursor: not-allowed; opacity: 0.65;"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </span>
                                        @else
                                            <form
                                                action="{{ route('umkm.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus UMKM ini?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="aksi-hapus">
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Tampilan Jika Data Kosong --}}
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px;">
                                    Belum ada data UMKM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="umkm-pagination">
                {{ $umkm->links('pagination::bootstrap-5') }}
            </div>

        </div>
        {{-- Akhir Area Hasil Pencarian AJAX --}}

    </div>
    {{-- Akhir Kontainer Data UMKM --}}

@endsection

{{-- JavaScript Live Search AJAX Data UMKM --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputCari = document.getElementById('cari-umkm');
            const hasilUmkm = document.getElementById('hasil-umkm');
            const tombolPdfFilter = document.getElementById('pdf-hasil-pencarian');
            const tombolHapus = document.getElementById('hapus-cari-umkm');

            // Menampilkan atau menyembunyikan tombol X
            function tampilkanTombolHapus() {
                tombolHapus.style.display = inputCari.value ? 'inline-flex' : 'none';
            }

            tampilkanTombolHapus();

            inputCari.addEventListener('input', tampilkanTombolHapus);

            // Menghapus kata kunci pencarian
            tombolHapus.addEventListener('click', function () {
                inputCari.value = '';
                inputCari.dispatchEvent(new Event('input', { bubbles: true }));
                inputCari.focus();
            });

            // Memperbarui kata kunci ketika PDF Hasil Pencarian diklik
            tombolPdfFilter.addEventListener('click', function (event) {
                const kataKunci = inputCari.value.trim();

                if (kataKunci === '') {
                    event.preventDefault();
                    alert('Silakan cari data UMKM terlebih dahulu.');
                    return;
                }

                const url = new URL(tombolPdfFilter.href);

                url.searchParams.set('mode', 'filter');
                url.searchParams.set('cari', kataKunci);

                tombolPdfFilter.href = url.toString();
            });

            let timerPencarian;
            let permintaanAktif;

            // Mengambil data dari Laravel tanpa refresh halaman
            async function muatData(url) {

                // Membatalkan permintaan sebelumnya
                if (permintaanAktif) {
                    permintaanAktif.abort();
                }

                permintaanAktif = new AbortController();

                try {
                    const response = await fetch(url, {
                        signal: permintaanAktif.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data UMKM.');
                    }

                    const html = await response.text();

                    // Mengambil tabel dan pagination dari hasil Laravel
                    const dokumen = new DOMParser().parseFromString(html, 'text/html');
                    const hasilBaru = dokumen.getElementById('hasil-umkm');

                    if (!hasilBaru) {
                        throw new Error('Hasil pencarian tidak ditemukan.');
                    }

                    // Memperbarui tabel tanpa refresh seluruh halaman
                    hasilUmkm.innerHTML = hasilBaru.innerHTML;

                    // Memperbarui URL browser
                    window.history.replaceState({}, '', url);

                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error(error);
                        alert('Pencarian gagal. Silakan coba lagi.');
                    }
                }
            }

            // Live Search otomatis saat pengguna mengetik
            inputCari.addEventListener('input', function () {
                clearTimeout(timerPencarian);

                timerPencarian = setTimeout(function () {
                    const url = new URL(window.location.href);
                    const kataKunci = inputCari.value.trim();

                    if (kataKunci !== '') {
                        url.searchParams.set('cari', kataKunci);
                    } else {
                        url.searchParams.delete('cari');
                    }

                    // Kembali ke halaman pertama ketika mencari
                    url.searchParams.delete('page');

                    muatData(url.toString());
                }, 350);
            });

            // Pagination tanpa refresh halaman
            hasilUmkm.addEventListener('click', function (event) {
                const link = event.target.closest('.umkm-pagination a');

                if (!link) {
                    return;
                }

                event.preventDefault();
                clearTimeout(timerPencarian);

                muatData(link.href);
            });
        });
    </script>
@endpush