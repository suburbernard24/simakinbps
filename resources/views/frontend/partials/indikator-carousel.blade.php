<!-- BEGIN: KeyIndicatorsCarousel -->
<section class="py-12 bg-gradient-to-br from-brand-900 via-blue-900 to-indigo-950 text-white relative overflow-hidden border-b border-slate-200" id="indikator">
<!-- Decorative background elements -->
<div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
<!-- Section Header -->
<div class="text-center max-w-3xl mx-auto mb-10">
<h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Indikator Terkini Kabupaten Manggarai</h2>
<p class="text-xs sm:text-sm text-blue-200 mt-2 leading-relaxed">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai. Klik kartu untuk membuka dan mengeksplorasi langsung di Tabel Dinamis BPS.</p>
</div>
<!-- Carousel Container with Prev/Next buttons -->
<div class="relative flex items-center">
<!-- Prev Button -->
<button onclick="scrollIndicatorCarousel(-1)" aria-label="Previous Indicator" class="absolute -left-3 sm:-left-5 z-20 w-11 h-11 rounded-full bg-white text-blue-700 shadow-xl border border-slate-200 flex items-center justify-center hover:bg-blue-50 hover:scale-105 transition-all focus:outline-none">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
</button>
<!-- Cards Carousel Grid -->
<div id="indicator-carousel" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 w-full px-2 sm:px-6">
@forelse($indikatorsList as $ind)
<div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg hover:shadow-xl hover:border-blue-400 hover:scale-105 transition-all flex flex-col justify-between text-center min-h-[300px] group">
<div>
<div class="flex items-center justify-between mb-2">
<span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $ind->badge_text ?: 'Tabel Dinamis' }}</span>
<span class="text-[10px] text-slate-400 font-medium">{{ $ind->periode }}</span>
</div>
<div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"></path>
<path d="M19 14v7M15 17v4M11 19v2M7 21v0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"></path>
</svg>
</div>
<h3 class="text-xs sm:text-sm font-bold text-brand-900 leading-snug px-1">{{ $ind->judul }} @if($ind->sub_judul)<br><span class="font-semibold text-blue-700">{{ $ind->sub_judul }}</span>@endif</h3>
</div>
<div class="my-auto py-2">
<div class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">{{ $ind->nilai }}</div>
<p class="text-xs text-slate-500 font-medium mt-0.5">{{ $ind->deskripsi ?: 'BPS Kabupaten Manggarai' }}</p>
</div>
<div class="pt-3 border-t border-slate-100">
<a class="w-full py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" href="{{ $ind->link_tabel ?: '#datapokok' }}" target="_blank">
<span class="">Buka Tabel Dinamis ↗</span>
</a>
</div>
</div>
@empty
<div class="col-span-full text-center py-8 text-blue-200 text-xs">Belum ada indikator yang ditampilkan.</div>
@endforelse
</div>
<!-- Next Button -->
<button onclick="scrollIndicatorCarousel(1)" aria-label="Next Indicator" class="absolute -right-3 sm:-right-5 z-20 w-11 h-11 rounded-full bg-white text-blue-700 shadow-xl border border-slate-200 flex items-center justify-center hover:bg-blue-50 hover:scale-105 transition-all focus:outline-none">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
</button>
</div>
</div>
</section>
<!-- END: KeyIndicatorsCarousel -->

