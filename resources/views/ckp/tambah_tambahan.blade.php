@extends('layouts.app')

@section('title', 'Tambah Kegiatan Tambahan - CKP')
@section('page_title', 'Tambah Kegiatan Tambahan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Breadcrumb & Info -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ckp.index', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-emerald-700 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke CKP Saya</span>
        </a>
        <div class="text-xs text-slate-400 font-medium">
            Form Penambahan Kegiatan Tambahan
        </div>
    </div>

    <!-- Form Card -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-white/80 shadow-glass">
        <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 rounded-2xl bg-amber-500/15 text-amber-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-plus-circle text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Tambah Kegiatan Tambahan</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Tambahkan rincian pekerjaan atau tugas kepanitiaan di luar kegiatan utama bulanan.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="font-bold flex items-center gap-1.5 mb-1">
                    <i class="fa-solid fa-circle-exclamation"></i> Terdapat kesalahan pengisian formulir:
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ckp.aksi_tambahan') }}" method="POST" class="mt-6 space-y-5">
            @csrf

            <!-- Periode Tahun & Bulan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tahun Periode <span class="text-rose-500">*</span>
                    </label>
                    <select name="tahun" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        @foreach($daftarTahun as $th)
                            <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bulan Periode <span class="text-rose-500">*</span>
                    </label>
                    <select name="bulan" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        @foreach($daftarBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Uraian Kegiatan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Uraian Kegiatan Tambahan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="uraian_kegiatan" value="{{ old('uraian_kegiatan') }}" required 
                       placeholder="Contoh: Panitia Hari Statistik Nasional (HSN) 2026 / Verifikasi SP2020 lanjutan"
                       class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                <span class="text-[11px] text-slate-400 mt-1 block">Tuliskan deskripsi ringkas dan jelas mengenai tugas tambahan Anda.</span>
            </div>

            <!-- Satuan, Target, Realisasi -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Satuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'Kegiatan') }}" required placeholder="Kegiatan / Laporan / Dokumen"
                           class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Kuantitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="any" min="0" name="target" value="{{ old('target', 1) }}" required
                           class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Realisasi Kuantitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="any" min="0" name="realisasi" value="{{ old('realisasi', 1) }}" required
                           class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <!-- Angka Kredit (Opsional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Angka Kredit (Opsional bagi Pejabat Fungsional)
                </label>
                <input type="number" step="any" min="0" name="kredit" value="{{ old('kredit') }}" placeholder="Contoh: 0.020"
                       class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                <span class="text-[11px] text-slate-400 mt-1 block">Kosongkan jika bukan tugas fungsional berangka kredit.</span>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="keterangan" rows="3" placeholder="Informasi pendukung, SK tim, atau catatan hasil pelaksanaan..."
                          class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('ckp.index', ['tahun' => $currentYear, 'bulan' => $currentMonth]) }}" 
                   class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-700/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Simpan Kegiatan Tambahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
