@extends('layouts.admin')

@section('title', 'Data UMKM')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm.css') }}">
@endpush

@section('content')

    {{-- Header halaman --}}
    <div class="umkm-header">
        <div>
            <h1>Data UMKM</h1>
            <p>Kelola data UMKM Kota Ambon.</p>
        </div>

        <div class="umkm-header-actions">

            {{-- Ekspor seluruh data --}}
            <a href="{{ route('admin.umkm.export.pdf', ['mode' => 'semua']) }}"
               target="_blank"
               rel="noopener"
               class="umkm-btn umkm-btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF Semua Data
            </a>

            {{-- Ekspor hasil pencarian --}}
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

    {{-- Kontainer data UMKM --}}
    <div class="umkm-card">

        {{-- Pencarian UMKM --}}
        <div class="umkm-toolbar">
            <div class="umkm-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="cari-umkm"
                    placeholder="Cari nama UMKM, alamat, atau telepon..."
                    value="{{ request('cari') }}"
                    autocomplete="off"
                    aria-label="Cari data UMKM"
                >

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

        {{-- Hasil pencarian AJAX --}}
        <div id="hasil-umkm">

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
                                                >
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </span>
                                        @else
                                            <form
                                                action="{{ route('umkm.destroy', $item->id) }}"
                                                method="POST"
                                                class="form-hapus-umkm"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="aksi-hapus"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="umkm-table-empty">
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
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-umkm.js') }}"></script>
@endpush