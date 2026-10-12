{{-- Modal Interaktif: Detail Penilaian Kinerja Mitra (Sobat BPS Standard) --}}
<div x-show="modalPenilaianOpen" 
     x-cloak 
     style="display: none;" 
     class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    
    <div @click.away="modalPenilaianOpen = false" 
         x-show="modalPenilaianOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg overflow-hidden flex flex-col">
        
        {{-- Header Modal --}}
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-r from-amber-500/10 via-blue-500/5 to-transparent flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/20">
                    <i class="fa-solid fa-star text-base"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Detail Penilaian Kinerja</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="penilaianTarget.nama_mitra ? ('Mitra: ' + penilaianTarget.nama_mitra) : 'Evaluasi Kinerja Petugas'"></p>
                </div>
            </div>
            <button @click="modalPenilaianOpen = false" class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Form Penilaian --}}
        <form method="POST" action="{{ route('kinerja-mitra.penilaian.store') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="alokasi_mitra_id" :value="penilaianTarget.alokasi_id">

            {{-- Ringkasan Penugasan --}}
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 space-y-2 text-xs">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Survei / Kegiatan</span>
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs mt-0.5" x-text="penilaianTarget.kegiatan || '-'"></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 font-bold text-[10px]" x-text="penilaianTarget.posisi || 'PCL'"></span>
                </div>
            </div>

            {{-- Teks Petunjuk Resmi Sobat BPS --}}
            <div class="p-4 rounded-2xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-800/40 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-400 mt-0.5 text-xs flex-shrink-0"></i>
                <p class="text-[11px] text-amber-900 dark:text-amber-200 leading-relaxed font-medium">
                    Skala penilaian 1–5 dengan keterangan skala terendah sampai skala tertinggi. Silakan memilih jumlah bintang sesuai dengan nilai yang diberikan. Berikan komentar minimal 20 karakter.
                </p>
            </div>

            {{-- Rating Bintang Interaktif --}}
            <div class="text-center py-2 space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                    Beri Bintang Penilaian <span class="text-rose-500">*</span>
                </label>
                
                <div class="flex items-center justify-center gap-2">
                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                        <button type="button" 
                                @click="penilaianTarget.bintang = star"
                                @mouseenter="hoverBintang = star"
                                @mouseleave="hoverBintang = 0"
                                class="p-1 text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer"
                                :class="(hoverBintang >= star || (!hoverBintang && penilaianTarget.bintang >= star)) ? 'text-amber-400' : 'text-slate-300 dark:text-slate-700'">
                            <i class="fa-solid fa-star"></i>
                        </button>
                    </template>
                </div>

                {{-- Keterangan Skala Nilai --}}
                <div class="text-xs font-bold transition-all" 
                     :class="penilaianTarget.bintang > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400'">
                    <span x-show="penilaianTarget.bintang === 1">★ (1 / 5) — Sangat Kurang</span>
                    <span x-show="penilaianTarget.bintang === 2">★★ (2 / 5) — Kurang</span>
                    <span x-show="penilaianTarget.bintang === 3">★★★ (3 / 5) — Cukup Baik</span>
                    <span x-show="penilaianTarget.bintang === 4">★★★★ (4 / 5) — Baik</span>
                    <span x-show="penilaianTarget.bintang === 5">★★★★★ (5 / 5) — Sangat Memuaskan</span>
                    <span x-show="!penilaianTarget.bintang">Pilih 1 s.d. 5 bintang</span>
                </div>
                <input type="hidden" name="bintang" :value="penilaianTarget.bintang">
            </div>

            {{-- Textarea Komentar --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Komentar Penilaian <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[10px] font-semibold" 
                          :class="(penilaianTarget.komentar.length >= 20) ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500'">
                        <span x-text="penilaianTarget.komentar.length"></span> / 20 karakter
                    </span>
                </div>
                
                <textarea name="komentar" 
                          x-model="penilaianTarget.komentar"
                          rows="3" 
                          placeholder="Tuliskan ulasan kinerja mitra terkait ketepatan waktu, kualitas data pencacahan, dan sikap kerja (minimal 20 karakter)..."
                          required
                          class="w-full px-3.5 py-2.5 rounded-xl border bg-slate-50 dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-bps-blue outline-none transition"
                          :class="(penilaianTarget.komentar.length > 0 && penilaianTarget.komentar.length < 20) ? 'border-rose-400 dark:border-rose-600' : 'border-slate-200 dark:border-slate-700'"></textarea>

                <p x-show="penilaianTarget.komentar.length > 0 && penilaianTarget.komentar.length < 20" 
                   class="text-[11px] text-rose-500 font-medium">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>Komentar masih kurang <span x-text="20 - penilaianTarget.komentar.length"></span> karakter lagi.
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <button type="button" 
                        @click="modalPenilaianOpen = false" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 transition">
                    Batal
                </button>
                <button type="submit" 
                        :disabled="!penilaianTarget.bintang || penilaianTarget.komentar.length < 20"
                        class="px-5 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-md shadow-bps-blue/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Penilaian</span>
                </button>
            </div>
        </form>

    </div>
</div>
