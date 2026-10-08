@extends('layouts.app')

@section('title', 'CKP Pegawai')
@section('page_title', 'CKP Pegawai')

@section('content')
<div class="space-y-6">

    <!-- Top Card & Filters -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-bps-blue text-xs font-bold uppercase tracking-wider mb-2 border border-blue-200">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Menu Laporan Resmi</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Laporan Capaian Kinerja Pegawai (CKP) Bulanan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Laporan komprehensif penghitungan CKP bulanan sesuai formula resmi BPS dan evaluasi jam kerja aktif.
            </p>
        </div>

        @if($kinerja)
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('ckp.export_excel', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'pegawai_id' => $selectedUserId]) }}" 
               class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('ckp.export_pdf', ['tahun' => $currentYear, 'bulan' => $currentMonth, 'pegawai_id' => $selectedUserId]) }}" target="_blank"
               class="px-4 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Form Sesuai Spesifikasi Bagian Q -->
    <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('ckp.laporan') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
            
            <!-- Pilih Tim -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Filter Tim</label>
                <select name="tim_id" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="">Semua Tim</option>
                    @foreach($daftarTim as $tim)
                        <option value="{{ $tim->id }}" {{ $timId == $tim->id ? 'selected' : '' }}>{{ $tim->nama_tim }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Pegawai -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Pilih Pegawai <span class="text-rose-500">*</span></label>
                <select name="pegawai_id" required class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($daftarPegawai as $peg)
                        <option value="{{ $peg->id }}" {{ $selectedUserId == $peg->id ? 'selected' : '' }}>
                            {{ $peg->name }} ({{ $peg->tim ? $peg->tim->singkatan : 'Non-Tim' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Bulan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Bulan</label>
                <select name="bulan" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Tahun -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Tahun</label>
                <select name="tahun" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none bg-slate-50">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm h-[38px] flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Tampilkan Laporan</span>
                </button>
            </div>

        </form>
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

    @if($kinerja && $selectedPegawai)
        <!-- Identitas Pegawai & Ringkasan CKP -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $selectedPegawai->avatar_url }}" alt="{{ $selectedPegawai->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-bps-blue/20">
                    <div class="space-y-0.5">
                        <h3 class="text-lg font-black text-slate-900">{{ $selectedPegawai->name }}</h3>
                        <p class="text-xs text-slate-500 font-mono">NIP: {{ $selectedPegawai->nip ?? '-' }} &bull; Jabatan: {{ $selectedPegawai->jabatan ?? '-' }} ({{ $selectedPegawai->golongan ?? '-' }})</p>
                        <p class="text-xs text-slate-600 font-semibold">Tim: {{ $selectedPegawai->tim ? $selectedPegawai->tim->nama_tim : 'Lintas Tim' }}</p>
                    </div>
                </div>

                <div class="text-right bg-slate-50 px-5 py-3 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400">Periode Laporan</span>
                    <p class="text-sm font-black text-slate-800">{{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</p>
                    <p class="text-[11px] text-slate-500">Hari Kerja Aktif: <strong>{{ $kinerja['hari_kerja_aktif'] }} Hari</strong> ({{ $kinerja['jam_kerja_aktif'] }} Jam)</p>
                </div>
            </div>

            <!-- Formula Breakdown Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 text-center">
                    <span class="text-[10px] font-extrabold uppercase text-bps-blue">Rata-rata Kuantitas</span>
                    <p class="text-2xl font-black text-bps-blue mt-1">{{ $kinerja['avg_kuantitas'] }}%</p>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 text-center">
                    <span class="text-[10px] font-extrabold uppercase text-indigo-700">Rata-rata Kualitas</span>
                    <p class="text-2xl font-black text-indigo-700 mt-1">{{ $kinerja['avg_kualitas'] }}%</p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200 text-center">
                    <span class="text-[10px] font-extrabold uppercase text-amber-800">Nilai CKP Bulanan</span>
                    <p class="text-2xl font-black text-amber-600 mt-1">{{ $kinerja['nilai_ckp'] }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200 text-center">
                    <span class="text-[10px] font-extrabold uppercase text-emerald-800">Skor Kinerja</span>
                    <p class="text-2xl font-black text-emerald-700 mt-1">{{ $kinerja['skor_kinerja'] }}%</p>
                </div>
            </div>

            <!-- Daftar Pekerjaan Table Sesuai Spesifikasi Bagian Q -->
            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 min-w-[220px]">Uraian Kegiatan</th>
                            <th class="py-3 px-4 text-center">Jenis Pekerjaan</th>
                            <th class="py-3 px-4 text-center">Target</th>
                            <th class="py-3 px-4 text-center">Realisasi</th>
                            <th class="py-3 px-4 text-center">% Kuantitas</th>
                            <th class="py-3 px-4 text-center">% Kualitas</th>
                            <th class="py-3 px-4 text-center">Estimasi Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kinerja['items'] as $idx => $task)
                            <tr class="hover:bg-slate-50/70">
                                <td class="py-3 px-4 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-900">{{ $task->nama_kegiatan }}</p>
                                    <p class="text-[10px] text-slate-400">Satuan: {{ $task->satuan }}</p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $task->jenis_pekerjaan === 'utama' ? 'bg-blue-50 text-bps-blue' : 'bg-purple-50 text-purple-700' }}">
                                        {{ $task->jenis_pekerjaan === 'utama' ? 'Utama' : 'Tambahan' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center font-bold text-slate-800">{{ $task->target_kuantitas }}</td>
                                <td class="py-3 px-4 text-center font-bold text-emerald-600">{{ $task->realisasi_kuantitas }}</td>
                                <td class="py-3 px-4 text-center font-bold">{{ $task->persentase_kuantitas }}%</td>
                                <td class="py-3 px-4 text-center font-bold text-amber-600">
                                    {{ $task->kualitas > 0 ? $task->kualitas . '%' : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-slate-700">{{ $task->estimasi_waktu }} Jam</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">
                                    Belum ada pekerjaan yang tercatat untuk pegawai ini pada periode terpilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @else
        <div class="p-12 text-center bg-white rounded-3xl border border-slate-200/80 shadow-sm text-slate-400">
            <i class="fa-solid fa-file-invoice text-4xl mb-3 text-slate-300 block"></i>
            <p class="font-bold text-sm text-slate-600">Silakan pilih Pegawai, Bulan, dan Tahun untuk menampilkan Laporan CKP</p>
        </div>
    @endif

</div>
@endsection
