document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('home-live-search');
    const searchResults = document.getElementById('home-search-results');

    if (!searchInput || !searchResults) {
        return;
    }

    const searchUrl = searchInput.dataset.searchUrl;

    let debounceTimer;
    let activeController = null;
    let requestNumber = 0;

    function closeResults() {
        searchResults.hidden = true;
        searchInput.setAttribute('aria-expanded', 'false');
    }

    function openResults() {
        searchResults.hidden = false;
        searchInput.setAttribute('aria-expanded', 'true');
    }

    function createSection(title, items) {

        const section = document.createElement('div');
        section.className = 'home-search-section';

        const heading = document.createElement('h3');
        heading.textContent = `${title} (${items.length})`;

        section.appendChild(heading);

        items.forEach(function (item) {

            const link = document.createElement('a');

            link.href = item.url;
            link.className = 'home-search-item';
            link.textContent = item.nama;

            section.appendChild(link);

        });

        return section;
    }

    function displayResults(data) {

        searchResults.replaceChildren();

        const umkm = Array.isArray(data.umkm) ? data.umkm : [];
        const produk = Array.isArray(data.produk) ? data.produk : [];

        if (umkm.length === 0 && produk.length === 0) {

            const message = document.createElement('p');
            message.className = 'home-search-empty';
            message.textContent = 'UMKM atau produk tidak ditemukan.';

            searchResults.appendChild(message);
            openResults();

            return;
        }

        if (umkm.length > 0) {
            searchResults.appendChild(createSection('UMKM', umkm));
        }

        if (produk.length > 0) {
            searchResults.appendChild(createSection('Produk', produk));
        }

        openResults();
    }

    searchInput.addEventListener('input', function () {

        clearTimeout(debounceTimer);

        if (activeController) {
            activeController.abort();
        }

        const keyword = searchInput.value.trim();
        const currentRequest = ++requestNumber;

        if (keyword === '') {
            searchResults.replaceChildren();
            closeResults();
            return;
        }

        debounceTimer = setTimeout(async function () {

            activeController = new AbortController();

            try {

                const url = new URL(searchUrl, window.location.origin);
                url.searchParams.set('cari', keyword);

                const response = await fetch(url, {
                    signal: activeController.signal,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Pencarian gagal.');
                }

                const data = await response.json();

                if (currentRequest !== requestNumber) {
                    return;
                }

                displayResults(data);

            } catch (error) {

                if (error.name === 'AbortError') {
                    return;
                }

                console.error('Live Search Error:', error);

                if (currentRequest !== requestNumber) {
                    return;
                }

                searchResults.replaceChildren();

                const message = document.createElement('p');
                message.className = 'home-search-empty';
                message.textContent = 'Terjadi kesalahan saat mencari data.';

                searchResults.appendChild(message);
                openResults();
            }

        }, 300);

    });

    document.addEventListener('click', function (event) {

        if (!event.target.closest('.home-search-wrapper')) {
            closeResults();
        }

    });

    searchInput.addEventListener('focus', function () {

        if (
            searchInput.value.trim() !== '' &&
            searchResults.hasChildNodes()
        ) {
            openResults();
        }

    });

    searchInput.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeResults();
        }

    });

});