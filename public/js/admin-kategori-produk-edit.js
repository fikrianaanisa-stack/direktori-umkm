document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditKategoriProduk');

    if (!form) return;

    // Konfirmasi sebelum menyimpan perubahan
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) return;

        const yakin = confirm(
            'Apakah Anda yakin data perubahan kategori produk sudah benar dan ingin disimpan?'
        );

        if (!yakin) {
            event.preventDefault();
        }
    });
});