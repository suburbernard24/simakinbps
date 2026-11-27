@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('page_title', 'Dashboard Administrator Sistem')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner (Admin Control Deck) -->
    <div class="p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-blue-200/60 dark:border-slate-800"
         style="background: linear-gradient(135deg, #EEF4FC 0%, #E0EDFD 35%, #1E40AF 85%, #0D47A1 100%);">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]" style="color: #0A2540;">
                    <span class="typewriter-greeting" style="color: #0A2540;" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-bps-blue ml-1.5 align-middle"></span>
                </h2>
                <p class="text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px] font-medium" style="color: #334155;">
                    <span class="typewriter-desc" data-text="Selamat Datang di SIMAKINBPS - Sistem Manajemen Kinerja BPS Manggarai"></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-slate-500 ml-1 align-middle hidden"></span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.pegawai.create') }}" class="px-5 py-3 rounded-2xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition-all shadow-lg shadow-bps-blue/25 hover:shadow-xl flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pegawai</span>
                </a>
                <a href="{{ route('admin.hari_kerja.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 backdrop-blur-md flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-business-time text-amber-400"></i>
                    <span>Atur Hari Kerja</span>
                </a>
                <a href="{{ route('admin.frontend.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 backdrop-blur-md flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-sliders text-blue-300"></i>
                    <span>Frontend Portal</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Pegawai -->
        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Pegawai</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center text-base">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalPegawai }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pegawai Aktif BPS Manggarai
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-bold text-bps-blue dark:text-blue-400 hover:underline flex items-center justify-between">
                    <span>Kelola Pegawai</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Total Tim Kerja -->
        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tim Kerja BPS</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-base">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalTim }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Tim Statistik &amp; Subbagian</p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.tim.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center justify-between">
                    <span>Kelola Tim Kerja</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Hari Kerja Bulan Ini -->
        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Hari Kerja Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base">
                    <i class="fa-solid fa-business-time"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">
                    {{ $hariKerjaBulanIni ? $hariKerjaBulanIni->jumlah_hari_kerja : 22 }} <span class="text-sm font-bold text-slate-500">Hari</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                    Total: <strong class="text-slate-800 dark:text-slate-200">{{ $hariKerjaBulanIni ? $hariKerjaBulanIni->total_jam : (22 * 7.5) }} Jam</strong> (Periode {{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.hari_kerja.index') }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center justify-between">
                    <span>Kelola Hari Kerja</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- e-Surat Kedinasan Quick Info -->
        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm saas-card">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">Master e-Surat</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-base">
                    <i class="fa-solid fa-stamp"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">Aktif</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                    Jenis &bull; Template &bull; Penomoran
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('surat.master.jenis_surat.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center justify-between">
                    <span>Pengaturan Naskah Dinas</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Quick Access Tables & Structure -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Tim Kerja BPS -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 saas-card flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-indigo-600"></i>
                            <span>Daftar Tim &amp; Ketua Tim</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Struktur organisasi tim kerja di BPS Kabupaten Manggarai</p>
                    </div>
                    <a href="{{ route('admin.tim.index') }}" class="text-xs font-bold text-bps-blue dark:text-blue-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 mt-2">
                    @forelse($teams as $tim)
                        <div class="py-3.5 flex items-center justify-between">
                            <div class="space-y-0.5 min-w-0 pr-3">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $tim->nama_tim }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                    Ketua: <strong class="text-slate-700 dark:text-slate-300">{{ $tim->ketua ? $tim->ketua->name : 'Belum Ditentukan' }}</strong>
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold shrink-0">
                                {{ $tim->pegawais_count }} Pegawai
                            </span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-xs text-slate-400">Belum ada tim yang didaftarkan.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-right">
                <a href="{{ route('admin.tim.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    <span>Kelola Struktur Tim</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Pegawai Terbaru -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 saas-card flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-user-clock text-bps-blue"></i>
                            <span>Pegawai Terbaru</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pengguna terdaftar terbaru dalam sistem SIMAKINBPS</p>
                    </div>
                    <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-bold text-bps-blue dark:text-blue-400 hover:underline">
                        Kelola Pegawai &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 mt-2">
                    @foreach($recentUsers as $usr)
                        <div class="py-3.5 flex items-center justify-between">
                            <div class="flex items-center gap-3 min-w-0 pr-3">
                                <img src="{{ $usr->avatar_url }}" alt="{{ $usr->name }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0">
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $usr->name }}</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ $usr->jabatan ?? '-' }} &bull; {{ $usr->tim ? $usr->tim->singkatan : 'Non-Tim' }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase shrink-0 {{ $usr->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-red-50 text-red-600 border border-red-200 dark:bg-rose-950/40 dark:text-rose-300' }}">
                                {{ $usr->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-right">
                <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-bps-blue dark:text-blue-400 hover:underline">
                    <span>Daftar Lengkap Pegawai</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
