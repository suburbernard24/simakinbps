@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('page_title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center flex-wrap min-h-[36px]">
                    <span class="typewriter-greeting" data-text="Halo, {{ Auth::user()->name }}!"></span><span class="typewriter-cursor title-cursor inline-block w-[3px] h-[0.85em] bg-white ml-1.5 align-middle"></span>
                </h2>
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed min-h-[22px]">
                    <span class="typewriter-desc" data-text="Selamat datang di SIMAKINBPS — Sistem Manajemen Kinerja Pegawai BPS Kabupaten Manggarai."></span><span class="typewriter-cursor desc-cursor inline-block w-[2px] h-[0.85em] bg-slate-300 ml-1 align-middle hidden"></span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.pegawai.create') }}" class="px-5 py-3 rounded-2xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition-all shadow-lg shadow-bps-blue/30 flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pegawai</span>
                </a>
                <a href="{{ route('admin.hari_kerja.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 flex items-center gap-2">
                    <i class="fa-solid fa-business-time"></i>
                    <span>Atur Hari Kerja</span>
                </a>
                <a href="{{ route('admin.frontend.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 flex items-center gap-2">
                    <i class="fa-solid fa-paint-roller"></i>
                    <span>Tampilan Frontend</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Pegawai -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total Pegawai</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-bps-blue flex items-center justify-center text-base">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900">{{ $totalPegawai }}</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pegawai Aktif BPS Manggarai
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-bold text-bps-blue hover:underline flex items-center justify-between">
                    <span>Kelola Pegawai</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Total Tim Kerja -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Tim Kerja BPS</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900">{{ $totalTim }}</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Tim Statistik & Subbagian</p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.tim.index') }}" class="text-xs font-bold text-indigo-600 hover:underline flex items-center justify-between">
                    <span>Kelola Tim</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Hari Kerja Bulan Ini -->
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Hari Kerja Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                    <i class="fa-solid fa-business-time"></i>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-3xl font-black text-slate-900">
                    {{ $hariKerjaBulanIni ? $hariKerjaBulanIni->jumlah_hari_kerja : 22 }} <span class="text-sm font-bold text-slate-500">Hari</span>
                </h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    Total: <strong class="text-slate-800">{{ $hariKerjaBulanIni ? $hariKerjaBulanIni->total_jam : (22 * 7.5) }} Jam</strong> (Periode {{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                </p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.hari_kerja.index') }}" class="text-xs font-bold text-amber-600 hover:underline flex items-center justify-between">
                    <span>Kelola Hari Kerja</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>


    <!-- Quick Access Tables & Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Tim Kerja BPS -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Tim & Ketua Tim</h3>
                    <p class="text-xs text-slate-500">Struktur organisasi tim kerja di BPS Kabupaten Manggarai</p>
                </div>
                <a href="{{ route('admin.tim.index') }}" class="text-xs font-bold text-bps-blue hover:underline">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100 mt-2">
                @forelse($teams as $tim)
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-slate-800">{{ $tim->nama_tim }}</h4>
                            <p class="text-[11px] text-slate-500">
                                Ketua: <strong class="text-slate-700">{{ $tim->ketua ? $tim->ketua->name : 'Belum Ditentukan' }}</strong>
                            </p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">
                            {{ $tim->pegawais_count }} Pegawai
                        </span>
                    </div>
                @empty
                    <p class="py-6 text-center text-xs text-slate-400">Belum ada tim yang didaftarkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Pegawai Terbaru -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Pegawai Terbaru</h3>
                    <p class="text-xs text-slate-500">Pengguna terdaftar baru dalam sistem SIMAKINBPS</p>
                </div>
                <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-bold text-bps-blue hover:underline">Kelola Pegawai</a>
            </div>

            <div class="divide-y divide-slate-100 mt-2">
                @foreach($recentUsers as $usr)
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $usr->avatar_url }}" alt="{{ $usr->name }}" class="w-9 h-9 rounded-xl object-cover ring-1 ring-slate-200">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">{{ $usr->name }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $usr->jabatan ?? '-' }} &bull; {{ $usr->tim ? $usr->tim->singkatan : 'Non-Tim' }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold uppercase {{ $usr->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                            {{ $usr->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
