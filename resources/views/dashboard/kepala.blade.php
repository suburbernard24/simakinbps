@extends('layouts.app')

@section('title', 'Dashboard Kepala BPS')
@section('page_title', 'Dashboard Pimpinan — Kepala BPS')

@section('content')
<div class="space-y-6">

    <!-- Header & Period Filter -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-bps-dark via-bps-blue to-indigo-900 text-white shadow-xl relative overflow-hidden">
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

    <!-- Summary Metrics (Kepala BPS) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Pegawai</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalPegawai }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Tim</span>
            <p class="text-2xl font-black text-indigo-600 mt-2">{{ $totalTim }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Pekerjaan</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalPekerjaan }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Selesai / Berjalan</span>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $pekerjaanSelesai }} <span class="text-xs text-slate-400 font-normal">/ {{ $pekerjaanBerjalan }}</span></p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rata-rata Skor</span>
            <p class="text-2xl font-black text-bps-blue mt-2">{{ $avgSkorKinerja }}%</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rata-rata CKP</span>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $avgCkp }}</p>
        </div>

    </div>

    <!-- Global Stats & Calendar (All Roles) -->
    @include('dashboard.partials.global-stats')


    <!-- Ringkasan Kinerja Seluruh Pegawai Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900">Peringkat & Ringkasan Kinerja Pegawai</h3>
                <p class="text-xs text-slate-500">Periode: {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</p>
            </div>
            <a href="{{ route('alokasi.pegawai', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">
                Lihat Rincian Kegiatan Pegawai &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 text-center">Rank</th>
                        <th class="py-3.5 px-4">Nama Pegawai</th>
                        <th class="py-3.5 px-4">Tim Kerja</th>
                        <th class="py-3.5 px-4 text-center">Total Jam</th>
                        <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Aksi Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekapPegawai as $idx => $r)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center">
                                @if($idx === 0)
                                    <span class="w-6 h-6 rounded-full bg-amber-400 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">1</span>
                                @elseif($idx === 1)
                                    <span class="w-6 h-6 rounded-full bg-slate-400 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">2</span>
                                @elseif($idx === 2)
                                    <span class="w-6 h-6 rounded-full bg-amber-700 text-white font-black inline-flex items-center justify-center text-xs shadow-sm">3</span>
                                @else
                                    <span class="font-mono text-slate-400 font-bold">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $r['pegawai']->avatar_url }}" alt="{{ $r['pegawai']->name }}" class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $r['pegawai']->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $r['pegawai']->jabatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800">{{ $r['pegawai']->tim ? $r['pegawai']->tim->nama_tim : 'Lintas Tim' }}</span>
                                @if($r['pegawai']->is_ketua_tim)
                                    <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 text-bps-blue border border-blue-200">Ketua Tim</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                                {{ $r['total_estimasi_jam'] }} Jam
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-xl text-xs font-black
                                    @if($r['skor_kinerja'] >= 100) bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($r['skor_kinerja'] >= 80) bg-blue-50 text-bps-blue border border-blue-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $r['skor_kinerja'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-800">
                                {{ $r['nilai_ckp'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($r['pegawai']->isKetuaTim() || $r['jumlah_tambahan'] > 0)
                                    <a href="{{ route('ckp.nilai', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'user_id' => $r['pegawai']->id]) }}" class="px-3 py-1 rounded-xl bg-bps-blue hover:bg-bps-light text-white font-bold text-xs transition inline-flex items-center gap-1 shadow-sm">
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
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data kinerja pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>


@endsection
