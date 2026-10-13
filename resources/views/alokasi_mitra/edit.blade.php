@extends('layouts.app')

@section('title', 'Edit Alokasi Pekerjaan Mitra')
@section('page_title', 'Edit Alokasi Pekerjaan Mitra')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    volume: {{ $alokasi->target_kuantitas ?? 0 }},
    hargaSatuan: '{{ $alokasi->harga_satuan ?? 0 }}',
    sisaKuota: {{ (int) $sisaKuotaSaatIni }},
    popOverLimitModal: false,
    konfirmasiMelebihiBatas: false,
    jumlah() {
        let v = parseFloat((this.volume + '').replace(/,/g, '.')) || 0;
        let h = parseInt((this.hargaSatuan + '').replace(/\D/g, '')) || 0;
        return v * h;
    },
    formatRp(val) {
        if (!val && val !== 0) return 'Rp 0';
        return 'Rp\u00a0' + new Intl.NumberFormat('id-ID').format(Math.round(val));
    },
    formatHarga(val) {
        let clean = (val + '').replace(/\D/g, '');
        this.hargaSatuan = clean;
    },
    isMelebihiBatas() {
        return this.jumlah() > this.sisaKuota;
    },
    getKelebihanHonor() {
        return this.isMelebihiBatas() ? (this.jumlah() - this.sisaKuota) : 0;
    },
    getHonorDibayar() {
        return Math.min(this.jumlah(), this.sisaKuota);
    },
    handleFormSubmit() {
        if (this.isMelebihiBatas()) {
            this.popOverLimitModal = true;
        } else {
            this.konfirmasiMelebihiBatas = false;
            this.$refs.editForm.submit();
        }
    },
    confirmAndSubmit() {
        this.konfirmasiMelebihiBatas = true;
        this.popOverLimitModal = false;
        this.$nextTick(() => {
            this.$refs.editForm.submit();
        });
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

        <form method="POST" action="{{ route('kinerja-mitra.alokasi.update', $alokasi->id) }}" 
              x-ref="editForm"
              class="p-6 sm:p-8 space-y-5 text-xs text-slate-700 dark:text-slate-300">
            @csrf
            @method('PUT')
            <input type="hidden" name="konfirmasi_melebihi_batas" :value="konfirmasiMelebihiBatas ? '1' : '0'">

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
                
                {{-- Kartu Status Sisa Kuota --}}
                <div class="mt-2.5 p-3 rounded-2xl border transition-all"
                     :class="isMelebihiBatas() 
                         ? 'bg-rose-50/70 dark:bg-rose-950/30 border-rose-200 dark:border-rose-900/60' 
                         : 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50'">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Sisa Kuota Honor Tersedia (di luar alokasi ini):</span>
                        <strong class="text-emerald-600 dark:text-emerald-400 font-extrabold">Rp {{ number_format($sisaKuotaSaatIni, 0, ',', '.') }}</strong>
                    </div>
                    <div x-show="isMelebihiBatas()" class="mt-2 pt-2 border-t border-rose-200 dark:border-rose-900/40 text-[11px] text-rose-700 dark:text-rose-300 flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
                        <span>
                            Honor baru (<strong x-text="formatRp(jumlah())"></strong>) melebihi kuota tersedia. 
                            Kelebihan sebesar <strong class="underline" x-text="formatRp(getKelebihanHonor())"></strong> tidak akan dibayarkan.
                        </span>
                    </div>
                </div>
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
                <button type="button" @click="handleFormSubmit()" 
                        class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold shadow-md shadow-bps-blue/20 flex items-center gap-2 transition cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>

            {{-- ==================== POP UP NOTIFIKASI MELEWATI BATAS (EDIT) ==================== --}}
            <div x-show="popOverLimitModal" x-cloak style="display: none;"
                 class="fixed inset-0 z-[80] bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
                <div @click.away="popOverLimitModal = false"
                     x-show="popOverLimitModal" x-transition.duration.200ms
                     class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full max-w-md overflow-hidden text-center p-6 space-y-5">
                    
                    {{-- Ikon Pop Up --}}
                    <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mx-auto shadow-inner">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    {{-- Teks Pertanyaan Konfirmasi Sesuai Permintaan User --}}
                    <div class="space-y-2">
                        <h3 class="font-black text-slate-900 dark:text-white text-base">
                            Konfirmasi Alokasi Pekerjaan Mitra
                        </h3>
                        <p class="text-sm font-extrabold text-amber-600 dark:text-amber-400 leading-snug px-2">
                            Apakah ingin mengalokasikan pekerjaan kepada Mitra? Jika Ya maka honor yang melebihi batas akan tidak dibayar
                        </p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            Ketentuan ini hanya berlaku ketika alokasi pekerjaan dan honor melewati batas maksimum kuota mitra.
                        </p>
                    </div>

                    {{-- Rincian Kalkulasi Honor --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 text-xs text-left space-y-2">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-500">Sisa Kuota Honor Tersedia:</span>
                            <strong class="text-emerald-600 dark:text-emerald-400" x-text="formatRp(sisaKuota)"></strong>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-500">Total Honor yang Diajukan:</span>
                            <strong class="text-bps-blue dark:text-blue-400" x-text="formatRp(jumlah())"></strong>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-2 flex justify-between items-center font-bold">
                            <span class="text-rose-600 dark:text-rose-400">Honor Melebihi Batas (TIDAK DIBAYAR):</span>
                            <span class="text-rose-600 dark:text-rose-400 font-black text-sm" x-text="formatRp(getKelebihanHonor())"></span>
                        </div>
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-500">Honor yang Tetap Dibayar:</span>
                            <strong class="text-emerald-600 dark:text-emerald-400" x-text="formatRp(getHonorDibayar())"></strong>
                        </div>
                    </div>

                    {{-- Tombol Pilihan Aksi Pop Up --}}
                    <div class="flex items-center justify-center gap-3 pt-1">
                        <button type="button" @click="popOverLimitModal = false"
                                class="w-1/2 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="button" @click="confirmAndSubmit()"
                                class="w-1/2 py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/30 transition cursor-pointer flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-check"></i>
                            Ya, Perbarui
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
