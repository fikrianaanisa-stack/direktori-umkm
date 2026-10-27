document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('grafikKategoriUmkm');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    // Mengambil data grafik dari Blade
    const labelKategori = JSON.parse(canvas.dataset.labels || '[]');
    const jumlahKategori = JSON.parse(canvas.dataset.values || '[]');

    // Menampilkan jumlah UMKM di atas batang grafik
    const labelJumlahPlugin = {
        id: 'labelJumlah',

        afterDatasetsDraw(chart) {
            const ctx = chart.ctx;

            ctx.save();
            ctx.fillStyle = '#334155';
            ctx.font = 'bold 13px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);

                meta.data.forEach((bar, index) => {
                    const jumlah = Number(dataset.data[index]);

                    if (Number.isFinite(jumlah)) {
                        ctx.fillText(jumlah, bar.x, bar.y - 6);
                    }
                });
            });

            ctx.restore();
        }
    };

    // Membuat grafik distribusi kategori UMKM
    new Chart(canvas, {
        type: 'bar',

        data: {
            labels: labelKategori,

            datasets: [{
                label: 'Jumlah UMKM',
                data: jumlahKategori,
                backgroundColor: '#2563eb',
                borderRadius: 6,
                maxBarThickness: 65
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Jumlah UMKM: ' + context.parsed.y;
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    grace: '20%',

                    ticks: {
                        precision: 0
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        },

        plugins: [labelJumlahPlugin]
    });
});