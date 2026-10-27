document.addEventListener('DOMContentLoaded', function () {

    // Mengambil elemen pencarian dan daftar UMKM
    const inputCari = document.getElementById('cari');
    const kartuUmkm = document.querySelectorAll('.umkm-list-item');
    const jumlahHasil = document.getElementById('jumlah-hasil');
    const pencarianKosong = document.getElementById('pencarian-kosong');

    if (!inputCari) {
        return;
    }

    // Menyaring kartu berdasarkan nama UMKM
    function filterUmkm() {
        const kataKunci = inputCari.value.toLocaleLowerCase('id-ID').trim();
        let jumlahDitemukan = 0;

        kartuUmkm.forEach(function (kartu) {
            const namaUmkm = (kartu.dataset.nama || '')
                .toLocaleLowerCase('id-ID');

            const cocok = namaUmkm.includes(kataKunci);

            kartu.hidden = !cocok;

            if (cocok) {
                jumlahDitemukan++;
            }
        });

        // Memperbarui jumlah hasil pencarian
        if (jumlahHasil) {
            jumlahHasil.textContent = kataKunci
                ? 'Ditemukan ' + jumlahDitemukan + ' UMKM'
                : 'Menampilkan ' + jumlahDitemukan + ' UMKM';
        }

        // Menampilkan pesan jika hasil kosong
        if (pencarianKosong) {
            pencarianKosong.hidden =
                jumlahDitemukan !== 0 || kartuUmkm.length === 0;
        }
    }

    // Menjalankan pencarian saat pengguna mengetik
    inputCari.addEventListener('input', filterUmkm);

    // Menyesuaikan hasil dengan nilai pencarian awal
    filterUmkm();

});