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
                                        data-confirm-delete
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
    <script src="{{ asset('js/admin-wilayah.js') }}"></script>
@endpush