// Konfirmasi perubahan profil admin
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formProfil');
    const btnBatal = document.getElementById('btnBatal');

    if (!form || !btnBatal) return;

    const dataAwal = new URLSearchParams(new FormData(form)).toString();
    let sedangMenyimpan = false;

    function adaPerubahan() {
        return new URLSearchParams(new FormData(form)).toString() !== dataAwal;
    }

    form.addEventListener('submit', function (event) {
        if (!window.confirm('Yakin ingin menyimpan perubahan profil?')) {
            event.preventDefault();
            return;
        }

        sedangMenyimpan = true;
    });

    btnBatal.addEventListener('click', function (event) {
        if (adaPerubahan()) {
            if (!window.confirm('Perubahan belum disimpan. Yakin ingin membatalkan?')) {
                event.preventDefault();
            } else {
                sedangMenyimpan = true;
            }
        }
    });

    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan() && !sedangMenyimpan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});