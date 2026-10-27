document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formTambahWilayah');
    const tombolBatal = document.getElementById('batalTambahWilayah');

    // Konfirmasi penyimpanan wilayah
    if (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Yakin ingin menyimpan data wilayah ini?')) {
                event.preventDefault();
            }
        });
    }

    // Konfirmasi pembatalan penambahan wilayah
    if (tombolBatal) {
        tombolBatal.addEventListener('click', function (event) {
            if (!confirm('Batalkan penambahan wilayah?')) {
                event.preventDefault();
            }
        });
    }
});