<!-- BEGIN: IndikatorTerkiniSection (Tabel Dinamis BPS Carousel - Maksimal 4 Kartu) -->
<section class="py-12 sm:py-16 bg-[#F4F7FA] border-b border-slate-200 relative" data-purpose="macro-indicators" id="indikator-terkini">
    <!-- Anchor alias for older links & test suites -->
    <div id="indikator" class="absolute -top-20"></div>
    <div id="datapokok" class="absolute -top-20"></div>
    <div id="data-pokok" class="absolute -top-20"></div>

    <style>
        #indicator-carousel::-webkit-scrollbar { display: none; }
        #indicator-carousel { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header: Title & Description -->
        <div class="mb-8 pb-5 border-b border-slate-200">
            <p class="text-xs font-semibold text-[#1E5B91] mb-1.5">
                Data Strategis &amp; Tabel Dinamis
            </p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">
                Indikator Terkini Kabupaten Manggarai
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm mt-1.5 max-w-2xl leading-relaxed">
                Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai dengan akses langsung ke Tabel Dinamis.
            </p>
        </div>

        <!-- Indicators Carousel Wrapper dengan Panah Kiri/Kanan di Samping Kartu -->
        <div class="relative">
            <button type="button" onclick="scrollIndicatorCarousel(-1)" aria-label="Geser ke Kiri" title="Geser ke Kiri" class="absolute left-2 sm:-left-5 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white hover:bg-[#1E5B91] text-[#1E5B91] hover:text-white border border-slate-200 shadow-md flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-left text-xs" aria-hidden="true"></i>
            </button>

            <button type="button" onclick="scrollIndicatorCarousel(1)" aria-label="Geser ke Kanan" title="Geser ke Kanan" class="absolute right-2 sm:-right-5 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white hover:bg-[#1E5B91] text-[#1E5B91] hover:text-white border border-slate-200 shadow-md flex items-center justify-center transition-colors">
                <i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i>
            </button>

            <!-- Indicators Carousel Track (Maksimal 4 Kartu Tampak di Layar Desktop, Geser > untuk Kartu Lainnya) -->
            <div id="indicator-carousel" class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2">
            @forelse($indikatorsList as $ind)
                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:border-[#1E5B91] flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="px-5 py-3 bg-[#123B63] text-white flex items-center justify-between gap-2 text-[11px]">
                            <span class="font-semibold text-[#9BBBD9] truncate">{{ $ind->badge_text ?: 'Tabel Dinamis' }}</span>
                            <span class="font-medium text-slate-200 shrink-0">{{ $ind->periode }}</span>
                        </div>

                        <div class="p-5">
                            <h3 class="text-[13px] font-bold text-[#123B63] min-h-[38px] leading-snug">
                                {{ $ind->judul }}
                            </h3>

                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-baseline gap-2 flex-wrap">
                                <span class="text-3xl sm:text-[34px] font-extrabold text-[#123B63] tabular-nums tracking-tight leading-none">{{ $ind->nilai }}</span>
                                @if($ind->sub_judul)
                                    <span class="text-xs font-semibold text-[#1E5B91] bg-[#1E5B91]/10 px-2 py-0.5 rounded">{{ $ind->sub_judul }}</span>
                                @endif
                            </div>

                            <p class="text-xs text-slate-600 mt-3 leading-relaxed min-h-[36px]">
                                {{ $ind->deskripsi ?: 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.' }}
                            </p>
                        </div>
                    </div>

                    <a class="px-5 py-3 bg-[#F4F7FA] group-hover:bg-[#1E5B91] border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-[#1E5B91] group-hover:text-white transition-colors" href="{{ $ind->link_tabel ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" rel="noopener noreferrer">
                        <span>Buka Tabel Dinamis</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
                    </a>
                </article>
            @empty
                <!-- Preset Default 4 Indicators -->
                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:border-[#1E5B91] flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="px-5 py-3 bg-[#123B63] text-white flex items-center justify-between gap-2 text-[11px]">
                            <span class="font-semibold text-[#9BBBD9]">Tabel Dinamis</span>
                            <span class="font-medium text-slate-200">Triwulan II 2026</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-[13px] font-bold text-[#123B63] min-h-[38px] leading-snug">Pertumbuhan Ekonomi</h3>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-baseline gap-2 flex-wrap">
                                <span class="text-3xl sm:text-[34px] font-extrabold text-[#123B63] tabular-nums tracking-tight leading-none">5,01</span>
                                <span class="text-xs font-semibold text-[#1E5B91] bg-[#1E5B91]/10 px-2 py-0.5 rounded">(y on y)</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                        </div>
                    </div>
                    <a class="px-5 py-3 bg-[#F4F7FA] group-hover:bg-[#1E5B91] border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-[#1E5B91] group-hover:text-white transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                        <span>Buka Tabel Dinamis</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
                    </a>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:border-[#1E5B91] flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="px-5 py-3 bg-[#123B63] text-white flex items-center justify-between gap-2 text-[11px]">
                            <span class="font-semibold text-[#9BBBD9]">Tabel Dinamis</span>
                            <span class="font-medium text-slate-200">2025</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-[13px] font-bold text-[#123B63] min-h-[38px] leading-snug">Umur Harapan Hidup Saat Lahir (UHH) SP2020</h3>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-baseline gap-2 flex-wrap">
                                <span class="text-3xl sm:text-[34px] font-extrabold text-[#123B63] tabular-nums tracking-tight leading-none">72,16</span>
                                <span class="text-xs font-semibold text-[#1E5B91] bg-[#1E5B91]/10 px-2 py-0.5 rounded">Tahun</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                        </div>
                    </div>
                    <a class="px-5 py-3 bg-[#F4F7FA] group-hover:bg-[#1E5B91] border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-[#1E5B91] group-hover:text-white transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                        <span>Buka Tabel Dinamis</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
                    </a>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:border-[#1E5B91] flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="px-5 py-3 bg-[#123B63] text-white flex items-center justify-between gap-2 text-[11px]">
                            <span class="font-semibold text-[#9BBBD9]">Tabel Dinamis</span>
                            <span class="font-medium text-slate-200">Triwulan II 2026</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-[13px] font-bold text-[#123B63] min-h-[38px] leading-snug">Pertumbuhan Ekonomi (Q to Q)</h3>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-baseline gap-2 flex-wrap">
                                <span class="text-3xl sm:text-[34px] font-extrabold text-[#123B63] tabular-nums tracking-tight leading-none">5,01</span>
                                <span class="text-xs font-semibold text-[#1E5B91] bg-[#1E5B91]/10 px-2 py-0.5 rounded">%</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                        </div>
                    </div>
                    <a class="px-5 py-3 bg-[#F4F7FA] group-hover:bg-[#1E5B91] border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-[#1E5B91] group-hover:text-white transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                        <span>Buka Tabel Dinamis</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
                    </a>
                </article>

                <article class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-lg overflow-hidden border border-slate-200 shadow-sm hover:shadow-md hover:border-[#1E5B91] flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="px-5 py-3 bg-[#123B63] text-white flex items-center justify-between gap-2 text-[11px]">
                            <span class="font-semibold text-[#9BBBD9]">Tabel Dinamis</span>
                            <span class="font-medium text-slate-200">SP2020LF</span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-[13px] font-bold text-[#123B63] min-h-[38px] leading-snug">Umur Harapan Hidup Perempuan</h3>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-baseline gap-2 flex-wrap">
                                <span class="text-3xl sm:text-[34px] font-extrabold text-[#123B63] tabular-nums tracking-tight leading-none">73,61</span>
                                <span class="text-xs font-semibold text-[#1E5B91] bg-[#1E5B91]/10 px-2 py-0.5 rounded">Tahun</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-3 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                        </div>
                    </div>
                    <a class="px-5 py-3 bg-[#F4F7FA] group-hover:bg-[#1E5B91] border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-[#1E5B91] group-hover:text-white transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                        <span>Buka Tabel Dinamis</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
                    </a>
                </article>
            @endforelse
            </div>
        </div>
    </div>
</section>
<!-- END: IndikatorTerkiniSection -->
