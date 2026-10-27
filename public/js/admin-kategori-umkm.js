document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cari-kategori');
    const form = document.getElementById('form-cari-kategori');
    const hasil = document.getElementById('hasil-kategori');
    const tombolHapus = document.getElementById('hapus-cari-kategori');
    const tombolPdfFilter = document.getElementById('pdf-hasil-kategori');

    if (!input || !form || !hasil || !tombolHapus || !tombolPdfFilter) {
        return;
    }

    // Memperbarui tautan PDF hasil pencarian
    function perbaruiLinkPdf() {
        const url = new URL(tombolPdfFilter.href);

        url.searchParams.set('mode', 'filter');
        url.searchParams.set('cari', input.value.trim());

        tombolPdfFilter.href = url.toString();
    }

    // Menampilkan atau menyembunyikan tombol hapus pencarian
    function tampilkanTombolHapus() {
        tombolHapus.style.display = input.value ? 'inline-flex' : 'none';
    }

    perbaruiLinkPdf();
    tampilkanTombolHapus();

    input.addEventListener('input', perbaruiLinkPdf);
    input.addEventListener('input', tampilkanTombolHapus);

    // Menghapus kata kunci pencarian
    tombolHapus.addEventListener('click', function () {
        input.value = '';

        tampilkanTombolHapus();

        input.dispatchEvent(new Event('input', {
            bubbles: true
        }));

        input.focus();
    });

    let timer;
    let controller;

    // Mengambil hasil pencarian dan pagination
    async function cariKategori(url) {
        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        try {
            const response = await fetch(url, {
                signal: controller.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data');
            }

            const html = await response.text();

            const dokumen = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            const hasilBaru = dokumen.getElementById('hasil-kategori');

            if (hasilBaru) {
                hasil.innerHTML = hasilBaru.innerHTML;

                window.history.replaceState({}, '', url);
            }

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        }
    }

    // Pencarian otomatis saat mengetik
    input.addEventListener('input', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            const url = new URL(form.action);

            url.searchParams.set('cari', input.value);

            cariKategori(url.toString());
        }, 350);
    });

    // Pagination tanpa memuat ulang halaman
    hasil.addEventListener('click', function (event) {
        const link = event.target.closest('.pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();

        clearTimeout(timer);

        cariKategori(link.href);
    });

    // Pencarian menggunakan Enter
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        clearTimeout(timer);

        const url = new URL(form.action);

        url.searchParams.set('cari', input.value);

        cariKategori(url.toString());
    });

    // Konfirmasi sebelum menghapus kategori UMKM
    hasil.addEventListener('submit', function (event) {
        const formHapus = event.target;

        if (!formHapus.matches('form[data-confirm-delete]')) {
            return;
        }

        if (!confirm('Apakah Anda yakin ingin menghapus kategori UMKM ini?')) {
            event.preventDefault();
        }
    });
});