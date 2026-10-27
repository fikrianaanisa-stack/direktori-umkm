document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pengunjungFilterForm');
    const search = document.getElementById('search');
    const status = document.getElementById('status');
    const results = document.getElementById('pengunjungResults');
    const reset = document.getElementById('resetPengunjung');

    if (!form || !search || !status || !results) return;

    let timer;
    let controller;

    // Mengambil hasil pencarian tanpa memuat ulang halaman
    async function loadResults(url) {
        if (controller) controller.abort();

        controller = new AbortController();
        const currentController = controller;

        results.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                signal: currentController.signal,
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data pengunjung.');
            }

            const html = await response.text();
            const documentResult = new DOMParser().parseFromString(html, 'text/html');
            const newResults = documentResult.getElementById('pengunjungResults');

            if (!newResults) {
                throw new Error('Hasil pencarian tidak ditemukan.');
            }

            // Mengganti tabel dan pagination
            results.innerHTML = newResults.innerHTML;

            // Memperbarui alamat URL
            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);

                // Form tetap dapat digunakan jika live search gagal
                results.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Gagal memuat data pengunjung. Silakan tekan Enter untuk mencoba kembali.
                    </div>
                `;
            }
        } finally {
            if (controller === currentController) {
                results.setAttribute('aria-busy', 'false');
            }
        }
    }

    // Membentuk URL berdasarkan pencarian dan filter
    function getFilterUrl() {
        const url = new URL(form.action);

        if (search.value.trim()) {
            url.searchParams.set('search', search.value.trim());
        }

        if (status.value) {
            url.searchParams.set('status', status.value);
        }

        return url.toString();
    }

    // Menjalankan pencarian setelah pengguna berhenti mengetik
    search.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            loadResults(getFilterUrl());
        }, 300);
    });

    // Filter status langsung memperbarui tabel
    status.addEventListener('change', function () {
        clearTimeout(timer);
        loadResults(getFilterUrl());
    });

    // Menangani pagination tanpa reload halaman
    results.addEventListener('click', function (event) {
        const link = event.target.closest('.pagination a');

        if (!link) return;

        event.preventDefault();
        clearTimeout(timer);

        loadResults(link.href);
    });

    // Reset pencarian dan filter
    if (reset) {
        reset.addEventListener('click', function (event) {
            event.preventDefault();

            clearTimeout(timer);
            search.value = '';
            status.value = '';

            loadResults(form.action);
        });
    }

    // Pencarian dengan Enter tetap tersedia
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        clearTimeout(timer);
        loadResults(getFilterUrl());
    });
});