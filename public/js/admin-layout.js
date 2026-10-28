document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('adminSidebar');
    const userMenu = document.querySelector('.user-menu');

    let toastTimer;

    // Mengatur penutupan notifikasi
    function aktifkanAdminToast(toast) {
        if (!toast) return;

        clearTimeout(toastTimer);

        const tombolTutup = toast.querySelector('.admin-toast-close');

        if (tombolTutup) {
            tombolTutup.addEventListener('click', function () {
                clearTimeout(toastTimer);
                toast.remove();
            });
        }

        // Notifikasi sukses hilang setelah 5 detik
        if (toast.dataset.type === 'success') {
            toastTimer = setTimeout(function () {
                if (!toast.isConnected) return;

                toast.classList.add('admin-toast-fade-out');

                setTimeout(function () {
                    toast.remove();
                }, 400);
            }, 5000);
        }
    }

    // Mengaktifkan notifikasi dari Laravel
    aktifkanAdminToast(document.getElementById('admin-toast'));

    // Menampilkan notifikasi dari AJAX
    window.tampilkanAdminToast = function (judul, pesan, berhasil = true) {
        const toastLama = document.getElementById('admin-toast');

        if (toastLama) {
            toastLama.remove();
        }

        const toast = document.createElement('div');
        toast.id = 'admin-toast';
        toast.className = berhasil
            ? 'admin-toast admin-toast-success'
            : 'admin-toast admin-toast-error';

        toast.dataset.type = berhasil ? 'success' : 'error';
        toast.setAttribute('role', berhasil ? 'status' : 'alert');

        const konten = document.createElement('div');
        konten.className = 'admin-toast-content';

        const teks = document.createElement('div');

        const judulToast = document.createElement('strong');
        judulToast.textContent = judul;

        const pesanToast = document.createElement('p');
        pesanToast.className = 'admin-toast-message';
        pesanToast.textContent = pesan;

        const tombolTutup = document.createElement('button');
        tombolTutup.type = 'button';
        tombolTutup.className = 'admin-toast-close';
        tombolTutup.setAttribute('aria-label', 'Tutup notifikasi');
        tombolTutup.textContent = '×';

        teks.append(judulToast, pesanToast);
        konten.append(teks, tombolTutup);
        toast.appendChild(konten);

        document.body.appendChild(toast);

        aktifkanAdminToast(toast);
    };

    // Membuka dan menutup sidebar
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            if (sidebar.classList.contains('closing')) {
                return;
            }

            if (sidebar.classList.contains('show')) {
                sidebar.classList.add('closing');

                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.setAttribute('aria-label', 'Buka menu navigasi');

                sidebar.classList.remove('show');

                setTimeout(function () {
                    sidebar.classList.remove('closing');
                }, 550);
            } else {
                sidebar.classList.add('show');

                menuToggle.setAttribute('aria-expanded', 'true');
                menuToggle.setAttribute('aria-label', 'Tutup menu navigasi');
            }
        });
    }

    // Menutup dropdown profil saat klik di luar
    document.addEventListener('click', function (event) {
        if (userMenu && !userMenu.contains(event.target)) {
            userMenu.removeAttribute('open');
        }
    });

    // Mengembalikan sidebar saat ukuran browser berubah
    window.addEventListener('resize', function () {
        if (window.innerWidth > 1024 && sidebar && menuToggle) {
            sidebar.classList.remove('show', 'closing');

            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Buka menu navigasi');
        }
    });

    // Mengatur perubahan tampilan header saat scroll
    const header = document.querySelector('.header');

    if (header) {
        function updateHeader() {
            header.classList.toggle('scrolled', window.scrollY > 20);
        }

        updateHeader();

        window.addEventListener('scroll', updateHeader, {
            passive: true
        });
    }
});