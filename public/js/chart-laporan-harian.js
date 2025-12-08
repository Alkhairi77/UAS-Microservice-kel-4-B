// Chart for Laporan Harian by Jenis Limbah
// Requires Chart.js loaded in the page

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chart-laporan-harian-jenis-limbah');
    if (!ctx) return;
    // Data injected from Blade
    const chartData = window.laporanHarianByJenisLimbahData;
    if (!chartData) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Total Laporan Harian',
                data: chartData.data,
                backgroundColor: [
                    '#34d399', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#f472b6', '#38bdf8', '#facc15', '#4ade80', '#f87171'
                ],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Laporan Harian per Jenis Limbah'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
