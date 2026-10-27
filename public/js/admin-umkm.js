document.addEventListener('DOMContentLoaded', function () {
    const inputCari = document.getElementById('cari-umkm');
    const hasilUmkm = document.getElementById('hasil-umkm');
    const tombolPdfFilter = document.getElementById('pdf-hasil-pencarian');
    const tombolHapus = document.getElementById('hapus-cari-umkm');

    if (!inputCari || !hasilUmkm || !tombolPdfFilter || !tombolHapus) {
        return;
    }

    // Menampilkan atau menyembunyikan tombol X
    function tampilkanTombolHapus() {
        tombolHapus.classList.toggle('is-visible', inputCari.value.length > 0);
    }

    tampilkanTombolHapus();

    inputCari.addEventListener('input', tampilkanTombolHapus);

    // Menghapus kata kunci pencarian
    tombolHapus.addEventListener('click', function () {
        inputCari.value = '';
        inputCari.dispatchEvent(new Event('input', { bubbles: true }));
        inputCari.focus();
    });

    // Memperbarui kata kunci PDF hasil pencarian
    tombolPdfFilter.addEventListener('click', function (event) {
        const kataKunci = inputCari.value.trim();

        if (kataKunci === '') {
            event.preventDefault();
            alert('Silakan cari data UMKM terlebih dahulu.');
            return;
        }

        const url = new URL(tombolPdfFilter.href);

        url.searchParams.set('mode', 'filter');
        url.searchParams.set('cari', kataKunci);

        tombolPdfFilter.href = url.toString();
    });

    let timerPencarian;
    let permintaanAktif;

    // Mengambil data dari Laravel tanpa refresh halaman
    async function muatData(url) {
        if (permintaanAktif) {
            permintaanAktif.abort();
        }

        permintaanAktif = new AbortController();

        try {
            const response = await fetch(url, {
                signal: permintaanAktif.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data UMKM.');
            }

            const html = await response.text();
            const dokumen = new DOMParser().parseFromString(html, 'text/html');
            const hasilBaru = dokumen.getElementById('hasil-umkm');

            if (!hasilBaru) {
                throw new Error('Hasil pencarian tidak ditemukan.');
            }

            hasilUmkm.innerHTML = hasilBaru.innerHTML;
            window.history.replaceState({}, '', url);

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                alert('Pencarian gagal. Silakan coba lagi.');
            }
        }
    }

    // Live Search otomatis saat pengguna mengetik
    inputCari.addEventListener('input', function () {
        clearTimeout(timerPencarian);

        timerPencarian = setTimeout(function () {
            const url = new URL(window.location.href);
            const kataKunci = inputCari.value.trim();

            if (kataKunci !== '') {
                url.searchParams.set('cari', kataKunci);
            } else {
                url.searchParams.delete('cari');
            }

            url.searchParams.delete('page');
            muatData(url.toString());
        }, 350);
    });

   // Pagination tanpa refresh halaman
    document.addEventListener('click', function (event) {
        if (!(event.target instanceof Element)) {
            return;
        }

        const link = event.target.closest('#hasil-umkm .umkm-pagination a');

        if (!link) {
            return;
        }

        if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }

        event.preventDefault();
        clearTimeout(timerPencarian);

        muatData(link.href);
    });

    // Konfirmasi sebelum menghapus UMKM
    document.addEventListener('submit', function (event) {
        if (!event.target.matches('.form-hapus-umkm')) {
            return;
        }

        if (!confirm('Apakah Anda yakin ingin menghapus UMKM ini?')) {
            event.preventDefault();
        }
    });

});