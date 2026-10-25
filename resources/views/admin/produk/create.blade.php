@extends('layouts.admin')

@section('content')
<div style="max-width:750px;margin:0 auto;">
    <div style="margin-bottom:20px;">
        <h2 style="margin:0;">Tambah Produk</h2>
        <p style="color:#64748b;margin:6px 0 0;">
            Tambahkan produk ke katalog UMKM Kota Ambon.
        </p>
    </div>

    <div style="background:white;padding:25px;border:1px solid #e2e8f0;border-radius:10px;">
        <form action="{{ route('produk.store') }}"
            method="POST"
            enctype="multipart/form-data"
            onsubmit="return confirm('Apakah Anda yakin data produk yang diinput sudah benar?')">

            @csrf

            <div style="margin-bottom:18px;">
                <label for="umkm_id" style="display:block;font-weight:600;margin-bottom:7px;">
                    UMKM Pemilik Produk
                </label>
                <select id="umkm_id" name="umkm_id" required
                        style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;">
                    <option value="">-- Pilih UMKM --</option>
                    @foreach ($umkm as $item)
                        <option value="{{ $item->id }}"
                            @selected(old('umkm_id') == $item->id)>
                            {{ $item->nama_umkm }}
                        </option>
                    @endforeach
                </select>
                @error('umkm_id')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom:18px;">
                <label for="kategori_produk_id" style="display:block;font-weight:600;margin-bottom:7px;">
                    Kategori Produk
                </label>
                <select id="kategori_produk_id" name="kategori_produk_id" required
                        style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoriProduk as $kategori)
                        <option value="{{ $kategori->id }}"
                            @selected(old('kategori_produk_id') == $kategori->id)>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_produk_id')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom:18px;">
                <label for="nama_produk" style="display:block;font-weight:600;margin-bottom:7px;">
                    Nama Produk
                </label>
                <input type="text" id="nama_produk" name="nama_produk"
                       value="{{ old('nama_produk') }}" required
                       style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">
                @error('nama_produk')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom:18px;">
                <label for="deskripsi" style="display:block;font-weight:600;margin-bottom:7px;">
                    Deskripsi Produk
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Pilihan harga produk -->
            <div style="margin-bottom:24px;padding:14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;">
                <h3 style="margin:0 0 8px;font-size:16px;">Pilihan Harga Produk</h3>

                <p style="font-size:13px;color:#64748b;margin:0 0 12px;">
                    Tambahkan satu atau beberapa pilihan harga.
                    Jika harga belum diketahui, bagian ini boleh dikosongkan.
                </p>

                <div id="daftar-pilihan-harga"></div>

                <button type="button" id="tambah-pilihan-harga"
                    style="background:#16a34a;color:white;border:none;padding:9px 14px;border-radius:6px;cursor:pointer;">
                    + Tambah Pilihan Harga
                </button>
            </div>

            <!-- Foto utama produk -->
            <div style="margin-bottom:24px;">
                <label for="foto" style="display:block;font-weight:600;margin-bottom:7px;">
                    Foto Utama Produk
                </label>

                <input type="file" id="foto" name="foto"
                    accept=".jpg,.jpeg,.png,.webp"
                    style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">

                <small style="display:block;color:#64748b;margin-top:6px;">
                    Foto utama yang ditampilkan pertama kali.
                    Format JPG, PNG, atau WebP. Maksimal 2 MB.
                </small>

                @error('foto')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror
            </div>

            <!-- Foto tambahan produk -->
            <div style="margin-bottom:24px;">
                <label for="foto_tambahan" style="display:block;font-weight:600;margin-bottom:7px;">
                    Foto Tambahan Produk
                </label>

                <input type="file"
                    id="foto_tambahan"
                    name="foto_tambahan[]"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                    style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">

                <small style="display:block;color:#64748b;margin-top:6px;">
                    Opsional. Bisa memilih beberapa foto sekaligus.
                    Setiap foto maksimal 2 MB.
                    Tahan Ctrl untuk memilih beberapa file.
                </small>

                @error('foto_tambahan')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror

                @error('foto_tambahan.*')
                    <small style="color:#dc2626;">{{ $message }}</small>
                @enderror

            </div>

            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit"
                        style="background:#2563eb;color:white;padding:11px 18px;border:none;border-radius:7px;cursor:pointer;">
                    Simpan Produk
                </button>

                <a href="{{ route('admin.produk.index') }}"
                    id="btn-batal-produk"
                style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#e2e8f0;color:#334155;border-radius:6px;text-decoration:none;font-weight:600;">
                    <i class="bi bi-x-circle"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const daftarHarga = document.getElementById('daftar-pilihan-harga');
    const tombolTambah = document.getElementById('tambah-pilihan-harga');

    let indeksHarga = 0;

    // Tambahkan pilihan harga baru
    tombolTambah.addEventListener('click', function () {
        const pilihan = document.createElement('div');
        pilihan.className = 'pilihan-harga-baru';
        pilihan.style.cssText =
            'padding:12px;margin-bottom:12px;background:white;border:1px solid #e2e8f0;border-radius:7px;';

        pilihan.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <strong>Pilihan Harga</strong>
                <button type="button" class="hapus-pilihan-harga"
                    style="background:#dc2626;color:white;border:none;padding:7px 12px;border-radius:6px;cursor:pointer;">
                    Hapus
                </button>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label>Harga Minimum (Rp)</label>
                    <input type="number" name="harga_produk[${indeksHarga}][harga_min]"
                        min="0" step="0.01" required
                        style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
                <div>
                    <label>Harga Maksimum (Rp)</label>
                    <input type="number" name="harga_produk[${indeksHarga}][harga_max]"
                        min="0" step="0.01" placeholder="Opsional"
                        style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label>Jumlah</label>
                    <input type="number" name="harga_produk[${indeksHarga}][jumlah_satuan]"
                        min="0.01" step="0.01" value="1" required
                        style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
                <div>
                    <label>Satuan</label>
                    <input type="text" name="harga_produk[${indeksHarga}][satuan]"
                        maxlength="50" placeholder="Contoh: kg, buah, botol"
                        style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
            </div>
        `;

        daftarHarga.appendChild(pilihan);
        indeksHarga++;
    });

    // Hapus pilihan harga setelah konfirmasi
    daftarHarga.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-pilihan-harga');
        if (!tombolHapus) return;

        if (!confirm('Apakah Anda yakin ingin menghapus pilihan harga ini?')) {
            return;
        }

        tombolHapus.closest('.pilihan-harga-baru').remove();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('produk.store') }}"]');
    const tombolBatal = document.getElementById('btn-batal-produk');

    if (!form || !tombolBatal) return;

    let adaPerubahan = false;
    let sedangMenyimpan = false;

    form.addEventListener('input', function () {
        adaPerubahan = true;
    });

    form.addEventListener('change', function () {
        adaPerubahan = true;
    });

    tombolBatal.addEventListener('click', function (event) {
        if (!adaPerubahan) return;

        const yakin = confirm(
            'Ada perubahan yang belum disimpan. Apakah Anda yakin ingin membatalkan?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    form.addEventListener('submit', function () {
        sedangMenyimpan = true;
    });

    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan && !sedangMenyimpan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});
</script>

