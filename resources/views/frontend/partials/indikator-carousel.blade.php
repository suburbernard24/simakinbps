<!-- BEGIN: IndikatorTerkiniSection (Tabel Dinamis BPS Carousel - Maksimal 4 Kartu) -->
<section class="py-14 sm:py-16 bg-[#F4F7FA] border-b border-slate-200/80 relative" data-purpose="macro-indicators" id="indikator-terkini">
    <!-- Anchor alias for older links & test suites -->
    <div id="indikator" class="absolute -top-20"></div>
    <div id="datapokok" class="absolute -top-20"></div>
    <div id="data-pokok" class="absolute -top-20"></div>

    <style>
        #indicator-carousel::-webkit-scrollbar { display: none; }
        #indicator-carousel { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#123B63] tracking-tight">
                Indikator Terkini Kabupaten Manggarai
            </h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                Indikator strategis resmi BPS Kabupaten Manggarai dengan akses langsung ke Tabel Dinamis.
            </p>
        </div>

        <!-- Indicators Carousel Wrapper -->
        <div class="relative">
            <button type="button" onclick="scrollIndicatorCarousel(-1)" aria-label="Geser ke Kiri" title="Geser ke Kiri" class="absolute left-2 sm:-left-5 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white hover:bg-[#1E5B91] text-[#1E5B91] hover:text-white border border-slate-200 shadow-sm flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-left text-xs" aria-hidden="true"></i>
            </button>

            <button type="button" onclick="scrollIndicatorCarousel(1)" aria-label="Geser ke Kanan" title="Geser ke Kanan" class="absolute right-2 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white hover:bg-[#1E5B91] text-[#1E5B91] hover:text-white border border-slate-200 shadow-sm flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i>
            </button>

            <!-- Indicators Carousel Track -->
            <div id="indicator-carousel" class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-1">
            @forelse($indikatorsList as $ind)
                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-xl p-6 border border-slate-200/80 hover:border-[#3686C8] flex flex-col justify-between transition-colors">
                    <div>
                        <span class="text-[11px] font-medium text-slate-400">{{ $ind->periode }}</span>
                        <h3 class="text-sm font-semibold text-[#123B63] mt-1.5 min-h-[40px] leading-snug">
                            {{ $ind->judul }}
                        </h3>
                        <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#123B63] tabular-nums tracking-tight">{{ $ind->nilai }}</span>
                            @if($ind->sub_judul)
                                <span class="text-xs font-medium text-slate-500">{{ $ind->sub_judul }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors" href="{{ $ind->link_tabel ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @empty
                <!-- Preset Default 4 Indicators -->
                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-xl p-6 border border-slate-200/80 hover:border-[#3686C8] flex flex-col justify-between transition-colors">
                    <div>
                        <span class="text-[11px] font-medium text-slate-400">Triwulan II 2026</span>
                        <h3 class="text-sm font-semibold text-[#123B63] mt-1.5 min-h-[40px] leading-snug">Pertumbuhan Ekonomi</h3>
                        <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#123B63] tabular-nums tracking-tight">5,01</span>
                            <span class="text-xs font-medium text-slate-500">(y on y)</span>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-xl p-6 border border-slate-200/80 hover:border-[#3686C8] flex flex-col justify-between transition-colors">
                    <div>
                        <span class="text-[11px] font-medium text-slate-400">2025</span>
                        <h3 class="text-sm font-semibold text-[#123B63] mt-1.5 min-h-[40px] leading-snug">Umur Harapan Hidup Saat Lahir (UHH) SP2020</h3>
                        <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#123B63] tabular-nums tracking-tight">72,16</span>
                            <span class="text-xs font-medium text-slate-500">Tahun</span>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-xl p-6 border border-slate-200/80 hover:border-[#3686C8] flex flex-col justify-between transition-colors">
                    <div>
                        <span class="text-[11px] font-medium text-slate-400">Triwulan II 2026</span>
                        <h3 class="text-sm font-semibold text-[#123B63] mt-1.5 min-h-[40px] leading-snug">Pertumbuhan Ekonomi (Q to Q)</h3>
                        <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#123B63] tabular-nums tracking-tight">5,01</span>
                            <span class="text-xs font-medium text-slate-500">%</span>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-xl p-6 border border-slate-200/80 hover:border-[#3686C8] flex flex-col justify-between transition-colors">
                    <div>
                        <span class="text-[11px] font-medium text-slate-400">SP2020LF</span>
                        <h3 class="text-sm font-semibold text-[#123B63] mt-1.5 min-h-[40px] leading-snug">Umur Harapan Hidup Perempuan</h3>
                        <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#123B63] tabular-nums tracking-tight">73,61</span>
                            <span class="text-xs font-medium text-slate-500">Tahun</span>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @endforelse
            </div>
        </div>
    </div>
</section>
<!-- END: IndikatorTerkiniSection -->
