document.addEventListener('DOMContentLoaded', function () {

    const filterForm = document.getElementById('activity-filter-form');
    const searchInput = document.getElementById('search');
    const hasilAktivitas = document.getElementById('hasil-aktivitas');
    const tombolHapus = document.getElementById('hapus-search');

    if (!filterForm || !searchInput || !hasilAktivitas) {
        console.error('Elemen Live Search Riwayat Aktivitas tidak ditemukan.');
        return;
    }

    let timerPencarian;
    let controllerPencarian;

    // Membuat URL berdasarkan filter
    function buatUrl(pageUrl = null) {
       const url = new URL(pageUrl || filterForm.getAttribute('action'), window.location.origin);
        const params = new URLSearchParams(new FormData(filterForm));

        params.forEach(function (value, key) {
            if (value !== '') {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        if (!pageUrl) {
            url.searchParams.delete('page');
        }

        return url;
    }

    // Mengambil hasil pencarian tanpa reload
    async function cariAktivitas(url) {
        if (controllerPencarian) {
            controllerPencarian.abort();
        }

        const controller = new AbortController();
        controllerPencarian = controller;

        try {
            const response = await fetch(url.toString(), {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil riwayat aktivitas.');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(html, 'text/html');
            const hasilBaru = dokumen.getElementById('hasil-aktivitas');

            if (!hasilBaru) {
                throw new Error('Area hasil aktivitas tidak ditemukan.');
            }

            if (controller.signal.aborted) {
                return;
            }

            hasilAktivitas.innerHTML = hasilBaru.innerHTML;

            // Menyesuaikan URL tanpa reload
            window.history.replaceState({}, '', url.toString());

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        }
    }

    // Menampilkan tombol X
    function tampilkanTombolHapus() {
        tombolHapus.style.display = searchInput.value ? 'inline-flex' : 'none';
    }

    tampilkanTombolHapus();

    searchInput.addEventListener('input', tampilkanTombolHapus);

    // Menghapus pencarian
    tombolHapus.addEventListener('click', function () {
        searchInput.value = '';
        tampilkanTombolHapus();

        clearTimeout(timerPencarian);
        cariAktivitas(buatUrl());

        searchInput.focus();
    });

    // Live Search saat mengetik
    searchInput.addEventListener('input', function () {
        clearTimeout(timerPencarian);

        timerPencarian = setTimeout(function () {
            cariAktivitas(buatUrl());
        }, 350);
    });

    // Filter otomatis saat pilihan berubah
    filterForm.querySelectorAll('select, input[type="date"]').forEach(function (element) {
        element.addEventListener('change', function () {
            clearTimeout(timerPencarian);
            cariAktivitas(buatUrl());
        });
    });

    // Tombol Terapkan
    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(timerPencarian);
        cariAktivitas(buatUrl());
    });

    // Pagination tanpa reload
    hasilAktivitas.addEventListener('click', function (event) {
        const link = event.target.closest('.activity-pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        clearTimeout(timerPencarian);

        cariAktivitas(buatUrl(link.href));
    });

    // Tombol detail aktivitas
    hasilAktivitas.addEventListener('click', function (event) {
        const button = event.target.closest('.activity-btn-detail');

        if (!button) {
            return;
        }

        const detail = document.getElementById(button.dataset.detailId);

        if (!detail) {
            return;
        }

        const willOpen = detail.hidden;
        detail.hidden = !willOpen;

        button.setAttribute('aria-expanded', String(willOpen));
        button.innerHTML = willOpen
            ? '<i class="bi bi-eye-slash"></i> Tutup'
            : '<i class="bi bi-eye"></i> Detail';
    });

});