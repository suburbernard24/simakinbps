@extends('layouts.app')

@section('title', 'Edit Alokasi Pekerjaan Mitra')
@section('page_title', 'Edit Alokasi Pekerjaan Mitra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    honorInput: '{{ number_format($alokasi->honor, 0, ',', '.') }}',
    formatRupiah(val) {
        let clean = (val + '').replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(clean);
    }
}">

    <div class="flex items-center justify-between">
        <a href="{{ route('kinerja-mitra.alokasi.index', ['tahun' => $alokasi->tahun, 'bulan' => $alokasi->bulan]) }}" 
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:border-bps-blue transition shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Alokasi</span>
        </a>

        <div class="px-3.5 py-1.5 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-xs">
            <span class="text-slate-500 text-[10px] uppercase font-bold block">Batas Honor Periode {{ $alokasi->bulan }}/{{ $alokasi->tahun }}:</span>
            <span class="font-extrabold text-bps-blue dark:text-blue-400">Rp {{ number_format($maksimalHonor, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Card Form Edit --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Edit Alokasi Pekerjaan Mitra</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui data survei, target penugasan, atau honor mitra</p>
                </div>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400">ID #{{ $alokasi->id }}</span>
        </div>

        <form method="POST" action="{{ route('kinerja-mitra.alokasi.update', $alokasi->id) }}" class="p-6 sm:p-8 space-y-5 text-xs text-slate-700 dark:text-slate-300">
            @csrf

            {{-- Mitra yang Ditugaskan --}}
            <div>
                <label class="block font-bold mb-1">Mitra Statistik <span class="text-rose-500">*</span></label>
                <select name="mitra_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-medium">
                    @foreach($mitras as $m)
                        <option value="{{ $m->id }}" {{ old('mitra_id', $alokasi->mitra_id) == $m->id ? 'selected' : '' }}>
                            {{ $m->nama }} (Sobat-ID: {{ $m->sobat_id }}) — {{ $m->kabupaten }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">
                    Kuota honor tersisa untuk mitra ini (di luar alokasi ini): <strong class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($sisaKuotaSaatIni, 0, ',', '.') }}</strong>
                </p>
            </div>

            {{-- Survei & Rincian Kegiatan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold mb-1">Survei / Kegiatan Utama <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $alokasi->nama_kegiatan) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Rincian Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kegiatan" value="{{ old('kegiatan', $alokasi->kegiatan) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>

            {{-- Posisi, Satuan, Target --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1">Posisi Penugasan <span class="text-rose-500">*</span></label>
                    <select name="posisi" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        <option value="Petugas Pencacah Lapangan (PCL)" {{ old('posisi', $alokasi->posisi) === 'Petugas Pencacah Lapangan (PCL)' ? 'selected' : '' }}>Petugas Pencacah Lapangan (PCL)</option>
                        <option value="Petugas Pemeriksa Lapangan (PML)" {{ old('posisi', $alokasi->posisi) === 'Petugas Pemeriksa Lapangan (PML)' ? 'selected' : '' }}>Petugas Pemeriksa Lapangan (PML)</option>
                        <option value="Petugas Pengolahan Data" {{ old('posisi', $alokasi->posisi) === 'Petugas Pengolahan Data' ? 'selected' : '' }}>Petugas Pengolahan Data</option>
                        <option value="Petugas Listing / Pemetaan" {{ old('posisi', $alokasi->posisi) === 'Petugas Listing / Pemetaan' ? 'selected' : '' }}>Petugas Listing / Pemetaan</option>
                        <option value="Operator Entri Data" {{ old('posisi', $alokasi->posisi) === 'Operator Entri Data' ? 'selected' : '' }}>Operator Entri Data</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Satuan Target <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', $alokasi->satuan) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Target Kuantitas <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" min="0.01" name="target_kuantitas" value="{{ old('target_kuantitas', $alokasi->target_kuantitas) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>

            {{-- Honor IDR --}}
            <div>
                <label class="block font-bold mb-1">Honor Mitra (IDR) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                    <input type="text" 
                           name="honor" 
                           x-model="honorInput"
                           @input="honorInput = formatRupiah($event.target.value)"
                           value="{{ old('honor', $alokasi->honor) }}" 
                           required 
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold text-sm text-slate-800 dark:text-slate-100">
                </div>
            </div>

            {{-- Jadwal & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $alokasi->tanggal_mulai?->format('Y-m-d')) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $alokasi->tanggal_selesai?->format('Y-m-d')) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Status Penugasan <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        <option value="terjadwal" {{ old('status', $alokasi->status) === 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="berjalan" {{ old('status', $alokasi->status) === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ old('status', $alokasi->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="diterima" {{ old('status', $alokasi->status) === 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="terlambat" {{ old('status', $alokasi->status) === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block font-bold mb-1">Catatan / Keterangan</label>
                <textarea name="keterangan" rows="2.5" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">{{ old('keterangan', $alokasi->keterangan) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('kinerja-mitra.alokasi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold shadow-md shadow-bps-blue/20">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
