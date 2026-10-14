<!-- BEGIN: QuoteSection -->
<section class="py-16 bg-gradient-to-r from-bps-900 via-bps-800 to-sky-900 text-white relative" data-purpose="quote-banner" id="quotes">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-[11px] font-bold tracking-widest text-bps-300 uppercase block mb-6">QUOTES OF THE DAY</span>

        @if($quotesList->isNotEmpty())
            <div id="quotes-carousel-container">
                @foreach($quotesList as $index => $qItem)
                    <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
                        <!-- Leader Avatar -->
                        <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white/20 border-2 border-white/30 mb-4 shadow-lg overflow-hidden">
                            @if($qItem->foto_url)
                                <img alt="{{ $qItem->nama }}" class="w-full h-full rounded-full object-cover" src="{{ $qItem->foto_url }}">
                            @else
                                <div class="w-full h-full rounded-full bg-gradient-to-tr from-bps-950 to-bps-700 flex items-center justify-center text-white font-bold text-xl">
                                    {{ strtoupper(substr($qItem->nama, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-white">{{ $qItem->nama }}</h3>
                        <p class="text-xs text-bps-300 tracking-wider uppercase mb-6">{{ $qItem->jabatan }}</p>
                        <blockquote class="text-lg sm:text-xl font-medium italic text-slate-100 max-w-2xl mx-auto leading-relaxed">
                            “{{ trim($qItem->quote, '\"') }}”
                        </blockquote>
                    </div>
                @endforeach
            </div>

            @if($quotesList->count() > 1)
                <!-- Pagination dots -->
                <div class="flex items-center justify-center space-x-2 mt-6">
                    @foreach($quotesList as $idx => $qItem)
                        <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-white scale-125' : 'bg-white/40' }} transition-all focus:outline-none" aria-label="Quote {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        @else
            <!-- Default Quote -->
            <div class="w-20 h-20 mx-auto rounded-full p-1 bg-white/20 border-2 border-white/30 mb-4 shadow-lg overflow-hidden">
                @if(isset($fs['img_quote_avatar']))
                    <img alt="{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}" class="w-full h-full rounded-full object-cover" src="{{ asset('storage/' . $fs['img_quote_avatar']) }}">
                @else
                    <div class="w-full h-full rounded-full bg-gradient-to-tr from-bps-950 to-bps-700 flex items-center justify-center text-white font-bold text-xl">
                        DH
                    </div>
                @endif
            </div>
            <h3 class="text-base font-bold text-white">{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}</h3>
            <p class="text-xs text-bps-300 tracking-wider uppercase mb-6">{{ $fs['quote_jabatan'] ?? 'KEPALA BPS KABUPATEN MANGGARAI' }}</p>
            <blockquote class="text-lg sm:text-xl font-medium italic text-slate-100 max-w-2xl mx-auto leading-relaxed">
                “{{ trim($fs['quote_text'] ?? 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.', '\"') }}”
            </blockquote>
        @endif
    </div>
</section>
<!-- END: QuoteSection -->
