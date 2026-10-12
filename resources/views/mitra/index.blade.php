@extends('layouts.app')

@section('title', 'Katalog Mitra Statistik')
@section('page_title', 'Katalog Mitra Statistik')

@section('content')
<div class="space-y-6">

    {{-- Banner & Search Header --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100 dark:border-slate-700">
                <i class="fa-solid fa-users-gear text-xs"></i>
                <span>Database Mitra BPS</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Mitra Statistik BPS
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                Daftar seluruh petugas mitra pencacah (PCL), pengawas (PML), dan pengolahan data statistik di BPS Kabupaten Manggarai.
            </p>
        </div>

        {{-- Filter & Pencarian --}}
        <form method="GET" action="{{ route('kinerja-mitra.mitra.index') }}" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, Sobat-ID, NIK..." 
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-bps-blue outline-none transition">
            </div>

            <div>
                <select name="status" onchange="this.form.submit()"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300 outline-none focus:ring-2 focus:ring-bps-blue">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-sm">
                Cari
            </button>

            @if(Auth::user()->isKetuaTim())
            <a href="{{ route('kinerja-mitra.master_mitra.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-users-gear text-xs"></i>
                <span>Kelola Master</span>
            </a>
            @endif
        </form>
    </div>

    {{-- Grid Kartu Mitra --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($mitras as $mitra)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col justify-between group">
            
            <div class="p-6 space-y-4">
                <div class="flex items-start justify-between gap-3">
                    <img src="{{ $mitra->foto_url }}" 
                         alt="{{ $mitra->nama }}" 
                         class="w-14 h-14 rounded-2xl object-cover ring-2 ring-blue-50 dark:ring-slate-800 shadow-2xs group-hover:scale-105 transition-transform">

                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $mitra->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                        {{ $mitra->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm line-clamp-1 group-hover:text-bps-blue transition-colors">
                        {{ $mitra->nama }}
                    </h3>
                    <p class="font-mono text-[11px] font-bold text-bps-blue dark:text-blue-400 mt-0.5">
                        {{ $mitra->sobat_id }}
                    </p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                        {{ $mitra->posisi_default ?? 'Pencacah Lapangan (PCL)' }}
                    </p>
                </div>

                {{-- Rating Bintang Rata-rata --}}
                <div class="p-3 rounded-2xl bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                        <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ number_format($mitra->rata_rata_bintang, 1) }}</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold">{{ $mitra->total_ulasan }} Ulasan</span>
                </div>

                <div class="space-y-1 text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <span>Wilayah:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[150px]">{{ $mitra->kabupaten }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Histori Survei:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $mitra->alokasi_mitras_count }} Kegiatan</span>
                    </div>
                </div>
            </div>

            {{-- Footer Kartu: Link ke Detail --}}
            <div class="p-4 bg-slate-50/80 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('kinerja-mitra.mitra.show', $mitra->id) }}" 
                   class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 hover:border-bps-blue hover:text-bps-blue text-xs font-bold text-slate-700 dark:text-slate-200 transition-colors shadow-2xs">
                    <span>Lihat Informasi &amp; Histori</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 dark:text-slate-500">
            <div class="w-16 h-16 rounded-3xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl text-slate-400 mx-auto mb-4">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base">Tidak Ditemukan Data Mitra</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Tidak ada mitra yang cocok dengan kata kunci pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>
        {{ $mitras->links() }}
    </div>

</div>
@endsection
