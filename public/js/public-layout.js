document.addEventListener('DOMContentLoaded', function () {

    // Mengaktifkan notifikasi toast Bootstrap
    const toastElement = document.getElementById('public-toast');

    if (toastElement && typeof bootstrap !== 'undefined') {

        // Membuat instance toast sesuai pengaturan HTML
        const toast = bootstrap.Toast.getOrCreateInstance(toastElement);

        // Menampilkan notifikasi
        toast.show();
    }

    // Mengatur perubahan tampilan header saat scroll
    const header = document.querySelector('.public-header');

    if (header) {

        // Menambahkan class scrolled ketika halaman digulir
        function updateHeader() {
            header.classList.toggle('scrolled', window.scrollY > 20);
        }

        // Memeriksa posisi scroll saat halaman dibuka
        updateHeader();

        // Memperbarui tampilan header ketika pengguna scroll
        window.addEventListener('scroll', updateHeader, {
            passive: true
        });
    }

});