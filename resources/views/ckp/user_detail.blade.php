@extends('layouts.app')

@section('title', 'Detail CKP - ' . $user->name)
@section('page_title', 'Detail CKP Pegawai')

@section('content')
<div class="space-y-8">

    <!-- TOP BREADCRUMB & ACTION -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('ckp.monitoring', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-emerald-700 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Monitoring CKP Pegawai</span>
        </a>

        <div class="flex items-center gap-2.5">
            <!-- Cetak CKP-T untuk user ini -->
            <a href="{{ route('ckp.t_cetak', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'user_id' => $user->id]) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-print text-amber-500"></i>
                <span>Cetak CKP-T</span>
            </a>

            <!-- Cetak CKP-R untuk user ini -->
            <a href="{{ route('ckp.cetak', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'user_id' => $user->id]) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>Cetak CKP-R</span>
            </a>
        </div>
    </div>

    <!-- PEGAWAI PROFILE & FILTER CARD -->
    <div class="glass-panel rounded-3xl p-6 sm:p-7 border border-white/80 shadow-glass">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                     class="w-16 h-16 rounded-2xl object-cover ring-4 ring-emerald-500/20 shadow-md">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider mb-1">
                        Pegawai BPS Manggarai
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">
                        NIP. {{ $user->nip ?? '-' }} &bull; {{ $user->jabatan ?? '-' }} ({{ $user->golongan ?? '-' }})
                    </p>
                </div>
            </div>

            <!-- Filter Period Form -->
            <form method="GET" action="{{ route('ckp.user_detail', $user->id) }}" class="flex items-center gap-2 bg-white/70 p-3 rounded-2xl border border-slate-200/80 shadow-xs">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-0.5">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="bg-white text-xs font-bold text-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @foreach($daftarTahun as $th)
                            <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-0.5">Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="bg-white text-xs font-bold text-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @foreach($daftarBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-3.5">
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Muat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- STAT SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-panel p-5 rounded-3xl border border-white/80 shadow-glass">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Output</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-slate-900 font-mono">
                    {{ number_format($totalRealisasi, 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400">/ {{ number_format($totalTarget, 0, ',', '.') }}</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">{{ $ckp->count() }} Utama, {{ $ckp_tambahan->count() }} Tambahan</p>
        </div>

        <div class="glass-panel p-5 rounded-3xl border border-white/80 shadow-glass">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">% Kuantitas</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-emerald-700 font-mono">
                    {{ number_format($avgPersentaseKuantitas, 2, ',', '.') }}%
                </span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $avgPersentaseKuantitas) }}%"></div>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-3xl border border-white/80 shadow-glass">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Kualitas</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-teal-800 font-mono">
                    {{ $avgKualitas > 0 ? number_format($avgKualitas, 2, ',', '.') : '-' }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Skala Penilaian 0 - 100</p>
        </div>

        <div class="glass-panel p-5 rounded-3xl border border-white/80 shadow-glass">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Akhir CKP</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-amber-800 font-mono">
                    {{ $avgNilaiCkp > 0 ? number_format($avgNilaiCkp, 2, ',', '.') : '-' }}
                </span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Periode {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</p>
        </div>
    </div>

    <!-- Kotak Rumus CKP & Panduan Penilaian Kualitas -->
    <div class="p-5 rounded-3xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/70 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fa-solid fa-calculator text-sm"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-extrabold text-slate-900 mb-1 flex items-center gap-2">
                    <span>Rumus CKP & Panduan Penilaian Kualitas</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold">Pejabat Penilai</span>
                </h4>
                <p class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Sebagai pejabat penilai, tugas Anda adalah mengisi komponen <strong class="text-amber-700">Kualitas (b)</strong>. Nilai akhir CKP dihitung otomatis dari rata-rata kuantitas dan kualitas.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-2xl bg-white/80 border border-blue-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-bps-blue tracking-wider mb-1.5">📐 % Kuantitas (a) — Otomatis</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            a = (Realisasi / Target) × 100
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Sudah dihitung otomatis — tidak diubah penilai</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-amber-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-amber-600 tracking-wider mb-1.5">⭐ % Kualitas (b) — Anda Isi</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            b = Nilai dari Pejabat Penilai
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Diisi oleh Ketua Tim / Kepala BPS (0–100)</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-emerald-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-emerald-700 tracking-wider mb-1.5">🏆 Nilai CKP Akhir</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            CKP = (a + b) / 2
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Rata-rata kuantitas (a) dan kualitas (b)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL KEGIATAN UTAMA -->
    <div class="glass-panel rounded-3xl border border-white/80 shadow-glass overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                I. Kegiatan Utama
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Uraian Kegiatan Utama</th>
                        <th class="py-3 px-3 text-center">Satuan</th>
                        <th class="py-3 px-3 text-right">Target</th>
                        <th class="py-3 px-3 text-right">Realisasi</th>
                        <th class="py-3 px-3 text-right">% Capaian</th>
                        <th class="py-3 px-3 text-right">Kualitas</th>
                        <th class="py-3 px-3 text-right">Kredit</th>
                        <th class="py-3 px-3 text-right">Nilai CKP</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($ckp as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $item->uraian_kegiatan }}</div>
                                @if($item->keterangan)
                                    <div class="text-[11px] text-slate-400 mt-0.5"><i class="fa-regular fa-comment-dots mr-1"></i>{{ $item->keterangan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-semibold">{{ $item->satuan }}</span>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-800">
                                {{ number_format($item->target_kuantitas, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-emerald-700">
                                {{ number_format($item->realisasi_kuantitas, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold">
                                {{ number_format($item->persentase_kuantitas, 1, ',', '.') }}%
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold">
                                {{ $item->kualitas > 0 ? number_format($item->kualitas, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-slate-500">
                                {{ $item->angka_kredit !== null ? number_format($item->angka_kredit, 3, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-amber-700">
                                {{ $item->nilai_ckp > 0 ? number_format($item->nilai_ckp, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->status === 'Sudah Dinilai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                Belum ada data kegiatan utama untuk bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL KEGIATAN TAMBAHAN -->
    <div class="glass-panel rounded-3xl border border-white/80 shadow-glass overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                II. Kegiatan Tambahan
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Uraian Kegiatan Tambahan</th>
                        <th class="py-3 px-3 text-center">Satuan</th>
                        <th class="py-3 px-3 text-right">Target</th>
                        <th class="py-3 px-3 text-right">Realisasi</th>
                        <th class="py-3 px-3 text-right">% Capaian</th>
                        <th class="py-3 px-3 text-right">Kualitas</th>
                        <th class="py-3 px-3 text-right">Kredit</th>
                        <th class="py-3 px-3 text-right">Nilai CKP</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($ckp_tambahan as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $item->uraian_kegiatan }}</div>
                                @if($item->keterangan)
                                    <div class="text-[11px] text-slate-400 mt-0.5"><i class="fa-regular fa-comment-dots mr-1"></i>{{ $item->keterangan }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-semibold">{{ $item->satuan }}</span>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-800">
                                {{ number_format($item->target_kuantitas, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-emerald-700">
                                {{ number_format($item->realisasi_kuantitas, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold">
                                {{ number_format($item->persentase_kuantitas, 1, ',', '.') }}%
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold">
                                {{ $item->kualitas > 0 ? number_format($item->kualitas, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-slate-500">
                                {{ $item->angka_kredit !== null ? number_format($item->angka_kredit, 3, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-amber-700">
                                {{ $item->nilai_ckp > 0 ? number_format($item->nilai_ckp, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($item->status === 'Sudah Dinilai')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                Belum ada data kegiatan tambahan untuk bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
