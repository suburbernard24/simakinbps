<!-- BEGIN: QuoteSection -->
<section class="py-14 sm:py-18 bg-[#F4F7FA] border-b border-slate-200" data-purpose="quote-banner" id="quotes">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-slate-200 p-8 sm:p-10 lg:p-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-100">
                <p class="text-xs font-semibold text-[#1E5B91]">
                    Kutipan Inspiratif &amp; Integritas Data
                </p>
                @if($quotesList->count() > 1)
                    <div class="flex items-center space-x-2">
                        @foreach($quotesList as $idx => $qItem)
                            <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-[#1E5B91] scale-125' : 'bg-slate-300' }} transition-colors" aria-label="Quote {{ $idx + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($quotesList->isNotEmpty())
                <div id="quotes-carousel-container">
                    @foreach($quotesList as $index => $qItem)
                        <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                                <div class="lg:col-span-4 flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full overflow-hidden border border-slate-200 shrink-0 bg-[#123B63]">
                                        @if($qItem->foto_url)
                                            <img alt="{{ $qItem->nama }}" width="64" height="64" class="w-full h-full object-cover" src="{{ $qItem->foto_url }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white font-bold text-base">
                                                {{ strtoupper(substr($qItem->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-[#123B63]">{{ $qItem->nama }}</h3>
                                        <p class="text-xs font-medium text-slate-600 mt-0.5">{{ $qItem->jabatan }}</p>
                                    </div>
                                </div>

                                <div class="lg:col-span-8 lg:border-l lg:border-slate-200 lg:pl-8">
                                    <blockquote class="text-lg sm:text-xl font-medium text-[#123B63] leading-relaxed">
                                        “{{ trim($qItem->quote, '\"') }}”
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Default Quote: Pimpinan BPS Kabupaten Manggarai -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-4 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border border-slate-200 shrink-0 bg-[#123B63]">
                            @if(isset($fs['img_quote_avatar']) && !empty($fs['img_quote_avatar']))
                                <img alt="{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}" width="64" height="64" class="w-full h-full object-cover" src="{{ asset('storage/' . $fs['img_quote_avatar']) }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-bold text-base">
                                    <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[#123B63]">{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}</h3>
                            <p class="text-xs font-medium text-slate-600 mt-0.5">{{ $fs['quote_jabatan'] ?? 'BPS Kabupaten Manggarai' }}</p>
                        </div>
                    </div>

                    <div class="lg:col-span-8 lg:border-l lg:border-slate-200 lg:pl-8">
                        <blockquote class="text-lg sm:text-xl font-medium text-[#123B63] leading-relaxed">
                            “{{ trim($fs['quote_text'] ?? 'Data statistik yang akurat, mutakhir, dan objektif adalah fondasi utama bagi perencanaan, penentuan arah kebijakan, serta evaluasi pembangunan berkelanjutan demi kesejahteraan seluruh masyarakat.', '\"') }}”
                        </blockquote>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
<!-- END: QuoteSection -->
