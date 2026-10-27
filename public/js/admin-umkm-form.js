document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-umkm');

    if (!form) {
        return;
    }

    // Mengatur pilihan wilayah berdasarkan kecamatan
    const kecamatanSelect = document.getElementById('kecamatan');
    const wilayahSelect = document.getElementById('wilayah_id');

    if (kecamatanSelect && wilayahSelect) {
        const semuaWilayah = Array.from(wilayahSelect.options)
            .filter(option => option.value !== '')
            .map(option => option.cloneNode(true));

        const wilayahLama = wilayahSelect.value;

        function tampilkanWilayah(wilayahDipilih = '') {
            const kecamatanDipilih = kecamatanSelect.value;

            wilayahSelect.replaceChildren();

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = kecamatanDipilih
                ? '-- Pilih Desa / Kelurahan / Negeri --'
                : '-- Pilih Kecamatan Terlebih Dahulu --';

            wilayahSelect.appendChild(placeholder);

            semuaWilayah.forEach(function (option) {
                if (option.dataset.kecamatan === kecamatanDipilih) {
                    wilayahSelect.appendChild(option.cloneNode(true));
                }
            });

            if (wilayahDipilih) {
                wilayahSelect.value = wilayahDipilih;
            }
        }

        kecamatanSelect.addEventListener('change', function () {
            tampilkanWilayah();
        });

        tampilkanWilayah(wilayahLama);
    }

    // Mengatur jam operasional berdasarkan status setiap hari
    const hariList = [
        'Senin', 'Selasa', 'Rabu', 'Kamis',
        'Jumat', 'Sabtu', 'Minggu'
    ];

    hariList.forEach(function (hari) {
        const status = document.getElementById('status-' + hari);

        const jamBuka = form.querySelector(
            `[name="jadwal[${hari}][jam_buka]"]`
        );

        const jamTutup = form.querySelector(
            `[name="jadwal[${hari}][jam_tutup]"]`
        );

        if (!status || !jamBuka || !jamTutup) {
            return;
        }

        function perbaruiKolomJam() {
            const isBuka = status.value === 'buka';

            jamBuka.disabled = !isBuka;
            jamTutup.disabled = !isBuka;
        }

        status.addEventListener('change', perbaruiKolomJam);

        perbaruiKolomJam();
    });

    // Menghapus pilihan file logo atau foto
    document.querySelectorAll('[data-clear-file]').forEach(function (button) {
        const inputFile = document.getElementById(button.dataset.clearFile);

        if (!inputFile) {
            return;
        }

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

    // Mendeteksi perubahan pada formulir
    let adaPerubahan = false;
    let sedangMenyimpan = false;

    form.addEventListener('input', function () {
        adaPerubahan = true;
    });

    form.addEventListener('change', function () {
        adaPerubahan = true;
    });

    // Konfirmasi sebelum menyimpan data
    form.addEventListener('submit', function (event) {
        const yakin = window.confirm(
            'Apakah Anda yakin data UMKM yang dimasukkan sudah benar?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan jika perubahan belum disimpan
    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan && !sedangMenyimpan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});