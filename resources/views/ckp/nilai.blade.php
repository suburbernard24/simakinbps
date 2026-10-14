@extends('layouts.app')

@section('title', 'Penilaian CKP Pegawai')
@section('page_title', 'Penilaian Pekerjaan Pegawai')

@section('content')
<div class="space-y-8" x-data="{ 
    modalNilai: false,
    nilaiData: { id: '', nama: '', pegawai: '', target: 0, realisasi: 0, persentase: 0, kualitas: 90, catatan: '', jenis: '' },
    get previewCkp() {
        let a = parseFloat(this.nilaiData.persentase) || 0;
        let b = parseFloat(this.nilaiData.kualitas) || 0;
        return ((a + b) / 2).toFixed(2);
    }
}">



    <!-- HEADER & ROLE NOTICE -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2 border border-amber-200">
                <i class="fa-solid fa-clipboard-check text-amber-600"></i>
                <span>Lembar Penilaian Pejabat Penilai</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Penilaian Kualitas & Capaian CKP
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl leading-relaxed">
                @if(Auth::user()->isKepala())
                    Anda login sebagai <strong class="text-bps-blue">Kepala BPS</strong>. Berwenang menilai seluruh pekerjaan Ketua Tim dan seluruh pekerjaan tambahan pegawai.
                @else
                    Anda login sebagai <strong class="text-bps-blue">Ketua Tim</strong>. Berwenang menilai pekerjaan utama pegawai dalam tim kerja Anda.
                @endif
            </p>
        </div>

        <!-- Filter Period & Pegawai Form -->
        <form method="GET" action="{{ route('ckp.nilai') }}" class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-2xl border border-slate-200">
            <div>
                <select name="bulan" onchange="this.form.submit()" class="bg-white text-xs font-bold text-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="tahun" onchange="this.form.submit()" class="bg-white text-xs font-bold text-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="user_id" onchange="this.form.submit()" class="bg-white text-xs font-bold text-slate-800 rounded-xl px-3 py-1.5 border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none max-w-[180px]">
                    <option value="">Semua Pegawai</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $selectedUserId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-3 py-1.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm">
                Muat
            </button>
        </form>
    </div>

    <!-- Kotak Rumus CKP untuk Pejabat Penilai -->
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

    <!-- 1. TABEL PEKERJAAN UTAMA YANG PERLU DINILAI -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-blue-50/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-bps-blue text-white flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pekerjaan Utama</h3>
                    <p class="text-[11px] text-slate-500">Pekerjaan tugas pokok tim yang memerlukan penilaian kualitas (Persentase Kualitas b)</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-xl bg-blue-100 text-bps-blue font-extrabold text-xs">
                {{ $ckp->count() }} Kegiatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Pegawai</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Uraian Kegiatan</th>
                        <th class="py-3.5 px-4 text-center">Target</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Kuantitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai Kualitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ckp as $idx => $task)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $task->pegawai->avatar_url }}" class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $task->pegawai->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $task->pegawai->tim ? $task->pegawai->tim->singkatan : 'Non-Tim' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $task->nama_kegiatan }}</p>
                                @if($task->catatan_realisasi)
                                    <p class="text-[10px] text-slate-500 mt-0.5 italic"><i class="fa-regular fa-comment mr-1"></i>{{ $task->catatan_realisasi }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $task->target_kuantitas }} {{ $task->satuan }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $task->realisasi_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold">{{ $task->persentase_kuantitas }}%</td>
                            <td class="py-3.5 px-4 text-center">
                                @if($task->kualitas > 0)
                                    <span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 font-extrabold border border-amber-200">
                                        {{ $task->kualitas }}%
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-900">
                                {{ $task->kualitas > 0 ? $task->nilai_ckp : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" @click="nilaiData = {
                                    id: '{{ $task->id }}',
                                    nama: '{{ addslashes($task->nama_kegiatan) }}',
                                    pegawai: '{{ addslashes($task->pegawai->name) }}',
                                    target: '{{ $task->target_kuantitas }} {{ $task->satuan }}',
                                    realisasi: '{{ $task->realisasi_kuantitas }} {{ $task->satuan }}',
                                    persentase: '{{ $task->persentase_kuantitas }}',
                                    kualitas: '{{ $task->kualitas > 0 ? $task->kualitas : 90 }}',
                                    catatan: '{{ addslashes($task->catatan_penilai ?? '') }}',
                                    jenis: 'Pekerjaan Utama'
                                }; modalNilai = true"
                                class="px-3 py-1.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white font-bold text-xs transition shadow-sm inline-flex items-center gap-1">
                                    <i class="fa-solid fa-stamp text-[10px]"></i>
                                    <span>{{ $task->kualitas > 0 ? 'Ubah Nilai' : 'Beri Nilai' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                Tidak ada pekerjaan utama yang memerlukan penilaian Anda pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. TABEL PEKERJAAN TAMBAHAN (Dinilai oleh Kepala BPS) -->
    @if(Auth::user()->isKepala())
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-purple-50/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pekerjaan Tambahan (Kewenangan Penilaian Kepala BPS)</h3>
                    <p class="text-[11px] text-slate-500">Pekerjaan tambahan seluruh pegawai yang dinilai langsung oleh Kepala BPS</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-xl bg-purple-100 text-purple-700 font-extrabold text-xs">
                {{ $ckp_tambahan->count() }} Kegiatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Pegawai</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Uraian Kegiatan Tambahan</th>
                        <th class="py-3.5 px-4 text-center">Target</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Kuantitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai Kualitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ckp_tambahan as $idx => $tmb)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $tmb->pegawai->avatar_url }}" class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $tmb->pegawai->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $tmb->asal_tim }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $tmb->nama_kegiatan }}</p>
                                @if($tmb->catatan_realisasi)
                                    <p class="text-[10px] text-slate-500 mt-0.5 italic"><i class="fa-regular fa-comment mr-1"></i>{{ $tmb->catatan_realisasi }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $tmb->target_kuantitas }} {{ $tmb->satuan }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $tmb->realisasi_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold">{{ $tmb->persentase_kuantitas }}%</td>
                            <td class="py-3.5 px-4 text-center">
                                @if($tmb->kualitas > 0)
                                    <span class="px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 font-extrabold border border-amber-200">
                                        {{ $tmb->kualitas }}%
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-900">
                                {{ $tmb->kualitas > 0 ? $tmb->nilai_ckp : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" @click="nilaiData = {
                                    id: '{{ $tmb->id }}',
                                    nama: '{{ addslashes($tmb->nama_kegiatan) }}',
                                    pegawai: '{{ addslashes($tmb->pegawai->name) }}',
                                    target: '{{ $tmb->target_kuantitas }} {{ $tmb->satuan }}',
                                    realisasi: '{{ $tmb->realisasi_kuantitas }} {{ $tmb->satuan }}',
                                    persentase: '{{ $tmb->persentase_kuantitas }}',
                                    kualitas: '{{ $tmb->kualitas > 0 ? $tmb->kualitas : 90 }}',
                                    catatan: '{{ addslashes($tmb->catatan_penilai ?? '') }}',
                                    jenis: 'Pekerjaan Tambahan'
                                }; modalNilai = true"
                                class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs transition shadow-sm inline-flex items-center gap-1">
                                    <i class="fa-solid fa-stamp text-[10px]"></i>
                                    <span>{{ $tmb->kualitas > 0 ? 'Ubah Nilai' : 'Beri Nilai' }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                Tidak ada pekerjaan tambahan yang perlu dinilai pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- ==================== MODAL: INPUT PENILAIAN KUALITAS ==================== -->
    <div x-show="modalNilai" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalNilai = false" class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5 max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Penilaian Kualitas Pekerjaan</h3>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="nilaiData.pegawai + ' • ' + nilaiData.jenis"></p>
                </div>
                <button @click="modalNilai = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" :action="'/ckp/penilaian/' + nilaiData.id + '/simpan'" class="space-y-4">
                @csrf

                <!-- Info Kegiatan -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                    <p class="text-slate-400 font-bold uppercase text-[10px]">Kegiatan yang Dinilai:</p>
                    <p class="font-bold text-slate-900 text-sm leading-snug" x-text="nilaiData.nama"></p>
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200/60">
                        <div class="text-center p-2 rounded-xl bg-white border border-slate-200">
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Target</p>
                            <p class="font-extrabold text-slate-800 text-sm" x-text="nilaiData.target"></p>
                        </div>
                        <div class="text-center p-2 rounded-xl bg-white border border-slate-200">
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Realisasi</p>
                            <p class="font-extrabold text-emerald-600 text-sm" x-text="nilaiData.realisasi"></p>
                        </div>
                        <div class="text-center p-2 rounded-xl bg-blue-50 border border-blue-200">
                            <p class="text-[10px] text-bps-blue uppercase font-bold">% Kuantitas (a)</p>
                            <p class="font-extrabold text-bps-blue text-sm" x-text="nilaiData.persentase + '%'"></p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 italic">
                        <i class="fa-solid fa-circle-info text-bps-blue mr-1"></i>
                        Komponen kuantitas (a) dihitung otomatis: <span class="font-mono">a = (Realisasi / Target) × 100%</span>
                    </p>
                </div>

                <!-- Input Kualitas -->
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-extrabold text-amber-800 uppercase tracking-wider">
                                <i class="fa-solid fa-star text-amber-500 mr-1"></i>
                                Persentase Kualitas (b) <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[10px] text-amber-700 mt-0.5">Geser slider atau ketik nilai 0–100 untuk menentukan kualitas pekerjaan</p>
                        </div>
                        <div class="text-center">
                            <span class="text-2xl font-black text-amber-600" x-text="nilaiData.kualitas + '%'"></span>
                        </div>
                    </div>
                    <input type="range" min="0" max="100" step="1" name="kualitas" x-model="nilaiData.kualitas" class="w-full h-2.5 bg-amber-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
                    <p class="text-[10px] text-amber-700 mt-1 italic">Masukkan nilai 0–100 berdasarkan penilaian kualitas hasil pekerjaan pegawai.</p>
                </div>

                <!-- Live Preview Perhitungan CKP Sesuai Formula Bagian N -->
                <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 text-xs space-y-2">
                    <p class="font-extrabold flex items-center gap-1.5 text-emerald-800">
                        <i class="fa-solid fa-calculator text-emerald-600"></i>
                        Simulasi Nilai CKP Akhir
                    </p>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2 rounded-xl bg-white/80 border border-blue-100">
                            <p class="text-[10px] text-slate-500 uppercase font-bold">Kuantitas (a)</p>
                            <p class="font-extrabold text-bps-blue text-base" x-text="nilaiData.persentase + '%'"></p>
                        </div>
                        <div class="p-2 rounded-xl bg-white/80 border border-amber-100">
                            <p class="text-[10px] text-slate-500 uppercase font-bold">Kualitas (b)</p>
                            <p class="font-extrabold text-amber-600 text-base" x-text="nilaiData.kualitas + '%'"></p>
                        </div>
                        <div class="p-2 rounded-xl bg-emerald-100 border border-emerald-200">
                            <p class="text-[10px] text-emerald-700 uppercase font-bold">CKP = (a+b)/2</p>
                            <p class="font-black text-emerald-800 text-base" x-text="previewCkp"></p>
                        </div>
                    </div>
                    <p class="text-[10px] text-emerald-700 italic">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Nilai CKP = (<span x-text="nilaiData.persentase"></span>% + <span x-text="nilaiData.kualitas"></span>%) ÷ 2 = <strong x-text="previewCkp"></strong>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Evaluasi Penilai</label>
                    <textarea name="catatan_penilai" rows="2" x-model="nilaiData.catatan" placeholder="Apresiasi, catatan perbaikan atau rekomendasi kualitas..." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalNilai = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-stamp text-[10px]"></i>
                        Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
