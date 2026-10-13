@extends('layouts.app')

@section('title', 'Pengaturan Honor Mitra')
@section('page_title', 'Pengaturan Honor Mitra')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    fmtPendataanRaw: '{{ old('maksimal_honor_pendataan', $honorPendataan) }}',
    fmtPengolahanRaw: '{{ old('maksimal_honor_pengolahan', $honorPengolahan) }}',
    showRiwayat: false,
    formatRupiah(val) {
        let clean = (val + '').replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(clean);
    }
}">

    {{-- =====================================================================
         BANNER HEADER
    ====================================================================== --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100 dark:border-slate-700">
                <i class="fa-solid fa-coins text-xs"></i>
                <span>Standar Biaya Masukan (SBM) BPS</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Batas Honor Mitra Statistik
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-xl leading-relaxed">
                Atur batas nominal honor maksimal untuk setiap klasifikasi mitra statistik BPS dalam satu bulan kalender.
            </p>
        </div>

        {{-- Tombol Riwayat --}}
        <button @click="showRiwayat = true"
                type="button"
                class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-700 transition-all hover:-translate-y-0.5 shadow-sm cursor-pointer shrink-0">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Riwayat Pembaruan</span>
        </button>
    </div>

    {{-- =====================================================================
         DUA PANEL BATAS HONOR SAAT INI
    ====================================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Panel Pendataan Lapangan --}}
        <div class="p-5 rounded-3xl bg-gradient-to-br from-bps-blue/10 to-blue-50 dark:from-blue-950/40 dark:to-slate-900 border border-blue-200/70 dark:border-blue-900/50 flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-bps-blue text-white flex items-center justify-center text-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="text-xs font-extrabold text-bps-blue dark:text-blue-400 uppercase tracking-wider">Mitra Pendataan Lapangan</span>
            </div>
            <div>
                <span class="text-2xl sm:text-3xl font-black text-bps-blue dark:text-blue-300">
                    Rp {{ number_format($honorPendataan, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 block mt-0.5">/ mitra / bulan (berlaku saat ini)</span>
            </div>
        </div>

        {{-- Panel Pengolahan --}}
        <div class="p-5 rounded-3xl bg-gradient-to-br from-emerald-500/10 to-emerald-50 dark:from-emerald-950/40 dark:to-slate-900 border border-emerald-200/70 dark:border-emerald-900/50 flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm">
                    <i class="fa-solid fa-server"></i>
                </div>
                <span class="text-xs font-extrabold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Mitra Pengolahan</span>
            </div>
            <div>
                <span class="text-2xl sm:text-3xl font-black text-emerald-700 dark:text-emerald-300">
                    Rp {{ number_format($honorPengolahan, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 block mt-0.5">/ mitra / bulan (berlaku saat ini)</span>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         FORM PENGATURAN HONOR
    ====================================================================== --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-bps-blue text-white flex items-center justify-center font-bold">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Atur Batas Maksimal Honor</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Nilai akan divalidasi saat Ketua Tim membuat alokasi pekerjaan mitra</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 font-bold text-xs">
                Aktif
            </span>
        </div>

        <form method="POST" action="{{ route('admin.honor_mitra.store') }}" class="p-6 sm:p-8 space-y-8 text-xs text-slate-700 dark:text-slate-300">
            @csrf

            {{-- ── Periode ──────────────────────────────────────────── --}}
            <div>
                <h4 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-slate-400"></i> Lingkup Periode
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1">Berlaku untuk Tahun <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="tahun" value="{{ old('tahun', $currentYear) }}" min="2020" max="2050"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold">
                        <p class="text-[10px] text-slate-400 mt-1">Biarkan default tahun ini atau kosongkan untuk berlaku umum.</p>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Berlaku untuk Bulan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <select name="bulan" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold">
                            <option value="">-- Berlaku Sepanjang Tahun --</option>
                            @for($b = 1; $b <= 12; $b++)
                                <option value="{{ $b }}" {{ old('bulan', $settingPendataanKhusus ? $currentMonth : '') == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate(2026, $b, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Pilih bulan spesifik atau biarkan berlaku sepanjang tahun.</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-slate-800">

            {{-- ── Dua Kolom Klasifikasi ────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- === Panel Kiri: Mitra Pendataan Lapangan === --}}
                <div class="rounded-2xl border border-blue-200 dark:border-blue-900/60 overflow-hidden">
                    <div class="px-5 py-3 bg-bps-blue/5 dark:bg-blue-950/30 border-b border-blue-200 dark:border-blue-900/60 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-bps-blue text-white flex items-center justify-center text-xs">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <span class="font-extrabold text-bps-blue dark:text-blue-400 text-xs">Mitra Pendataan Lapangan</span>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- Nominal --}}
                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-800 dark:text-slate-200">
                                Batas Honor (IDR) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-base">
                                    <span>Rp</span>
                                </div>
                                <input type="text"
                                       name="maksimal_honor_pendataan"
                                       id="input_pendataan"
                                       :value="formatRupiah(fmtPendataanRaw)"
                                       @input="fmtPendataanRaw = $event.target.value.replace(/\D/g, '')"
                                       required
                                       placeholder="Contoh: 3.653.000"
                                       class="w-full pl-14 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-bps-blue font-black text-lg text-slate-900 dark:text-white tracking-wide transition">
                            </div>
                        </div>
                        {{-- Keterangan --}}
                        <div class="space-y-1">
                            <label class="block font-bold mb-1">Keterangan / Dasar Regulasi</label>
                            <input type="text" name="keterangan_pendataan"
                                   value="{{ old('keterangan_pendataan', $settingPendataanKhusus->keterangan ?? $settingPendataanGlobal->keterangan ?? 'SBM Honor Mitra Pendataan Lapangan BPS') }}"
                                   placeholder="Contoh: SBM BPS TA 2026"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>
                </div>

                {{-- === Panel Kanan: Mitra Pengolahan === --}}
                <div class="rounded-2xl border border-emerald-200 dark:border-emerald-900/60 overflow-hidden">
                    <div class="px-5 py-3 bg-emerald-50 dark:bg-emerald-950/30 border-b border-emerald-200 dark:border-emerald-900/60 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <span class="font-extrabold text-emerald-700 dark:text-emerald-400 text-xs">Mitra Pengolahan</span>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- Nominal --}}
                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-800 dark:text-slate-200">
                                Batas Honor (IDR) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black text-base">
                                    <span>Rp</span>
                                </div>
                                <input type="text"
                                       name="maksimal_honor_pengolahan"
                                       id="input_pengolahan"
                                       :value="formatRupiah(fmtPengolahanRaw)"
                                       @input="fmtPengolahanRaw = $event.target.value.replace(/\D/g, '')"
                                       required
                                       placeholder="Contoh: 3.379.000"
                                       class="w-full pl-14 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-emerald-500 font-black text-lg text-slate-900 dark:text-white tracking-wide transition">
                            </div>
                        </div>
                        {{-- Keterangan --}}
                        <div class="space-y-1">
                            <label class="block font-bold mb-1">Keterangan / Dasar Regulasi</label>
                            <input type="text" name="keterangan_pengolahan"
                                   value="{{ old('keterangan_pengolahan', $settingPengolahanKhusus->keterangan ?? $settingPengolahanGlobal->keterangan ?? 'SBM Honor Mitra Pengolahan BPS') }}"
                                   placeholder="Contoh: SBM BPS TA 2026"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

            </div>{{-- end grid --}}

            {{-- Info --}}
            <p class="text-[11px] text-slate-400 -mt-2">
                * Sistem akan memvalidasi agar total honor seorang mitra dalam bulan yang sama tidak melebihi nominal sesuai klasifikasinya.
            </p>

            {{-- Tombol Simpan --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <button type="submit"
                        class="px-6 py-3 bg-bps-blue hover:bg-bps-dark text-white rounded-2xl font-bold text-xs shadow-md shadow-bps-blue/25 hover:-translate-y-0.5 transition-all flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Pengaturan Honor</span>
                </button>
            </div>
        </form>
    </div>

</div>

{{-- ==========================================================================
     MODAL RIWAYAT PEMBARUAN BATAS HONOR
========================================================================== --}}
<div x-show="showRiwayat"
     x-cloak
     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
     @keydown.escape.window="showRiwayat = false">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
         @click="showRiwayat = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    {{-- Modal Panel --}}
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-bps-blue text-white flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm">Riwayat Pembaruan Batas Honor</h3>
                    <p class="text-[11px] text-slate-400">Catatan seluruh perubahan pengaturan honor mitra</p>
                </div>
            </div>
            <button @click="showRiwayat = false"
                    class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Modal Body (Scrollable) --}}
        <div class="overflow-y-auto flex-1 px-6 py-4">
            @if($riwayatPengaturan->isNotEmpty())
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @foreach($riwayatPengaturan as $r)
                    <div class="py-3.5 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            {{-- Badge Jenis Mitra --}}
                            @if(($r->jenis_mitra ?? 'pendataan_lapangan') === 'pengolahan')
                                <span class="mt-0.5 shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] border border-emerald-200 dark:border-emerald-900/60">
                                    <i class="fa-solid fa-server text-[9px]"></i> Pengolahan
                                </span>
                            @else
                                <span class="mt-0.5 shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/40 text-bps-blue dark:text-blue-400 font-bold text-[10px] border border-blue-200 dark:border-blue-900/60">
                                    <i class="fa-solid fa-map-location-dot text-[9px]"></i> Pendataan
                                </span>
                            @endif
                            <div class="min-w-0">
                                <span class="font-bold text-slate-800 dark:text-slate-200">
                                    Rp {{ number_format($r->maksimal_honor, 0, ',', '.') }}
                                </span>
                                <span class="text-slate-400 ml-2">
                                    @if($r->tahun && $r->bulan)
                                        (Periode {{ \Carbon\Carbon::createFromDate($r->tahun, $r->bulan, 1)->translatedFormat('M Y') }})
                                    @elseif($r->tahun)
                                        (Tahun {{ $r->tahun }})
                                    @else
                                        (Global)
                                    @endif
                                </span>
                                <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $r->keterangan }}</p>
                            </div>
                        </div>
                        <div class="text-right text-[11px] text-slate-400 shrink-0">
                            <span class="font-medium text-slate-600 dark:text-slate-400">{{ $r->updater->name ?? 'Admin' }}</span>
                            <span class="block mt-0.5">{{ $r->updated_at?->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($riwayatPengaturan->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $riwayatPengaturan->links() }}
                </div>
                @endif
            @else
                <div class="py-16 text-center text-slate-400">
                    <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-30"></i>
                    <p class="font-semibold text-sm">Belum ada riwayat pengaturan</p>
                    <p class="text-xs mt-1">Simpan pengaturan honor pertama kali untuk memulai riwayat.</p>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
