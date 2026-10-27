document.addEventListener('DOMContentLoaded', function () {
const inputCari = document.getElementById('cari-wilayah');
const tombolPdfHasil = document.getElementById('pdf-hasil-wilayah');
const hasilWilayah = document.getElementById('hasil-wilayah');
const tombolHapus = document.getElementById('hapus-cari-wilayah');

if (!inputCari || !tombolPdfHasil || !hasilWilayah || !tombolHapus) {
    return;
}

let timerPencarian;
let permintaanAktif;
let nomorPermintaan = 0;

// Memperbarui link PDF hasil pencarian
function perbaruiLinkPdf() {
    const kataKunci = inputCari.value.trim();
    const url = new URL(tombolPdfHasil.href);

    url.searchParams.set('mode', 'filter');

    if (kataKunci !== '') {
        url.searchParams.set('search', kataKunci);
    } else {
        url.searchParams.delete('search');
    }

    tombolPdfHasil.href = url.toString();
    tombolPdfHasil.classList.toggle('disabled', kataKunci === '');
    tombolPdfHasil.setAttribute(
        'aria-disabled',
        String(kataKunci === '')
    );
}

// Menampilkan tombol hapus pencarian
function tampilkanTombolHapus() {
    tombolHapus.hidden = inputCari.value.trim() === '';
}

// Membuat URL pencarian
function buatUrlPencarian() {
    const url = new URL(window.location.href);
    const kataKunci = inputCari.value.trim();

    if (kataKunci !== '') {
        url.searchParams.set('search', kataKunci);
    } else {
        url.searchParams.delete('search');
    }

    url.searchParams.delete('page');

    return url.toString();
}

// Memuat data tanpa refresh halaman
async function muatData(url) {
    if (permintaanAktif) {
        permintaanAktif.abort();
    }

    const controller = new AbortController();
    permintaanAktif = controller;
    const nomorSaatIni = ++nomorPermintaan;

    try {
        const response = await fetch(url, {
            signal: controller.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil data wilayah.');
        }

        const html = await response.text();
        const dokumen = new DOMParser().parseFromString(
            html,
            'text/html'
        );

        const hasilBaru = dokumen.getElementById('hasil-wilayah');

        if (!hasilBaru) {
            throw new Error('Hasil pencarian tidak ditemukan.');
        }

        if (nomorSaatIni !== nomorPermintaan) {
            return;
        }

        hasilWilayah.innerHTML = hasilBaru.innerHTML;
        window.history.replaceState({}, '', url);

    } catch (error) {
        if (error.name !== 'AbortError') {
            console.error(error);
            alert('Pencarian gagal. Silakan coba lagi.');
        }
    } finally {
        if (permintaanAktif === controller) {
            permintaanAktif = null;
        }
    }
}

// Pencarian otomatis saat mengetik
inputCari.addEventListener('input', function () {
    tampilkanTombolHapus();
    perbaruiLinkPdf();

    clearTimeout(timerPencarian);

    timerPencarian = setTimeout(function () {
        muatData(buatUrlPencarian());
    }, 350);
});

// Menghapus kata kunci pencarian
tombolHapus.addEventListener('click', function () {
    inputCari.value = '';

    inputCari.dispatchEvent(
        new Event('input', { bubbles: true })
    );

    inputCari.focus();
});

// Pagination tanpa refresh halaman
hasilWilayah.addEventListener('click', function (event) {
    const link = event.target.closest('.wilayah-pagination a');

    if (!link) {
        return;
    }

    event.preventDefault();
    clearTimeout(timerPencarian);

    muatData(link.href);
});

// Mencegah PDF hasil pencarian tanpa kata kunci
tombolPdfHasil.addEventListener('click', function (event) {
    if (inputCari.value.trim() === '') {
        event.preventDefault();
    }
});

// Konfirmasi sebelum menghapus data wilayah
hasilWilayah.addEventListener('submit', function (event) {
    const form = event.target;

    if (!form.matches('form[data-confirm-delete]')) {
        return;
    }

    const yakin = confirm(
        'Apakah Anda yakin ingin menghapus data wilayah ini?'
    );

    if (!yakin) {
        event.preventDefault();
    }
});

tampilkanTombolHapus();
perbaruiLinkPdf();
});