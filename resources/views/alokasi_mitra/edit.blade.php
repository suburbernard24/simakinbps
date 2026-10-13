@extends('layouts.app')

@section('title', 'Edit Alokasi Pekerjaan Mitra')
@section('page_title', 'Edit Alokasi Pekerjaan Mitra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    volume: {{ $alokasi->target_kuantitas ?? 0 }},
    hargaSatuan: '{{ $alokasi->harga_satuan ?? 0 }}',
    jumlah() {
        let v = parseFloat((this.volume + '').replace(/,/g, '.')) || 0;
        let h = parseInt((this.hargaSatuan + '').replace(/\D/g, '')) || 0;
        return v * h;
    },
    formatRp(val) {
        if (!val && val !== 0) return '';
        return 'Rp\u00a0' + new Intl.NumberFormat('id-ID').format(Math.round(val));
    },
    formatHarga(val) {
        let clean = (val + '').replace(/\D/g, '');
        this.hargaSatuan = clean;
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
            @method('PUT')

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
                           placeholder="Cth: Susenas, VIMK, dll."
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Rincian Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kegiatan" value="{{ old('kegiatan', $alokasi->kegiatan) }}" required 
                           placeholder="Uraian pekerjaan yang dilakukan..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>

            {{-- Posisi, Jenis Mitra, Satuan, Target --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <label class="block font-bold mb-1">Jenis Mitra <span class="text-rose-500">*</span></label>
                    <select name="jenis_mitra" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        <option value="pendataan_lapangan" {{ old('jenis_mitra', $alokasi->jenis_mitra ?? 'pendataan_lapangan') === 'pendataan_lapangan' ? 'selected' : '' }}>
                            🗺️ Mitra Pendataan Lapangan
                        </option>
                        <option value="pengolahan" {{ old('jenis_mitra', $alokasi->jenis_mitra ?? 'pendataan_lapangan') === 'pengolahan' ? 'selected' : '' }}>
                            🖥️ Mitra Pengolahan
                        </option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Menentukan batas honor yang berlaku untuk validasi.</p>
                </div>
            </div>

            {{-- Satuan, Volume, Harga Satuan, Jumlah --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block font-bold mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', $alokasi->satuan) }}" required 
                           placeholder="Dokumen, Dok, dll."
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block font-bold mb-1">Volume <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" min="0.01" name="target_kuantitas"
                           x-model="volume"
                           value="{{ old('target_kuantitas', $alokasi->target_kuantitas) }}" required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue text-center">
                </div>
                <div>
                    <label class="block font-bold mb-1">Harga Satuan <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-[11px]">Rp</span>
                        <input type="text" 
                               name="harga_satuan"
                               x-model="hargaSatuan"
                               @input="formatHarga($event.target.value)"
                               value="{{ old('harga_satuan', $alokasi->harga_satuan) }}" 
                               required placeholder="0"
                               class="w-full pl-9 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold text-slate-800 dark:text-slate-100">
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1 text-slate-400">Jumlah Honor</label>
                    <div class="w-full px-3.5 py-2.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-xl font-extrabold text-emerald-700 dark:text-emerald-400 text-sm whitespace-nowrap"
                         x-text="formatRp(jumlah())">
                    </div>
                </div>
            </div>

            {{-- Akun POK --}}
            <div>
                <label class="block font-bold mb-1">Beban Anggaran / Akun POK</label>
                <input type="text" name="akun_pok" value="{{ old('akun_pok', $alokasi->akun_pok) }}"
                       placeholder="Cth: Belanja Honor Output Kegiatan"
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
            </div>

            {{-- Jadwal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block font-bold mb-1">Catatan / Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">{{ old('keterangan', $alokasi->keterangan) }}</textarea>
            </div>

            {{-- Info kalkulasi honor --}}
            <div class="flex items-center gap-2 text-[11px] bg-blue-50 dark:bg-slate-800/60 rounded-xl px-4 py-2.5 border border-blue-100 dark:border-slate-700">
                <i class="fa-solid fa-calculator text-bps-blue"></i>
                <span class="text-slate-500 dark:text-slate-400">Honor dihitung otomatis:</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">Volume × Harga Satuan =</span>
                <span class="font-extrabold text-emerald-600 dark:text-emerald-400" x-text="formatRp(jumlah())"></span>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('kinerja-mitra.alokasi.index', ['tahun' => $alokasi->tahun, 'bulan' => $alokasi->bulan]) }}" 
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold shadow-md shadow-bps-blue/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
