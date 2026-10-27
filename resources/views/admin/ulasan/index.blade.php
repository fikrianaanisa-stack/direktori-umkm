@extends('layouts.admin')

@section('title', 'Moderasi Ulasan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-ulasan.css') }}">
@endpush

@section('content')
<div class="container-fluid admin-ulasan-page">

    <h2 class="mb-2">Moderasi Ulasan</h2>
    <p class="text-muted mb-4">
        Kelola rating dan ulasan yang dikirimkan pengunjung.
    </p>

    {{-- Ringkasan status --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Menunggu Persetujuan</div>
                    <h3 class="mb-0">{{ $jumlahPending }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Disetujui</div>
                    <h3 class="mb-0">{{ $jumlahApproved }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted">Ditolak</div>
                    <h3 class="mb-0">{{ $jumlahRejected }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter status --}}
    <div class="mb-3 d-flex gap-2 flex-wrap">
        @foreach ([
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'semua' => 'Semua'
        ] as $nilai => $label)

            <a href="{{ route('admin.ulasan.index', ['status' => $nilai]) }}"
               class="btn {{ $status === $nilai ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>

        @endforeach
    </div>

    {{-- Daftar ulasan --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Pengunjung</th>
                            <th>Objek Ulasan</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="ulasan-kolom-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ulasan as $item)
                            <tr>
                                <td>
                                    {{ $ulasan->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>{{ $item->user?->name ?? 'Pengguna tidak tersedia' }}</strong>
                                    <div class="small text-muted">
                                        {{ $item->user?->email ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    @if ($item->umkm_id)
                                        <span class="badge bg-info text-dark">UMKM</span>
                                        <div>{{ $item->umkm?->nama_umkm ?? 'UMKM tidak tersedia' }}</div>
                                    @elseif ($item->produk_id)
                                        <span class="badge bg-secondary">Produk</span>
                                        <div>{{ $item->produk?->nama_produk ?? 'Produk tidak tersedia' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="text-warning">
                                        {{ str_repeat('★', $item->rating) }}
                                    </span>
                                    <div class="small text-muted">
                                        {{ $item->rating }}/5
                                    </div>
                                </td>

                                <td class="ulasan-kolom-komentar">
                                    {{ $item->komentar }}
                                </td>

                                <td>
                                    @if ($item->status === 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif ($item->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->created_at?->format('d/m/Y H:i') }}
                                </td>
                                
                                <td>
                                    @if ($item->status === 'pending')
                                        <div class="d-flex flex-column gap-2">

                                            {{-- Tombol menyetujui ulasan --}}
                                            <form method="POST"
                                                action="{{ route('admin.ulasan.approve', $item->id) }}"
                                                data-confirm-action="approve">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-success btn-sm w-100 text-nowrap">
                                                    <i class="bi bi-check-circle"></i>
                                                    Setujui
                                                </button>
                                            </form>

                                            {{-- Tombol menolak ulasan --}}
                                            <form method="POST"
                                                action="{{ route('admin.ulasan.reject', $item->id) }}"
                                                data-confirm-action="reject">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-danger btn-sm w-100 text-nowrap">
                                                    <i class="bi bi-x-circle"></i>
                                                    Tolak
                                                </button>
                                            </form>

                                        </div>
                                    @else
                                        <span class="text-muted small">
                                            Sudah dimoderasi
                                        </span>

                                        @if ($item->reviewer)
                                            <div class="small mt-1">
                                                Oleh: {{ $item->reviewer->name }}
                                            </div>
                                        @endif

                                        @if ($item->reviewed_at)
                                            <div class="small text-muted">
                                                {{ $item->reviewed_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif

                                        @if ($item->status === 'rejected' && $item->alasan_penolakan)
                                            <div class="small text-danger mt-2">
                                                Alasan: {{ $item->alasan_penolakan }}
                                            </div>
                                        @endif
                                        
                                        {{-- Tombol hapus ulasan yang sudah dimoderasi --}}
                                        <form
                                            method="POST"
                                            action="{{ route('admin.ulasan.destroy', $item->id) }}"
                                            class="mt-2"
                                            data-confirm-action="delete">

                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status" value="{{ $status }}">

                                            <button type="submit" class="btn btn-danger btn-sm w-100 text-nowrap">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada ulasan pada kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $ulasan->links() }}
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-ulasan.js') }}"></script>
@endpush
