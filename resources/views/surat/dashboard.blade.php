@extends('layouts.app')

@section('title', 'Dashboard e-Surat BPS')
@section('page_title', 'Dashboard e-Surat BPS')

@section('content')
<div class="space-y-6">

    <!-- Welcome & Quick Action Header -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-bps-dark via-bps-blue to-blue-600 p-6 md:p-8 text-white shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold">
                    <i class="fa-solid fa-stamp text-amber-300"></i>
                    <span>Sistem Administrasi e-Surat BPS Kab. Manggarai</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Otomatisasi Naskah Dinas & Administrasi Surat
                </h2>
                <p class="text-white/80 text-xs md:text-sm max-w-2xl font-normal leading-relaxed">
                    Pembuatan naskah dinas, penomoran otomatis terpusat, dan arsip digital resmi sesuai pedoman kearsipan BPS.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(Auth::user()->canCreateSurat())
                <a href="{{ route('surat.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold text-sm shadow-lg shadow-amber-400/30 transition-transform active:scale-95">
                    <i class="fa-solid fa-plus-circle text-base"></i>
                    <span>Buat Surat Baru</span>
                </a>
                @endif
                <a href="{{ route('surat.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-sm backdrop-blur-md transition">
                    <i class="fa-solid fa-folder-open"></i>
                    <span>Daftar Surat</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistic Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Surat -->
        <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 hover-lift bg-white">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Surat</span>
                <span class="w-8 h-8 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-sm">
                    <i class="fa-solid fa-envelope"></i>
                </span>
            </div>
            <p class="text-3xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ $totalSurat }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Semua naskah dinas</p>
        </div>

        <!-- Draft -->
        <a href="{{ route('surat.index', ['status' => 'draft']) }}" class="glass-panel p-5 rounded-2xl border border-slate-200/80 hover-lift bg-white block">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Draft</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-file-pen"></i>
                </span>
            </div>
            <p class="text-3xl font-black text-slate-700 dark:text-slate-200 mt-2">{{ $totalDraft }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Belum diterbitkan</p>
        </a>

        <!-- Selesai & Nomor Terbit -->
        <a href="{{ route('surat.index', ['status' => 'selesai']) }}" class="glass-panel p-5 rounded-2xl border border-emerald-100 hover-lift bg-white block">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Selesai (Dinomori)</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <p class="text-3xl font-black text-emerald-600 mt-2">{{ $totalSelesai }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Dinomori resmi & siap cetak</p>
        </a>
    </div>

    @if(Auth::user()->canCreateSurat())
    <!-- Quick Shortcuts by Letter Type (PRD 8: Jenis Surat) -->
    <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-plus text-bps-blue"></i>
                    <span>Pilih Format Surat untuk Diajukan</span>
                </h3>
                <p class="text-xs text-slate-400">Klik jenis surat di bawah untuk langsung membuat draft formulir naskah dinas</p>
            </div>
            <a href="{{ route('surat.create') }}" class="text-xs font-bold text-bps-blue hover:underline">
                Lihat Semua Template &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Surat Tugas -->
            <a href="{{ route('surat.create', ['jenis_id' => $statsByJenis->firstWhere('kode', 'ST')?->id]) }}" 
               class="p-4 rounded-xl border border-blue-100 bg-blue-50/50 hover:bg-blue-50 hover:border-blue-300 transition group flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Tugas</span>
                </div>
                <div class="mt-4">
                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-blue-700 transition">Surat Tugas</h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">Penugasan dinas lapangan/survei satu atau banyak pegawai.</p>
                </div>
                <div class="mt-3 pt-3 border-t border-blue-100/80 flex items-center justify-between text-xs text-blue-600 font-semibold">
                    <span>Buat Surat Tugas</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Surat Keputusan (SK) -->
            <a href="{{ route('surat.create', ['jenis_id' => $statsByJenis->firstWhere('kode', 'SK')?->id]) }}" 
               class="p-4 rounded-xl border border-purple-100 bg-purple-50/50 hover:bg-purple-50 hover:border-purple-300 transition group flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-gavel"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">SK</span>
                </div>
                <div class="mt-4">
                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-purple-700 transition">Surat Keputusan (SK)</h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">Penetapan tim kerja, panitia kegiatan, dan keputusan pimpinan.</p>
                </div>
                <div class="mt-3 pt-3 border-t border-purple-100/80 flex items-center justify-between text-xs text-purple-600 font-semibold">
                    <span>Buat SK Tim</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Surat Keluar Dinas -->
            <a href="{{ route('surat.create', ['jenis_id' => $statsByJenis->firstWhere('kode', 'B')?->id]) }}" 
               class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50 hover:bg-emerald-50 hover:border-emerald-300 transition group flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Keluar</span>
                </div>
                <div class="mt-4">
                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-emerald-700 transition">Surat Keluar</h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">Surat dinas resmi eksternal kepada OPD, instansi, atau mitra.</p>
                </div>
                <div class="mt-3 pt-3 border-t border-emerald-100/80 flex items-center justify-between text-xs text-emerald-600 font-semibold">
                    <span>Buat Surat Keluar</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Berita Acara -->
            <a href="{{ route('surat.create', ['jenis_id' => $statsByJenis->firstWhere('kode', 'BA')?->id]) }}" 
               class="p-4 rounded-xl border border-amber-100 bg-amber-50/50 hover:bg-amber-50 hover:border-amber-300 transition group flex flex-col justify-between">
                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">BA</span>
                </div>
                <div class="mt-4">
                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-amber-700 transition">Berita Acara</h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">Pemeriksaan, serah terima dokumen, aset atau pekerjaan.</p>
                </div>
                <div class="mt-3 pt-3 border-t border-amber-100/80 flex items-center justify-between text-xs text-amber-600 font-semibold">
                    <span>Buat Berita Acara</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </div>
    </div>
    @endif

    <!-- Recent Letters & Workflow Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Daftar Surat Terbaru -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-bps-blue"></i>
                        <span>Aktivitas Surat Terkini</span>
                    </h3>
                    <p class="text-xs text-slate-400">Riwayat pengajuan dan status surat terakhir dalam sistem</p>
                </div>
                <a href="{{ route('surat.index') }}" class="text-xs font-bold text-bps-blue hover:underline">
                    Semua Surat &rarr;
                </a>
            </div>

            @if($recentSurats->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600">Belum ada surat yang diajukan</p>
                    @if(Auth::user()->canCreateSurat())
                    <p class="text-xs text-slate-400 mt-1">Mulai buat surat perdana Anda dengan mengklik tombol Buat Surat.</p>
                    <a href="{{ route('surat.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-bps-blue text-white text-xs font-bold rounded-xl shadow">
                        <i class="fa-solid fa-plus"></i> Buat Surat Sekarang
                    </a>
                    @else
                    <p class="text-xs text-slate-400 mt-1">Belum ada naskah dinas yang diajukan oleh pegawai.</p>
                    @endif
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentSurats as $s)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 -mx-3 px-3 rounded-xl transition">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-bps-faint text-bps-blue flex-shrink-0 flex items-center justify-center font-bold text-xs mt-0.5">
                                {{ $s->jenisSurat->kode ?? 'ST' }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('surat.show', $s->id) }}" class="text-xs font-bold text-slate-800 hover:text-bps-blue truncate block">
                                    {{ $s->perihal }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-[11px] text-slate-400">
                                    <span><i class="fa-regular fa-user mr-1"></i>{{ $s->pemohon->name ?? '-' }}</span>
                                    <span>&bull;</span>
                                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($s->tanggal_surat)->translatedFormat('d M Y') }}</span>
                                    @if($s->nomor_surat)
                                    <span>&bull;</span>
                                    <span class="font-mono text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.2 rounded">{{ $s->nomor_surat }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0 sm:self-center">
                            @php $badge = $s->status_badge; @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badge['bg'] }}">
                                {{ $badge['label'] }}
                            </span>
                            <a href="{{ route('surat.show', $s->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-bps-blue hover:bg-bps-faint transition">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Alur & Ringkasan e-Surat BPS (PRD 6) -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-route text-bps-blue"></i>
                <span>Alur Proses Surat BPS</span>
            </h3>

            <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-bps-blue text-white flex items-center justify-center text-[9px] font-bold">1</div>
                    <p class="text-xs font-bold text-slate-800">Pembuatan Naskah Dinas</p>
                    <p class="text-[11px] text-slate-500">Pemohon memilih jenis surat, mengisi rincian kegiatan/tujuan, serta menugaskan pegawai.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[9px] font-bold">2</div>
                    <p class="text-xs font-bold text-slate-800">Penomoran Otomatis & Terbit</p>
                    <p class="text-[11px] text-slate-500">Sistem otomatis menerbitkan nomor surat resmi terpusat BPS tanpa menunggu persetujuan manual.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-slate-600 text-white flex items-center justify-center text-[9px] font-bold">3</div>
                    <p class="text-xs font-bold text-slate-800">Cetak & Arsip Digital</p>
                    <p class="text-[11px] text-slate-500">Dokumen resmi siap dicetak PDF dan tersimpan otomatis dalam arsip digital BPS.</p>
                </div>
            </div>

            <!-- Satker Tag -->
            <div class="p-3 rounded-xl bg-bps-faint border border-bps-pale text-[11px] text-bps-dark flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-bps-blue"></i>
                <span>Satker: <strong>53131 - BPS Kabupaten Manggarai</strong></span>
            </div>
        </div>

    </div>

</div>
@endsection
