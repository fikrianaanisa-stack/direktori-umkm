// Pengelolaan formulir perubahan password admin
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPassword');
    const btnBatal = document.getElementById('btnBatal');

    if (!form || !btnBatal) return;

    let bolehMeninggalkan = false;

    const inputPassword = form.querySelectorAll('input[name="current_password"], input[name="password"], input[name="password_confirmation"]');

    function adaPerubahan() {
        return Array.from(inputPassword).some(input => input.value !== '');
    }

    // Pengaturan visibilitas password
    form.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const icon = button.querySelector('i');

            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
                button.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
                button.setAttribute('aria-label', 'Tampilkan password');
            }
        });
    });

    // Konfirmasi perubahan password
    form.addEventListener('submit', function (event) {
        if (!window.confirm('Yakin ingin mengubah password akun?')) {
            event.preventDefault();
            return;
        }

        bolehMeninggalkan = true;
    });

    // Konfirmasi pembatalan perubahan
    btnBatal.addEventListener('click', function (event) {
        if (adaPerubahan()) {
            if (!window.confirm('Perubahan belum disimpan. Yakin ingin membatalkan?')) {
                event.preventDefault();
            } else {
                bolehMeninggalkan = true;
            }
        }
    });

    // Peringatan perubahan yang belum disimpan
    window.addEventListener('beforeunload', function (event) {
        if (adaPerubahan() && !bolehMeninggalkan) {
            event.preventDefault();
            event.returnValue = '';
        }
    });
});