@extends('layouts.app')

@section('title', 'CKP Saya - Capaian Kinerja Pegawai')
@section('page_title', 'CKP Saya')

@section('content')
<div class="space-y-8" x-data="{ 
    modalTambah: false, 
    modalEditRealisasi: false,
    editData: { id: '', nama: '', target: 0, realisasi: 0, satuan: '', keterangan: '', link: '' }
}">



    <!-- HEADER & PERIOD FILTER -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2 border border-emerald-200">
                <i class="fa-solid fa-award text-emerald-600"></i>
                <span>Sub Menu: CKP Saya</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Capaian Kinerja Pegawai (CKP)
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Kelola realisasi capaian bulanan. 
                <strong class="text-bps-blue">Pekerjaan Utama</strong> dialokasikan oleh Ketua Tim, sedangkan 
                <strong class="text-purple-700">Pekerjaan Tambahan</strong> diinput mandiri melalui menu ini.
            </p>
        </div>

        <!-- Filter Period & Action Buttons -->
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('ckp.index') }}" class="flex items-center gap-2 bg-slate-50 p-2 rounded-2xl border border-slate-200">
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

                <button type="submit" class="px-3 py-1.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-sm">
                    Muat
                </button>
            </form>

            <button type="button" @click="modalTambah = true" class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Tambah Pekerjaan Tambahan</span>
            </button>
        </div>
    </div>

    <!-- SUMMARY METRICS CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Total Target</span>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalTarget }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Total Realisasi</span>
            <p class="text-2xl font-black text-emerald-600 mt-2">{{ $totalRealisasi }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">% Kuantitas</span>
            <p class="text-2xl font-black text-bps-blue mt-2">{{ $avgPersentaseKuantitas }}%</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Rata-rata Kualitas</span>
            <p class="text-2xl font-black text-indigo-600 mt-2">{{ $avgKualitas }}%</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Nilai Rata-rata CKP</span>
            <p class="text-2xl font-black text-amber-500 mt-2">{{ $avgNilaiCkp }}</p>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-extrabold uppercase text-slate-400">Skor Kinerja</span>
            <p class="text-2xl font-black text-emerald-700 mt-2">{{ $skorKinerja }}%</p>
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
                    <span>Panduan Rumus CKP (Capaian Kinerja Pegawai)</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold">Rumus</span>
                </h4>
                <p class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Nilai CKP Anda dihitung dari dua komponen: <strong class="text-bps-blue">% Kuantitas (a)</strong> yang merupakan capaian realisasi terhadap target, dan <strong class="text-amber-700">% Kualitas (b)</strong> yang diberikan oleh Pejabat Penilai.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-3 rounded-2xl bg-white/80 border border-blue-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-bps-blue tracking-wider mb-1.5">📐 % Kuantitas (a)</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            a = (Realisasi / Target) × 100
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Dihitung otomatis dari input realisasi Anda</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-amber-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-amber-600 tracking-wider mb-1.5">⭐ % Kualitas (b)</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            b = Nilai dari Pejabat Penilai
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Diisi oleh Ketua Tim / Kepala BPS</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/80 border border-emerald-100 shadow-xs">
                        <p class="text-[10px] font-extrabold uppercase text-emerald-700 tracking-wider mb-1.5">🏆 Nilai CKP Akhir</p>
                        <code class="block text-[11px] font-mono text-slate-800 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200">
                            CKP = (a + b) / 2
                        </code>
                        <p class="text-[10px] text-slate-500 mt-1.5 italic">Rata-rata dari kuantitas dan kualitas</p>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded-2xl bg-amber-100/60 border border-amber-200 text-xs text-amber-900">
                    <strong>Contoh:</strong> Jika realisasi 8 dari target 10 (a = 80%), dan pejabat penilai memberi kualitas 90% (b = 90%), maka
                    <span class="font-mono font-bold"> CKP = (80 + 90) / 2 = <span class="text-emerald-700">85</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- CETAK DOKUMEN BPS ACTIONS -->
    <div class="p-4 rounded-2xl bg-bps-faint border border-bps-pale flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
        <div class="flex items-center gap-2.5 text-bps-dark">
            <i class="fa-solid fa-file-contract text-base text-bps-blue"></i>
            <span>Dokumen Cetak Format Resmi BPS Manggarai:</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('ckp.t_cetak', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-white text-slate-700 font-bold border border-slate-200 hover:bg-slate-50 transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-print text-slate-400"></i> Cetak CKP-T (Target)
            </a>
            <a href="{{ route('ckp.cetak', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white font-bold transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> Cetak CKP-R (Realisasi)
            </a>
            <a href="{{ route('ckp.laporan', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-file-export"></i> Laporan & Excel/PDF
            </a>
        </div>
    </div>

    <!-- 1. TABEL PEKERJAAN UTAMA -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-blue-50/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-bps-blue text-white flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">1. Pekerjaan Utama (Dialokasikan Ketua Tim)</h3>
                    <p class="text-[11px] text-slate-500">Pekerjaan yang ditugaskan oleh Ketua Tim dan dinilai oleh Pejabat Penilai</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-xl bg-blue-100/70 text-bps-blue font-extrabold text-xs">
                {{ $ckp->count() }} Kegiatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[220px]">Uraian Kegiatan</th>
                        <th class="py-3.5 px-4">Satuan</th>
                        <th class="py-3.5 px-4 text-center">Target</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Kuantitas</th>
                        <th class="py-3.5 px-4 text-center">Kualitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Estimasi Jam</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ckp as $idx => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $item->nama_kegiatan }}</p>
                                <p class="text-[10px] text-slate-400">Tim: {{ $item->asal_tim }}</p>
                                @if($item->catatan_realisasi)
                                    <p class="text-[10px] text-slate-500 mt-0.5 italic"><i class="fa-regular fa-comment mr-1 text-slate-400"></i>{{ $item->catatan_realisasi }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium">{{ $item->satuan }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $item->target_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $item->realisasi_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold">{{ $item->persentase_kuantitas }}%</td>
                            <td class="py-3.5 px-4 text-center font-bold text-amber-600">
                                {{ $item->kualitas > 0 ? $item->kualitas . '%' : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-800">
                                {{ $item->kualitas > 0 ? $item->nilai_ckp : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ $item->estimasi_waktu }} Jam</td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" @click="editData = {
                                    id: '{{ $item->id }}',
                                    nama: '{{ addslashes($item->nama_kegiatan) }}',
                                    target: '{{ $item->target_kuantitas }}',
                                    realisasi: '{{ $item->realisasi_kuantitas }}',
                                    satuan: '{{ $item->satuan }}',
                                    keterangan: '{{ addslashes($item->catatan_realisasi ?? '') }}',
                                    link: '{{ addslashes($item->link_bukti ?? '') }}'
                                }; modalEditRealisasi = true" 
                                class="px-3 py-1.5 rounded-xl bg-bps-faint text-bps-blue font-bold text-xs hover:bg-bps-blue hover:text-white transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Isi Realisasi
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                Belum ada pekerjaan utama yang dialokasikan pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. TABEL PEKERJAAN TAMBAHAN -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-purple-50/30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">2. Pekerjaan Tambahan (Diinput Mandiri)</h3>
                    <p class="text-[11px] text-slate-500">Pekerjaan di luar tugas pokok tim yang Anda input secara mandiri (dinilai oleh Kepala BPS)</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[220px]">Uraian Kegiatan Tambahan</th>
                        <th class="py-3.5 px-4">Satuan</th>
                        <th class="py-3.5 px-4 text-center">Target</th>
                        <th class="py-3.5 px-4 text-center">Realisasi</th>
                        <th class="py-3.5 px-4 text-center">Kuantitas</th>
                        <th class="py-3.5 px-4 text-center">Kualitas</th>
                        <th class="py-3.5 px-4 text-center">Nilai CKP</th>
                        <th class="py-3.5 px-4 text-center">Estimasi Jam</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ckp_tambahan as $idx => $tmb)
                        <tr class="hover:bg-purple-50/40 transition">
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-purple-100 text-purple-600 text-[10px] font-bold" title="Pekerjaan Tambahan">
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $tmb->nama_kegiatan }}</p>
                                @if($tmb->keterangan)
                                    <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1 italic">{{ $tmb->keterangan }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium">{{ $tmb->satuan }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $tmb->target_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $tmb->realisasi_kuantitas }}</td>
                            <td class="py-3.5 px-4 text-center font-bold">{{ $tmb->persentase_kuantitas }}%</td>
                            <td class="py-3.5 px-4 text-center font-bold text-amber-600">
                                {{ $tmb->kualitas > 0 ? $tmb->kualitas . '%' : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-slate-800">
                                {{ $tmb->kualitas > 0 ? $tmb->nilai_ckp : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ $tmb->estimasi_waktu }} Jam</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" @click="editData = {
                                        id: '{{ $tmb->id }}',
                                        nama: '{{ addslashes($tmb->nama_kegiatan) }}',
                                        target: '{{ $tmb->target_kuantitas }}',
                                        realisasi: '{{ $tmb->realisasi_kuantitas }}',
                                        satuan: '{{ $tmb->satuan }}',
                                        keterangan: '{{ addslashes($tmb->catatan_realisasi ?? $tmb->keterangan ?? '') }}',
                                        link: '{{ addslashes($tmb->link_bukti ?? '') }}'
                                    }; modalEditRealisasi = true" 
                                    class="p-1.5 rounded-lg text-bps-blue hover:bg-bps-faint transition" title="Isi Realisasi">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('ckp.hapus_tambahan', $tmb->id) }}" onsubmit="return confirm('Hapus pekerjaan tambahan ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus">
                                             <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                Belum ada pekerjaan tambahan yang diinput pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODAL: ISI REALISASI ==================== -->
    <div x-show="modalEditRealisasi" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalEditRealisasi = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Lengkapi Realisasi Pekerjaan</h3>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="editData.nama"></p>
                </div>
                <button @click="modalEditRealisasi = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" :action="'/ckp/edit-realisasi/' + editData.id" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Target Kuantitas</span>
                        <p class="text-base font-black text-slate-800" x-text="editData.target + ' ' + editData.satuan"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Realisasi Kuantitas <span class="text-rose-500">*</span></label>
                        <input type="number" step="any" min="0" name="realisasi_kuantitas" x-model="editData.realisasi" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan / Keterangan Realisasi</label>
                    <textarea name="catatan_realisasi" rows="2" x-model="editData.keterangan" placeholder="Keterangan progres, kendala atau rincian hasil..." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Link Bukti Dukung (Opsional)</label>
                    <input type="url" name="link_bukti" x-model="editData.link" placeholder="https://drive.google.com/..." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalEditRealisasi = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm">Simpan Realisasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL: TAMBAH PEKERJAAN TAMBAHAN ==================== -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalTambah = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tambah Pekerjaan Tambahan Mandiri</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Periode {{ $daftarBulan[$currentMonth] }} {{ $currentYear }}</p>
                </div>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" action="{{ route('ckp.aksi_tambahan') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tahun" value="{{ $currentYear }}">
                <input type="hidden" name="bulan" value="{{ $currentMonth }}">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Uraian Pekerjaan Tambahan <span class="text-rose-500">*</span></label>
                    <input type="text" name="uraian_kegiatan" required placeholder="Contoh: Panitia Survei Biaya Hidup (SBH)" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Satuan <span class="text-rose-500">*</span></label>
                        <input type="text" name="satuan" value="Kegiatan" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Target <span class="text-rose-500">*</span></label>
                        <input type="number" step="any" min="0.01" name="target" value="1" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Realisasi <span class="text-rose-500">*</span></label>
                        <input type="number" step="any" min="0" name="realisasi" value="1" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Estimasi Waktu Pengerjaan (JAM) <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" min="0.5" name="estimasi_waktu" value="10" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Estimasi waktu kerja dalam satuan jam untuk perhitungan skor kinerja</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan mengenai surat tugas / kepanitiaan..." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-600 outline-none"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-sm">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
