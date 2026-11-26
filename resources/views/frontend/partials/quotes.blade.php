<!-- BEGIN: QuoteSection -->
<section class="py-16 sm:py-20 bg-gradient-to-b from-white to-[#F7F9FA] border-b border-slate-200/70 relative" data-purpose="quote-banner" id="quotes">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border-2 border-slate-200/80 shadow-md text-center relative overflow-hidden">
            <!-- Background Quote Marks -->
            <div class="absolute -top-6 -left-6 text-slate-100 text-9xl font-serif pointer-events-none select-none">
                “
            </div>
            <div class="absolute -bottom-10 -right-6 text-slate-100 text-9xl font-serif pointer-events-none select-none">
                ”
            </div>

            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-[#087E8B]/10 text-[#087E8B] tracking-wide uppercase mb-6">
                    <i class="fa-solid fa-quote-left text-[11px]"></i>
                    <span>Kutipan Inspiratif &amp; Integritas Data</span>
                </span>

                @if($quotesList->isNotEmpty())
                    <div id="quotes-carousel-container">
                        @foreach($quotesList as $index => $qItem)
                            <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
                                <!-- Leader Avatar -->
                                <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white border-2 border-[#087E8B]/30 mb-4 shadow-sm overflow-hidden">
                                    @if($qItem->foto_url)
                                        <img alt="{{ $qItem->nama }}" class="w-full h-full rounded-full object-cover" src="{{ $qItem->foto_url }}">
                                    @else
                                        <div class="w-full h-full rounded-full bg-[#153448] flex items-center justify-center text-white font-bold text-xl">
                                            {{ strtoupper(substr($qItem->nama, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-[#153448]">{{ $qItem->nama }}</h3>
                                <p class="text-xs font-semibold text-[#087E8B] tracking-wider uppercase mb-5">{{ $qItem->jabatan }}</p>
                                <blockquote class="text-base sm:text-lg font-medium italic text-slate-700 max-w-2xl mx-auto leading-relaxed">
                                    “{{ trim($qItem->quote, '\"') }}”
                                </blockquote>
                            </div>
                        @endforeach
                    </div>

                    @if($quotesList->count() > 1)
                        <!-- Pagination dots -->
                        <div class="flex items-center justify-center space-x-2 mt-6">
                            @foreach($quotesList as $idx => $qItem)
                                <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-[#087E8B] scale-125' : 'bg-slate-300' }} transition-all focus:outline-none cursor-pointer" aria-label="Quote {{ $idx + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <!-- Default Quote: Pimpinan BPS Kabupaten Manggarai -->
                    <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white border-2 border-[#087E8B]/30 mb-4 shadow-sm overflow-hidden">
                        @if(isset($fs['img_quote_avatar']) && !empty($fs['img_quote_avatar']))
                            <img alt="{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}" class="w-full h-full rounded-full object-cover" src="{{ asset('storage/' . $fs['img_quote_avatar']) }}">
                        @else
                            <div class="w-full h-full rounded-full bg-[#153448] flex items-center justify-center text-white font-bold text-xl">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-[#153448]">{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}</h3>
                    <p class="text-xs font-semibold text-[#087E8B] tracking-wider uppercase mb-5">{{ $fs['quote_jabatan'] ?? 'BPS KABUPATEN MANGGARAI' }}</p>
                    <blockquote class="text-base sm:text-lg font-medium italic text-slate-700 max-w-2xl mx-auto leading-relaxed">
                        “{{ trim($fs['quote_text'] ?? 'Data statistik yang akurat, mutakhir, dan objektif adalah fondasi utama bagi perencanaan, penentuan arah kebijakan, serta evaluasi pembangunan berkelanjutan demi kesejahteraan seluruh masyarakat.', '\"') }}”
                    </blockquote>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- END: QuoteSection -->
