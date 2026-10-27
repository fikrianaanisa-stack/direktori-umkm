// Konfirmasi pengiriman ulasan pengunjung
document.addEventListener('DOMContentLoaded', function () {
    const formulirUlasan = document.querySelectorAll('.js-konfirmasi-ulasan');

    formulirUlasan.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                return;
            }

            const pesan = 'Apakah rating dan ulasan yang Anda isi sudah benar dan ingin dikirim?';

            if (!window.confirm(pesan)) {
                event.preventDefault();
            }
        });
    });
});