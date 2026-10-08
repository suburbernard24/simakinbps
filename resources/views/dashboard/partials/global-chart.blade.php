<!-- resources/views/dashboard/partials/global-chart.blade.php -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col h-full">
    <div class="p-6 border-b border-slate-100 flex flex-wrap gap-4 items-center justify-between">
        <div>
            <h3 class="text-base font-black text-slate-900">
                Visualisasi Alokasi Pekerjaan Pegawai
            </h3>
            <p class="text-xs text-slate-500 mt-1">Beban alokasi pekerjaan, persentase skor kinerja, dan nilai capaian CKP pegawai</p>
        </div>
    </div>

    <div class="p-6 flex-1 relative h-80 min-h-[320px]">
        <canvas id="globalChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('globalChart')) {
            const ctxGlobal = document.getElementById('globalChart').getContext('2d');
            new Chart(ctxGlobal, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($globalChartLabels ?? []) !!},
                    datasets: [
                        {
                            label: 'Total Pekerjaan',
                            data: {!! json_encode($globalChartPekerjaan ?? []) !!},
                            backgroundColor: '#1565C0',
                            borderRadius: 8,
                        },
                        {
                            label: 'Skor Kinerja (%)',
                            data: {!! json_encode($globalChartKinerja ?? []) !!},
                            backgroundColor: '#6366F1',
                            borderRadius: 8,
                        },
                        {
                            label: 'Nilai CKP',
                            data: {!! json_encode($globalChartCkps ?? []) !!},
                            backgroundColor: '#F59E0B',
                            borderRadius: 8,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' } } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false }, ticks: { maxRotation: 45, minRotation: 45 } }
                    }
                }
            });
        }
    });
</script>
@endpush
