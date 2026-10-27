document.addEventListener('DOMContentLoaded', function () {
    const formCari = document.getElementById('form-cari-produk');
    const inputCari = document.getElementById('cari-produk');
    const hasilProduk = document.getElementById('hasil-produk');
    const tombolPdfHasil = document.getElementById('pdf-hasil-produk');
    const tombolHapus = document.getElementById('hapus-cari-produk');

    if (!formCari || !inputCari || !hasilProduk) return;

    let timerPencarian;
    let controllerPencarian;

    // Mengatur tombol hapus pencarian
    function tampilkanTombolHapus() {
        if (!tombolHapus) return;

        tombolHapus.style.display = inputCari.value
            ? 'inline-flex'
            : 'none';
    }

    tampilkanTombolHapus();

    inputCari.addEventListener('input', tampilkanTombolHapus);

    if (tombolHapus) {
        tombolHapus.addEventListener('click', function () {
            inputCari.value = '';
            inputCari.dispatchEvent(
                new Event('input', { bubbles: true })
            );
            inputCari.focus();
        });
    }

    // Menyesuaikan PDF dengan pencarian
    if (tombolPdfHasil) {
        tombolPdfHasil.addEventListener('click', function () {
            const url = new URL(tombolPdfHasil.href);
            const kataKunci = inputCari.value.trim();

            url.searchParams.set('mode', 'filter');

            if (kataKunci) {
                url.searchParams.set('cari', kataKunci);
            } else {
                url.searchParams.delete('cari');
            }

            tombolPdfHasil.href = url.toString();
        });
    }

    // Mengambil hasil pencarian tanpa reload
    async function cariProduk(url) {
        if (controllerPencarian) {
            controllerPencarian.abort();
        }

        controllerPencarian = new AbortController();

        try {
            const response = await fetch(url, {
                signal: controllerPencarian.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data produk.');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const hasilBaru = dokumen.getElementById('hasil-produk');

            if (!hasilBaru) {
                throw new Error('Area hasil produk tidak ditemukan.');
            }

            hasilProduk.innerHTML = hasilBaru.innerHTML;

            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Live Search Produk:', error);
            }
        }
    }

    // Menjalankan pencarian otomatis
    inputCari.addEventListener('input', function () {
        clearTimeout(timerPencarian);

        timerPencarian = setTimeout(function () {
            const url = new URL(formCari.action);
            const kataKunci = inputCari.value.trim();

            if (kataKunci) {
                url.searchParams.set('cari', kataKunci);
            } else {
                url.searchParams.delete('cari');
            }

            url.searchParams.delete('page');

            cariProduk(url.toString());
        }, 350);
    });

    // Mengatur pagination AJAX
    hasilProduk.addEventListener('click', function (event) {
        const link = event.target.closest('.produk-pagination a');

        if (!link) return;

        event.preventDefault();
        clearTimeout(timerPencarian);

        const url = new URL(link.href);
        const kataKunci = inputCari.value.trim();

        if (kataKunci) {
            url.searchParams.set('cari', kataKunci);
        } else {
            url.searchParams.delete('cari');
        }

        cariProduk(url.toString());
    });

    // Mencegah reload saat Enter ditekan
    formCari.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(timerPencarian);

        const url = new URL(formCari.action);
        const kataKunci = inputCari.value.trim();

        if (kataKunci) {
            url.searchParams.set('cari', kataKunci);
        } else {
            url.searchParams.delete('cari');
        }

        url.searchParams.delete('page');

        cariProduk(url.toString());
    });

    // Konfirmasi sebelum menghapus produk
    hasilProduk.addEventListener('submit', function (event) {
        const formHapus = event.target.closest('form[data-confirm-delete]');

        if (!formHapus) return;

        if (!window.confirm('Yakin ingin menghapus produk ini?')) {
            event.preventDefault();
        }
    });
});