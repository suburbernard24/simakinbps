<!-- BEGIN: IndikatorTerkiniSection (Tabel Dinamis BPS Carousel - Maksimal 4 Kartu) -->
<section class="py-12 sm:py-16 bg-[#F7F9FA] border-b border-slate-200/70 relative" data-purpose="macro-indicators" id="indikator-terkini">
    <!-- Anchor alias for older links & test suites -->
    <div id="indikator" class="absolute -top-20"></div>
    <div id="datapokok" class="absolute -top-20"></div>
    <div id="data-pokok" class="absolute -top-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header: Title, Description & Clean Navigation Buttons -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#087E8B]/10 text-[#087E8B] tracking-wide uppercase mb-2.5">
                    <i class="fa-solid fa-chart-line text-[#36A6A0]"></i>
                    <span>Data Strategis &amp; Tabel Dinamis</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#153448] tracking-tight">
                    Indikator Terkini Kabupaten Manggarai
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1.5 max-w-2xl leading-relaxed">
                    Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai dengan akses langsung ke Tabel Dinamis.
                </p>
            </div>

            <!-- Header Navigation Controls (> untuk Geser ke Samping) -->
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-xs font-semibold text-slate-500 hidden sm:inline-block">
                    Geser indikator
                </span>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="scrollIndicatorCarousel(-1)" aria-label="Geser ke Kiri" title="Geser ke Kiri" class="w-10 h-10 rounded-xl bg-white hover:bg-slate-100 text-[#153448] border border-slate-200/90 shadow-2xs flex items-center justify-center transition-all duration-150 active:scale-95 cursor-pointer group">
                        <i class="fa-solid fa-chevron-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
                    </button>
                    <button type="button" onclick="scrollIndicatorCarousel(1)" aria-label="Geser ke Kanan" title="Geser ke Kanan" class="w-10 h-10 rounded-xl bg-[#087E8B] hover:bg-[#153448] text-white shadow-xs flex items-center justify-center transition-all duration-150 active:scale-95 cursor-pointer group">
                        <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Indicators Carousel Track (Maksimal 4 Kartu Tampak di Layar Desktop, Geser > untuk Kartu Lainnya) -->
        <div id="indicator-carousel" class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-3 pt-1 px-0.5 custom-scrollbar">
            @forelse($indikatorsList as $ind)
                <div class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-lg border border-slate-200/90 hover:border-[#087E8B]/40 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">
                                {{ $ind->badge_text ?: 'TABEL DINAMIS' }}
                            </span>
                            <span class="font-medium text-slate-500 text-xs">{{ $ind->periode }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#087E8B]/10 text-[#087E8B] group-hover:bg-[#087E8B] group-hover:text-white flex items-center justify-center mb-4 transition-all duration-200 shadow-2xs border border-[#087E8B]/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <!-- Fixed min-height so numbers line up horizontally -->
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2 min-h-[36px] leading-snug">
                            {{ $ind->judul }}
                        </h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#153448] font-mono tracking-tight">{{ $ind->nilai }}</span>
                            @if($ind->sub_judul)
                                <span class="text-xs font-semibold text-slate-500">{{ $ind->sub_judul }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed min-h-[36px]">
                            {{ $ind->deskripsi ?: 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.' }}
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors" href="{{ $ind->link_tabel ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Preset Default 4 Indicators -->
                <div class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-lg border border-slate-200/90 hover:border-[#087E8B]/40 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">TABEL DINAMIS</span>
                            <span class="font-medium text-slate-500 text-xs">Triwulan II 2026</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#087E8B]/10 text-[#087E8B] group-hover:bg-[#087E8B] group-hover:text-white flex items-center justify-center mb-4 transition-all duration-200 shadow-2xs border border-[#087E8B]/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2 min-h-[36px] leading-snug">PERTUMBUHAN EKONOMI</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#153448] font-mono tracking-tight">5,01</span>
                            <span class="text-xs font-semibold text-slate-500">(y on y)</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-lg border border-slate-200/90 hover:border-[#087E8B]/40 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">TABEL DINAMIS</span>
                            <span class="font-medium text-slate-500 text-xs">2025</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#087E8B]/10 text-[#087E8B] group-hover:bg-[#087E8B] group-hover:text-white flex items-center justify-center mb-4 transition-all duration-200 shadow-2xs border border-[#087E8B]/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2 min-h-[36px] leading-snug">UMUR HARAPAN HIDUP SAAT LAHIR (UHH) SP2020</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#153448] font-mono tracking-tight">72,16</span>
                            <span class="text-xs font-semibold text-slate-500">Tahun</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-lg border border-slate-200/90 hover:border-[#087E8B]/40 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">TABEL DINAMIS</span>
                            <span class="font-medium text-slate-500 text-xs">Triwulan II 2026</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#087E8B]/10 text-[#087E8B] group-hover:bg-[#087E8B] group-hover:text-white flex items-center justify-center mb-4 transition-all duration-200 shadow-2xs border border-[#087E8B]/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2 min-h-[36px] leading-snug">PERTUMBUHAN EKONOMI (Q TO Q)</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#153448] font-mono tracking-tight">5,01</span>
                            <span class="text-xs font-semibold text-slate-500">%</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="indicator-card-item w-full sm:w-[calc(50%-10px)] lg:w-[calc(25%-15px)] shrink-0 snap-start bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-lg border border-slate-200/90 hover:border-[#087E8B]/40 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">TABEL DINAMIS</span>
                            <span class="font-medium text-slate-500 text-xs">SP2020LF</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#087E8B]/10 text-[#087E8B] group-hover:bg-[#087E8B] group-hover:text-white flex items-center justify-center mb-4 transition-all duration-200 shadow-2xs border border-[#087E8B]/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2 min-h-[36px] leading-snug">UMUR HARAPAN HIDUP PEREMPUAN</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#153448] font-mono tracking-tight">73,61</span>
                            <span class="text-xs font-semibold text-slate-500">Tahun</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed min-h-[36px]">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!-- END: IndikatorTerkiniSection -->
