@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-pengunjung.css') }}">
@endpush

@section('content')
<div class="container-fluid admin-pengunjung">

    {{-- Judul halaman --}}
    <div class="mb-4">
        <h2>Data Pengunjung</h2>
        <p class="text-muted">
            Informasi akun pengunjung yang terdaftar pada Sistem Informasi
            Direktori UMKM dan Katalog Produk Kota Ambon.
        </p>
    </div>

    {{-- Ringkasan jumlah pengunjung --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="text-muted mb-2">Total Pengunjung Terdaftar</h6>
            <h3 class="mb-0">{{ number_format($totalPengunjung, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Daftar pengunjung --}}
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Pencarian dan filter --}}
            <form
                id="pengunjungFilterForm"
                method="GET"
                action="{{ route('admin.pengunjung.index') }}"
                class="row g-3 mb-4"
                role="search"
            >

                <div class="col-md-5">
                    <label for="search" class="form-label">Pencarian</label>
                    <input
                        type="search"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Cari nama atau email pengunjung"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>

                <div class="col-md-4">
                    <label for="status" class="form-label">Status Verifikasi</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="verified" @selected(request('status') === 'verified')>
                            Terverifikasi
                        </option>
                        <option value="unverified" @selected(request('status') === 'unverified')>
                            Belum Terverifikasi
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('admin.pengunjung.index') }}"
                       id="resetPengunjung"
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>

            </form>

            {{-- Hasil pencarian dan pagination --}}
            <div id="pengunjungResults" aria-live="polite" aria-busy="false">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama Pengunjung</th>
                                <th>Email</th>
                                <th>Status Verifikasi</th>
                                <th>Tanggal Registrasi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pengunjung as $user)
                                <tr>
                                    <td>{{ $pengunjung->firstItem() + $loop->index }}</td>

                                    <td>{{ $user->name }}</td>

                                    <td>{{ $user->email }}</td>

                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-circle"></i>
                                                Belum Terverifikasi
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data pengunjung yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($pengunjung->hasPages())
                    <div class="mt-3">
                        {{ $pengunjung->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-pengunjung.js') }}" defer></script>
@endpush