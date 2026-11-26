<!-- BEGIN: StrategicDataSection -->
<section class="py-16 sm:py-20 bg-slate-50/70 border-b border-slate-200/70 relative" data-purpose="strategic-data" id="data-strategis">
    <!-- Anchor aliases for older links & test suites -->
    <div id="datapokok" class="absolute -top-20"></div>
    <div id="data-pokok" class="absolute -top-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header (NTT BPS Portal Style) -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#087E8B]/10 text-[#087E8B] tracking-wide uppercase mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#087E8B]"></span>
                    Data Strategis BPS
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#153448] tracking-tight">
                    Data Strategis Kabupaten Manggarai
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
                    Indikator makro dan statistik kunci pembangunan daerah merujuk pada publikasi resmi <strong class="text-slate-800">Booklet Statistik Kabupaten Manggarai 2021</strong>.
                </p>
            </div>

            <!-- Booklet Link Button -->
            <div class="shrink-0 flex items-center gap-2">
                <a href="https://manggaraikab.bps.go.id/id/publication/2021/10/26/0953c00ae1dee07d294825e8/booklet-statistik-kabupaten-manggarai-2021.html" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-[#087E8B] hover:text-[#153448] font-semibold text-xs border border-slate-200 shadow-2xs transition-all duration-150">
                    <i class="fa-solid fa-book text-sky-600"></i>
                    <span>Unduh Booklet Statistik 2021</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                </a>
            </div>
        </div>

        <!-- Category Filter Tabs (Sesuai Tampilan ntt.bps.go.id) -->
        <div class="flex flex-wrap items-center gap-2 mb-8 pb-2 border-b border-slate-200/80">
            <button type="button" onclick="filterDataStrategis('semua', this)" class="data-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-[#087E8B] text-white shadow-xs cursor-pointer transition-all">
                Semua Indikator (8)
            </button>
            <button type="button" onclick="filterDataStrategis('sosial', this)" class="data-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Sosial &amp; Kependudukan
            </button>
            <button type="button" onclick="filterDataStrategis('ekonomi', this)" class="data-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Ekonomi &amp; PDRB
            </button>
            <button type="button" onclick="filterDataStrategis('kesejahteraan', this)" class="data-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Ketenagakerjaan &amp; Kesejahteraan
            </button>
        </div>

        <!-- 8 Strategic Indicator Cards Grid (NTT BPS Card Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Jumlah Penduduk (Sosial) -->
            <div class="data-card-item data-cat-sosial bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-100">
                            Sosial &amp; Kependudukan
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            SP2020 / Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Jumlah Penduduk</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_penduduk'] ?? '338.414' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">Jiwa</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Laki-laki:</span>
                            <span class="font-mono font-semibold text-slate-700">169.347 Jiwa</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Perempuan:</span>
                            <span class="font-mono font-semibold text-slate-700">169.067 Jiwa</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Jumlah Penduduk Kabupaten Manggarai: {{ $fs['stat_penduduk'] ?? '338.414' }} Jiwa (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 2. Laju Pertumbuhan Ekonomi (Ekonomi) -->
            <div class="data-card-item data-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            Ekonomi &amp; PDRB
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Pertumbuhan Ekonomi (PDRB)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">y-on-y</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Status Tren:</span>
                            <span class="font-semibold text-emerald-600 inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i>
                                Positif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Basis Hitung:</span>
                            <span class="text-slate-700">ADHK 2010</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Laju Pertumbuhan Ekonomi Manggarai: {{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 3. Tingkat Inflasi (Ekonomi) -->
            <div class="data-card-item data-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                            Ekonomi &amp; Harga
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Tingkat Inflasi (IHK)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_inflasi'] ?? '2,15%' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">y-on-y</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Kondisi Harga:</span>
                            <span class="font-semibold text-amber-700">Terkendali &amp; Stabil</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kelompok Utama:</span>
                            <span class="text-slate-700">Makanan &amp; Energi</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Tingkat Inflasi Manggarai: {{ $fs['stat_inflasi'] ?? '2,15%' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 4. Persentase Penduduk Miskin (Sosial) -->
            <div class="data-card-item data-cat-sosial bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                            Sosial &amp; Kemiskinan
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Susenas / Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Persentase Penduduk Miskin (P0)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_kemiskinan'] ?? '19,84%' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">%</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Garis Kemiskinan:</span>
                            <span class="font-mono font-semibold text-slate-700">Rp 397.436/kap/bln</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kedalaman (P1):</span>
                            <span class="text-slate-700 font-mono">2,87</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Persentase Penduduk Miskin Manggarai: {{ $fs['stat_kemiskinan'] ?? '19,84%' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 5. Tingkat Pengangguran Terbuka (Kesejahteraan) -->
            <div class="data-card-item data-cat-kesejahteraan bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Ketenagakerjaan
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Sakernas / Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Tingkat Pengangguran Terbuka (TPT)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_tpt'] ?? '2,82%' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">%</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Partisipasi Kerja (TPAK):</span>
                            <span class="font-mono font-semibold text-slate-700">74,68%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sektor Dominan:</span>
                            <span class="text-slate-700">Pertanian</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Tingkat Pengangguran Terbuka (TPT) Manggarai: {{ $fs['stat_tpt'] ?? '2,82%' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 6. Indeks Pembangunan Manusia (Sosial) -->
            <div class="data-card-item data-cat-sosial bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            Pembangunan Manusia
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Indeks Pembangunan Manusia (IPM)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_ipm'] ?? '67,03' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">Poin</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Klasifikasi:</span>
                            <span class="font-semibold text-blue-700">Status Sedang</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Umur Harapan Hidup:</span>
                            <span class="font-mono font-semibold text-slate-700">68,96 Tahun</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Indeks Pembangunan Manusia (IPM) Manggarai: {{ $fs['stat_ipm'] ?? '67,03' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 7. Gini Ratio (Kesejahteraan) -->
            <div class="data-card-item data-cat-kesejahteraan bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-100">
                            Ketimpangan
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">Gini Ratio (Ketimpangan)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_gini'] ?? '0,312' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">Indeks</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Taraf Ketimpangan:</span>
                            <span class="font-semibold text-teal-700">Rendah (&lt; 0,4)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Distribusi:</span>
                            <span class="text-slate-700">Merata</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('Gini Ratio Manggarai: {{ $fs['stat_gini'] ?? '0,312' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>

            <!-- 8. PDRB Per Kapita (Ekonomi) -->
            <div class="data-card-item data-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 hover:border-[#087E8B]/50 shadow-xs hover:shadow-lg transition-all duration-300 p-5 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-[#087E8B]
                <div>
                    <div class="flex items-center justify-between text-xs mb-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-100">
                            Pendapatan Daerah
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                            Booklet 2021
                        </span>
                    </div>

                    <h3 class="text-xs font-semibold text-slate-600 mb-1">PDRB Per Kapita (ADHB)</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-extrabold font-mono text-[#153448] tracking-tight">
                            {{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">/Tahun</span>
                    </div>

                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1 text-[11px] text-slate-500">
                        <div class="flex justify-between">
                            <span>Total PDRB ADHB:</span>
                            <span class="font-mono font-semibold text-slate-700">Rp 9,35 Triliun</span>
                        </div>
                        <div class="flex justify-between">
                            <span>PDRB ADHK:</span>
                            <span class="font-mono text-slate-700">Rp 6,48 Triliun</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <button onclick="salinTeksData('PDRB Per Kapita ADHB Manggarai: {{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }} (Booklet Statistik 2021)')" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-50 hover:bg-[#087E8B] text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-colors duration-150 cursor-pointer shadow-2xs">
                        <i class="fa-regular fa-copy text-[11px]"></i>
                        <span>Salin Data</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: StrategicDataSection -->
