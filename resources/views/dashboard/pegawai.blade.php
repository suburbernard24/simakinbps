@extends('layouts.app')

@section('title', 'Dashboard Pegawai')
@section('page_title', 'Dashboard Kinerja Pribadi')

@section('content')
<div class="space-y-6">

    <!-- Header Hero Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-bps-dark to-bps-blue text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]">
                    <span class="typewriter-greeting" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-white ml-1.5 align-middle"></span>
                </h2>
                <p class="text-bps-pale text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px]">
                    <span class="typewriter-desc" data-text="Selamat datang di SIMAKINBPS — Sistem Manajemen Kinerja Pegawai BPS Kabupaten Manggarai."></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-white/80 ml-1 align-middle hidden"></span>
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
        <a href="{{ route('ckp.index') }}" class="px-5 py-2.5 rounded-2xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Lengkapi Realisasi (CKP Saya)</span>
        </a>
        <a href="{{ route('ckp.tambah') }}" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 text-xs font-bold transition shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-emerald-600"></i>
            <span>Input Pekerjaan Tambahan Mandiri</span>
        </a>
    </div>

    <!-- Personal Metrics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Pekerjaan Utama</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $kinerja['jumlah_utama'] }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Pekerjaan Tambahan</span>
            <p class="text-2xl font-black text-indigo-600 mt-2">{{ $kinerja['jumlah_tambahan'] }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Berjalan</span>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $kinerja['berjalan'] }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Selesai</span>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $kinerja['selesai'] }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Skor Kinerja</span>
            <p class="text-2xl font-black text-bps-blue mt-2">{{ $kinerja['skor_kinerja'] }}%</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">CKP Bulanan</span>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $kinerja['nilai_ckp'] }}</p>
        </div>

    </div>

    <!-- Main Layout: Chart & Employee List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Left: Global Chart (approx 66%) -->
        <div class="lg:col-span-2">
            @include('dashboard.partials.global-chart')
        </div>

        <!-- Right: Daftar Pegawai (Mini Progress Bars, approx 33%) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-users text-bps-blue"></i>
                        <span>Pegawai dalam Tim</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-1">Kinerja Bulan {{ $daftarBulan[$currentMonth] }}</p>
                </div>
                <a href="{{ route('ckp.index') }}" class="text-[10px] font-bold text-bps-blue hover:underline bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100 transition-colors">
                    CKP &rarr;
                </a>
            </div>

            <div class="p-6 flex-1 flex flex-col gap-5 overflow-y-auto">
                @php $count = 0; @endphp
                @forelse($rekapTimPegawai as $p)
                    @if($count < 6)
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p['pegawai']->avatar_url }}" alt="{{ $p['pegawai']->name }}" class="w-9 h-9 rounded-xl object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 leading-tight">{{ $p['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $p['pegawai']->jabatan ?? 'Anggota Tim' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-black {{ $p['skor_kinerja'] >= 100 ? 'text-emerald-600' : ($p['skor_kinerja'] >= 80 ? 'text-bps-blue' : 'text-amber-600') }}">
                                    {{ $p['skor_kinerja'] }}%
                                </span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] text-slate-500 font-semibold w-14 shrink-0">Pekerjaan</span>
                                <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                    @php
                                        $progPercent = $p['total_target'] > 0 ? round(($p['total_realisasi'] / $p['total_target']) * 100) : 0;
                                        $progPercent = $progPercent > 100 ? 100 : $progPercent;
                                    @endphp
                                    <div class="h-full bg-bps-blue rounded-full" style="width: {{ $progPercent }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-700 w-10 text-right">{{ $p['total_realisasi'] }}/{{ $p['total_target'] }}</span>
                            </div>
                        </div>
                    @endif
                    @php $count++; @endphp
                @empty
                    <div class="text-center text-slate-400 text-xs py-8 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-user-xmark text-2xl mb-2 text-slate-300"></i>
                        Belum ada pegawai dalam tim ini.
                    </div>
                @endforelse
            </div>
            
            @if(count($rekapTimPegawai) > 6)
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 text-center hover:bg-slate-50 transition-colors">
                <a href="{{ route('ckp.index') }}" class="text-xs font-bold text-bps-blue flex items-center justify-center gap-1">
                    Lihat Semua ({{ count($rekapTimPegawai) }}) <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @include('dashboard.partials.global-calendar')
    </div>

    <!-- Active Tasks Needing Realization -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900">Daftar Pekerjaan Bulan {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</h3>
                <p class="text-xs text-slate-500">Rincian pekerjaan yang ditugaskan kepada Anda</p>
            </div>
            <a href="{{ route('ckp.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                Buka CKP Saya &rarr;
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($personalTasks as $task)
                <div class="p-5 sm:px-6 hover:bg-slate-50/70 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $task->jenis_pekerjaan === 'utama' ? 'bg-blue-50 text-bps-blue border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                {{ $task->jenis_pekerjaan === 'utama' ? 'Pekerjaan Utama' : 'Pekerjaan Tambahan' }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $task->nama_kegiatan }}</h4>
                        <p class="text-xs text-slate-500">
                            Periode: <strong>{{ $task->tanggal_mulai ? $task->tanggal_mulai->format('d M Y') : '-' }} s/d {{ $task->tanggal_selesai ? $task->tanggal_selesai->format('d M Y') : '-' }}</strong> &bull;
                            Estimasi: <strong>{{ $task->estimasi_waktu }} Jam</strong> &bull;
                            Asal Tim: <strong>{{ $task->asal_tim }}</strong>
                        </p>
                    </div>

                    <div class="flex items-center gap-5 sm:text-right shrink-0">
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Capaian</p>
                            <p class="text-sm font-black text-slate-800">
                                {{ $task->realisasi_kuantitas }} / {{ $task->target_kuantitas }} {{ $task->satuan }}
                                <span class="text-xs text-emerald-600 font-bold">({{ $task->persentase_kuantitas }}%)</span>
                            </p>
                            <p class="text-[11px] text-slate-500 font-semibold">
                                Nilai CKP: <strong class="text-bps-blue">{{ $task->kualitas > 0 ? $task->nilai_ckp : 'Belum Dinilai' }}</strong>
                            </p>
                        </div>

                        <a href="{{ route('ckp.index') }}" class="px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm">
                            Isi Realisasi
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                    Belum ada pekerjaan yang tercatat pada periode bulan ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
