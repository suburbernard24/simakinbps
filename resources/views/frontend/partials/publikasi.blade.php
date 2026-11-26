<!-- BEGIN: PublikasiSection -->
<section class="py-16 sm:py-20 bg-[#F7F9FA] border-b border-slate-200/70 relative" data-purpose="publications" id="publikasi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#087E8B]/10 text-[#087E8B] tracking-wide uppercase">
                Katalog Resmi BPS
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#153448] tracking-tight mt-2">
                Publikasi Statistik Terkini
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed">
                Koleksi publikasi resmi Badan Pusat Statistik Kabupaten Manggarai dengan akses unduh PDF dan link tabel dinamis
            </p>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <button type="button" onclick="filterPubCategory('semua', this)" class="pub-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-[#153448] text-white shadow-xs cursor-pointer transition-all">Semua Publikasi</button>
            <button type="button" onclick="filterPubCategory('dda', this)" class="pub-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Tahunan (DDA)</button>
            <button type="button" onclick="filterPubCategory('statda', this)" class="pub-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Statistik Daerah</button>
            <button type="button" onclick="filterPubCategory('pdrb', this)" class="pub-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">PDRB &amp; Ekonomi</button>
        </div>

        <!-- Publication Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <!-- Book 1: DDA 2024 -->
            <article class="pub-card-item pub-cat-dda bg-white rounded-2xl border border-slate-200/90 overflow-hidden flex flex-col justify-between hover:border-[#087E8B]/40 hover:shadow-md transition-all duration-200 group">
                <div>
                    <!-- Book Cover Simulation -->
                    <div class="p-6 bg-[#153448] text-white relative flex flex-col justify-between aspect-[16/10] border-b border-slate-800">
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-300">
                            <span class="tracking-widest">BPS MANGGARAI</span>
                            <span class="px-2 py-0.5 rounded bg-white/10 text-white font-semibold">EDISI RESMI 2024</span>
                        </div>
                        <div class="text-center my-auto py-2">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-white/10 flex items-center justify-center mb-2 text-sky-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </div>
                            <h4 class="font-bold text-base uppercase tracking-wider text-white">KABUPATEN MANGGARAI<br/>DALAM ANGKA</h4>
                            <p class="text-[11px] text-slate-300 mt-1 font-mono">Manggarai Regency in Figures 2024</p>
                        </div>
                        <div class="flex justify-between items-center text-[9px] text-slate-400 border-t border-white/10 pt-2 font-mono">
                            <span>KATALOG: 1102001.5310</span>
                            <span>PUBLIKASI: 53100.2401</span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-2.5">
                            <span class="flex items-center gap-1.5 font-medium text-slate-600">
                                <svg class="w-3.5 h-3.5 text-[#087E8B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                28 Feb 2024
                            </span>
                            <span class="font-mono text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded">PDF • 14.8 MB</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-base group-hover:text-[#087E8B] transition-colors line-clamp-2">
                            Kabupaten Manggarai Dalam Angka 2024
                        </h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                            Memuat data komprehensif geografi, iklim, pemerintahan, kependudukan, ketenagakerjaan, pertanian, dan perekonomian Kabupaten Manggarai.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F7F9FA] p-3.5 rounded-xl border border-slate-200/80 text-xs">
                            <span class="font-bold text-slate-800 block mb-2 flex items-center justify-between">
                                <span>Daftar Tabel Publikasi (142 Tabel)</span>
                                <span class="text-[10px] text-[#087E8B] font-mono font-semibold">XLSX/Web</span>
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel 1.1 Luas Wilayah &amp; Geografi</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel 2.3 Penduduk Menurut Kec.</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel 3.1 PDRB Lapangan Usaha</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-6 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 rounded-xl bg-[#087E8B] hover:bg-[#153448] text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1.5 shadow-xs" href="https://manggaraikab.bps.go.id/id/publication/2024/02/28/dda-manggarai-2024.html" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>

            <!-- Book 2: Statistik Daerah 2024 -->
            <article class="pub-card-item pub-cat-statda bg-white rounded-2xl border border-slate-200/90 overflow-hidden flex flex-col justify-between hover:border-[#087E8B]/40 hover:shadow-md transition-all duration-200 group">
                <div>
                    <!-- Book Cover Simulation -->
                    <div class="p-6 bg-[#153448] text-white relative flex flex-col justify-between aspect-[16/10] border-b border-slate-800">
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-300">
                            <span class="tracking-widest">BPS MANGGARAI</span>
                            <span class="px-2 py-0.5 rounded bg-white/10 text-white font-semibold">STATDA 2024</span>
                        </div>
                        <div class="text-center my-auto py-2">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-white/10 flex items-center justify-center mb-2 text-emerald-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </div>
                            <h4 class="font-bold text-base uppercase tracking-wider text-white">STATISTIK DAERAH<br/>KABUPATEN MANGGARAI</h4>
                            <p class="text-[11px] text-slate-300 mt-1 font-mono">Tahun 2024</p>
                        </div>
                        <div class="flex justify-between items-center text-[9px] text-slate-400 border-t border-white/10 pt-2 font-mono">
                            <span>KATALOG: 1101002.5310</span>
                            <span>PUBLIKASI: 53100.2405</span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-2.5">
                            <span class="flex items-center gap-1.5 font-medium text-slate-600">
                                <svg class="w-3.5 h-3.5 text-[#087E8B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                26 Sep 2024
                            </span>
                            <span class="font-mono text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded">PDF • 8.2 MB</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-base group-hover:text-[#087E8B] transition-colors line-clamp-2">
                            Statistik Daerah Kabupaten Manggarai 2024
                        </h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                            Ulasan deskriptif populer dan indikator terpilih yang membedah dinamika pembangunan daerah secara ringkas dan informatif.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F7F9FA] p-3.5 rounded-xl border border-slate-200/80 text-xs">
                            <span class="font-bold text-slate-800 block mb-2 flex items-center justify-between">
                                <span>Daftar Tematik (58 Tabel)</span>
                                <span class="text-[10px] text-[#087E8B] font-mono font-semibold">XLSX/Web</span>
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel Kesejahteraan Rakyat</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/makro-sosial-ekonomi.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel Harapan Hidup &amp; IPM</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/ipm-manggarai-komponen.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel Kemiskinan &amp; Gini</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/kemiskinan-manggarai-susenas.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-6 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 rounded-xl bg-[#087E8B] hover:bg-[#153448] text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1.5 shadow-xs" href="https://manggaraikab.bps.go.id/id/publication/2024/09/26/statistik-daerah-kabupaten-manggarai-2024.html" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>

            <!-- Book 3: PDRB Menurut Lapangan Usaha -->
            <article class="pub-card-item pub-cat-pdrb bg-white rounded-2xl border border-slate-200/90 overflow-hidden flex flex-col justify-between hover:border-[#087E8B]/40 hover:shadow-md transition-all duration-200 group">
                <div>
                    <!-- Book Cover Simulation -->
                    <div class="p-6 bg-[#153448] text-white relative flex flex-col justify-between aspect-[16/10] border-b border-slate-800">
                        <div class="flex items-center justify-between text-[10px] font-mono text-slate-300">
                            <span class="tracking-widest">BPS MANGGARAI</span>
                            <span class="px-2 py-0.5 rounded bg-white/10 text-white font-semibold">PDRB 2019-2023</span>
                        </div>
                        <div class="text-center my-auto py-2">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-white/10 flex items-center justify-center mb-2 text-sky-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </div>
                            <h4 class="font-bold text-base uppercase tracking-wider text-white">PRODUK DOMESTIK REGIONAL BRUTO</h4>
                            <p class="text-[11px] text-slate-300 mt-1 font-mono">Menurut Lapangan Usaha 2019 - 2023</p>
                        </div>
                        <div class="flex justify-between items-center text-[9px] text-slate-400 border-t border-white/10 pt-2 font-mono">
                            <span>KATALOG: 9302001.5310</span>
                            <span>PUBLIKASI: 53100.2409</span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-6">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-2.5">
                            <span class="flex items-center gap-1.5 font-medium text-slate-600">
                                <svg class="w-3.5 h-3.5 text-[#087E8B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                05 Apr 2024
                            </span>
                            <span class="font-mono text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded">PDF • 5.4 MB</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-base group-hover:text-[#087E8B] transition-colors line-clamp-2">
                            PDRB Kabupaten Manggarai Menurut Lapangan Usaha 2019-2023
                        </h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                            Menyajikan tabel-tabel PDRB ADHB dan ADHK 2010 beserta laju pertumbuhan sektor primer, sekunder, dan tersier di Manggarai.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F7F9FA] p-3.5 rounded-xl border border-slate-200/80 text-xs">
                            <span class="font-bold text-slate-800 block mb-2 flex items-center justify-between">
                                <span>Daftar Tabel Sektoral (34 Tabel)</span>
                                <span class="text-[10px] text-[#087E8B] font-mono font-semibold">XLSX/Web</span>
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel PDRB ADHB 17 Sektor Usaha</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel PDRB ADHK &amp; Pertumbuhan</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/laju-pertumbuhan-pdrb.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="truncate pr-2">• Tabel Distribusi Persentase PDRB</span>
                                    <a class="text-[#087E8B] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-6 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 rounded-xl bg-[#087E8B] hover:bg-[#153448] text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1.5 shadow-xs" href="https://manggaraikab.bps.go.id/id/publication/2024/04/05/pdrb-manggarai-2019-2023.html" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>
<!-- END: PublikasiSection -->
