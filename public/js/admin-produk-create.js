document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-tambah-produk');
    const daftarHarga = document.getElementById('daftar-pilihan-harga');
    const tombolTambah = document.getElementById('tambah-pilihan-harga');
    const tombolBatal = document.getElementById('btn-batal-produk');

    if (!daftarHarga || !tombolTambah) return;

    let indeksHarga = 0;
    let adaPerubahan = false;
    let sedangMenyimpan = false;

    // Menambahkan pilihan harga
    tombolTambah.addEventListener('click', function () {
        const pilihan = document.createElement('div');

        pilihan.className = 'pilihan-harga-baru';

        pilihan.innerHTML = `
            <div class="pilihan-harga-header">
                <strong>Pilihan Harga</strong>

                <button type="button" class="hapus-pilihan-harga">
                    Hapus
                </button>
            </div>

            <div class="pilihan-harga-grid">
                <div>
                    <label>Harga Minimum (Rp)</label>
                    <input
                        type="number"
                        name="harga_produk[${indeksHarga}][harga_min]"
                        class="pilihan-harga-input"
                        min="0"
                        step="0.01"
                        required
                    >
                </div>

                <div>
                    <label>Harga Maksimum (Rp)</label>
                    <input
                        type="number"
                        name="harga_produk[${indeksHarga}][harga_max]"
                        class="pilihan-harga-input"
                        min="0"
                        step="0.01"
                        placeholder="Opsional"
                    >
                </div>
            </div>

            <div class="pilihan-harga-grid">
                <div>
                    <label>Jumlah</label>
                    <input
                        type="number"
                        name="harga_produk[${indeksHarga}][jumlah_satuan]"
                        class="pilihan-harga-input"
                        min="0.01"
                        step="0.01"
                        value="1"
                        required
                    >
                </div>

                <div>
                    <label>Satuan</label>
                    <input
                        type="text"
                        name="harga_produk[${indeksHarga}][satuan]"
                        class="pilihan-harga-input"
                        maxlength="50"
                        placeholder="Contoh: kg, buah, botol"
                    >
                </div>
            </div>
        `;

        daftarHarga.appendChild(pilihan);

        indeksHarga++;
        adaPerubahan = true;
    });

    // Menghapus pilihan harga
    daftarHarga.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-pilihan-harga');

        if (!tombolHapus) return;

        const yakin = window.confirm(
            'Apakah Anda yakin ingin menghapus pilihan harga ini?'
        );

        if (!yakin) return;

        tombolHapus.closest('.pilihan-harga-baru').remove();
        adaPerubahan = true;
    });

    if (!form) return;

    // Menghapus pilihan foto utama atau foto tambahan
    form.querySelectorAll('[data-clear-file]').forEach(function (button) {
        const inputFile = document.getElementById(button.dataset.clearFile);

        if (!inputFile) return;

        function perbaruiTombol() {
            const adaFile = inputFile.files.length > 0;
            button.classList.toggle('d-none', !adaFile);
        }

        inputFile.addEventListener('change', perbaruiTombol);

        button.addEventListener('click', function () {
            inputFile.value = '';
            inputFile.dispatchEvent(new Event('change', { bubbles: true }));
        });

        perbaruiTombol();
    });

    // Mendeteksi perubahan formulir
    form.addEventListener('input', function () {
        adaPerubahan = true;
    });

    form.addEventListener('change', function () {
        adaPerubahan = true;
    });

    // Konfirmasi pembatalan
    if (tombolBatal) {
        tombolBatal.addEventListener('click', function (event) {
            if (!adaPerubahan) return;

            const yakin = window.confirm(
                'Ada perubahan yang belum disimpan. Apakah Anda yakin ingin membatalkan?'
            );

            if (!yakin) {
                event.preventDefault();
                return;
            }

            sedangMenyimpan = true;
        });
    }

    // Konfirmasi penyimpanan
    form.addEventListener('submit', function (event) {
        const yakin = window.confirm(
            'Apakah Anda yakin data produk yang diinput sudah benar?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan saat meninggalkan halaman
    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan && !sedangMenyimpan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});