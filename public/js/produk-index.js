document.addEventListener('DOMContentLoaded', function () {
    // Mengambil elemen pencarian dan daftar produk
    const inputCari = document.getElementById('cari');
    const kartuProduk = document.querySelectorAll('.produk-list-item');
    const jumlahHasil = document.getElementById('jumlah-hasil');
    const pencarianKosong = document.getElementById('pencarian-kosong');

    if (!inputCari) {
        return;
    }

    // Menyaring kartu berdasarkan nama produk
    function filterProduk() {
        const kataKunci = inputCari.value.toLocaleLowerCase('id-ID').trim();
        let jumlahDitemukan = 0;

        kartuProduk.forEach(function (kartu) {
            const namaProduk = (kartu.dataset.nama || '')
                .toLocaleLowerCase('id-ID');

            const cocok = namaProduk.includes(kataKunci);

            kartu.hidden = !cocok;

            if (cocok) {
                jumlahDitemukan++;
            }
        });

        // Memperbarui jumlah hasil pencarian
        if (jumlahHasil) {
            jumlahHasil.textContent = kataKunci
                ? 'Ditemukan ' + jumlahDitemukan + ' produk'
                : 'Menampilkan ' + jumlahDitemukan + ' produk';
        }

        // Menampilkan pesan jika hasil pencarian kosong
        if (pencarianKosong) {
            pencarianKosong.hidden =
                jumlahDitemukan !== 0 || kartuProduk.length === 0;
        }
    }

    // Menjalankan pencarian ketika pengguna mengetik
    inputCari.addEventListener('input', filterProduk);

    // Menyesuaikan hasil dengan nilai pencarian awal
    filterProduk();
});