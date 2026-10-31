@push('scripts')
<script>
    window.formatRupiah = function (angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
    };

    window.chartOptions = function (isLine) {
        isLine = isLine || false;
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 8, boxHeight: 8, padding: 12,
                        font: { family: "'Inter', sans-serif", size: 11, weight: '500' },
                        color: '#374151',
                        usePointStyle: true, pointStyle: 'circle',
                    },
                },
                tooltip: {
                    backgroundColor: '#1f2937', padding: 10, cornerRadius: 6,
                    titleFont: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                    bodyFont: { family: "'Inter', sans-serif", size: 11 },
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + window.formatRupiah(context.parsed.y);
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#9ca3af', maxRotation: 0, autoSkipPadding: 20,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 10 },
                        color: '#9ca3af', maxTicksLimit: 6,
                        callback: function (value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
                            if (value >= 1000)    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                            return 'Rp ' + value;
                        },
                    },
                },
            },
        };
    };
</script>
@endpush