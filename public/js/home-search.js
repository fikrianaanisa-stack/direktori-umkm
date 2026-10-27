document.addEventListener('DOMContentLoaded', function () {

    // Mengambil elemen Live Search
    const searchInput = document.getElementById('home-live-search');
    const searchResults = document.getElementById('home-search-results');

    // Menghentikan proses jika elemen tidak tersedia
    if (!searchInput || !searchResults) {
        return;
    }

    // Mengambil URL pencarian dari atribut HTML
    const searchUrl = searchInput.dataset.searchUrl;

    let debounceTimer = null;
    let activeController = null;
    let requestNumber = 0;

    // Menutup dropdown hasil pencarian
    function closeResults() {
        searchResults.hidden = true;
        searchInput.setAttribute('aria-expanded', 'false');
    }

    // Membuka dropdown hasil pencarian
    function openResults() {
        searchResults.hidden = false;
        searchInput.setAttribute('aria-expanded', 'true');
    }

    // Membuat kelompok hasil pencarian UMKM atau produk
    function createSection(title, items) {

        const section = document.createElement('div');
        section.className = 'home-search-section';

        const heading = document.createElement('h3');
        heading.textContent = `${title} (${items.length})`;

        section.appendChild(heading);

        // Membuat tautan untuk setiap hasil pencarian
        items.forEach(function (item) {

            const link = document.createElement('a');

            link.href = item.url;
            link.className = 'home-search-item';
            link.textContent = item.nama;

            section.appendChild(link);
        });

        return section;
    }

    // Menampilkan hasil pencarian
    function displayResults(data) {

        searchResults.replaceChildren();

        const umkm = Array.isArray(data.umkm) ? data.umkm : [];
        const produk = Array.isArray(data.produk) ? data.produk : [];

        // Menampilkan pesan jika tidak ditemukan data
        if (umkm.length === 0 && produk.length === 0) {

            const message = document.createElement('p');

            message.className = 'home-search-empty';
            message.textContent = 'UMKM atau produk tidak ditemukan.';

            searchResults.appendChild(message);
            openResults();

            return;
        }

        // Menampilkan hasil pencarian UMKM
        if (umkm.length > 0) {
            searchResults.appendChild(createSection('UMKM', umkm));
        }

        // Menampilkan hasil pencarian produk
        if (produk.length > 0) {
            searchResults.appendChild(createSection('Produk', produk));
        }

        openResults();
    }

    // Menampilkan pesan ketika pencarian gagal
    function showSearchError() {

        searchResults.replaceChildren();

        const message = document.createElement('p');

        message.className = 'home-search-empty';
        message.textContent = 'Terjadi kesalahan saat mencari data.';

        searchResults.appendChild(message);
        openResults();
    }

    // Menangani perubahan kata kunci pencarian
    searchInput.addEventListener('input', function () {

        clearTimeout(debounceTimer);

        // Membatalkan permintaan pencarian sebelumnya
        if (activeController) {
            activeController.abort();
            activeController = null;
        }

        const keyword = searchInput.value.trim();
        const currentRequest = ++requestNumber;

        // Menghapus hasil jika input kosong
        if (keyword === '') {
            searchResults.replaceChildren();
            closeResults();
            return;
        }

        // Menunda pencarian selama 300 milidetik
        debounceTimer = setTimeout(async function () {

            const controller = new AbortController();
            activeController = controller;

            try {

                // Membuat URL pencarian
                const url = new URL(searchUrl, window.location.origin);
                url.searchParams.set('cari', keyword);

                // Mengambil data dari server Laravel
                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Pencarian gagal.');
                }

                const data = await response.json();

                // Mengabaikan respons pencarian yang sudah tidak berlaku
                if (currentRequest !== requestNumber) {
                    return;
                }

                displayResults(data);

            } catch (error) {

                // Mengabaikan permintaan yang sengaja dibatalkan
                if (error.name === 'AbortError') {
                    return;
                }

                console.error('Live Search Error:', error);

                if (currentRequest !== requestNumber) {
                    return;
                }

                showSearchError();

            } finally {

                // Membersihkan controller setelah permintaan selesai
                if (activeController === controller) {
                    activeController = null;
                }
            }

        }, 300);
    });

    // Menutup hasil pencarian saat klik di luar area
    document.addEventListener('click', function (event) {

        if (!event.target.closest('.home-search-wrapper')) {
            closeResults();
        }
    });

    // Membuka kembali hasil saat input mendapatkan fokus
    searchInput.addEventListener('focus', function () {

        if (
            searchInput.value.trim() !== '' &&
            searchResults.hasChildNodes()
        ) {
            openResults();
        }
    });

    // Menutup hasil pencarian menggunakan tombol Escape
    searchInput.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeResults();
        }
    });

});