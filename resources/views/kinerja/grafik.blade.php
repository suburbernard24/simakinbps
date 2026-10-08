@extends('layouts.app')

@section('title', 'Grafik Perbandingan Kinerja Pegawai')
@section('page_title', 'Perbandingan Skor Kinerja Pegawai')

@section('content')
<div class="space-y-6">

    <!-- Header Hero Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-bps-blue via-indigo-900 to-slate-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-sm border border-white/20">
                    <i class="fa-solid fa-chart-simple text-amber-300"></i>
                    <span>Analisis Kinerja Pegawai BPS Manggarai</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight">Grafik Perbandingan Skor Kinerja Antar Pegawai</h2>
                <p class="text-bps-pale text-xs sm:text-sm max-w-2xl leading-relaxed">
                    Evaluasi produktivitas kerja berdasarkan rasio estimasi waktu kerja terhadap kapasitas jam kerja aktif bulanan.
                </p>
            </div>

            <!-- Basis Waktu Info Badge -->
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 text-xs space-y-1">
                <span class="text-amber-300 font-extrabold uppercase text-[10px] tracking-wider">Basis Perhitungan Bulan Ini:</span>
                <p class="font-bold text-white text-sm">Hari Kerja: {{ $hariKerja }} Hari &bull; Jam Aktif: {{ $jamKerjaAktif }} Jam</p>
                <p class="text-[11px] text-slate-300">Standar BPS: 7.5 Jam/Hari (37.5 Jam/Minggu)</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Sesuai Spesifikasi Bagian M) -->
    <!-- Filter Bar (Sesuai Spesifikasi Bagian M) -->
    <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm">
        <form method="GET" action="{{ route('kinerja.grafik') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
            
            <!-- Bulan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white cursor-pointer">
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white cursor-pointer">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tim Kerja -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Tim Kerja</label>
                <select name="tim_id" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Semua Tim</option>
                    @foreach($daftarTim as $tim)
                        <option value="{{ $tim->id }}" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white" {{ $timId == $tim->id ? 'selected' : '' }}>{{ $tim->nama_tim }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Pekerjaan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Jenis Pekerjaan</label>
                <select name="jenis_pekerjaan" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white cursor-pointer">
                    <option value="" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white">Semua (Utama + Tambahan)</option>
                    <option value="utama" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white" {{ $jenisPekerjaan === 'utama' ? 'selected' : '' }}>Hanya Pekerjaan Utama</option>
                    <option value="tambahan" class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white" {{ $jenisPekerjaan === 'tambahan' ? 'selected' : '' }}>Hanya Pekerjaan Tambahan</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm h-[38px] flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>

        </form>
    </div>

    <!-- MAIN BAR CHART: PERBANDINGAN SKOR KINERJA PEGAWAI -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-bps-blue"></i>
                    <span>Grafik Batang Skor Kinerja Pegawai (%)</span>
                </h3>
                <p class="text-xs text-slate-500">
                    Rumus: <code>Skor Kinerja = (Total Estimasi Jam Kerja / Total Jam Kerja Aktif Bulanan {{ $jamKerjaAktif }} Jam) × 100%</code>
                </p>
            </div>
            <span class="text-xs font-bold text-slate-400">Periode: {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</span>
        </div>

        <div class="h-80 relative">
            <canvas id="grafikKinerjaChart"></canvas>
        </div>
    </div>

    <!-- TABEL PERBANDINGAN & RANKING VISUAL -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900">Tabel Peringkat & Perbandingan Kinerja Antar Pegawai</h3>
                <p class="text-xs text-slate-500">Pemeringkatan visual tanpa mengubah integritas data dasar</p>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ count($rekapPegawai) }} Pegawai Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-12">Peringkat</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Pegawai</th>
                        <th class="py-3.5 px-4">Tim Kerja</th>
                        <th class="py-3.5 px-4 text-center">Jml Kegiatan</th>
                        <th class="py-3.5 px-4 text-center">Total Estimasi Jam</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Visualisasi Progress Beban Jam</th>
                        <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapPegawai as $idx => $r)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center">
                                @if($idx === 0)
                                    <span class="w-7 h-7 rounded-xl bg-amber-400 text-white font-black inline-flex items-center justify-center text-xs shadow-sm"><i class="fa-solid fa-trophy mr-0.5 text-[10px]"></i>1</span>
                                @elseif($idx === 1)
                                    <span class="w-7 h-7 rounded-xl bg-slate-400 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">2</span>
                                @elseif($idx === 2)
                                    <span class="w-7 h-7 rounded-xl bg-amber-700 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">3</span>
                                @else
                                    <span class="font-mono font-bold text-slate-400">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $r['pegawai']->avatar_url }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight">{{ $r['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $r['pegawai']->jabatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $r['pegawai']->tim ? $r['pegawai']->tim->nama_tim : 'Lintas Tim' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                                {{ $r['total_pekerjaan'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">
                                {{ $r['total_estimasi_jam'] }} / {{ $jamKerjaAktif }} Jam
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full {{ $r['skor_kinerja'] >= 100 ? 'bg-emerald-500' : ($r['skor_kinerja'] >= 80 ? 'bg-bps-blue' : 'bg-amber-500') }}"
                                         style="width: {{ min(100, $r['skor_kinerja']) }}%"></div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-black
                                    @if($r['skor_kinerja'] >= 100) bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($r['skor_kinerja'] >= 80) bg-blue-50 text-bps-blue border border-blue-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $r['skor_kinerja'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-900">
                                {{ $r['nilai_ckp'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data pegawai yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('grafikKinerjaChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Skor Kinerja (%)',
                        data: {!! json_encode($chartScores) !!},
                        backgroundColor: {!! json_encode($backgroundColors) !!},
                        borderRadius: 10,
                        borderSkipped: false,
                    },
                    {
                        label: 'Nilai CKP',
                        data: {!! json_encode($chartCkps) !!},
                        type: 'line',
                        borderColor: '#F59E0B',
                        backgroundColor: '#F59E0B',
                        borderWidth: 3,
                        pointBackgroundColor: '#F59E0B',
                        pointRadius: 5,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' } }
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                if (context.datasetIndex === 0) {
                                    const hours = {!! json_encode($chartHours) !!}[context.dataIndex];
                                    return `Total Jam: ${hours} Jam (Basis: {{ $jamKerjaAktif }} Jam)`;
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        title: { display: true, text: 'Persentase (%) / Nilai CKP' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
