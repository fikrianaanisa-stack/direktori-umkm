@extends('layouts.admin')

@section('title', 'Dashboard')

{{-- CSS Dashboard --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

    {{-- Header Dashboard --}}
    <div class="dashboard-header">
        <h1>Dashboard</h1>

        <p>
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>!
            Anda login sebagai <strong>Admin</strong>.
        </p>
    </div>

    {{-- Ringkasan Data --}}
    <h2 class="dashboard-section-title">Ringkasan Data</h2>

    <div class="dashboard-stats">

        {{-- Total UMKM --}}
        <div class="dashboard-card">
            <div class="dashboard-card-top">
                <h3 class="dashboard-card-title">Total UMKM</h3>

                <div class="dashboard-card-icon icon-umkm">
                    <i class="bi bi-shop"></i>
                </div>
            </div>

            <p class="dashboard-card-value">{{ $totalUmkm }}</p>
        </div>

        {{-- Total Produk --}}
        <div class="dashboard-card">
            <div class="dashboard-card-top">
                <h3 class="dashboard-card-title">Total Produk</h3>

                <div class="dashboard-card-icon icon-produk">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>

            <p class="dashboard-card-value">{{ $totalProduk }}</p>
        </div>

        {{-- Total Pengunjung Terdaftar--}}
        <div class="dashboard-card">
            <div class="dashboard-card-top">
                <h3 class="dashboard-card-title">Total Pengunjung Terdaftar</h3>

                <div class="dashboard-card-icon icon-pengunjung">
                    <i class="bi bi-people"></i>
                </div>
            </div>

            <p class="dashboard-card-value">{{ $totalPengunjung }}</p>
        </div>

        {{-- Total Pengelola --}}
        <div class="dashboard-card">
            <div class="dashboard-card-top">
                <h3 class="dashboard-card-title">Total Pengelola</h3>

                <div class="dashboard-card-icon icon-pengelola">
                    <i class="bi bi-person-gear"></i>
                </div>
            </div>

            <p class="dashboard-card-value">{{ $totalPengelola }}</p>
        </div>

    </div>

    {{-- Grafik Distribusi UMKM --}}
    <div class="dashboard-chart-card">
        <h2 class="dashboard-section-title">
            Distribusi UMKM Berdasarkan Kategori
        </h2>

        <div class="dashboard-chart-container">
            <canvas id="grafikKategoriUmkm"></canvas>
        </div>
    </div>

@endsection

{{-- JavaScript Dashboard --}}
@push('scripts')

    {{-- Library Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

    {{-- Grafik Distribusi UMKM Berdasarkan Kategori --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('grafikKategoriUmkm');

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            // Plugin untuk menampilkan jumlah UMKM di atas batang grafik
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

            // Membuat grafik batang distribusi kategori UMKM
            new Chart(canvas, {
                type: 'bar',

                data: {
                    labels: @json($labelKategori),

                    datasets: [{
                        label: 'Jumlah UMKM',
                        data: @json($jumlahKategori),
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
    </script>

@endpush
