@extends('layouts.admin')

@push('styles')

<style>
    .edit-produk-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    .edit-produk-grid > div {
        min-width: 0;
    }

    /* Tipografi halaman Edit Produk */
    .edit-produk-page h2 {
        font-size: 26px;
    }

    .edit-produk-page h3 {
        font-size: 20px;
    }

    .edit-produk-page label {
        font-size: 13px;
    }

    .edit-produk-page input,
    .edit-produk-page select,
    .edit-produk-page textarea {
        font-size: 14px;
    }

    .edit-produk-page button,
    .edit-produk-page a {
        font-size: 13px;
    }

    .edit-produk-page small {
        font-size: 12px;
    }

    .edit-produk-page .pilihan-harga-blade strong {
        font-size: 14px;
    }

    @media (max-width: 900px) {
        .edit-produk-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Tombol aksi Edit Produk - sama dengan Edit UMKM */
    .edit-produk-page .form-actions-produk {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 24px;
    }

    .edit-produk-page .btn-simpan-produk,
    .edit-produk-page .btn-batal-produk {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none;
        cursor: pointer;
    }

    .edit-produk-page .btn-simpan-produk {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
    }

    .edit-produk-page .btn-simpan-produk:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .edit-produk-page .btn-batal-produk {
        background: #e2e8f0;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .edit-produk-page .btn-batal-produk:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

</style>

@endpush


@section('content')
<div class="edit-produk-page" style="width:100%;">

    <div style="margin-bottom:20px;text-align:center;">
        <h2 style="margin:0;">Edit Produk</h2>
        <p style="color:#64748b;margin:6px 0 0;">
            Perbarui informasi produk UMKM Kota Ambon.
        </p>
    </div>

    <div style="background:white;padding:25px;border:1px solid #e2e8f0;border-radius:10px;">

        <form id="form-edit-produk"
              action="{{ route('produk.update', $produk->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="edit-produk-grid">

                <!-- KOLOM KIRI -->
                <div class="edit-produk-kiri">

                    <div style="margin-bottom:18px;">
                        <label for="umkm_id" style="display:block;font-weight:600;margin-bottom:7px;">
                            UMKM Pemilik Produk
                        </label>

                        <select id="umkm_id" name="umkm_id" required
                                style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;">
                            <option value="">-- Pilih UMKM --</option>

                            @foreach ($umkm as $item)
                                <option value="{{ $item->id }}"
                                    @selected(old('umkm_id', $produk->umkm_id) == $item->id)>
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
                                    @selected(old('kategori_produk_id', $produk->kategori_produk_id) == $kategori->id)>
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

                        <input type="text"
                               id="nama_produk"
                               name="nama_produk"
                               value="{{ old('nama_produk', $produk->nama_produk) }}"
                               required
                               style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">

                        @error('nama_produk')
                            <small style="color:#dc2626;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div style="margin-bottom:18px;">
                        <label for="deskripsi" style="display:block;font-weight:600;margin-bottom:7px;">
                            Deskripsi Produk
                        </label>

                        <textarea id="deskripsi"
                                  name="deskripsi"
                                  rows="4"
                                  style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;">{{ old('deskripsi', $produk->deskripsi) }}</textarea>

                        @error('deskripsi')
                            <small style="color:#dc2626;">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Galeri Foto Produk --}}
                    <div style="margin-bottom:24px;">

                        <label style="display:block;font-weight:600;margin-bottom:10px;">
                            Foto Produk Saat Ini
                        </label>

                        @php
                            // Ambil seluruh foto dari tabel foto_produk
                            $daftarFoto = $produk->fotoProduk;
                        @endphp

                        @if ($daftarFoto->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:15px;">

                                @foreach ($daftarFoto as $index => $foto)
                                    <div style="text-align:center;">
                                        <img
                                            src="{{ asset('storage/' . $foto->foto) }}"
                                            alt="Foto {{ $index + 1 }}"
                                            style="width:120px;height:120px;object-fit:cover;border-radius:7px;border:1px solid #e2e8f0;"
                                        >

                                        <small style="display:block;color:#64748b;margin-top:5px;">
                                            Foto {{ $index + 1 }}
                                        </small>

                                        @if (isset($foto->id))
                                            <button
                                                type="button"
                                                class="btn-hapus-foto-produk"
                                                data-url="{{ route('produk.foto.destroy', ['id' => $produk->id, 'fotoId' => $foto->id]) }}"
                                                style="margin-top:7px;padding:6px 12px;background:#dc2626;color:white;border:none;border-radius:6px;cursor:pointer;font-size:12px;"
                                            >
                                                Hapus Foto
                                            </button>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
                        @else
                            <p style="color:#64748b;">
                                Belum ada foto produk.
                            </p>
                        @endif

                        {{-- Mengganti Foto Utama --}}
                        <label for="foto" style="display:block;font-weight:600;margin-bottom:7px;">
                            Ganti Foto Utama (Opsional)
                        </label>

                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp"
                            style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;"
                        >

                        <small style="display:block;color:#64748b;margin-top:6px;">
                            Kosongkan jika tidak ingin mengganti foto utama.
                            Foto sebelumnya tetap tersimpan. Maksimal 2 MB.
                        </small>

                        @error('foto')
                            <small style="color:#dc2626;">{{ $message }}</small>
                        @enderror

                        {{-- Menambahkan Foto Baru --}}
                        <label for="foto_tambahan"
                            style="display:block;font-weight:600;margin-top:20px;margin-bottom:7px;">
                            Tambah Foto Produk (Opsional)
                        </label>

                        <input
                            type="file"
                            id="foto_tambahan"
                            name="foto_tambahan[]"
                            accept=".jpg,.jpeg,.png,.webp"
                            multiple
                            style="width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:7px;box-sizing:border-box;"
                        >

                        <small style="display:block;color:#64748b;margin-top:6px;">
                            Pilih beberapa foto sekaligus. Foto lama tidak akan terhapus.
                            Maksimal 2 MB per foto.
                        </small>

                        @error('foto_tambahan')
                            <small style="color:#dc2626;">{{ $message }}</small>
                        @enderror

                        @error('foto_tambahan.*')
                            <small style="color:#dc2626;">{{ $message }}</small>
                        @enderror

                    </div>

                </div>
                <!-- AKHIR KOLOM KIRI -->

                <!-- KOLOM KANAN -->
                <div class="edit-produk-kanan">

                    <div style="margin-bottom:20px;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">

                        <h3 style="margin:0 0 8px;">Pilihan Harga Produk</h3>

                        <p style="font-size:13px;color:#64748b;">
                            Berikut pilihan harga yang tersimpan untuk produk ini.
                        </p>

                        <!-- Wadah seluruh pilihan harga -->
                        <div id="daftar-pilihan-harga">

                            @php
                                // Gunakan input sebelumnya jika validasi gagal
                                $pilihanHargaForm = old('harga_produk');

                                // Jika tidak ada input sebelumnya, gunakan data database
                                if ($pilihanHargaForm === null) {
                                    $pilihanHargaForm = $produk->hargaProduk->toArray();
                                }
                            @endphp

                            @forelse ($pilihanHargaForm as $index => $pilihan)

                                <div class="pilihan-harga-blade"
                                     style="padding:12px;margin-bottom:12px;background:white;border:1px solid #e2e8f0;border-radius:7px;">

                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                                        <strong>
                                            Pilihan Harga {{ $loop->iteration }}
                                        </strong>                       
                                       
                                        <button type="button"
                                                class="hapus-pilihan-harga"
                                                style="background:#dc2626;color:white;border:none;border-radius:6px;padding:7px 12px;cursor:pointer;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>

                                    </div>

                                    <!-- ID pilihan harga lama -->
                                    <input type="hidden"
                                           name="harga_produk[{{ $index }}][id]"
                                           value="{{ $pilihan['id'] ?? '' }}">

                                    <!-- Harga minimum dan maksimum -->
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">

                                        <div>
                                            <label>Harga Minimum (Rp)</label>

                                            <input type="number"
                                                   name="harga_produk[{{ $index }}][harga_min]"
                                                   min="0"
                                                   step="0.01"
                                                   value="{{ $pilihan['harga_min'] ?? '' }}"
                                                   required
                                                   style="width:100%;padding:9px;box-sizing:border-box;">
                                        </div>

                                        <div>
                                            <label>Harga Maksimum (Rp)</label>

                                            <input type="number"
                                                   name="harga_produk[{{ $index }}][harga_max]"
                                                   min="0"
                                                   step="0.01"
                                                   value="{{ $pilihan['harga_max'] ?? '' }}"
                                                   placeholder="Opsional"
                                                   style="width:100%;padding:9px;box-sizing:border-box;">
                                        </div>

                                    </div>

                                    <!-- Jumlah dan satuan -->
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

                                        <div>
                                            <label>Jumlah</label>

                                            <input type="number"
                                                   name="harga_produk[{{ $index }}][jumlah_satuan]"
                                                   min="0.01"
                                                   step="0.01"
                                                   value="{{ $pilihan['jumlah_satuan'] ?? 1 }}"
                                                   required
                                                   style="width:100%;padding:9px;box-sizing:border-box;">
                                        </div>

                                        <div>
                                            <label>Satuan</label>

                                            <input type="text"
                                                   name="harga_produk[{{ $index }}][satuan]"
                                                   value="{{ $pilihan['satuan'] ?? '' }}"
                                                   placeholder="Contoh: kg, buah, botol"
                                                   maxlength="50"
                                                   style="width:100%;padding:9px;box-sizing:border-box;">
                                        </div>

                                    </div>

                                </div>

                            @empty
                                <p style="color:#64748b;font-size:13px;">
                                    Belum ada pilihan harga yang tersimpan.
                                </p>
                            @endforelse

                        </div>
                        <!-- Akhir daftar-pilihan-harga -->

                        <button type="button"
                                id="tambah-pilihan-harga"
                                style="background:#16a34a;color:white;padding:10px 15px;border:none;border-radius:7px;cursor:pointer;margin-top:10px;">
                            + Tambah Pilihan Harga
                        </button>

                    </div>
                    <!-- Akhir bagian Pilihan Harga Produk -->

                </div>
                <!-- AKHIR KOLOM KANAN -->

            </div>
            <!-- AKHIR GRID DUA KOLOM -->
            
            <!-- TOMBOL AKSI -->
            <div class="form-actions-produk">

                <button type="submit" class="btn-simpan-produk">
                    <i class="bi bi-floppy me-2"></i>
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.produk.index') }}"
                class="btn-batal-produk">
                    <i class="bi bi-x-circle me-2"></i>
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tombolTambah = document.getElementById('tambah-pilihan-harga');
    const daftarHarga = document.getElementById('daftar-pilihan-harga');

    // Tentukan indeks berikutnya dari data formulir
    let indeksHarga = {{ count($pilihanHargaForm) ? max(array_keys($pilihanHargaForm)) + 1 : 0 }};

    tombolTambah.addEventListener('click', function () {
        const nomorPilihan = indeksHarga + 1;

        const pilihanBaru = document.createElement('div');
        pilihanBaru.style.cssText =
            'padding:12px;margin-bottom:12px;background:white;border:1px solid #e2e8f0;border-radius:7px;';

        pilihanBaru.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <strong>
                    Pilihan Harga ${nomorPilihan}
                </strong>

                <button type="button"
                        class="hapus-pilihan-harga"
                        style="background:#dc2626;color:white;border:none;border-radius:6px;padding:7px 12px;cursor:pointer;">
                    Hapus
                </button>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label>Harga Minimum (Rp)</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][harga_min]"
                           min="0" step="0.01" required
                           style="width:100%;padding:9px;box-sizing:border-box;">
                </div>

                <div>
                    <label>Harga Maksimum (Rp)</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][harga_max]"
                           min="0" step="0.01"
                           placeholder="Opsional"
                           style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label>Jumlah</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][jumlah_satuan]"
                           min="0.01" step="0.01" value="1" required
                           style="width:100%;padding:9px;box-sizing:border-box;">
                </div>

                <div>
                    <label>Satuan</label>
                    <input type="text"
                           name="harga_produk[${indeksHarga}][satuan]"
                           placeholder="Contoh: kg, buah, botol"
                           maxlength="50"
                           style="width:100%;padding:9px;box-sizing:border-box;">
                </div>
            </div>
        `;

        daftarHarga.appendChild(pilihanBaru);

        
        // Hapus pilihan harga baru setelah konfirmasi
        pilihanBaru.querySelector('.hapus-pilihan-harga')
            .addEventListener('click', function () {

                if (!confirm('Apakah Anda yakin ingin menghapus pilihan harga ini?')) {
                    return;
                }

                pilihanBaru.remove();
            });

                indeksHarga++;
    });

    // Hapus pilihan harga setelah konfirmasi
    daftarHarga.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-pilihan-harga');

        if (!tombolHapus) return;

        const pilihanHarga = tombolHapus.closest('.pilihan-harga-blade');

        if (pilihanHarga) {

            // Konfirmasi sebelum menghapus pilihan harga
            if (!confirm('Apakah Anda yakin ingin menghapus pilihan harga ini?')) {
                return;
            }

            pilihanHarga.remove();
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const daftarHarga = document.getElementById('daftar-pilihan-harga');

    if (!daftarHarga) return;

    // Validasi harga minimum dan maksimum
    function validasiHarga() {
        let semuaValid = true;

        for (const pilihan of daftarHarga.children) {
            const minimum = pilihan.querySelector('[name$="[harga_min]"]');
            const maksimum = pilihan.querySelector('[name$="[harga_max]"]');

            if (!minimum || !maksimum) continue;

            // Bersihkan kesalahan sebelumnya
            const pesanLama = pilihan.querySelector('.pesan-error-harga');
            if (pesanLama) pesanLama.remove();

            maksimum.style.border = '';

            if (
                minimum.value.trim() !== '' &&
                maksimum.value.trim() !== '' &&
                Number(maksimum.value) < Number(minimum.value)
            ) {
                semuaValid = false;

                // Tandai kolom yang salah
                maksimum.style.border = '2px solid #dc2626';

                // Tampilkan pesan kesalahan
                const pesan = document.createElement('small');
                pesan.className = 'pesan-error-harga';
                pesan.style.cssText =
                    'display:block;color:#dc2626;margin-top:5px;font-size:12px;';

                pesan.textContent =
                    'Harga maksimum harus lebih besar atau sama dengan harga minimum.';

                maksimum.insertAdjacentElement('afterend', pesan);
            }
        }

        return semuaValid;
    }

    // Validasi langsung saat pengguna mengubah harga
    daftarHarga.addEventListener('input', function (event) {
        if (
            event.target.matches('[name$="[harga_min]"]') ||
            event.target.matches('[name$="[harga_max]"]')
        ) {
            validasiHarga();
        }
    });

    // Validasi ketika nilai berubah
    daftarHarga.addEventListener('change', validasiHarga);

    // Gunakan formulir Edit Produk untuk proses AJAX
    const formulir = document.getElementById('form-edit-produk');

    if (formulir) {
        formulir.addEventListener('submit', function (event) {
            const hasilValidasi = validasiHarga();

            if (!hasilValidasi) {
                event.preventDefault();

                // Tampilkan toast kesalahan tanpa reload
                let toast = document.getElementById('admin-toast');

                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'admin-toast';
                    toast.dataset.type = 'error';
                    toast.setAttribute('role', 'alert');

                    toast.style.cssText = `
                        position:fixed;
                        top:20px;
                        right:20px;
                        z-index:9999;
                        width:calc(100% - 40px);
                        max-width:360px;
                        padding:16px;
                        border-radius:10px;
                        background:#fef2f2;
                        border:1px solid #fca5a5;
                        color:#991b1b;
                        box-shadow:0 8px 24px rgba(0,0,0,0.15);
                    `;

                    document.body.appendChild(toast);
                }

                toast.replaceChildren();

                const isiToast = document.createElement('div');
                isiToast.style.cssText =
                    'display:flex;justify-content:space-between;gap:12px;';

                const pesanToast = document.createElement('div');

                const judulToast = document.createElement('strong');
                judulToast.textContent = 'Validasi Gagal';

                const keteranganToast = document.createElement('p');
                keteranganToast.style.cssText =
                    'margin:8px 0 0;font-size:14px;line-height:1.5;';

                // Cari pilihan harga yang memiliki kesalahan
                const pilihanSalah = Array.from(daftarHarga.children)
                    .filter(pilihan => pilihan.querySelector('[name$="[harga_max]"]'))
                    .find(pilihan => pilihan.querySelector('.pesan-error-harga'));

                const nomorPilihan = pilihanSalah
                    ? Array.from(daftarHarga.children)
                        .filter(pilihan => pilihan.querySelector('[name$="[harga_max]"]'))
                        .indexOf(pilihanSalah) + 1
                    : null;

                // Tampilkan pesan kesalahan yang spesifik
                keteranganToast.textContent = nomorPilihan
                    ? `Harga maksimum pada Pilihan Harga ${nomorPilihan} harus lebih besar atau sama dengan harga minimum.`
                    : 'Periksa kembali pilihan harga yang tidak valid.';    



                pesanToast.append(judulToast, keteranganToast);

                const tombolTutup = document.createElement('button');
                tombolTutup.type = 'button';
                tombolTutup.textContent = '×';
                tombolTutup.setAttribute('aria-label', 'Tutup notifikasi');
                tombolTutup.style.cssText =
                    'background:none;border:none;color:inherit;cursor:pointer;font-size:20px;align-self:flex-start;';

                tombolTutup.addEventListener('click', function () {
                    toast.remove();
                });

                isiToast.append(pesanToast, tombolTutup);
                toast.appendChild(isiToast);

                // Fokus pada kolom yang salah
                const pesanError = daftarHarga.querySelector('.pesan-error-harga');

                if (pesanError) {
                    pesanError.previousElementSibling?.focus();
                }
            }
            // Persiapan AJAX: cegah pengiriman formulir biasa
            event.preventDefault();

            if (hasilValidasi) {

                // Konfirmasi sebelum mengirim data melalui AJAX
                if (!confirm('Apakah Anda yakin data produk sudah benar dan ingin menyimpan perubahan?')) {
                    return;
                }

                console.log('Formulir valid dan siap dikirim menggunakan AJAX.');

                // Siapkan seluruh data formulir, termasuk foto produk
                const dataFormulir = new FormData(formulir);

                console.log('Data formulir siap diproses.');
                
                // Cegah pengiriman formulir berulang
                const tombolSimpan = formulir.querySelector('button[type="submit"]');

                if (tombolSimpan) {
                    tombolSimpan.disabled = true;
                    tombolSimpan.textContent = 'Menyimpan...';
                }

                // Kirim data produk melalui AJAX
                fetch(formulir.action, {
                    method: 'POST',
                    body: dataFormulir,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async function (response) {
                    const hasil = await response.json();

                    
                    if (!response.ok) {
                        console.error('Validasi atau penyimpanan gagal:', hasil);

                        // Ambil pesan kesalahan dari Laravel
                        const semuaError = hasil.errors
                            ? Object.values(hasil.errors).flat()
                            : [];

                        const pesanError = semuaError.length > 0
                            ? semuaError[0]
                            : (hasil.message || 'Data gagal disimpan. Silakan periksa kembali.');

                        // Tampilkan toast kesalahan
                        let toast = document.getElementById('admin-toast');

                        if (!toast) {
                            toast = document.createElement('div');
                            toast.id = 'admin-toast';
                            document.body.appendChild(toast);
                        }

                        toast.style.cssText = `
                            position:fixed;
                            top:20px;
                            right:20px;
                            z-index:9999;
                            max-width:360px;
                            padding:16px;
                            border-radius:10px;
                            background:#fef2f2;
                            border:1px solid #fca5a5;
                            color:#991b1b;
                            box-shadow:0 8px 24px rgba(0,0,0,0.15);
                        `;

                        toast.replaceChildren();

                        const judul = document.createElement('strong');
                        judul.textContent = 'Validasi Gagal';

                        const keterangan = document.createElement('p');
                        keterangan.style.margin = '8px 0 0';
                        keterangan.textContent = pesanError;

                        toast.append(judul, keterangan);

                        return;
                    }

                    if (typeof window.tandaiProdukBerhasilDisimpan === 'function') {
                        window.tandaiProdukBerhasilDisimpan();
                    }

                    // Tampilkan toast keberhasilan
                    let toast = document.getElementById('admin-toast');

                    if (!toast) {
                        toast = document.createElement('div');
                        toast.id = 'admin-toast';
                        document.body.appendChild(toast);
                    }

                    toast.style.cssText = `
                        position:fixed;
                        top:20px;
                        right:20px;
                        z-index:9999;
                        width:calc(100% - 40px);
                        max-width:360px;
                        box-sizing:border-box;
                        padding:16px;
                        border-radius:10px;
                        background:#f0fdf4;
                        border:1px solid #86efac;
                        color:#166534;
                        box-shadow:0 8px 24px rgba(0,0,0,0.15);
                    `;

                    toast.replaceChildren();

                    const judul = document.createElement('strong');
                    judul.textContent = 'Berhasil';

                    const keterangan = document.createElement('p');
                    keterangan.style.margin = '8px 0 0';
                    keterangan.textContent =
                        hasil.message || 'Produk berhasil diperbarui.';

                    toast.append(judul, keterangan);

                    // Arahkan ke daftar produk setelah notifikasi ditampilkan
                    if (hasil.redirect) {
                        setTimeout(function () {
                            window.location.href = hasil.redirect;
                        }, 1500);
                    }

                })

                .catch(function (error) {
                    console.error('Terjadi kesalahan AJAX:', error);

                    // Tampilkan pesan jika koneksi atau respons server bermasalah
                    let toast = document.getElementById('admin-toast');

                    if (!toast) {
                        toast = document.createElement('div');
                        toast.id = 'admin-toast';
                        document.body.appendChild(toast);
                    }

                    toast.style.cssText = `
                        position:fixed;
                        top:20px;
                        right:20px;
                        z-index:9999;
                        max-width:360px;
                        padding:16px;
                        border-radius:10px;
                        background:#fef2f2;
                        border:1px solid #fca5a5;
                        color:#991b1b;
                        box-shadow:0 8px 24px rgba(0,0,0,0.15);
                    `;

                    toast.textContent =
                        'Koneksi atau respons server bermasalah. Periksa koneksi dan status penyimpanan sebelum mencoba lagi.';
                })

                .finally(function () {
                    // Aktifkan kembali tombol setelah permintaan selesai
                    if (tombolSimpan) {
                        tombolSimpan.disabled = false;
                        tombolSimpan.textContent = 'Simpan Perubahan';
                    }
                });
            }
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-hapus-foto-produk').forEach(function (tombol) {

        tombol.addEventListener('click', async function () {
            if (
                typeof window.adaPerubahanProdukBelumDisimpan === 'function' &&
                window.adaPerubahanProdukBelumDisimpan()
            ) {
                alert(
                    'Ada perubahan data produk yang belum disimpan. Simpan perubahan terlebih dahulu sebelum menghapus foto.'
                );
                return;
            }

            const konfirmasi = confirm(
                'Apakah Anda yakin ingin menghapus foto ini? Foto yang dihapus tidak dapat dikembalikan.'
            );

            if (!konfirmasi) return;

            // Jangan meninggalkan halaman jika ada perubahan formulir
            // yang belum disimpan.
            const formulir = document.getElementById('form-edit-produk');

            if (formulir && typeof window.tandaiProdukBerhasilDisimpan !== 'function') {
                // Tidak mengubah mekanisme penyimpanan yang sudah ada.
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                || document.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                alert('Token keamanan tidak ditemukan. Muat ulang halaman.');
                return;
            }

            tombol.disabled = true;
            tombol.textContent = 'Menghapus...';

            try {
                const response = await fetch(tombol.dataset.url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const hasil = await response.json();

                if (!response.ok || !hasil.success) {
                    throw new Error(hasil.message || 'Foto gagal dihapus.');
                }

                // Tampilkan notifikasi tanpa perlu menekan Oke
                const notifikasi = document.createElement('div');

                notifikasi.textContent = hasil.message || 'Foto berhasil dihapus.';

                notifikasi.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 99999;
                    background: #f0fdf4;
                    color: #166534;
                    border: 1px solid #86efac;
                    padding: 15px 20px;
                    border-radius: 10px;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                `;

                document.body.appendChild(notifikasi);

                // Muat ulang halaman secara otomatis
                setTimeout(function () {
                    window.location.reload();
                }, 1200);

            } catch (error) {
                alert(error.message || 'Terjadi kesalahan saat menghapus foto.');
                tombol.disabled = false;
                tombol.textContent = 'Hapus Foto';
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulir = document.getElementById('form-edit-produk');

    if (!formulir) return;

    let penyimpananBerhasil = false;

    function ambilDataForm() {
        const data = [];

        formulir.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (!field.name) return;

            if (field.type === 'file') {
                const files = Array.from(field.files).map(function (file) {
                    return [file.name, file.size, file.lastModified];
                });

                data.push([field.name, files]);
            } else {
                data.push([field.name, field.value]);
            }
        });

        return JSON.stringify(data);
    }

    const dataAwal = ambilDataForm();
    window.adaPerubahanProdukBelumDisimpan = function () {
        return !penyimpananBerhasil && ambilDataForm() !== dataAwal;
    };

    window.addEventListener('beforeunload', function (event) {
        if (penyimpananBerhasil) return;

        if (ambilDataForm() !== dataAwal) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    formulir.addEventListener('submit', function () {
        // Form menggunakan AJAX; jangan tandai berhasil sebelum server merespons.
    });

    window.tandaiProdukBerhasilDisimpan = function () {
        penyimpananBerhasil = true;
    };
});
</script>




