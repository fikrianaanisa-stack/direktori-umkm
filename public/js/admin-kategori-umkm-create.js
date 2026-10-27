// Konfirmasi penyimpanan dan pembatalan kategori UMKM
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formKategoriUmkm');
    const btnBatal = document.getElementById('btnBatalKategoriUmkm');

    if (!form || !btnBatal) return;

    form.addEventListener('submit', function (event) {
        const pesan = 'Apakah data kategori UMKM yang Anda isi sudah benar dan ingin disimpan?';

        if (!window.confirm(pesan)) {
            event.preventDefault();
        }
    });

    btnBatal.addEventListener('click', function (event) {
        const pesan = 'Apakah Anda yakin ingin membatalkan penambahan kategori UMKM? Data yang sudah diisi tidak akan disimpan.';

        if (!window.confirm(pesan)) {
            event.preventDefault();
        }
    });
});