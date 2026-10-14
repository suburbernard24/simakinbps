<!-- BEGIN: MainDataGrid -->
<section class="py-16 bg-gradient-to-b from-blue-600 to-brand-900 text-white relative" id="datapokok">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center max-w-2xl mx-auto mb-12">
<h2 class="text-3xl font-extrabold tracking-tight">DATA – DATA</h2>
<p class="text-xs sm:text-sm text-blue-100 mt-1">8 Indikator Strategis &amp; Statistik Kunci Kabupaten Manggarai</p>
</div>
<!-- 8 Metric Cards Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

<!-- Card 1: Penduduk -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_penduduk'] ?? '338.414' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Jumlah Penduduk (Jiwa)</p>
<button onclick="salinTeksData('Jumlah Penduduk Kabupaten Manggarai: {{ $fs['stat_penduduk'] ?? '338.414' }} Jiwa')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 2: Pertumbuhan Ekonomi -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Pertumbuhan Ekonomi</p>
<button onclick="salinTeksData('Pertumbuhan Ekonomi Kabupaten Manggarai: {{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 3: Tingkat Inflasi -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_inflasi'] ?? '2,15%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Tingkat Inflasi (y-on-y)</p>
<button onclick="salinTeksData('Tingkat Inflasi Kabupaten Manggarai: {{ $fs['stat_inflasi'] ?? '2,15%' }} (y-on-y)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 4: Persentase Kemiskinan -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_kemiskinan'] ?? '19,84%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Persentase Penduduk Miskin</p>
<button onclick="salinTeksData('Persentase Penduduk Miskin Kabupaten Manggarai: {{ $fs['stat_kemiskinan'] ?? '19,84%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 5: TPT -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_tpt'] ?? '2,82%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Tingkat Pengangguran (TPT)</p>
<button onclick="salinTeksData('Tingkat Pengangguran Terbuka (TPT) Kabupaten Manggarai: {{ $fs['stat_tpt'] ?? '2,82%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 6: IPM -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_ipm'] ?? '67,03' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Indeks Pembangunan Manusia</p>
<button onclick="salinTeksData('Indeks Pembangunan Manusia (IPM) Kabupaten Manggarai: {{ $fs['stat_ipm'] ?? '67,03' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 7: Gini Ratio -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 6l3 18h12l3-18H3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_gini'] ?? '0,312' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Gini Ratio (Ketimpangan Rendah)</p>
<button onclick="salinTeksData('Gini Ratio Kabupaten Manggarai: {{ $fs['stat_gini'] ?? '0,312' }} (Ketimpangan Rendah)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 8: PDRB Kapita -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">PDRB Per Kapita ADHB</p>
<button onclick="salinTeksData('PDRB Per Kapita Kabupaten Manggarai: {{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }} (ADHB)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

</div>
</div>
</section>
<!-- END: MainDataGrid -->

