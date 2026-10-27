document.addEventListener('DOMContentLoaded', function () {
    const tombolTambah = document.getElementById('tambah-pilihan-harga');
    const daftarHarga = document.getElementById('daftar-pilihan-harga');

    if (!tombolTambah || !daftarHarga) return;

    // Mengambil indeks berikutnya dari pilihan harga yang sudah ada
    let indeksHarga = Number(daftarHarga.dataset.nextIndex || 0);

    // Menambah pilihan harga
    tombolTambah.addEventListener('click', function () {
        const nomorPilihan = indeksHarga + 1;

        const pilihanBaru = document.createElement('div');
        pilihanBaru.className = 'pilihan-harga-baru';

        pilihanBaru.innerHTML = `
            <div class="edit-produk-harga-header">
                <strong>Pilihan Harga ${nomorPilihan}</strong>

                <button type="button" class="hapus-pilihan-harga">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>

            <div class="edit-produk-harga-grid">
                <div>
                    <label>Harga Minimum (Rp)</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][harga_min]"
                           class="edit-produk-harga-input"
                           min="0"
                           step="0.01"
                           required>
                </div>

                <div>
                    <label>Harga Maksimum (Rp)</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][harga_max]"
                           class="edit-produk-harga-input"
                           min="0"
                           step="0.01"
                           placeholder="Opsional">
                </div>
            </div>

            <div class="edit-produk-harga-grid">
                <div>
                    <label>Jumlah</label>
                    <input type="number"
                           name="harga_produk[${indeksHarga}][jumlah_satuan]"
                           class="edit-produk-harga-input"
                           min="0.01"
                           step="0.01"
                           value="1"
                           required>
                </div>

                <div>
                    <label>Satuan</label>
                    <input type="text"
                           name="harga_produk[${indeksHarga}][satuan]"
                           class="edit-produk-harga-input"
                           maxlength="50"
                           placeholder="Contoh: kg, buah, botol">
                </div>
            </div>
        `;

        daftarHarga.appendChild(pilihanBaru);
        indeksHarga++;
    });

    // Menghapus pilihan harga
    daftarHarga.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-pilihan-harga');

        if (!tombolHapus) return;

        const pilihanHarga = tombolHapus.closest(
            '.pilihan-harga-blade, .pilihan-harga-baru'
        );

        if (!pilihanHarga) return;

        const yakin = window.confirm(
            'Apakah Anda yakin ingin menghapus pilihan harga ini?'
        );

        if (!yakin) return;

        pilihanHarga.remove();
    });

        // Memvalidasi harga minimum dan maksimum
    function validasiHarga() {
        let semuaValid = true;

        const semuaPilihan = daftarHarga.querySelectorAll(
            '.pilihan-harga-blade, .pilihan-harga-baru'
        );

        semuaPilihan.forEach(function (pilihan) {
            const minimum = pilihan.querySelector('[name$="[harga_min]"]');
            const maksimum = pilihan.querySelector('[name$="[harga_max]"]');

            if (!minimum || !maksimum) return;

            const pesanLama = pilihan.querySelector('.pesan-error-harga');

            if (pesanLama) {
                pesanLama.remove();
            }

            maksimum.classList.remove('is-invalid');

            if (
                minimum.value.trim() !== '' &&
                maksimum.value.trim() !== '' &&
                Number(maksimum.value) < Number(minimum.value)
            ) {
                semuaValid = false;

                maksimum.classList.add('is-invalid');

                const pesan = document.createElement('small');
                pesan.className = 'pesan-error-harga';
                pesan.textContent =
                    'Harga maksimum harus lebih besar atau sama dengan harga minimum.';

                maksimum.insertAdjacentElement('afterend', pesan);
            }
        });

        return semuaValid;
    }

    // Validasi ketika harga diketik
    daftarHarga.addEventListener('input', function (event) {
        if (
            event.target.matches('[name$="[harga_min]"]') ||
            event.target.matches('[name$="[harga_max]"]')
        ) {
            validasiHarga();
        }
    });

    // Validasi ketika nilai harga berubah
    daftarHarga.addEventListener('change', validasiHarga);

        // Menyimpan perubahan produk menggunakan AJAX
    const formulir = document.getElementById('form-edit-produk');

    if (formulir) {
        formulir.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!validasiHarga()) {
                tampilkanNotifikasiProduk(
                    'error',
                    'Validasi Gagal',
                    'Harga maksimum harus lebih besar atau sama dengan harga minimum.'
                );

                const kolomSalah = daftarHarga.querySelector('.is-invalid');
                if (kolomSalah) kolomSalah.focus();

                return;
            }

            const yakin = window.confirm(
                'Apakah Anda yakin data produk sudah benar dan ingin menyimpan perubahan?'
            );

            if (!yakin) return;

            const tombolSimpan = formulir.querySelector('button[type="submit"]');
            const isiTombolAwal = tombolSimpan ? tombolSimpan.innerHTML : '';

            if (tombolSimpan) {
                tombolSimpan.disabled = true;
                tombolSimpan.textContent = 'Menyimpan...';
            }

            try {
                const response = await fetch(formulir.action, {
                    method: 'POST',
                    body: new FormData(formulir),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const hasil = await response.json();

                if (!response.ok) {
                    const semuaError = hasil.errors
                        ? Object.values(hasil.errors).flat()
                        : [];

                    const pesan = semuaError.length
                        ? semuaError[0]
                        : (hasil.message || 'Data produk gagal disimpan.');

                    tampilkanNotifikasiProduk('error', 'Validasi Gagal', pesan);
                    return;
                }

                if (typeof window.tandaiProdukBerhasilDisimpan === 'function') {
                    window.tandaiProdukBerhasilDisimpan();
                }

                tampilkanNotifikasiProduk(
                    'success',
                    'Berhasil',
                    hasil.message || 'Produk berhasil diperbarui.'
                );

                if (hasil.redirect) {
                    setTimeout(function () {
                        window.location.href = hasil.redirect;
                    }, 1500);
                }
            } catch (error) {
                console.error('Gagal menyimpan produk:', error);

                tampilkanNotifikasiProduk(
                    'error',
                    'Kesalahan',
                    'Koneksi atau respons server bermasalah. Periksa status penyimpanan sebelum mencoba lagi.'
                );
            } finally {
                if (tombolSimpan) {
                    tombolSimpan.disabled = false;
                    tombolSimpan.innerHTML = isiTombolAwal;
                }
            }
        });
    }

    // Menampilkan notifikasi hasil penyimpanan
    function tampilkanNotifikasiProduk(tipe, judul, pesan) {
        let toast = document.getElementById('admin-toast');

        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'admin-toast';
            document.body.appendChild(toast);
        }

        toast.dataset.type = tipe;
        toast.setAttribute('role', 'alert');

        toast.replaceChildren();

        const judulToast = document.createElement('strong');
        judulToast.textContent = judul;

        const pesanToast = document.createElement('p');
        pesanToast.className = 'mt-2 mb-0';
        pesanToast.textContent = pesan;

        const tombolTutup = document.createElement('button');
        tombolTutup.type = 'button';
        tombolTutup.className = 'btn-close ms-3';
        tombolTutup.setAttribute('aria-label', 'Tutup notifikasi');

        tombolTutup.addEventListener('click', function () {
            toast.classList.add('d-none');
        });

        const isiToast = document.createElement('div');
        isiToast.className = 'd-flex justify-content-between align-items-start';
        isiToast.append(judulToast, tombolTutup);

        toast.append(isiToast, pesanToast);
        toast.classList.remove('d-none');
    }

    // Menghapus foto produk yang sudah tersimpan
    document.querySelectorAll('.btn-hapus-foto-produk').forEach(function (tombol) {
        tombol.addEventListener('click', async function () {

            // Periksa perubahan formulir yang belum disimpan
            if (
                typeof window.adaPerubahanProdukBelumDisimpan === 'function' &&
                window.adaPerubahanProdukBelumDisimpan()
            ) {
                window.alert(
                    'Ada perubahan data produk yang belum disimpan. Simpan perubahan terlebih dahulu sebelum menghapus foto.'
                );
                return;
            }

            const yakin = window.confirm(
                'Apakah Anda yakin ingin menghapus foto ini? Foto yang dihapus tidak dapat dikembalikan.'
            );

            if (!yakin) return;

            // Ambil token keamanan Laravel
            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                tampilkanNotifikasiProduk(
                    'error',
                    'Gagal',
                    'Token keamanan tidak ditemukan. Muat ulang halaman.'
                );
                return;
            }

            const isiTombolAwal = tombol.innerHTML;

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

                tampilkanNotifikasiProduk(
                    'success',
                    'Berhasil',
                    hasil.message || 'Foto berhasil dihapus.'
                );

                // Muat ulang halaman setelah penghapusan berhasil
                setTimeout(function () {
                    window.location.reload();
                }, 1200);

            } catch (error) {
                tampilkanNotifikasiProduk(
                    'error',
                    'Gagal',
                    error.message || 'Terjadi kesalahan saat menghapus foto.'
                );

                tombol.disabled = false;
                tombol.innerHTML = isiTombolAwal;
            }
        });
    });

    // Mendeteksi perubahan formulir yang belum disimpan
    if (formulir) {
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

        // Digunakan sebelum menghapus foto produk
        window.adaPerubahanProdukBelumDisimpan = function () {
            return !penyimpananBerhasil && ambilDataForm() !== dataAwal;
        };

        // Menampilkan peringatan ketika meninggalkan halaman
        window.addEventListener('beforeunload', function (event) {
            if (window.adaPerubahanProdukBelumDisimpan()) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        // Dipanggil setelah penyimpanan AJAX berhasil
        window.tandaiProdukBerhasilDisimpan = function () {
            penyimpananBerhasil = true;
        };
    }

    // Pratinjau dan hapus pilihan foto utama
    const inputFoto = document.getElementById('foto');
    const previewFoto = document.getElementById('preview-foto-utama');
    const gambarPreview = document.getElementById('gambar-preview-foto-utama');
    const tombolHapus = document.getElementById('hapus-preview-foto-utama');

    if (inputFoto && previewFoto && gambarPreview && tombolHapus) {
        let urlPreview = null;

        inputFoto.addEventListener('change', function () {
            if (urlPreview) {
                URL.revokeObjectURL(urlPreview);
                urlPreview = null;
            }

            const file = inputFoto.files[0];

            if (!file) {
                gambarPreview.removeAttribute('src');
                previewFoto.classList.add('d-none');
                return;
            }

            urlPreview = URL.createObjectURL(file);
            gambarPreview.src = urlPreview;
            previewFoto.classList.remove('d-none');
        });

        tombolHapus.addEventListener('click', function () {
            inputFoto.value = '';

            if (urlPreview) {
                URL.revokeObjectURL(urlPreview);
                urlPreview = null;
            }

            gambarPreview.removeAttribute('src');
            previewFoto.classList.add('d-none');

            inputFoto.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    // Membatalkan pilihan foto tambahan
    const inputFotoTambahan = document.getElementById('foto_tambahan');
    const tombolHapusFotoTambahan = document.getElementById('hapus-pilihan-foto-tambahan');

    if (inputFotoTambahan && tombolHapusFotoTambahan) {
        function perbaruiTombolHapusFoto() {
            const adaFoto = inputFotoTambahan.files.length > 0;
            tombolHapusFotoTambahan.classList.toggle('d-none', !adaFoto);
        }

        inputFotoTambahan.addEventListener('change', perbaruiTombolHapusFoto);

        tombolHapusFotoTambahan.addEventListener('click', function () {
            inputFotoTambahan.value = '';
            perbaruiTombolHapusFoto();
        });

        perbaruiTombolHapusFoto();
    }
});