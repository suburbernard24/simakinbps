@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Kinerja Pegawai')

@section('content')
<div class="space-y-8">
    
    <!-- HERO WELCOME BANNER (IKN Forest Aesthetic with Glass Card) -->
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-r from-emerald-900 via-emerald-800 to-emerald-950 text-white p-6 sm:p-8 shadow-xl border border-emerald-700/40">
        <!-- Decorative Glow & Background Details -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute top-0 right-1/4 w-80 h-80 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Sistem Informasi Manajemen Kinerja TerintegrasiII</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100/80 leading-relaxed">
                    Pantau capaian target, alokasi beban kerja (IBK), dan kualitas capaian kinerja (CKP) di lingkungan Badan Pusat Statistik Kabupaten Manggarai secara terstruktur dan transparan.
                </p>
            </div>

            <!-- Quick Period Selector Filter Form -->
            <div class="shrink-0 bg-white/10 backdrop-blur-xl p-4 rounded-2xl border border-white/20 shadow-inner">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-emerald-200 mb-1">Tahun</label>
                        <select name="tahun" onchange="this.form.submit()" class="bg-emerald-950/80 text-white text-xs font-semibold rounded-xl px-3 py-2 border border-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer">
                            @foreach(range(date('Y') + 1, date('Y') - 3) as $y)
                                <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-emerald-200 mb-1">Bulan</label>
                        <select name="bulan" onchange="this.form.submit()" class="bg-emerald-950/80 text-white text-xs font-semibold rounded-xl px-3 py-2 border border-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer">
                            @php
                                $bulanNama = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach($bulanNama as $num => $nama)
                                <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="p-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold transition-all shadow-md" title="Muat Ulang Data">
                            <i class="fa-solid fa-arrows-rotate text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3 MAIN SUMMARY CARDS (SPECIFICATION HIGHLIGHT) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- CARD 1: Rata-Rata CKP Bulan Ini -->
        <div class="glass-panel p-6 rounded-3xl shadow-glass hover-lift border border-white/80 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-500"></div>
            
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 bg-amber-100/80 px-2.5 py-1 rounded-lg border border-amber-200">
                        <i class="fa-solid fa-award text-amber-600"></i>
                        Capaian Kinerja
                    </span>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-3">Rata-rata CKP Bulan Ini</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-gauge-high text-xl"></i>
                </div>
            </div>

            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($avgCkp, 2, ',', '.') }}
                </span>
                <span class="text-xs font-bold text-slate-400">/ 100</span>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-medium text-slate-500">Status Kualitas:</span>
                @if($avgCkp >= 90)
                    <span class="font-bold text-emerald-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Sangat Baik
                    </span>
                @elseif($avgCkp >= 76)
                    <span class="font-bold text-blue-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Baik
                    </span>
                @else
                    <span class="font-bold text-amber-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> Cukup
                    </span>
                @endif
            </div>
        </div>

        <!-- CARD 2: Total IBK Kantor -->
        <div class="glass-panel p-6 rounded-3xl shadow-glass hover-lift border border-white/80 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>

            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-100/80 px-2.5 py-1 rounded-lg border border-emerald-200">
                        <i class="fa-solid fa-building text-emerald-600"></i>
                        Pekerjaan Kantor
                    </span>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-3">Total IBK Kantor</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white shadow-lg shadow-emerald-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
            </div>

            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($totalIbkKantor, 2, ',', '.') }}
                </span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">Poin IBK</span>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium">Periode Terpilih:</span>
                <span class="font-bold text-slate-700">{{ $bulanNama[$currentMonth] }} {{ $currentYear }}</span>
            </div>
        </div>

        <!-- CARD 3: Total IBK Lapangan -->
        <div class="glass-panel p-6 rounded-3xl shadow-glass hover-lift border border-white/80 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl group-hover:bg-teal-500/20 transition-all duration-500"></div>

            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-100/80 px-2.5 py-1 rounded-lg border border-teal-200">
                        <i class="fa-solid fa-person-walking text-teal-600"></i>
                        Pekerjaan Lapangan
                    </span>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-3">Total IBK Lapangan</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white shadow-lg shadow-teal-600/25 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-map-location-dot text-xl"></i>
                </div>
            </div>

            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($totalIbkLapangan, 2, ',', '.') }}
                </span>
                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">Poin IBK</span>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium">Periode Terpilih:</span>
                <span class="font-bold text-slate-700">{{ $bulanNama[$currentMonth] }} {{ $currentYear }}</span>
            </div>
        </div>

    </div>

    <!-- ANALYTICS SECTION: TREND CHARTS & QUICK SHORTCUTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Chart: Tren Beban Kinerja & Capaian (Span 2 cols) -->
        <div class="lg:col-span-2 glass-panel p-6 sm:p-7 rounded-3xl shadow-glass border border-white/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-600"></i>
                        <span>Tren Beban Kerja & Kinerja Bulanan (Tahun {{ $currentYear }})</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbandingan kumulatif IBK Kantor, IBK Lapangan, serta Skor CKP</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Kantor
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Lapangan
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> CKP
                    </span>
                </div>
            </div>

            <!-- Canvas for Chart.js -->
            <div class="relative h-72 sm:h-80 w-full">
                <canvas id="dashboardTrendChart"></canvas>
            </div>
        </div>

        <!-- Quick Access & Status Satker Card -->
        <div class="space-y-6">
            
            <!-- Mini Card: Rekap Satker BPS -->
            <div class="glass-panel p-6 rounded-3xl shadow-glass border border-white/80 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-circle-nodes text-amber-600"></i>
                    <span>Informasi Satuan Kerja</span>
                </h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">Pegawai Aktif</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900">{{ $totalPegawaiAktif }} Orang</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700">
                                <i class="fa-solid fa-list-check text-sm"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">Kegiatan Terdaftar</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900">{{ $totalKegiatan }} Kegiatan</span>
                    </div>
                </div>


            </div>

            <!-- Quick Action Shortcut -->
            <div class="p-6 rounded-3xl bg-linear-to-br from-amber-500/10 to-amber-700/10 border border-amber-300/40 space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shadow-xs">
                        <i class="fa-solid fa-bullhorn text-xs"></i>
                    </div>
                    <h4 class="text-xs font-bold text-amber-950 uppercase tracking-wider">Standar Kinerja BPS</h4>
                </div>
                <p class="text-xs text-amber-900/80 leading-relaxed">
                    Setiap pegawai diharapkan memenuhi target kuantitas IBK per bulan minimal 100 poin, dengan standar kualitas CKP minimal 80,00.
                </p>
            </div>

        </div>

    </div>


</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trendData = @json($monthlyTrend);
        
        const labels = trendData.map(item => item.bulan);
        const dataKantor = trendData.map(item => item.kantor);
        const dataLapangan = trendData.map(item => item.lapangan);
        const dataCkp = trendData.map(item => item.ckp);

        const ctx = document.getElementById('dashboardTrendChart').getContext('2d');
        
        // Gradient fill for chart
        const gradientKantor = ctx.createLinearGradient(0, 0, 0, 300);
        gradientKantor.addColorStop(0, 'rgba(5, 150, 105, 0.4)');
        gradientKantor.addColorStop(1, 'rgba(5, 150, 105, 0.0)');

        const gradientLapangan = ctx.createLinearGradient(0, 0, 0, 300);
        gradientLapangan.addColorStop(0, 'rgba(13, 148, 136, 0.4)');
        gradientLapangan.addColorStop(1, 'rgba(13, 148, 136, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'IBK Kantor',
                        data: dataKantor,
                        borderColor: '#059669',
                        backgroundColor: gradientKantor,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#059669',
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'IBK Lapangan',
                        data: dataLapangan,
                        borderColor: '#0d9488',
                        backgroundColor: gradientLapangan,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#0d9488',
                        pointRadius: 3,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Skor CKP',
                        data: dataCkp,
                        borderColor: '#d97706',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#d97706',
                        pointRadius: 3,
                        yAxisID: 'yCkp',
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
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(3, 43, 31, 0.9)',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                        padding: 10,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    y: {
                        position: 'left',
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } },
                        title: { display: true, text: 'Total Poin IBK', font: { size: 10 } }
                    },
                    yCkp: {
                        position: 'right',
                        grid: { display: false },
                        min: 0,
                        max: 100,
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } },
                        title: { display: true, text: 'Skor CKP (0-100)', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
