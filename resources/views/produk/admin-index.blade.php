@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
    <div>
        <h2 style="margin:0;">Daftar Produk</h2>
        <p style="color:#64748b;margin:6px 0 0;">
            Kelola katalog produk UMKM Kota Ambon.
        </p>
    </div>

    <a href="{{ route('produk.create') }}"
       style="background:#2563eb;color:white;padding:10px 16px;border-radius:8px;text-decoration:none;">
        + Tambah Produk
    </a>
</div>

@if (session('success'))
    <div style="background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
@endif

<div style="background:white;border:1px solid #e2e8f0;border-radius:10px;overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;text-align:left;">
        <thead style="background:#f1f5f9;">
            <tr>
                <th style="padding:12px;">No.</th>
                <th style="padding:12px;">Foto</th>
                <th style="padding:12px;">Nama Produk</th>
                <th style="padding:12px;">UMKM</th>
                <th style="padding:12px;">Kategori</th>
                <th style="padding:12px;">Harga</th>
                <th style="padding:12px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $item)
                <tr style="border-top:1px solid #e2e8f0;">
                    <td style="padding:12px;">{{ $loop->iteration }}</td>
                    <td style="padding:12px;">
                        @if ($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}"
                                 alt="{{ $item->nama_produk }}"
                                 style="width:65px;height:65px;object-fit:cover;border-radius:6px;">
                        @else
                            <span style="color:#94a3b8;">Tidak ada foto</span>
                        @endif
                    </td>
                    <td style="padding:12px;">{{ $item->nama_produk }}</td>
                    <td style="padding:12px;">{{ $item->umkm?->nama_umkm ?? '-' }}</td>
                    <td style="padding:12px;">{{ $item->kategoriProduk?->nama_kategori ?? '-' }}</td>
                    
                    <td style="padding:12px;">
                        @php
                            // Ambil pilihan harga produk
                            $pilihanHarga = $item->hargaProduk;
                            $jumlahPilihan = $pilihanHarga->count();
                            $hargaTerendah = $pilihanHarga->min('harga_min');
                        @endphp

                        @if ($jumlahPilihan === 0)
                            <span style="color:#64748b;">
                                Hubungi UMKM
                            </span>

                        @elseif ($jumlahPilihan === 1)
                            @php
                                $pilihan = $pilihanHarga->first();
                            @endphp

                            <strong>
                                Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                                @if ($pilihan->harga_max !== null && $pilihan->harga_max > $pilihan->harga_min)
                                    – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                                @endif
                            </strong>

                            @if ($pilihan->satuan)
                                <div style="font-size:12px;color:#64748b;margin-top:4px;">
                                    Untuk
                                    {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                    {{ $pilihan->satuan }}
                                </div>
                            @endif

                        @else
                            <strong style="color:#2563eb;">
                                Mulai Rp {{ number_format($hargaTerendah, 0, ',', '.') }}
                            </strong>

                            <div style="font-size:12px;color:#64748b;margin-top:4px;">
                                {{ $jumlahPilihan }} pilihan harga
                            </div>
                        @endif
                    </td>

                    <td style="padding:12px;">
                        <div style="display:flex;gap:8px;align-items:center;">
                            <a href="{{ route('produk.edit', $item->id) }}"
                               style="color:#2563eb;text-decoration:none;">
                                Edit
                            </a>

                            <form action="{{ route('produk.destroy', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="border:none;background:none;color:#dc2626;cursor:pointer;padding:0;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7"
                        style="padding:24px;text-align:center;color:#64748b;">
                        Belum ada produk yang ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
