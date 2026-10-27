@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-activity-logs.css') }}">
@endpush

@section('content')
<div class="activity-page" id="activity-page">

    <div class="activity-header">
        <div>
            <h1>Riwayat Aktivitas Admin</h1>
            <p>Memantau aktivitas pengelolaan data dalam sistem Direktori UMKM Kota Ambon.</p>
        </div>
    </div>

    <div class="activity-card">
        <div class="activity-table-header">
            <form method="GET"
            action="{{ route('admin.activity-logs.index') }}"
            class="activity-filters"
            id="activity-filter-form">

            <div class="activity-field activity-search">
                <label for="search">Pencarian</label>

                <div class="activity-search-wrapper">
                    <input type="text" id="search" name="search"
                        value="{{ request('search') }}"
                        placeholder="Nama Admin atau deskripsi aktivitas">

                    <button type="button"
                            id="hapus-search"
                            class="activity-search-clear"
                            aria-label="Hapus pencarian">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="activity-field">
                <label for="admin">Nama Admin</label>
                <select name="admin" id="admin">
                    <option value="">Semua Admin</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin }}" @selected(request('admin') === $admin)>
                            {{ $admin }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="activity-field">
                <label for="module">Modul</label>
                <select name="module" id="module">
                    <option value="">Semua Modul</option>
                    @foreach ([
                        'umkm' => 'UMKM',
                        'produk' => 'Produk',
                        'kategori_umkm' => 'Kategori UMKM',
                        'kategori_produk' => 'Kategori Produk',
                        'wilayah' => 'Wilayah',
                        'ulasan' => 'Ulasan',
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(request('module') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="activity-field">
                <label for="action">Aktivitas</label>
                <select name="action" id="action">
                    <option value="">Semua Aktivitas</option>
                    <option value="create" @selected(request('action') === 'create')>Tambah</option>
                    <option value="update" @selected(request('action') === 'update')>Edit</option>
                    <option value="delete" @selected(request('action') === 'delete')>Hapus</option>
                    <option value="approve" @selected(request('action') === 'approve')>Setujui</option>
                    <option value="reject" @selected(request('action') === 'reject')>Tolak</option>
                </select>
            </div>

            <div class="activity-field">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal"
                       value="{{ request('tanggal_awal') }}">
            </div>

            <div class="activity-field">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                       value="{{ request('tanggal_akhir') }}">
            </div>

            <div class="activity-field">
                <label for="sort">Urutkan</label>
                <select name="sort" id="sort">
                    <option value="terbaru" @selected(request('sort', 'terbaru') === 'terbaru')>Terbaru</option>
                    <option value="terlama" @selected(request('sort') === 'terlama')>Terlama</option>
                    <option value="nama_asc" @selected(request('sort') === 'nama_asc')>Nama Admin A–Z</option>
                    <option value="nama_desc" @selected(request('sort') === 'nama_desc')>Nama Admin Z–A</option>
                </select>
            </div>

            <div class="activity-filter-actions">
                <button type="submit" class="activity-btn-primary">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="activity-btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="activity-card">
        <div id="hasil-aktivitas">
            <div class="activity-table-header">
                <h2>Daftar Aktivitas</h2>
            <span>Total {{ $logs->total() }} aktivitas</span>
        </div>

        <div class="activity-table-wrapper">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Waktu</th>
                        <th>Nama Admin</th>
                        <th>Modul</th>
                        <th>Aktivitas</th>
                        <th>Deskripsi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $moduleLabels = [
                                'umkm' => 'UMKM',
                                'produk' => 'Produk',
                                'kategori_umkm' => 'Kategori UMKM',
                                'kategori_produk' => 'Kategori Produk',
                                'wilayah' => 'Wilayah',
                                'ulasan' => 'Ulasan',
                            ];

                            $actionLabels = [
                                'create' => 'Tambah',
                                'update' => 'Edit',
                                'delete' => 'Hapus',
                                'approve' => 'Setujui',
                                'reject' => 'Tolak',
                            ];

                            $oldValues = is_array($log->old_values)
                                ? $log->old_values
                                : (json_decode($log->old_values ?? 'null', true) ?: []);

                            $newValues = is_array($log->new_values)
                                ? $log->new_values
                                : (json_decode($log->new_values ?? 'null', true) ?: []);

                            $sensitiveKeys = ['password', 'remember_token', 'token', 'secret', 'api_key'];

                            $formatValues = function ($values) use ($sensitiveKeys) {
                                return collect($values)->map(function ($value, $key) use ($sensitiveKeys) {
                                    if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                                        return '********';
                                    }

                                    if (is_array($value) || is_object($value)) {
                                        return json_encode($value, JSON_UNESCAPED_UNICODE);
                                    }

                                    return $value === null ? '—' : (string) $value;
                                })->all();
                            };

                            $oldValues = $formatValues($oldValues);
                            $newValues = $formatValues($newValues);
                        @endphp

                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td class="activity-date">
                                {{ $log->created_at?->format('d/m/Y') }}
                                <small>{{ $log->created_at?->format('H:i:s') }}</small>
                            </td>
                            <td>{{ $log->nama_pengguna ?? 'Tidak diketahui' }}</td>
                            <td>{{ $moduleLabels[$log->module] ?? $log->module }}</td>
                            <td>
                                <span class="activity-badge activity-{{ $log->action }}">
                                    {{ $actionLabels[$log->action] ?? $log->action }}
                                </span>
                            </td>
                            <td>{{ $log->description ?? '—' }}</td>
                            <td>
                                <button type="button"
                                        class="activity-btn-detail"
                                        data-detail-id="activity-detail-{{ $log->id }}"
                                        aria-expanded="false">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <tr id="activity-detail-{{ $log->id }}" class="activity-detail-row" hidden>
                            <td colspan="7">
                                <div class="activity-detail-content">
                                    <div>
                                        <h3>Data Sebelum</h3>
                                        @if (count($oldValues))
                                            <dl>

                                                @foreach ($oldValues as $key => $value)
                                                    @continue($key === 'harga')

                                                    @php
                                                        $labels = [
                                                            'id' => 'ID',
                                                            'umkm_id' => 'ID UMKM',
                                                            'produk_id' => 'ID Produk',
                                                            'user_id' => 'ID Pengunjung',
                                                            'kategori_umkm_id' => 'ID Kategori UMKM',
                                                            'kategori_produk_id' => 'ID Kategori Produk',
                                                            'nama_umkm' => 'Nama UMKM',
                                                            'nama_produk' => 'Nama Produk',
                                                            'nama_pengunjung' => 'Nama Pengunjung',
                                                            'jenis_ulasan' => 'Jenis Ulasan',
                                                            'rating' => 'Rating',
                                                            'komentar' => 'Komentar',
                                                            'status' => 'Status',
                                                            'reviewed_by' => 'ID Admin Pemeriksa',
                                                            'reviewed_at' => 'Tanggal Moderasi',
                                                            'alasan_penolakan' => 'Alasan Penolakan',
                                                            'harga_produk' => 'Harga Produk',
                                                            'created_at' => 'Tanggal Dibuat',
                                                            'updated_at' => 'Terakhir Diperbarui',
                                                        ];

                                                        $label = $labels[$key] ?? ucwords(str_replace('_', ' ', $key));

                                                        if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                                            try {
                                                                $value = \Carbon\Carbon::parse($value)
                                                                    ->setTimezone('Asia/Jayapura')
                                                                    ->format('d/m/Y H:i:s') . ' WIT';
                                                            } catch (\Exception $e) {
                                                                // Gunakan nilai asli jika tanggal tidak valid
                                                            }
                                                        }

                                                        if ($log->module === 'ulasan' && $key === 'status') {
                                                        $value = match ($value) {
                                                            'pending' => 'Menunggu',
                                                            'approved' => 'Disetujui',
                                                            'rejected' => 'Ditolak',
                                                            default => $value,
                                                        };
                                                    }

                                                        if ($value === null || $value === '' || $value === '[]' || $value === []) {
                                                            $value = '—';
                                                        }
                                                    @endphp

                                                    @php
                                                        $berubah = array_key_exists($key, $newValues)
                                                            && ($oldValues[$key] !== $newValues[$key]);
                                                    @endphp

                                                    <dt>{{ $label }}</dt>

                                                    <dd class="{{ $berubah ? 'activity-value-before' : '' }} {{ in_array($key, ['harga_produk', 'foto_produk']) ? 'activity-value-list' : '' }}">
                                                        @if ($log->module === 'produk' && $key === 'harga_produk')
                                                            @php
                                                                $daftarHarga = json_decode($value, true);
                                                                $daftarHarga = is_array($daftarHarga) ? $daftarHarga : [];
                                                            @endphp
                                                            @forelse ($daftarHarga as $harga)
                                                                <div>
                                                                    @if (isset($harga['harga_min']))
                                                                        Rp{{ number_format((float) $harga['harga_min'], 0, ',', '.') }}
                                                                        @if (isset($harga['harga_max']))
                                                                            – Rp{{ number_format((float) $harga['harga_max'], 0, ',', '.') }}
                                                                        @endif
                                                                        @if (!empty($harga['satuan']))
                                                                            /{{ $harga['satuan'] }}
                                                                        @endif
                                                                    @else
                                                                        Harga belum tersedia
                                                                    @endif
                                                                </div>
                                                            @empty
                                                                <span>—</span>
                                                            @endforelse
                                                        @elseif ($log->module === 'produk' && $key === 'foto_produk')
                                                            @php
                                                                $daftarFoto = json_decode($value, true);
                                                                $daftarFoto = is_array($daftarFoto) ? $daftarFoto : [];
                                                            @endphp
                                                            <div class="activity-photo-list">
                                                                @forelse ($daftarFoto as $foto)
                                                                    @php
                                                                        $pathFoto = $foto['foto'] ?? null;
                                                                        $tersedia = $pathFoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($pathFoto);
                                                                    @endphp
                                                                    @if ($tersedia)
                                                                        <img src="{{ asset('storage/' . $pathFoto) }}" alt="Foto Produk" class="activity-photo">
                                                                    @else
                                                                        <span>Foto tidak tersedia</span>
                                                                    @endif
                                                                @empty
                                                                    <span>—</span>
                                                                @endforelse
                                                            </div>
                                                        @else
                                                            {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                                        @endif
                                                    </dd>
                                                @endforeach

                                            </dl>
                                        @else
                                            <p>Tidak ada data sebelumnya.</p>
                                        @endif
                                    </div>

                                    <div>
                                        <h3>Data Sesudah</h3>
                                        @if (count($newValues))
                                            <dl>

                                                @foreach ($newValues as $key => $value)
                                                    @continue($key === 'harga')

                                                    @php
                                                        $labels = [
                                                            'id' => 'ID',
                                                            'umkm_id' => 'ID UMKM',
                                                            'produk_id' => 'ID Produk',
                                                            'user_id' => 'ID Pengunjung',
                                                            'kategori_umkm_id' => 'ID Kategori UMKM',
                                                            'kategori_produk_id' => 'ID Kategori Produk',
                                                            'nama_umkm' => 'Nama UMKM',
                                                            'nama_produk' => 'Nama Produk',
                                                            'nama_pengunjung' => 'Nama Pengunjung',
                                                            'jenis_ulasan' => 'Jenis Ulasan',
                                                            'rating' => 'Rating',
                                                            'komentar' => 'Komentar',
                                                            'status' => 'Status',
                                                            'reviewed_by' => 'ID Admin Pemeriksa',
                                                            'reviewed_at' => 'Tanggal Moderasi',
                                                            'alasan_penolakan' => 'Alasan Penolakan',
                                                            'harga_produk' => 'Harga Produk',
                                                            'created_at' => 'Tanggal Dibuat',
                                                            'updated_at' => 'Terakhir Diperbarui',
                                                        ];

                                                        $label = $labels[$key] ?? ucwords(str_replace('_', ' ', $key));

                                                        if (in_array($key, ['created_at', 'updated_at']) && $value) {
                                                            try {
                                                                $value = \Carbon\Carbon::parse($value)
                                                                    ->setTimezone('Asia/Jayapura')
                                                                    ->format('d/m/Y H:i:s') . ' WIT';
                                                            } catch (\Exception $e) {
                                                                // Gunakan nilai asli jika tanggal tidak valid
                                                            }
                                                        }

                                                        if ($value === null || $value === '' || $value === '[]' || $value === []) {
                                                            $value = '—';
                                                        }
                                                    @endphp

                                                    @php
                                                        $berubah = array_key_exists($key, $oldValues)
                                                            && ($oldValues[$key] !== $newValues[$key]);
                                                    @endphp

                                                    <dt>{{ $label }}</dt>

                                                    <dd class="{{ $berubah ? 'activity-value-after' : '' }} {{ in_array($key, ['harga_produk', 'foto_produk']) ? 'activity-value-list' : '' }}">
                                                        @if ($log->module === 'produk' && $key === 'harga_produk')
                                                            @php
                                                                $daftarHarga = json_decode($value, true);
                                                                $daftarHarga = is_array($daftarHarga) ? $daftarHarga : [];
                                                            @endphp
                                                            @forelse ($daftarHarga as $harga)
                                                                <div>
                                                                    @if (isset($harga['harga_min']))
                                                                        Rp{{ number_format((float) $harga['harga_min'], 0, ',', '.') }}
                                                                        @if (isset($harga['harga_max']))
                                                                            – Rp{{ number_format((float) $harga['harga_max'], 0, ',', '.') }}
                                                                        @endif
                                                                        @if (!empty($harga['satuan']))
                                                                            /{{ $harga['satuan'] }}
                                                                        @endif
                                                                    @else
                                                                        Harga belum tersedia
                                                                    @endif
                                                                </div>
                                                            @empty
                                                                <span>—</span>
                                                            @endforelse
                                                        @elseif ($log->module === 'produk' && $key === 'foto_produk')
                                                            @php
                                                                $daftarFoto = json_decode($value, true);
                                                                $daftarFoto = is_array($daftarFoto) ? $daftarFoto : [];
                                                            @endphp
                                                            <div class="activity-photo-list">
                                                                @forelse ($daftarFoto as $foto)
                                                                    @php
                                                                        $pathFoto = $foto['foto'] ?? null;
                                                                        $tersedia = $pathFoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($pathFoto);
                                                                    @endphp
                                                                    @if ($tersedia)
                                                                        <img src="{{ asset('storage/' . $pathFoto) }}" alt="Foto Produk" class="activity-photo">
                                                                    @else
                                                                        <span>Foto tidak tersedia</span>
                                                                    @endif
                                                                @empty
                                                                    <span>—</span>
                                                                @endforelse
                                                            </div>
                                                        @else
                                                            {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                                        @endif
                                                    </dd>
                                                @endforeach

                                            </dl>
                                        @else
                                            <p>Tidak ada data sesudahnya.</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="activity-empty">
                                <i class="bi bi-inbox"></i>
                                <p>Belum ada aktivitas yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="activity-pagination">
                <nav aria-label="Navigasi halaman aktivitas">
                    <ul class="pagination">

                        <li class="page-item {{ $logs->onFirstPage() ? 'disabled' : '' }}">
                            @if ($logs->onFirstPage())
                                <span class="page-link">&lsaquo;</span>
                            @else
                                <a class="page-link" href="{{ $logs->previousPageUrl() }}">&lsaquo;</a>
                            @endif
                        </li>

                        @php
                            $currentPage = $logs->currentPage();
                            $lastPage = $logs->lastPage();

                            // Tiga halaman awal, tiga halaman akhir, dan halaman sekitar yang aktif.
                            $pages = collect(range(1, $lastPage))
                                ->filter(function ($page) use ($currentPage, $lastPage) {
                                    return $page <= 3
                                        || $page > $lastPage - 3
                                        || abs($page - $currentPage) <= 1;
                                })
                                ->values();

                            $previousPage = 0;
                        @endphp

                        @foreach ($pages as $page)
                            @if ($page - $previousPage > 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ $logs->url($page) }}">
                                    {{ $page }}
                                </a>
                            </li>

                            @php
                                $previousPage = $page;
                            @endphp
                        @endforeach

                        <li class="page-item {{ !$logs->hasMorePages() ? 'disabled' : '' }}">
                            @if ($logs->hasMorePages())
                                <a class="page-link" href="{{ $logs->nextPageUrl() }}">&rsaquo;</a>
                            @else
                                <span class="page-link">&rsaquo;</span>
                            @endif
                        </li>

                    </ul>
                </nav>
            </div>
        @endif
    </div>
    </div>
</div>

@endsection
@push('scripts')
    <script src="{{ asset('js/admin-activity-logs.js') }}"></script>
@endpush
