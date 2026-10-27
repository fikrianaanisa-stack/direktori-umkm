document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.produk-slider');

    sliders.forEach(function (slider) {
        const foto = slider.querySelectorAll('.produk-slide');
        const indikator = slider.querySelector('.produk-slide-aktif');
        const tombolPrev = slider.querySelector('.produk-slider-prev');
        const tombolNext = slider.querySelector('.produk-slider-next');

        if (foto.length <= 1) return;

        let posisiAktif = 0;

        function tampilkanFoto(index) {
            posisiAktif = (index + foto.length) % foto.length;

            foto.forEach(function (item, nomor) {
                item.hidden = nomor !== posisiAktif;
            });

            if (indikator) {
                indikator.textContent = posisiAktif + 1;
            }
        }

        if (tombolPrev) {
            tombolPrev.addEventListener('click', function () {
                tampilkanFoto(posisiAktif - 1);
            });
        }

        if (tombolNext) {
            tombolNext.addEventListener('click', function () {
                tampilkanFoto(posisiAktif + 1);
            });
        }

        tampilkanFoto(0);
    });
});