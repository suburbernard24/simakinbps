<!-- resources/views/dashboard/partials/global-chart.blade.php -->
<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col h-full saas-card">
    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-wrap gap-4 items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-bps-blue"></span>
                <h3 class="text-base font-black text-slate-900 dark:text-white">
                    Visualisasi Alokasi &amp; Capaian Kinerja Pegawai
                </h3>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Perbandingan beban alokasi pekerjaan, persentase skor kinerja, dan nilai capaian CKP pegawai
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-900/30 text-bps-blue dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                <i class="fa-solid fa-chart-column text-[10px]"></i>
                <span>Multi-Metrik</span>
            </span>
        </div>
    </div>

    <div class="p-6 flex-1 relative min-h-[340px]">
        <canvas id="globalChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('globalChart')) {
            const ctxGlobal = document.getElementById('globalChart').getContext('2d');
            
            // Check if dark mode is active
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(51, 65, 85, 0.4)' : '#F1F5F9';
            const textColor = isDark ? '#94A3B8' : '#64748B';

            new Chart(ctxGlobal, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($globalChartLabels ?? []) !!},
                    datasets: [
                        {
                            label: 'Total Pekerjaan',
                            data: {!! json_encode($globalChartPekerjaan ?? []) !!},
                            backgroundColor: '#1565C0',
                            borderRadius: 6,
                            barPercentage: 0.75,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Skor Kinerja (%)',
                            data: {!! json_encode($globalChartKinerja ?? []) !!},
                            backgroundColor: '#6366F1',
                            borderRadius: 6,
                            barPercentage: 0.75,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Nilai CKP',
                            data: {!! json_encode($globalChartCkps ?? []) !!},
                            backgroundColor: '#F59E0B',
                            borderRadius: 6,
                            barPercentage: 0.75,
                            categoryPercentage: 0.8,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { 
                            position: 'top', 
                            align: 'end',
                            labels: { 
                                boxWidth: 12,
                                boxHeight: 12,
                                useBorderRadius: true,
                                borderRadius: 4,
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                                color: textColor,
                                padding: 16
                            } 
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1E293B' : '#0F172A',
                            titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                            padding: 12,
                            cornerRadius: 10,
                            boxPadding: 6,
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: gridColor },
                            ticks: { 
                                font: { family: 'Plus Jakarta Sans', size: 11 },
                                color: textColor
                            } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { 
                                maxRotation: 45, 
                                minRotation: 30,
                                font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' },
                                color: textColor
                            } 
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
