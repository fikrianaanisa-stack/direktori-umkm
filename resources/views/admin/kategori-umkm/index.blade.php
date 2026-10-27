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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 kategori-table">
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
                                        data-confirm-delete>
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
                            <td colspan="5" class="text-center py-4 text-muted">
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

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-kategori-umkm.js') }}"></script>
@endpush