@extends('layouts.app')

@section('title', 'Alokasi Tim Saya')
@section('page_title', 'Alokasi Tim Saya')

@section('content')
<div class="space-y-6">

    @php
        $total_alokasi = $alokasi->count();
        $total_pegawai = $alokasi->pluck('pegawai_id')->unique()->count();
        $berjalan = $alokasi->where('status', 'berjalan')->count();
        $selesai = $alokasi->where('status', 'selesai')->count();
        $total_jam = $alokasi->sum('estimasi_waktu');
    @endphp

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Total Alokasi</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $total_alokasi }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Pekerjaan</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Total Pegawai</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $total_pegawai }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Orang Terlibat</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Sedang Berjalan</span>
            <p class="text-2xl font-black text-amber-500 mt-1">{{ $berjalan }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Dalam Proses</p>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/80">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Selesai Dikerjakan</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $selesai }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Tuntas</p>
        </div>
    </div>

    <!-- Kotak Penjelasan Formula Skor Kinerja -->
    <div class="p-5 rounded-3xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/70 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-bps-blue text-white flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fa-solid fa-calculator text-sm"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-extrabold text-slate-900 mb-1 flex items-center gap-2">
                    <span>Rumus Skor Kinerja Per Pekerjaan</span>
                    <span class="px-2 py-0.5 rounded-full bg-bps-blue text-white text-[10px] font-bold">Formula</span>
                </h4>
                <p class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Skor kinerja setiap pekerjaan dihitung berdasarkan <strong>persentase capaian kuantitas</strong> (realisasi vs target) yang ditimbang dengan <strong>porsi estimasi waktu</strong> pekerjaan tersebut terhadap total jam kerja aktif bulan berjalan.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-white/80 border border-blue-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-bps-blue tracking-wider mb-1.5">📐 Rumus Skor Per Pekerjaan</p>
                        <code class="block text-xs font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            Skor = (Realisasi / Target) × 100%
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Dibatasi maksimal 100% (tidak melebihi target)</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-blue-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-bps-blue tracking-wider mb-1.5">📊 Contoh Perhitungan</p>
                        <div class="text-xs text-slate-700 space-y-1">
                            <p>Target: <strong>10 dokumen</strong>, Realisasi: <strong>8 dokumen</strong></p>
                            <p class="font-mono text-bps-blue font-bold">→ Skor = (8/10) × 100% = 80%</p>
                            <p class="text-[10px] text-slate-400 italic">Jika realisasi ≥ target, skor = 100%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Sesuai Spesifikasi Bagian I) -->
    <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('alokasi.tim') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            
            <!-- Bulan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="">Semua Bulan</option>
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ request('bulan', $currentMonth) == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ request('tahun', $currentYear) == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Asal Tim -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Asal Tim</label>
                <select name="asal_tim" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="" {{ empty($selectedAsalTim) ? 'selected' : '' }}>Semua Tim</option>
                    @foreach($daftarTim as $tim)
                        <option value="{{ $tim->nama_tim }}" {{ $selectedAsalTim == $tim->nama_tim ? 'selected' : '' }}>{{ $tim->nama_tim }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pegawai -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Pegawai</label>
                <select name="pegawai_id" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="">Semua Pegawai</option>
                    @foreach($daftarPegawai as $peg)
                        <option value="{{ $peg->id }}" {{ request('pegawai_id') == $peg->id ? 'selected' : '' }}>{{ $peg->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jenis Pekerjaan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Jenis Pekerjaan</label>
                <select name="jenis_pekerjaan" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="">Semua Jenis</option>
                    <option value="utama" {{ request('jenis_pekerjaan') == 'utama' ? 'selected' : '' }}>Pekerjaan Utama</option>
                    <option value="tambahan" {{ request('jenis_pekerjaan') == 'tambahan' ? 'selected' : '' }}>Pekerjaan Tambahan</option>
                </select>
            </div>

            <!-- Tombol Submit Filter -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm h-[38px] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>

        </form>
    </div>

    <!-- Table Card Sesuai Spesifikasi Bagian I -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Daftar Alokasi Pekerjaan Tim</h3>
                <p class="text-xs text-slate-500">Data bersifat <em class="italic">Read-Only</em> bagi role yang bukan pembuat alokasi</p>
            </div>
            <span class="text-xs font-bold text-slate-500">Total: {{ $alokasi->count() }} Kegiatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 min-w-[200px]">Nama Kegiatan</th>
                        <th class="py-3.5 px-4">Jenis</th>
                        <th class="py-3.5 px-4">Pegawai Ditugaskan</th>
                        <th class="py-3.5 px-4">Asal Tim</th>
                        <th class="py-3.5 px-4 text-center">Target & Satuan</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Estimasi Waktu</th>
                        <th class="py-3.5 px-4">Tanggal Mulai - Selesai</th>
                        <th class="py-3.5 px-4 text-center">Skor Kinerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alokasi as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $item->nama_kegiatan }}</p>
                                @if($item->keterangan)
                                    <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1 italic"><i class="fa-regular fa-comment mr-1"></i>{{ $item->keterangan }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $item->jenis_pekerjaan === 'utama' ? 'bg-blue-50 text-bps-blue border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ $item->jenis_pekerjaan === 'utama' ? 'Utama' : 'Tambahan' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $item->pegawai ? $item->pegawai->avatar_url : '' }}" class="w-6 h-6 rounded-full object-cover">
                                    <span class="font-semibold text-slate-800">{{ $item->pegawai ? $item->pegawai->name : '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600">
                                {{ $item->asal_tim ?? ($item->tim ? $item->tim->nama_tim : '-') }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">
                                {{ $item->target_kuantitas }} <span class="text-[10px] text-slate-400">{{ $item->satuan }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">
                                {{ $item->realisasi_kuantitas }} <span class="text-[10px] text-slate-400 font-normal">({{ $item->persentase_kuantitas }}%)</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                {{ $item->estimasi_waktu }} Jam
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M') : '-' }} s/d {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold text-bps-blue bg-blue-50 border border-blue-100">
                                    {{ $item->skor_kinerja }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                Tidak ada data alokasi pekerjaan untuk filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
