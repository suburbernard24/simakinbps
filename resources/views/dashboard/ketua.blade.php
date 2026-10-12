@extends('layouts.app')

@section('title', 'Dashboard Ketua Tim')
@section('page_title', 'Dashboard Ketua Tim — ' . ($tim ? $tim->nama_tim : 'Manajemen Tim'))

@section('content')
<div class="space-y-6">

    <!-- Header Hero Banner (Ketua Tim Command Center) -->
    <div class="p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-blue-200/60 dark:border-slate-800"
         style="background: linear-gradient(135deg, #EEF4FC 0%, #E0EDFD 35%, #1E40AF 85%, #0D47A1 100%);">
        <div class="absolute -right-8 -bottom-8 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]" style="color: #0A2540;">
                    <span class="typewriter-greeting" style="color: #0A2540;" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-bps-blue ml-1.5 align-middle"></span>
                </h2>
                <p class="text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px] font-medium" style="color: #334155;">
                    <span class="typewriter-desc" data-text="Kelola alokasi kegiatan anggota tim, pantau progres realisasi, dan berikan penilaian kinerja berkala."></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-slate-500 ml-1 align-middle hidden"></span>
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

    <!-- Quick Action Banner (Khusus Ketua Tim) -->
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('alokasi.input') }}" class="px-5 py-2.5 rounded-2xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-md shadow-bps-blue/20 hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-circle-plus"></i>
            <span>Input Alokasi Tugas Tim</span>
        </a>
        <a href="{{ route('ckp.nilai') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-md shadow-emerald-600/20 hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-clipboard-check"></i>
            <span>Beri Nilai Kinerja Anggota</span>
        </a>
        <a href="{{ route('ckp.index') }}" class="px-5 py-2.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200/90 dark:border-slate-700 text-xs font-bold transition shadow-xs flex items-center gap-2 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-pen-to-square text-bps-blue"></i>
            <span>CKP Saya (Realisasi Mandiri)</span>
        </a>
        <a href="{{ route('alokasi.tim') }}" class="px-5 py-2.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200/90 dark:border-slate-700 text-xs font-bold transition shadow-xs flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-users text-indigo-600"></i>
            <span>Monitoring Tim Saya</span>
        </a>
    </div>

    <!-- SECTION 1: RINGKASAN KINERJA TIM KERJA (TIM COMMAND STATS) -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-bps-blue"></span>
                <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Statistik Tim: {{ $tim ? $tim->nama_tim : 'Tim Kerja' }} (Periode {{ $daftarBulan[$currentMonth] }} {{ $currentYear }})
                </h3>
            </div>
            <span class="text-[11px] font-bold text-bps-blue dark:text-blue-400">Total Anggota: {{ count($rekapTimPegawai) }} Pegawai</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            
            <!-- 1. Total Tugas Tim -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Total Tugas Tim</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $totalUtama + $totalTambahan }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Utama: <strong>{{ $totalUtama }}</strong> | Tambahan: <strong>{{ $totalTambahan }}</strong></p>
            </div>

            <!-- 2. Tim: Berjalan -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Pek. Berjalan</span>
                <p class="text-2xl font-black text-amber-500 mt-2">{{ $pekerjaanBerjalan }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Dalam proses tim</p>
            </div>

            <!-- 3. Tim: Selesai -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Pek. Selesai</span>
                <p class="text-2xl font-black text-emerald-600 mt-2">{{ $pekerjaanSelesai }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Selesai dikerjakan</p>
            </div>

            <!-- 4. Total Realisasi Kuantitas -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Capaian Target</span>
                <p class="text-2xl font-black text-indigo-600 mt-2">
                    {{ $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100) : 0 }}%
                </p>
                <p class="text-[10px] text-slate-500 mt-1">{{ $totalRealisasi }} / {{ $totalTarget }} Output</p>
            </div>

            <!-- 5. Rata-rata Skor Tim -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Rata-rata Skor Tim</span>
                <p class="text-2xl font-black text-bps-blue mt-2">{{ $timSkorKinerja }}%</p>
                <p class="text-[10px] text-slate-500 mt-1">Efisiensi jam kerja tim</p>
            </div>

            <!-- 6. Rata-rata Nilai CKP Tim -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Avg CKP Tim</span>
                <p class="text-2xl font-black text-amber-500 mt-2">{{ $timCkp }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Kualitas &amp; kuantitas tim</p>
            </div>

        </div>
    </div>

    <!-- SECTION 2: KINERJA PRIBADI KETUA TIM (INDIVIDUAL RESPONSIBILITY) -->
    <div class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-700/60">
        <div class="flex items-center justify-between mb-3 px-1">
            <h4 class="text-xs font-black text-slate-800 dark:text-slate-200 flex items-center gap-2">
                <i class="fa-solid fa-user-check text-bps-blue"></i>
                <span>Capaian Pribadi Ketua Tim ({{ Auth::user()->name }})</span>
            </h4>
            <a href="{{ route('ckp.index') }}" class="text-[11px] font-bold text-bps-blue hover:underline">
                Kelola CKP Pribadi &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Tugas Pribadi</span>
                <p class="text-lg font-black text-slate-900 dark:text-white mt-1">{{ $kinerja['jumlah_utama'] + $kinerja['jumlah_tambahan'] }}</p>
                <p class="text-[10px] text-slate-400">Utama: {{ $kinerja['jumlah_utama'] }} &bull; Tambahan: {{ $kinerja['jumlah_tambahan'] }}</p>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Status Tugas</span>
                <p class="text-lg font-black text-emerald-600 mt-1">{{ $kinerja['selesai'] }} <span class="text-xs font-medium text-slate-400">/ {{ $kinerja['berjalan'] }} jalan</span></p>
                <p class="text-[10px] text-slate-400">Pekerjaan mandiri</p>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Skor Kinerja Pribadi</span>
                <p class="text-lg font-black text-bps-blue mt-1">{{ $kinerja['skor_kinerja'] }}%</p>
                <p class="text-[10px] text-slate-400">Beban jam kerja pribadi</p>
            </div>
            <div class="p-3.5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Nilai CKP Pribadi</span>
                <p class="text-lg font-black text-amber-500 mt-1">{{ $kinerja['nilai_ckp'] }}</p>
                <p class="text-[10px] text-slate-400">Capaian CKP bulanan</p>
            </div>
        </div>
    </div>

    <!-- Main Layout: Chart & Employee List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Global Chart (approx 66%) -->
        <div class="lg:col-span-2">
            @include('dashboard.partials.global-chart')
        </div>

        <!-- Right: Daftar Pegawai Tim & Nilai Action (approx 33%) -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden saas-card">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-users text-bps-blue"></i>
                        <span>Anggota dalam Tim</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pantau dan beri penilaian kualitas CKP</p>
                </div>
                <a href="{{ route('ckp.nilai') }}" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition-colors">
                    Nilai Anggota &rarr;
                </a>
            </div>

            <div class="p-6 flex-1 flex flex-col gap-4 overflow-y-auto">
                @php $count = 0; @endphp
                @forelse($rekapTimPegawai as $p)
                    @if($count < 6)
                        <div class="p-3 rounded-2xl bg-slate-50/70 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/60 flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $p['pegawai']->avatar_url }}" alt="{{ $p['pegawai']->name }}" class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[130px]">{{ $p['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[130px]">{{ $p['pegawai']->jabatan ?? 'Anggota Tim' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-black {{ $p['skor_kinerja'] >= 100 ? 'text-emerald-600' : ($p['skor_kinerja'] >= 80 ? 'text-bps-blue' : 'text-amber-600') }}">
                                    {{ $p['skor_kinerja'] }}%
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="flex items-center gap-2.5 pt-1">
                                <div class="flex-1 h-2 bg-slate-200/70 dark:bg-slate-700 rounded-full overflow-hidden">
                                    @php
                                        $progPercent = $p['total_target'] > 0 ? round(($p['total_realisasi'] / $p['total_target']) * 100) : 0;
                                        $progPercent = $progPercent > 100 ? 100 : $progPercent;
                                    @endphp
                                    <div class="h-full bg-bps-blue rounded-full" style="width: {{ $progPercent }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 w-12 text-right">{{ $p['total_realisasi'] }}/{{ $p['total_target'] }}</span>
                            </div>
                        </div>
                    @endif
                    @php $count++; @endphp
                @empty
                    <div class="text-center text-slate-400 text-xs py-8 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-user-xmark text-2xl mb-2 text-slate-300 dark:text-slate-600"></i>
                        Belum ada pegawai dalam tim ini.
                    </div>
                @endforelse
            </div>
            
            @if(count($rekapTimPegawai) > 6)
            <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-center hover:bg-slate-100/60 transition-colors">
                <a href="{{ route('ckp.nilai') }}" class="text-xs font-bold text-bps-blue dark:text-blue-400 flex items-center justify-center gap-1.5">
                    Lihat &amp; Nilai Semua ({{ count($rekapTimPegawai) }}) <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @include('dashboard.partials.global-calendar')
    </div>

</div>
@endsection
