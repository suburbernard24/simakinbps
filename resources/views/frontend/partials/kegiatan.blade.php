<!-- BEGIN: StatisticalActivitiesSection -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="kegiatan">
<div class="text-center max-w-2xl mx-auto mb-12">
<h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">KEGIATAN</h2>
<p class="text-xs sm:text-sm text-slate-500 mt-1">Dokumentasi dan Berita Kegiatan Terkini BPS Kabupaten Manggarai</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

@forelse($kegiatansList as $keg)
@php
    $bgBadge = match($keg->badge_color) {
        'purple' => 'bg-purple-50 text-purple-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        default => 'bg-blue-50 text-blue-600',
    };
    $textBadge = match($keg->badge_color) {
        'purple' => 'text-purple-600',
        'teal' => 'text-teal-600',
        'orange' => 'text-orange-600',
        'emerald' => 'text-emerald-600',
        default => 'text-blue-600',
    };
@endphp
<div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all flex flex-col justify-between">
<div>
<div class="flex items-center space-x-3 mb-4">
<div class="w-10 h-10 rounded-xl {{ $bgBadge }} flex items-center justify-center shrink-0">
    @if($keg->icon == 'users')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'book')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'comments')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'check-circle')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'bolt')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @else
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @endif
</div>
<div>
<span class="text-[10px] font-bold {{ $textBadge }} uppercase tracking-wide">{{ $keg->kategori }}</span>
<p class="text-[11px] text-slate-400">{{ $keg->tanggal_display ?? $keg->created_at->format('d M Y') }}</p>
</div>
</div>
<h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $keg->judul }}</h3>
<p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
    {{ $keg->deskripsi }}
</p>
</div>
<div class="mt-4 pt-3 border-t border-slate-100">
<a class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1" href="{{ $keg->link_url ?: '#' }}" {{ str_starts_with($keg->link_url ?? '', 'http') ? 'target="_blank"' : '' }}>
    Baca Selengkapnya
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
</div>
</div>
@empty
<div class="col-span-full py-8 text-center text-slate-400 text-xs">
    Belum ada kegiatan yang ditampilkan.
</div>
@endforelse

</div>
</section>


