// Konfirmasi dan peringatan perubahan kategori UMKM
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditKategori');

    if (!form) return;

    const namaKategori = document.getElementById('nama_kategori');
    const deskripsi = document.getElementById('deskripsi');

    const nilaiAwal = {
        nama: namaKategori.value,
        deskripsi: deskripsi.value
    };

    let sedangMenyimpan = false;

    function adaPerubahan() {
        return namaKategori.value !== nilaiAwal.nama ||
               deskripsi.value !== nilaiAwal.deskripsi;
    }

    // Konfirmasi penyimpanan perubahan
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) return;

        if (!window.confirm('Apakah Anda yakin ingin menyimpan perubahan kategori UMKM ini?')) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan perubahan yang belum disimpan
    window.addEventListener('beforeunload', function (event) {
        if (sedangMenyimpan || !adaPerubahan()) return;

        event.preventDefault();
        event.returnValue = '';
    });
});