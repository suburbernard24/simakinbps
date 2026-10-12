@extends('layouts.app')

@section('title', 'Pengaturan Maksimal Honor Mitra')
@section('page_title', 'Maksimal Honor Mitra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    nominalRaw: '{{ old('maksimal_honor', $currentHonor) }}',
    formatRupiah(val) {
        let clean = (val + '').replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(clean);
    }
}">

    {{-- Banner Header --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400 text-xs font-bold uppercase tracking-wider mb-2 border border-blue-100 dark:border-slate-700">
                <i class="fa-solid fa-coins text-xs"></i>
                <span>Standar Biaya Masukan (SBM) BPS</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Batas Maksimal Honor Mitra
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-xl leading-relaxed">
                Tentukan batas nominal honor maksimal yang dapat diterima oleh seorang mitra statistik dalam satu bulan kalender.
            </p>
        </div>

        <div class="p-4 rounded-2xl bg-bps-faint dark:bg-slate-800 text-right shrink-0 border border-blue-100 dark:border-slate-700">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 block tracking-wider">Berlaku Saat Ini:</span>
            <span class="text-xl sm:text-2xl font-black text-bps-blue dark:text-blue-400">
                Rp {{ number_format($currentHonor, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-slate-400 block mt-0.5">/ mitra / bulan</span>
        </div>
    </div>

    {{-- Form Pengaturan Honor --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-bps-blue text-white flex items-center justify-center font-bold">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Atur Batas Maksimal Honor</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Nilai nominal akan divalidasi saat Ketua Tim membuat alokasi pekerjaan</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 font-bold text-xs">
                Aktif
            </span>
        </div>

        <form method="POST" action="{{ route('admin.honor_mitra.store') }}" class="p-6 sm:p-8 space-y-6 text-xs text-slate-700 dark:text-slate-300">
            @csrf

            {{-- Input Nominal Rupiah --}}
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-800 dark:text-slate-200">
                    Batas Maksimal Honor per Bulan (IDR) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center gap-1.5 text-slate-400 font-black text-base">
                        <span>Rp</span>
                    </div>
                    <input type="text" 
                           name="maksimal_honor" 
                           :value="formatRupiah(nominalRaw)"
                           @input="nominalRaw = $event.target.value.replace(/\D/g, '')"
                           required 
                           placeholder="Contoh: 4.500.000"
                           class="w-full pl-14 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-bps-blue font-black text-lg text-slate-900 dark:text-white tracking-wide transition">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">
                    * Masukkan angka murni atau gunakan format rupiah. Sistem akan memvalidasi agar total honor satu mitra dalam bulan yang sama tidak melebihi nominal ini.
                </p>
            </div>

            {{-- Lingkup Periode --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-bold mb-1">Berlaku untuk Tahun (Opsional)</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $currentYear) }}" min="2020" max="2050" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold">
                    <p class="text-[10px] text-slate-400 mt-1">Biarkan default tahun ini atau kosongkan untuk berlaku umum.</p>
                </div>

                <div>
                    <label class="block font-bold mb-1">Berlaku untuk Bulan (Opsional)</label>
                    <select name="bulan" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold">
                        <option value="">-- Berlaku Sepanjang Tahun --</option>
                        @for($b = 1; $b <= 12; $b++)
                            <option value="{{ $b }}" {{ old('bulan', $settingKhusus ? $currentMonth : '') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(2026, $b, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <p class="text-[10px] text-slate-400 mt-1">Pilih bulan spesifik atau biarkan berlaku sepanjang tahun.</p>
                </div>
            </div>

            {{-- Keterangan / Dasar Aturan --}}
            <div class="space-y-1">
                <label class="block font-bold mb-1">Keterangan / Dasar Regulasi</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $settingKhusus->keterangan ?? $settingGlobal->keterangan ?? 'Standar Biaya Masukan (SBM) Honor Mitra Statistik BPS') }}" 
                       placeholder="Contoh: SBM BPS TA 2026 / Keputusan Kepala BPS"
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-bps-blue hover:bg-bps-dark text-white rounded-2xl font-bold text-xs shadow-md shadow-bps-blue/25 hover:-translate-y-0.5 transition-all flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Pengaturan Honor</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat Pengaturan Honor --}}
    @if($riwayatPengaturan->isNotEmpty())
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 space-y-4">
        <h4 class="font-extrabold text-slate-900 dark:text-white text-sm">Riwayat Pembaruan Batas Honor</h4>
        <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
            @foreach($riwayatPengaturan as $r)
            <div class="py-3 flex items-center justify-between">
                <div>
                    <span class="font-bold text-slate-800 dark:text-slate-200">
                        Rp {{ number_format($r->maksimal_honor, 0, ',', '.') }}
                    </span>
                    <span class="text-slate-400 ml-2">
                        @if($r->tahun && $r->bulan)
                            (Periode {{ $r->bulan }}/{{ $r->tahun }})
                        @elseif($r->tahun)
                            (Tahun {{ $r->tahun }})
                        @else
                            (Pengaturan Global)
                        @endif
                    </span>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $r->keterangan }}</p>
                </div>
                <div class="text-right text-[11px] text-slate-400">
                    <span>Oleh: {{ $r->updater->name ?? 'Admin' }}</span>
                    <span class="block">{{ $r->updated_at?->translatedFormat('d M Y, H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
