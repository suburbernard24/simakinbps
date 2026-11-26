<!-- BEGIN: QuoteSection -->
<section class="py-16 sm:py-20 bg-[#F4F7FA] border-b border-slate-200/80" data-purpose="quote-banner" id="quotes">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-semibold text-[#1E5B91] mb-6">
            Kutipan Inspiratif &amp; Integritas Data
        </p>

        @if($quotesList->isNotEmpty())
            <div id="quotes-carousel-container">
                @foreach($quotesList as $index => $qItem)
                    <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
                        <blockquote class="text-lg sm:text-2xl font-medium text-[#123B63] leading-relaxed">
                            “{{ trim($qItem->quote, '\"') }}”
                        </blockquote>
                        <div class="mt-6 flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 bg-[#123B63] mb-2.5">
                                @if($qItem->foto_url)
                                    <img alt="{{ $qItem->nama }}" width="48" height="48" loading="lazy" decoding="async" class="w-full h-full object-cover" src="{{ $qItem->foto_url }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($qItem->nama, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-sm font-bold text-[#123B63]">{{ $qItem->nama }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $qItem->jabatan }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($quotesList->count() > 1)
                <div class="flex items-center justify-center space-x-2 mt-6">
                    @foreach($quotesList as $idx => $qItem)
                        <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-[#1E5B91] scale-125' : 'bg-slate-300' }} transition-colors" aria-label="Quote {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        @else
            <blockquote class="text-lg sm:text-2xl font-medium text-[#123B63] leading-relaxed">
                “{{ trim($fs['quote_text'] ?? 'Data statistik yang akurat, mutakhir, dan objektif adalah fondasi utama bagi perencanaan, penentuan arah kebijakan, serta evaluasi pembangunan berkelanjutan demi kesejahteraan seluruh masyarakat.', '\"') }}”
            </blockquote>
            <div class="mt-6 flex flex-col items-center">
                <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 bg-[#123B63] mb-2.5">
                    @if(isset($fs['img_quote_avatar']) && !empty($fs['img_quote_avatar']))
                        <img alt="{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}" width="48" height="48" loading="lazy" decoding="async" class="w-full h-full object-cover" src="{{ asset('storage/' . $fs['img_quote_avatar']) }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white font-bold text-sm">
                            <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                        </div>
                    @endif
                </div>
                <h3 class="text-sm font-bold text-[#123B63]">{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ $fs['quote_jabatan'] ?? 'BPS Kabupaten Manggarai' }}</p>
            </div>
        @endif
    </div>
</section>
<!-- END: QuoteSection -->
