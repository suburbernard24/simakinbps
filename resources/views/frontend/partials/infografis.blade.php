<!-- BEGIN: InfographicsGallery -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="infografis">
<div class="text-center max-w-2xl mx-auto mb-10">
<h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">GALERI INFOGRAFIS</h2>
<p class="text-xs sm:text-sm text-slate-500 mt-1">Sajian Visualisasi Data Statistik Menarik, Ringkas, dan Edukatif</p>
<!-- Filter Chips -->
<div class="flex flex-wrap items-center justify-center gap-2 mt-6">
<button onclick="filterInfografis('semua', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm">Semua Infografis</button>
<button onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Transportasi</button>
<button onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Ekonomi &amp; PDRB</button>
<button onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Pertanian ST2023</button>
<button onclick="filterInfografis('sosial', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Sosial &amp; Pendidikan</button>
</div>
</div>
<!-- Dynamic Infographic Visual Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

@forelse($infografisList as $info)
<div class="info-card-item info-cat-{{ strtolower($info->kategori) }} bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all group flex flex-col justify-between">
@if($info->image_url)
    <div class="h-64 relative overflow-hidden bg-slate-900 group">
        <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @if($info->badge_text)
        <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-xs">{{ $info->badge_text }}</span>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex flex-col justify-end p-4 text-white">
            <h4 class="font-extrabold text-sm line-clamp-2">{{ $info->judul }}</h4>
        </div>
    </div>
@else
    <div class="h-64 bg-gradient-to-br {{ $info->bg_gradient ?: 'from-blue-600 to-indigo-700' }} text-white p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between text-xs">
            <span class="font-bold tracking-wider">BPS MANGGARAI</span>
            @if($info->badge_text)
            <span class="bg-black/20 px-2 py-0.5 rounded text-[10px]">{{ $info->badge_text }}</span>
            @endif
        </div>
        <div class="text-center my-auto">
            <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-white/20 flex items-center justify-center">
                @if($info->icon == 'truck')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'chart-pie')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'tree')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 12h5v8h6v-8h5L12 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'graduation-cap')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'users')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'hotel')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @else
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @endif
            </div>
            <h4 class="font-extrabold text-base uppercase leading-snug">{{ $info->judul }}</h4>
            @if(!empty($info->data_points_array))
            <div class="mt-3 bg-white/10 rounded-lg p-2 text-xs text-left max-w-xs mx-auto">
                @foreach($info->data_points_array as $dp)
                <div class="flex justify-between py-0.5"><span class="">{{ $dp['label'] ?? '' }}</span><span class="font-bold">{{ $dp['value'] ?? '' }}</span></div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="flex justify-between text-[10px] text-white/80 border-t border-white/20 pt-2">
            <span class="">{{ $info->sumber ? 'Sumber: ' . $info->sumber : 'BPS Kabupaten Manggarai' }}</span>
            <span class="">{{ $info->tahun ?? '' }}</span>
        </div>
    </div>
@endif
    <div class="p-4 flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $info->judul }}</h3>
        <a href="{{ $info->link_url ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1 cursor-pointer shrink-0">
            Lihat
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>
    </div>
</div>
@empty
<div class="col-span-full py-12 text-center text-slate-400 text-sm">
    Belum ada infografis yang ditampilkan.
</div>
@endforelse

</div>
<!-- View All Button -->
<div class="text-center mt-10">
<a href="https://manggaraikab.bps.go.id" target="_blank" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:border-blue-600 hover:text-blue-600 font-semibold text-xs transition-colors inline-flex items-center gap-2">
<span class="">LEBIH BANYAK INFOGRAFIS</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
</div>
</section>
<!-- END: InfographicsGallery -->

