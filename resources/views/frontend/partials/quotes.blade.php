<!-- BEGIN: QuotesOfTheDaySection -->
<section class="py-14 bg-blue-600 text-white relative overflow-hidden">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
<span class="text-xs font-bold tracking-widest uppercase text-blue-200">QUOTES OF THE DAY</span>

@if($quotesList->isNotEmpty())
<div id="quotes-carousel-container">
    @foreach($quotesList as $index => $qItem)
    <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
        <!-- Leader Avatar -->
        <div class="my-6">
            <div class="w-20 h-20 rounded-full border-4 border-white/40 shadow-lg mx-auto overflow-hidden bg-slate-300">
                <img alt="{{ $qItem->nama }}" class="w-full h-full object-cover" src="{{ $qItem->foto_url }}">
            </div>
            <div class="mt-3">
                <h4 class="text-base font-bold text-white tracking-wide">{{ $qItem->nama }}</h4>
                <p class="text-xs text-blue-200 font-medium">{{ $qItem->jabatan }}</p>
            </div>
        </div>
        <blockquote class="text-base sm:text-xl font-medium italic text-blue-50 leading-relaxed max-w-3xl mx-auto">
            "{{ trim($qItem->quote, '\"') }}"
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
<div class="my-6">
    <div class="w-20 h-20 rounded-full border-4 border-white/40 shadow-lg mx-auto overflow-hidden bg-slate-300">
        <img alt="{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}" class="w-full h-full object-cover" src="{{ isset($fs['img_quote_avatar']) ? asset('storage/' . $fs['img_quote_avatar']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjXZA9MLptXHc4LghoJ0kijRke5AbNhNWkkCx1xTrmprh9StVUt9Q6xOhnsMWvUhNjiGK5UxXHLEzMnmMNm6BQlVWJMmdQAhyYP_Fvo03iCpoUgxxlDCiGxWpMuQOG98pXOD1nugLcPIzPemY1fQGA_K-0F0BbNO3UKS83gx_AfzVDR8tbvD5wZTd3hEtfzEOycA4oWrh3rTW5vJRAm1IQtTnDkXZTFFIxOfNJrlLf8ikRWaLhNCqN' }}">
    </div>
    <div class="mt-3">
        <h4 class="text-base font-bold text-white tracking-wide">{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}</h4>
        <p class="text-xs text-blue-200 font-medium">{{ $fs['quote_jabatan'] ?? 'KEPALA BPS KABUPATEN MANGGARAI' }}</p>
    </div>
</div>
<blockquote class="text-base sm:text-xl font-medium italic text-blue-50 leading-relaxed max-w-3xl mx-auto">
    "{{ trim($fs['quote_text'] ?? 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.', '\"') }}"
</blockquote>
@endif

</div>
</section>


