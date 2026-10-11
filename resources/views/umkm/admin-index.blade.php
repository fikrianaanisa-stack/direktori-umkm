
@extends('layouts.admin')

@section('title', 'Data UMKM')

@section('content')

<style>
    .umkm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .umkm-header h1 {
        margin: 0 0 8px;
        font-size: 24px;
        color: #1e293b;
    }

    .umkm-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .btn-tambah {
        display: inline-block;
        background: #2563eb;
        color: white;
        padding: 11px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-tambah:hover {
        background: #1d4ed8;
    }

    .umkm-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .umkm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .umkm-table th,
    .umkm-table td {
        padding: 13px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .umkm-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: bold;
    }

    .umkm-table tr:hover {
        background: #f8fafc;
    }

    .aksi {
        display: flex;
        gap: 12px;
        align-items: center;
        white-space: nowrap;
    }

    .aksi a {
        text-decoration: none;
        font-size: 13px;
    }

    .aksi-detail {
        color: #2563eb;
    }

    .aksi-edit {
        color: #d97706;
    }

    .aksi-hapus {
        border: none;
        background: none;
        color: #dc2626;
        cursor: pointer;
        padding: 0;
        font-size: 13px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 14px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
</style>

<div class="umkm-header">
    <div>
        <h1>Data UMKM</h1>
        <p>Pengelolaan data Usaha Mikro, Kecil, dan Menengah Kota Ambon.</p>
    </div>

    <a href="{{ route('umkm.create') }}" class="btn-tambah">
        + Tambah UMKM
    </a>
</div>

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="umkm-card">
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_umkm }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->telepon ?? '-' }}</td>
                        <td>
                            <div class="aksi">
                                <a href="{{ route('admin.umkm.show', $item->id) }}"
                                   class="aksi-detail">
                                    Detail
                                </a>

                                <a href="{{ route('umkm.edit', $item->id) }}"
                                   class="aksi-edit">
                                    Edit
                                </a>

                                <form action="{{ route('umkm.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus UMKM ini?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="aksi-hapus">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 25px;">
                            Belum ada data UMKM.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
