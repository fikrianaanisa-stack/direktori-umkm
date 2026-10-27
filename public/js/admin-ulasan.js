document.addEventListener('DOMContentLoaded', function () {
    const konfirmasi = {
        approve: 'Yakin ingin menyetujui ulasan ini?',
        reject: 'Yakin ingin menolak ulasan ini?',
        delete: 'Yakin ingin menghapus ulasan ini secara permanen? Aktivitas penghapusan akan dicatat dalam Riwayat Aktivitas.'
    };

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form[data-confirm-action]');

        if (!form) {
            return;
        }

        const action = form.dataset.confirmAction;
        const pesan = konfirmasi[action];

        if (pesan && !window.confirm(pesan)) {
            event.preventDefault();
        }
    });

    // Konfirmasi tindakan moderasi ulasan
    document.addEventListener('DOMContentLoaded', function () {
        const konfirmasi = {
            approve: 'Yakin ingin menyetujui ulasan ini?',
            reject: 'Yakin ingin menolak ulasan ini?',
            delete: 'Yakin ingin menghapus ulasan ini secara permanen? Aktivitas penghapusan akan dicatat dalam Riwayat Aktivitas.'
        };

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('form[data-confirm-action]');

            if (!form) {
                return;
            }

            const action = form.dataset.confirmAction;
            const pesan = konfirmasi[action];

            if (pesan && !window.confirm(pesan)) {
                event.preventDefault();
            }
        });
    });
});