document.addEventListener('DOMContentLoaded', function () {

    // Mengatur notifikasi toast publik
    const toast = document.getElementById('public-toast');

    if (toast) {
        const closeButton = toast.querySelector('.public-toast-close');

        // Menutup toast dengan animasi
        function closeToast() {
            if (!toast.isConnected || toast.classList.contains('hiding')) {
                return;
            }

            toast.classList.add('hiding');

            setTimeout(function () {
                toast.remove();
            }, 400);
        }

        // Menutup toast melalui tombol
        if (closeButton) {
            closeButton.addEventListener('click', closeToast);
        }

        // Toast sukses hilang otomatis setelah 5 detik
        if (toast.dataset.type === 'success') {
            setTimeout(closeToast, 5000);
        }
    }

    // Mengatur dropdown akun pengguna
    const dropdown = document.querySelector('.public-account-dropdown');
    const toggle = document.getElementById('publicAccountToggle');

    if (dropdown && toggle) {

        // Membuka dan menutup dropdown
        toggle.addEventListener('click', function (event) {
            event.stopPropagation();

            const isOpen = dropdown.classList.toggle('open');

            toggle.setAttribute('aria-expanded', String(isOpen));
        });

        // Menutup dropdown saat klik di luar
        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Menutup dropdown dengan tombol Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Mengatur efek navbar saat halaman di-scroll
    const header = document.querySelector('.public-header');

    if (header) {

        function updateHeader() {
            header.classList.toggle('scrolled', window.scrollY > 20);
        }

        // Memeriksa posisi halaman saat pertama dibuka
        updateHeader();

        // Memperbarui tampilan navbar saat scroll
        window.addEventListener('scroll', updateHeader, { passive: true });
    }

});