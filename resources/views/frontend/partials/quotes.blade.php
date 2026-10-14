<!-- BEGIN: QuoteSection -->
<section class="py-16 sm:py-20 bg-[#F4F7FB] border-b border-slate-200/70 relative" data-purpose="quote-banner" id="quotes">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-xs text-center relative">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#0B4F82]/10 text-[#0B4F82] tracking-wide uppercase mb-6">
                Kutipan Inspiratif &amp; Nilai Inti
            </span>

            @if($quotesList->isNotEmpty())
                <div id="quotes-carousel-container">
                    @foreach($quotesList as $index => $qItem)
                        <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
                            <!-- Leader Avatar -->
                            <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white border-2 border-[#0B4F82]/30 mb-4 shadow-xs overflow-hidden">
                                @if($qItem->foto_url)
                                    <img alt="{{ $qItem->nama }}" class="w-full h-full rounded-full object-cover" src="{{ $qItem->foto_url }}">
                                @else
                                    <div class="w-full h-full rounded-full bg-[#172B4D] flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($qItem->nama, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-[#172B4D]">{{ $qItem->nama }}</h3>
                            <p class="text-xs font-semibold text-[#0B4F82] tracking-wider uppercase mb-5">{{ $qItem->jabatan }}</p>
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
                            <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-[#0B4F82] scale-125' : 'bg-slate-300' }} transition-all focus:outline-none cursor-pointer" aria-label="Quote {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            @else
                <!-- Default Quote -->
                <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white border-2 border-[#0B4F82]/30 mb-4 shadow-xs overflow-hidden">
                    @if(isset($fs['img_quote_avatar']))
                        <img alt="{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}" class="w-full h-full rounded-full object-cover" src="{{ asset('storage/' . $fs['img_quote_avatar']) }}">
                    @else
                        <div class="w-full h-full rounded-full bg-[#172B4D] flex items-center justify-center text-white font-bold text-xl">
                            DH
                        </div>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-[#172B4D]">{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}</h3>
                <p class="text-xs font-semibold text-[#0B4F82] tracking-wider uppercase mb-5">{{ $fs['quote_jabatan'] ?? 'KEPALA BPS KABUPATEN MANGGARAI' }}</p>
                <blockquote class="text-base sm:text-lg font-medium italic text-slate-700 max-w-2xl mx-auto leading-relaxed">
                    “{{ trim($fs['quote_text'] ?? 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.', '\"') }}”
                </blockquote>
            @endif
        </div>
    </div>
</section>
<!-- END: QuoteSection -->
