document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-edit-umkm');

    if (!form) {
        return;
    }

    // Mengatur pilihan wilayah
    const kecamatanSelect = document.getElementById('kecamatan');
    const wilayahSelect = document.getElementById('wilayah_id');

    if (kecamatanSelect && wilayahSelect) {
        const semuaWilayah = Array.from(wilayahSelect.options)
            .filter(option => option.value !== '')
            .map(option => option.cloneNode(true));

        const wilayahAwal = wilayahSelect.value;

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

        tampilkanWilayah(wilayahAwal);
    }

    // Mengatur jam operasional harian
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

    // Membatalkan pilihan logo atau foto baru
    form.querySelectorAll('[data-clear-file]').forEach(function (button) {
        const inputFile = document.getElementById(button.dataset.clearFile);

        if (!inputFile) {
            return;
        }

        function perbaruiTombol() {
            button.classList.toggle('d-none', inputFile.files.length === 0);
        }

        inputFile.addEventListener('change', perbaruiTombol);

        button.addEventListener('click', function () {
            inputFile.value = '';
            inputFile.dispatchEvent(new Event('change', { bubbles: true }));
        });

        perbaruiTombol();
    });

    // Mengambil kondisi formulir untuk mendeteksi perubahan
    function ambilDataForm() {
        const data = [];

        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            if (!field.name) {
                return;
            }

            if (field.type === 'file') {
                const files = Array.from(field.files).map(function (file) {
                    return [file.name, file.size, file.lastModified];
                });

                data.push([field.name, files]);
            } else if (field.type === 'checkbox' || field.type === 'radio') {
                data.push([field.name, field.checked, field.value]);
            } else {
                data.push([field.name, field.value]);
            }
        });

        return JSON.stringify(data);
    }

    let dataAwal = ambilDataForm();
    let penyimpananBerhasil = false;
    let sedangMenyimpan = false;

    function adaPerubahan() {
        return ambilDataForm() !== dataAwal;
    }

    // Peringatan jika perubahan belum disimpan
    window.addEventListener('beforeunload', function (event) {
        if (!penyimpananBerhasil && adaPerubahan()) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    // Menyimpan perubahan melalui AJAX
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        if (sedangMenyimpan) {
            return;
        }

        const yakin = window.confirm(
            'Apakah Anda yakin data UMKM sudah benar dan ingin menyimpan perubahan?'
        );

        if (!yakin) {
            return;
        }

        const tombolSimpan = form.querySelector('button[type="submit"]');
        const isiTombolAwal = tombolSimpan ? tombolSimpan.innerHTML : '';

        sedangMenyimpan = true;

        if (tombolSimpan) {
            tombolSimpan.disabled = true;
            tombolSimpan.textContent = 'Menyimpan...';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const hasil = await response.json();

            if (!response.ok || hasil.success === false) {
                const semuaError = hasil.errors
                    ? Object.values(hasil.errors).flat()
                    : [];

                const pesan = semuaError.length
                    ? semuaError.join('\n')
                    : (hasil.message || 'Data UMKM gagal disimpan.');

                window.tampilkanAdminToast('Validasi Gagal', pesan, false);
                return;
            }

            dataAwal = ambilDataForm();
                penyimpananBerhasil = false;

            window.tampilkanAdminToast(
                'Berhasil',
                hasil.message || 'Data UMKM berhasil diperbarui.',
                true
            );

            if (hasil.redirect) {
                setTimeout(function () {
                    window.location.href = hasil.redirect;
                }, 1500);
            }
        } catch (error) {
            console.error('Kesalahan AJAX Edit UMKM:', error);

            window.tampilkanAdminToast(
                'Terjadi Kesalahan',
                'Koneksi atau respons server bermasalah. Periksa status penyimpanan sebelum mencoba lagi.',
                false
            );
        } finally {
            sedangMenyimpan = false;

            if (tombolSimpan) {
                tombolSimpan.disabled = false;
                tombolSimpan.innerHTML = isiTombolAwal;
            }
        }
    });
});