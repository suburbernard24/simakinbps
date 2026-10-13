@extends('layouts.app')

@section('title', 'Dashboard Kepala BPS')
@section('page_title', 'Dashboard Pimpinan — Kepala BPS Manggarai')

@section('content')
<div class="space-y-6">

    <!-- Header & Period Filter (Executive Leadership Banner) -->
    <div class="p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-blue-200/60 dark:border-slate-800"
         style="background: linear-gradient(135deg, #EEF4FC 0%, #E0EDFD 35%, #1E40AF 85%, #0D47A1 100%);">
        <div class="absolute -right-8 -bottom-8 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]" style="color: #0A2540;">
                    <span class="typewriter-greeting" style="color: #0A2540;" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-bps-blue ml-1.5 align-middle"></span>
                </h2>
                <p class="text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px] font-medium" style="color: #334155;">
                    <span class="typewriter-desc" data-text="Pantau kinerja makro organisasi, efektivitas tim kerja statistik, dan peringkat evaluasi seluruh pegawai."></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-slate-500 ml-1 align-middle hidden"></span>
                </p>
            </div>

            <!-- Filter Periode -->
            <form method="GET" action="{{ route('dashboard') }}" class="filter-card-periode flex flex-wrap items-center gap-2 bg-white/15 dark:bg-slate-900/60 backdrop-blur-md p-2 rounded-2xl border border-white/20 dark:border-slate-700 shadow-sm">
                <select name="bulan" class="text-xs font-bold px-3 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 outline-none focus:ring-2 focus:ring-bps-blue cursor-pointer">
                    @foreach($daftarBulan as $blnNum => $blnName)
                        <option value="{{ $blnNum }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white" {{ $currentMonth == $blnNum ? 'selected' : '' }}>{{ $blnName }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="text-xs font-bold px-3 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 outline-none focus:ring-2 focus:ring-bps-blue cursor-pointer">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-white" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-950 font-black text-xs transition shadow-sm cursor-pointer flex items-center gap-1.5" style="color: #0f172a !important;">
                    <i class="fa-solid fa-filter text-[11px]" style="color: #0f172a !important;"></i>
                    <span style="color: #0f172a !important; font-weight: 900;">Terapkan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Executive Summary Metrics (6 Distinct Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- 1. Total Pegawai -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Pegawai</span>
                <div class="w-7 h-7 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $totalPegawai }}</p>
        </div>

        <!-- 2. Total Tim Kerja -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tim Kerja</span>
                <div class="w-7 h-7 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-2">{{ $totalTim }}</p>
        </div>

        <!-- 3. Total Pekerjaan -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Pekerjaan</span>
                <div class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-bars-progress"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $totalPekerjaan }}</p>
        </div>

        <!-- 4. Selesai vs Berjalan -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Selesai / Jalan</span>
                <div class="w-7 h-7 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-check-double"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                {{ $pekerjaanSelesai }} <span class="text-xs text-slate-400 font-semibold">/ {{ $pekerjaanBerjalan }}</span>
            </p>
        </div>

        <!-- 5. Rata-rata Skor Kinerja -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Rata-rata Skor Kinerja Pegawai</span>
                <div class="w-7 h-7 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-bps-blue dark:text-blue-400 mt-2">{{ $avgSkorKinerja }}%</p>
        </div>

        <!-- 6. Rata-rata Capaian CKP -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Rata-rata Capaian CKP</span>
                <div class="w-7 h-7 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $avgCkp }}</p>
        </div>

    </div>

    <!-- Ringkasan Kinerja Antar Tim Kerja (Executive Team Cards) -->
    @if(isset($rekapTim) && count($rekapTim) > 0)
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-bps-blue"></i>
                <span>Komparasi Kinerja Antar Tim Kerja Statistik</span>
            </h3>
            <span class="text-[11px] font-semibold text-slate-400">Periode {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($rekapTim as $rt)
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-sm transition saas-card">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-900 dark:text-white truncate">
                            {{ $rt['tim']->singkatan ?? $rt['tim']->nama_tim }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 dark:bg-blue-900/30 text-bps-blue dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                            {{ $rt['skor_kinerja'] }}% Skor
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5">Ketua: {{ $rt['tim']->ketua ? $rt['tim']->ketua->name : '-' }}</p>
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-500">Rata-rata CKP:</span>
                        <span class="text-amber-500 font-black">{{ $rt['avg_ckp'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Global Stats & Calendar (All Roles) -->
    @include('dashboard.partials.global-stats')

    <!-- Peringkat & Ringkasan Kinerja Seluruh Pegawai Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden saas-card">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-amber-500"></i>
                    <span>Peringkat &amp; Ringkasan Kinerja Pegawai</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Evaluasi skor kinerja dan penilaian capaian CKP seluruh pegawai BPS Kabupaten Manggarai</p>
            </div>
            <a href="{{ route('alokasi.pegawai', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <span>Rincian Kegiatan Pegawai</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-700 text-[11px] font-extrabold uppercase text-slate-500 dark:text-slate-400 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-16">Peringkat</th>
                        <th class="py-3.5 px-4">Nama Pegawai</th>
                        <th class="py-3.5 px-4">Tim Kerja</th>
                        <th class="py-3.5 px-4 text-center">Beban Kerja</th>
                        <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Tindakan Evaluasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($rekapPegawai as $idx => $r)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                            <td class="py-3.5 px-4 text-center">
                                @if($idx === 0)
                                    <span class="w-6 h-6 rounded-full bg-amber-400 text-slate-950 font-black inline-flex items-center justify-center text-xs shadow-xs ring-2 ring-amber-200">1</span>
                                @elseif($idx === 1)
                                    <span class="w-6 h-6 rounded-full bg-slate-300 dark:bg-slate-600 text-slate-900 dark:text-white font-black inline-flex items-center justify-center text-xs shadow-xs">2</span>
                                @elseif($idx === 2)
                                    <span class="w-6 h-6 rounded-full bg-amber-700 text-white font-black inline-flex items-center justify-center text-xs shadow-xs">3</span>
                                @else
                                    <span class="font-mono text-slate-400 font-bold">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $r['pegawai']->avatar_url }}" alt="{{ $r['pegawai']->name }}" class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 dark:text-white truncate">{{ $r['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-400 truncate">{{ $r['pegawai']->jabatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $r['pegawai']->tim ? $r['pegawai']->tim->nama_tim : 'Lintas Tim' }}</span>
                                @if($r['pegawai']->is_ketua_tim)
                                    <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 text-bps-blue border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300">Ketua Tim</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                {{ $r['total_estimasi_jam'] }} Jam
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-black
                                    @if($r['skor_kinerja'] >= 100) bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300
                                    @elseif($r['skor_kinerja'] >= 80) bg-blue-50 text-bps-blue border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300
                                    @else bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 @endif">
                                    {{ $r['skor_kinerja'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-900 dark:text-white">
                                {{ $r['nilai_ckp'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($r['pegawai']->isKetuaTim() || $r['jumlah_tambahan'] > 0)
                                    <a href="{{ route('ckp.nilai', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'user_id' => $r['pegawai']->id]) }}" class="px-3 py-1.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                        <i class="fa-solid fa-stamp text-[10px]"></i>
                                        <span>Beri Nilai</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Dinilai Ketua Tim</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada data kinerja yang tercatat pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
