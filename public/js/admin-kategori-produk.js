document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('kategoriSearch');
    const form = document.getElementById('kategoriSearchForm');
    const hasil = document.getElementById('hasil-kategori-produk');
    const tombolHapus = document.getElementById('kategoriSearchClear');
    const tombolPdfFilter = document.getElementById('pdf-hasil-kategori-produk');

    if (!input || !form || !hasil || !tombolHapus || !tombolPdfFilter) {
        return;
    }

    let timer;
    let controller;

    // Memperbarui tautan PDF
    function perbaruiLinkPdf() {
        const url = new URL(tombolPdfFilter.href);

        url.searchParams.set('mode', 'filter');
        url.searchParams.set('cari', input.value.trim());

        tombolPdfFilter.href = url.toString();
    }

    // Menampilkan tombol hapus pencarian
    function tampilkanTombolHapus() {
        tombolHapus.hidden = input.value === '';
    }

    // Mengambil hasil pencarian
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
                throw new Error('Gagal mengambil data kategori produk');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(html, 'text/html');
            const hasilBaru = dokumen.getElementById('hasil-kategori-produk');

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

    // Pencarian otomatis
    input.addEventListener('input', function () {
        perbaruiLinkPdf();
        tampilkanTombolHapus();

        clearTimeout(timer);

        timer = setTimeout(function () {
            const url = new URL(form.action);
            url.searchParams.set('cari', input.value);

            cariKategori(url.toString());
        }, 350);
    });

    // Menghapus pencarian
    tombolHapus.addEventListener('click', function () {
        input.value = '';
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.focus();
    });

    // Pagination AJAX
    hasil.addEventListener('click', function (event) {
        const link = event.target.closest('.pagination a');

        if (!link) {
            return;
        }

        event.preventDefault();
        clearTimeout(timer);

        cariKategori(link.href);
    });

    // Pencarian dengan Enter
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(timer);

        const url = new URL(form.action);
        url.searchParams.set('cari', input.value);

        cariKategori(url.toString());
    });

    // Konfirmasi penghapusan kategori
    document.addEventListener('submit', function (event) {
        const formHapus = event.target.closest('.kategori-actions form');

        if (!formHapus) {
            return;
        }

        if (!confirm('Apakah Anda yakin ingin menghapus kategori produk ini?')) {
            event.preventDefault();
        }
    });

    // Pengaturan awal
    perbaruiLinkPdf();
    tampilkanTombolHapus();
});