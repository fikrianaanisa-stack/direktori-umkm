document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formEditWilayah');

    if (!form) return;

    const kecamatan = document.getElementById('kecamatan');
    const kelurahanDesa = document.getElementById('kelurahan_desa');
    const jenisWilayah = document.getElementById('jenis_wilayah');

    const nilaiAwal = {
        kecamatan: kecamatan.value,
        kelurahanDesa: kelurahanDesa.value,
        jenisWilayah: jenisWilayah.value
    };

    let sedangMenyimpan = false;

    // Memeriksa perubahan pada formulir.
    function adaPerubahan() {
        return (
            kecamatan.value !== nilaiAwal.kecamatan ||
            kelurahanDesa.value !== nilaiAwal.kelurahanDesa ||
            jenisWilayah.value !== nilaiAwal.jenisWilayah
        );
    }

    // Konfirmasi penyimpanan.
    form.addEventListener('submit', function (event) {

        if (!form.checkValidity()) return;

        const yakin = confirm(
            'Apakah Anda yakin ingin menyimpan perubahan data wilayah ini?'
        );

        if (!yakin) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    // Peringatan ketika meninggalkan halaman
    // dengan perubahan yang belum disimpan.
    window.addEventListener('beforeunload', function (event) {

        if (sedangMenyimpan || !adaPerubahan()) return;

        event.preventDefault();
        event.returnValue = '';

    });

});