document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.form-container form');
    const tombolBatal = document.querySelector('.form-container .btn-cancel');

    if (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Apakah data kategori produk yang Anda isi sudah benar dan ingin disimpan?')) {
                event.preventDefault();
            }
        });
    }

    if (tombolBatal) {
        tombolBatal.addEventListener('click', function (event) {
            if (!confirm('Apakah Anda yakin ingin membatalkan pengisian data kategori produk? Data yang sudah diisi tidak akan disimpan.')) {
                event.preventDefault();
            }
        });
    }
});