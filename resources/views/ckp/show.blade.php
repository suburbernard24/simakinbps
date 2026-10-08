@extends('layouts.app')

@section('title', 'Detail CKP Pegawai')
@section('page_title', 'Detail Capaian Kinerja Pegawai')

@section('content')
<div class="space-y-8">
    
    <!-- Top Action Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ckp.index', ['tahun' => $tahun, 'bulan' => $bulan]) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:text-emerald-700 transition shadow-xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Ringkasan CKP</span>
        </a>

        <div class="text-xs font-semibold text-slate-500">
            Periode Penilaian: <strong class="text-emerald-800">{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</strong>
        </div>
    </div>

    <!-- Pegawai Header Profile Card -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-white/80 shadow-glass">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover ring-4 ring-emerald-500/20 shadow-md" />
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-amber-100 text-amber-900 border border-amber-300">
                        {{ $user->role }} BPS Manggarai
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500 font-mono">NIP. {{ $user->nip ?? '-' }} &bull; Golongan: {{ $user->golongan ?? '-' }}</p>
                    <p class="text-xs font-semibold text-emerald-800">{{ $user->jabatan ?? 'Statistisi Ahli' }}</p>
                </div>
            </div>

            <!-- Capaian Score Badges -->
            <div class="grid grid-cols-3 gap-3 bg-white/60 p-4 rounded-2xl border border-slate-200/60 shadow-inner">
                <div class="text-center px-3 border-r border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">% Kuantitas</span>
                    <span class="text-base font-extrabold text-emerald-700 font-mono">{{ number_format($avgKuantitas, 2, ',', '.') }}%</span>
                </div>
                <div class="text-center px-3 border-r border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Kualitas</span>
                    <span class="text-base font-extrabold text-blue-700 font-mono">{{ number_format($avgKualitas, 2, ',', '.') }}</span>
                </div>
                <div class="text-center px-3">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Nilai Akhir CKP</span>
                    <span class="text-base font-extrabold text-amber-700 font-mono">{{ number_format($avgNilaiCkp, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian CKP -->
    <div class="glass-panel rounded-3xl p-6 sm:p-7 border border-white/80 shadow-glass space-y-4">
        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-clipboard-check text-emerald-600"></i>
            <span>Rincian Target & Realisasi Capaian Kinerja (CKP)</span>
        </h3>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-emerald-900 to-emerald-800 text-white font-extrabold uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center rounded-tl-2xl">No</th>
                        <th class="py-3.5 px-6">Uraian Kegiatan</th>
                        <th class="py-3.5 px-4 text-center">Satuan</th>
                        <th class="py-3.5 px-4 text-center">Target</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">% Kuantitas</th>
                        <th class="py-3.5 px-4 text-center">Kualitas</th>
                        <th class="py-3.5 px-6 text-right rounded-tr-2xl">Nilai CKP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white/70">
                    @forelse($items as $idx => $ckp)
                        <tr class="hover:bg-emerald-50/50 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $ckp->uraian_kegiatan }}</td>
                            <td class="py-3.5 px-4 text-center text-slate-500">{{ $ckp->satuan }}</td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-700">{{ number_format($ckp->target_kuantitas, 0) }}</td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-700">{{ number_format($ckp->realisasi_kuantitas, 0) }}</td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                <span class="{{ $ckp->persentase_kuantitas >= 100 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ number_format($ckp->persentase_kuantitas, 2, ',', '.') }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-blue-700">
                                {{ number_format($ckp->kualitas, 2, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-right font-mono font-extrabold text-amber-700 text-sm">
                                {{ number_format($ckp->nilai_ckp, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data rincian CKP pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($items->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-100/90 font-extrabold text-slate-900 border-t-2 border-emerald-600">
                            <td colspan="7" class="py-3 px-6 text-right uppercase tracking-wider text-xs">Rata-rata Nilai CKP:</td>
                            <td class="py-3 px-6 text-right font-mono text-base text-amber-800">
                                {{ number_format($avgNilaiCkp, 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection
