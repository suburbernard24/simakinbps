@extends('layouts.app')

@section('title', 'Monitoring CKP Pegawai')
@section('page_title', 'Monitoring CKP Pegawai')

@section('content')
<div class="space-y-8">

    <!-- HEADER & FILTER SECTION -->
    <div class="glass-panel rounded-3xl p-6 sm:p-7 border border-white/80 shadow-glass">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-bold uppercase tracking-wider mb-2 border border-emerald-200">
                    <i class="fa-solid fa-users-viewfinder"></i>
                    Sub Menu: Rekapitulasi CKP Pegawai
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Monitoring Capaian Kinerja Pegawai
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pantau target, realisasi, kualitas, dan status penilaian CKP seluruh pegawai BPS Kabupaten Manggarai.
                </p>
            </div>

            <!-- Filter Period & Search Form -->
            <form method="GET" action="{{ route('ckp.monitoring') }}" class="flex flex-wrap items-center gap-3 bg-white/70 p-3 rounded-2xl border border-slate-200/80 shadow-xs">
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

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-0.5">Cari Pegawai</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama / NIP..."
                               class="bg-white text-xs font-semibold text-slate-800 rounded-xl pl-8 pr-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none w-40 sm:w-48">
                        <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <div class="pt-3.5 flex items-center gap-1.5">
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    @if($search)
                        <a href="{{ route('ckp.monitoring', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="mt-5 pt-4 border-t border-slate-200/60 flex flex-wrap items-center justify-between text-xs text-slate-500">
            <div>
                Periode: <strong class="text-slate-800 font-bold">{{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</strong>
            </div>
            <div>
                Menampilkan <strong class="text-slate-800 font-bold">{{ $rekapPegawai->count() }}</strong> pegawai aktif
            </div>
        </div>
    </div>


    <!-- Kotak Rumus CKP -->
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

    <!-- TABLE REKAPITULASI SELURUH PEGAWAI -->
    <div class="glass-panel rounded-3xl border border-white/80 shadow-glass overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Pegawai & Jabatan</th>
                        <th class="py-3.5 px-3 text-center">Kegiatan</th>
                        <th class="py-3.5 px-3 text-right">Target</th>
                        <th class="py-3.5 px-3 text-right">Realisasi</th>
                        <th class="py-3.5 px-3 text-right">% Kuantitas</th>
                        <th class="py-3.5 px-3 text-right">Kualitas</th>
                        <th class="py-3.5 px-3 text-right">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Status Penilaian</th>
                        <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($rekapPegawai as $idx => $row)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <td class="py-3.5 px-4 text-center text-slate-400 font-bold">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $row->user->avatar_url }}" alt="{{ $row->user->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-emerald-500/20">
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs hover:text-emerald-700 transition">
                                            {{ $row->user->name }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                            NIP. {{ $row->user->nip ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-emerald-700/80 font-semibold">
                                            {{ $row->user->jabatan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 text-[11px] font-bold border border-emerald-200/60" title="{{ $row->jumlah_utama }} Utama, {{ $row->jumlah_tambahan }} Tambahan">
                                    {{ $row->total_kegiatan }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $row->jumlah_utama }}U + {{ $row->jumlah_tambahan }}T</div>
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-bold text-slate-700">
                                {{ number_format($row->total_target, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-bold text-emerald-700">
                                {{ number_format($row->total_realisasi, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono">
                                <span class="font-bold {{ $row->persentase >= 100 ? 'text-emerald-600' : ($row->persentase >= 80 ? 'text-amber-600' : 'text-rose-600') }}">
                                    {{ number_format($row->persentase, 1, ',', '.') }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-bold">
                                {{ $row->kualitas > 0 ? number_format($row->kualitas, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-extrabold text-amber-700">
                                {{ $row->nilai_ckp > 0 ? number_format($row->nilai_ckp, 1, ',', '.') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($row->total_kegiatan == 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                        Belum Ada Data
                                    </span>
                                @elseif($row->belum_dinilai > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        <i class="fa-regular fa-clock text-[9px]"></i> {{ $row->belum_dinilai }} Belum Dinilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i class="fa-solid fa-check-double text-[9px]"></i> Lengkap Dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('ckp.user_detail', ['id_user' => $row->user->id, 'tahun' => $currentYear, 'bulan' => $currentMonth]) }}" 
                                   class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-300 transition text-xs font-bold inline-flex items-center gap-1.5 shadow-xs">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-300"></i>
                                <p class="font-bold">Tidak ada data pegawai yang sesuai dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
