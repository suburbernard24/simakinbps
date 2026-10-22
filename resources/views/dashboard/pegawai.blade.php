@extends('layouts.app')

@section('title', 'Dashboard Pegawai')
@section('page_title', 'Dashboard Kinerja Pribadi')

@section('content')
<div class="space-y-6">

    <!-- Header Hero Banner (Ruang Kerja Pegawai) -->
    <div class="p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-blue-200/60 dark:border-slate-800"
         style="background: linear-gradient(135deg, #EEF4FC 0%, #E0EDFD 35%, #1E40AF 85%, #0D47A1 100%);">
        <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]" style="color: #0A2540;">
                    <span class="typewriter-greeting" style="color: #0A2540;" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-bps-blue ml-1.5 align-middle"></span>
                </h2>
                <p class="text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px] font-medium" style="color: #334155;">
                    <span class="typewriter-desc" data-text="Selamat Datang di SIMAKINBPS - Sistem Manajemen Kinerja BPS Manggarai"></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-slate-500 ml-1 align-middle hidden"></span>
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

    <!-- Quick Action Buttons -->
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('ckp.index') }}" class="px-5 py-2.5 rounded-2xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-md shadow-bps-blue/20 hover:shadow-lg flex items-center gap-2 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Lengkapi Realisasi (CKP Saya)</span>
        </a>
        <a href="{{ route('ckp.tambah') }}" class="px-5 py-2.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200/90 dark:border-slate-700 text-xs font-bold transition shadow-xs hover:shadow-sm flex items-center gap-2 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-plus text-emerald-600 dark:text-emerald-400"></i>
            <span>Input Pekerjaan Tambahan Mandiri</span>
        </a>
        <a href="{{ route('ckp.laporan', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" class="px-5 py-2.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200/90 dark:border-slate-700 text-xs font-bold transition shadow-xs flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-file-invoice text-bps-blue dark:text-blue-400"></i>
            <span>Lihat Laporan CKP Bulanan</span>
        </a>
    </div>

    <!-- Personal Metrics Grid (6 Distinct Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- 1. Pekerjaan Utama -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Pekerjaan Utama</span>
                <div class="w-7 h-7 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-2">{{ $kinerja['jumlah_utama'] }}</p>
        </div>

        <!-- 2. Pekerjaan Tambahan -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Pekerjaan Tambahan</span>
                <div class="w-7 h-7 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-2">{{ $kinerja['jumlah_tambahan'] }}</p>
        </div>

        <!-- 3. Berjalan -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-amber-200/80 dark:border-amber-900/40 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Berjalan</span>
                <div class="w-7 h-7 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-spinner fa-spin-pulse"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-2">{{ $kinerja['berjalan'] }}</p>
            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                Berbasis alokasi waktu
            </p>
        </div>

        <!-- 4. Selesai -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-emerald-200/80 dark:border-emerald-900/40 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Selesai</span>
                <div class="w-7 h-7 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $kinerja['selesai'] }}</p>
            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Melewati batas waktu
            </p>
        </div>

        <!-- 5. Skor Kinerja -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Skor Kinerja</span>
                <div class="w-7 h-7 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-bps-blue dark:text-blue-400 mt-2">{{ $kinerja['skor_kinerja'] }}%</p>
            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full mt-2 overflow-hidden">
                <div class="h-full bg-bps-blue rounded-full" style="width: {{ min(100, $kinerja['skor_kinerja']) }}%"></div>
            </div>
        </div>

        <!-- 6. CKP Bulanan -->
        <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase text-slate-400 dark:text-slate-500 tracking-wider">CKP Saya</span>
                <div class="w-7 h-7 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-500 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $kinerja['nilai_ckp'] }}</p>
        </div>

    </div>

    <!-- Main Layout: Chart & Employee List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Global Chart (approx 66%) -->
        <div class="lg:col-span-2">
            @include('dashboard.partials.global-chart')
        </div>

        <!-- Right: Daftar Rekan Pegawai dalam Tim (approx 33%) -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden saas-card">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-users text-bps-blue"></i>
                        <span>Pegawai dalam Tim</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Beban &amp; Capaian {{ $daftarBulan[$currentMonth] }}</p>
                </div>
                <a href="{{ route('ckp.index') }}" class="text-[10px] font-bold text-bps-blue dark:text-blue-400 hover:underline bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1.5 rounded-lg border border-blue-100 dark:border-blue-800 transition-colors">
                    CKP &rarr;
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
                <a href="{{ route('ckp.index') }}" class="text-xs font-bold text-bps-blue dark:text-blue-400 flex items-center justify-center gap-1.5">
                    Lihat Semua Rekan Tim ({{ count($rekapTimPegawai) }}) <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @include('dashboard.partials.global-calendar')
    </div>

    <!-- Active Tasks Needing Realization -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden saas-card">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-bps-blue"></i>
                    <span>Daftar Pekerjaan Pribadi — Periode {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rincian pekerjaan yang dialokasikan dan capaian realisasi Anda</p>
            </div>
            <a href="{{ route('ckp.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition">
                <span>Buka CKP Saya</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($personalTasks as $task)
                <div class="p-5 sm:px-6 hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            @php
                                $sw = $task->status_waktu;
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $task->jenis_pekerjaan === 'utama' ? 'bg-blue-50 text-bps-blue border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800' : 'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-900/40 dark:text-purple-300 dark:border-purple-800' }}">
                                {{ $task->jenis_pekerjaan === 'utama' ? 'Pekerjaan Utama' : 'Pekerjaan Tambahan' }}
                            </span>
                            @if($sw === 'selesai')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Selesai
                                </span>
                            @elseif($sw === 'berjalan')
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800 flex items-center gap-1">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Berjalan
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700 flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[9px]"></i> Terjadwal
                                </span>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $task->nama_kegiatan }}</h4>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span><i class="fa-regular fa-calendar text-[11px] mr-1 text-slate-400"></i> {{ $task->tanggal_mulai ? $task->tanggal_mulai->format('d M Y') : '-' }} s/d {{ $task->tanggal_selesai ? $task->tanggal_selesai->format('d M Y') : '-' }}</span>
                            <span>&bull; Estimasi: <strong class="text-slate-700 dark:text-slate-300">{{ $task->estimasi_waktu }} Jam</strong></span>
                            <span>&bull; Asal Tim: <strong class="text-slate-700 dark:text-slate-300">{{ $task->asal_tim ?: '-' }}</strong></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-5 sm:text-right shrink-0">
                        <div class="space-y-0.5">
                            <p class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider">Realisasi</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white">
                                {{ $task->realisasi_kuantitas }} / {{ $task->target_kuantitas }} <span class="text-xs font-semibold text-slate-500">{{ $task->satuan }}</span>
                                <span class="text-xs font-bold text-emerald-600 ml-1">({{ $task->persentase_kuantitas }}%)</span>
                            </p>
                            <p class="text-[11px] text-slate-500 font-medium">
                                Nilai CKP: <strong class="text-bps-blue dark:text-blue-400">{{ $task->kualitas > 0 ? $task->nilai_ckp : 'Belum Dinilai' }}</strong>
                            </p>
                        </div>

                        <a href="{{ route('ckp.index') }}" class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-xs hover:shadow-sm cursor-pointer whitespace-nowrap">
                            <i class="fa-solid fa-pen-to-square text-[11px] mr-1"></i>
                            <span>Isi Realisasi</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                    Belum ada pekerjaan yang tercatat pada periode ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
