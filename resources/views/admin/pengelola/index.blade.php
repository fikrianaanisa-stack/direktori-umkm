@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Judul halaman --}}
    <div class="mb-4">
        <h2>Daftar Pengelola Admin</h2>
        <p class="text-muted">
            Informasi administrator yang mengelola Sistem Informasi
            Direktori UMKM dan Katalog Produk Kota Ambon.
        </p>
    </div>

    {{-- Tabel daftar pengelola --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="70">No.</th>
                            <th>Nama Pengelola</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pengelola as $admin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        Admin
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada data pengelola admin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection