<!-- BEGIN: StrategicDataSection -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-200/70 relative" data-purpose="strategic-data" id="data-pokok">
    <!-- Anchor alias for older links -->
    <div id="datapokok" class="absolute -top-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#0B4F82]/10 text-[#0B4F82] tracking-wide uppercase">
                Kompilasi Utama
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#172B4D] tracking-tight mt-2">
                Data Strategis Kabupaten Manggarai
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed">
                8 Indikator Pembangunan & Statistik Kunci untuk Perencanaan, Evaluasi, dan Riset Daerah
            </p>
        </div>

        <!-- 8 Indicator Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Jumlah Penduduk -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_penduduk'] ?? '338.414' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Jumlah Penduduk (Jiwa)</p>
                </div>
                <button onclick="salinTeksData('Jumlah Penduduk: {{ $fs['stat_penduduk'] ?? '338.414' }} Jiwa')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 2: Pertumbuhan Ekonomi -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Pertumbuhan Ekonomi (y-on-y)</p>
                </div>
                <button onclick="salinTeksData('Pertumbuhan Ekonomi: {{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 3: Tingkat Inflasi -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_inflasi'] ?? '2,15%' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Tingkat Inflasi (y-on-y)</p>
                </div>
                <button onclick="salinTeksData('Tingkat Inflasi: {{ $fs['stat_inflasi'] ?? '2,15%' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 4: Persentase Penduduk Miskin -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_kemiskinan'] ?? '19,84%' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Persentase Penduduk Miskin</p>
                </div>
                <button onclick="salinTeksData('Persentase Penduduk Miskin: {{ $fs['stat_kemiskinan'] ?? '19,84%' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 5: TPT -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_tpt'] ?? '2,82%' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Tingkat Pengangguran Terbuka</p>
                </div>
                <button onclick="salinTeksData('Tingkat Pengangguran Terbuka (TPT): {{ $fs['stat_tpt'] ?? '2,82%' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 6: IPM -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_ipm'] ?? '67,03' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Indeks Pembangunan Manusia</p>
                </div>
                <button onclick="salinTeksData('Indeks Pembangunan Manusia (IPM): {{ $fs['stat_ipm'] ?? '67,03' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 7: Gini Ratio -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_gini'] ?? '0,312' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Gini Ratio (Ketimpangan)</p>
                </div>
                <button onclick="salinTeksData('Gini Ratio: {{ $fs['stat_gini'] ?? '0,312' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>

            <!-- Metric 8: PDRB Per Kapita -->
            <div class="bg-[#F4F7FB] hover:bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-[#0B4F82]/40 shadow-xs hover:shadow-md transition-all duration-200 text-center flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-xl bg-[#0B4F82]/10 group-hover:bg-[#0B4F82] text-[#0B4F82] group-hover:text-white flex items-center justify-center mb-3 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-[#172B4D] tracking-tight">{{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }}</div>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">PDRB Per Kapita ADHB</p>
                </div>
                <button onclick="salinTeksData('PDRB Per Kapita ADHB: {{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }}')" class="mt-5 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-white group-hover:bg-[#0B4F82] text-slate-700 group-hover:text-white border border-slate-200 group-hover:border-[#0B4F82] text-[11px] font-semibold transition-colors duration-150 cursor-pointer shadow-2xs" data-purpose="copy-action">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Salin Data</span>
                </button>
            </div>
        </div>
    </div>
</section>
<!-- END: StrategicDataSection -->
