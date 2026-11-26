<!-- BEGIN: PublikasiSection -->
<section class="py-14 sm:py-18 bg-white border-b border-slate-200" data-purpose="publications" id="publikasi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <p class="text-xs font-semibold text-[#1E5B91] mb-1.5">
                    Katalog Resmi BPS
                </p>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">
                    Publikasi Statistik Terkini
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1.5 max-w-2xl leading-relaxed">
                    Koleksi publikasi resmi Badan Pusat Statistik Kabupaten Manggarai dengan akses unduh PDF dan tautan tabel dinamis.
                </p>
            </div>
        </div>

        <!-- Categories Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 mb-8 pb-4 border-b border-slate-200">
            <button type="button" onclick="filterPubCategory('semua', this)" class="pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-semibold bg-[#123B63] text-white transition-colors">Semua Publikasi</button>
            <button type="button" onclick="filterPubCategory('dda', this)" class="pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-[#F4F7FA] transition-colors">Tahunan (DDA)</button>
            <button type="button" onclick="filterPubCategory('statda', this)" class="pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-[#F4F7FA] transition-colors">Statistik Daerah</button>
            <button type="button" onclick="filterPubCategory('pdrb', this)" class="pub-filter-btn px-4 py-2 min-h-[40px] rounded-md text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-[#F4F7FA] transition-colors">PDRB &amp; Ekonomi</button>
        </div>

        <!-- Publication Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Book 1: DDA 2024 -->
            <article class="pub-card-item pub-cat-dda bg-white rounded-lg border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-[#1E5B91] transition-colors">
                <div>
                    <!-- Catalog Header Band -->
                    <div class="p-5 bg-[#123B63] text-white">
                        <div class="flex items-center justify-between text-[11px] text-slate-300 mb-3">
                            <span class="font-semibold text-[#9BBBD9]">Edisi Resmi 2024</span>
                            <span class="tabular-nums">Katalog 1102001.5310</span>
                        </div>
                        <h2 class="font-bold text-base leading-snug text-white">
                            Kabupaten Manggarai Dalam Angka 2024
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Manggarai Regency in Figures 2024</p>
                    </div>

                    <!-- Content Details -->
                    <div class="p-5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                            <span class="font-medium text-slate-600">28 Feb 2024</span>
                            <span class="text-[11px] text-slate-600 bg-[#F4F7FA] border border-slate-200 px-2 py-0.5 rounded">PDF, 14.8 MB</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Memuat data komprehensif geografi, iklim, pemerintahan, kependudukan, ketenagakerjaan, pertanian, dan perekonomian Kabupaten Manggarai.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F4F7FA] p-3.5 rounded-md border border-slate-200 text-xs">
                            <span class="font-semibold text-[#123B63] block mb-2">
                                Daftar Tabel Publikasi (142 Tabel)
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel 1.1 Luas Wilayah &amp; Geografi</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel 2.3 Penduduk Menurut Kec.</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel 3.1 PDRB Lapangan Usaha</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-5 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold text-center transition-colors flex items-center justify-center gap-1.5" href="https://manggaraikab.bps.go.id/id/publication/2024/02/28/dda-manggarai-2024.html" target="_blank" rel="noopener noreferrer">
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 min-h-[40px] rounded-md bg-white hover:bg-[#F4F7FA] border border-slate-300 text-slate-700 text-xs font-semibold transition-colors flex items-center" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>

            <!-- Book 2: Statistik Daerah 2024 -->
            <article class="pub-card-item pub-cat-statda bg-white rounded-lg border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-[#1E5B91] transition-colors">
                <div>
                    <!-- Catalog Header Band -->
                    <div class="p-5 bg-[#123B63] text-white">
                        <div class="flex items-center justify-between text-[11px] text-slate-300 mb-3">
                            <span class="font-semibold text-[#9BBBD9]">Statda 2024</span>
                            <span class="tabular-nums">Katalog 1101002.5310</span>
                        </div>
                        <h2 class="font-bold text-base leading-snug text-white">
                            Statistik Daerah Kabupaten Manggarai 2024
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Publikasi Tahun 2024</p>
                    </div>

                    <!-- Content Details -->
                    <div class="p-5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                            <span class="font-medium text-slate-600">26 Sep 2024</span>
                            <span class="text-[11px] text-slate-600 bg-[#F4F7FA] border border-slate-200 px-2 py-0.5 rounded">PDF, 8.2 MB</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Ulasan deskriptif populer dan indikator terpilih yang membedah dinamika pembangunan daerah secara ringkas dan informatif.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F4F7FA] p-3.5 rounded-md border border-slate-200 text-xs">
                            <span class="font-semibold text-[#123B63] block mb-2">
                                Daftar Tematik (58 Tabel)
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel Kesejahteraan Rakyat</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/makro-sosial-ekonomi.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel Harapan Hidup &amp; IPM</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/ipm-manggarai-komponen.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel Kemiskinan &amp; Gini</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/kemiskinan-manggarai-susenas.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-5 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold text-center transition-colors flex items-center justify-center gap-1.5" href="https://manggaraikab.bps.go.id/id/publication/2024/09/26/statistik-daerah-kabupaten-manggarai-2024.html" target="_blank" rel="noopener noreferrer">
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 min-h-[40px] rounded-md bg-white hover:bg-[#F4F7FA] border border-slate-300 text-slate-700 text-xs font-semibold transition-colors flex items-center" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>

            <!-- Book 3: PDRB Menurut Lapangan Usaha -->
            <article class="pub-card-item pub-cat-pdrb bg-white rounded-lg border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-[#1E5B91] transition-colors">
                <div>
                    <!-- Catalog Header Band -->
                    <div class="p-5 bg-[#123B63] text-white">
                        <div class="flex items-center justify-between text-[11px] text-slate-300 mb-3">
                            <span class="font-semibold text-[#9BBBD9]">PDRB 2019-2023</span>
                            <span class="tabular-nums">Katalog 9302001.5310</span>
                        </div>
                        <h2 class="font-bold text-base leading-snug text-white">
                            PDRB Kabupaten Manggarai Menurut Lapangan Usaha 2019-2023
                        </h2>
                        <p class="text-xs text-slate-300 mt-1">Seri 2019 - 2023</p>
                    </div>

                    <!-- Content Details -->
                    <div class="p-5">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                            <span class="font-medium text-slate-600">05 Apr 2024</span>
                            <span class="text-[11px] text-slate-600 bg-[#F4F7FA] border border-slate-200 px-2 py-0.5 rounded">PDF, 5.4 MB</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Menyajikan tabel-tabel PDRB ADHB dan ADHK 2010 beserta laju pertumbuhan sektor primer, sekunder, dan tersier di Manggarai.
                        </p>

                        <!-- Included Tables Snippet -->
                        <div class="mt-4 bg-[#F4F7FA] p-3.5 rounded-md border border-slate-200 text-xs">
                            <span class="font-semibold text-[#123B63] block mb-2">
                                Daftar Tabel Sektoral (34 Tabel)
                            </span>
                            <ul class="space-y-1.5 text-slate-600 text-[11px]">
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel PDRB ADHB 17 Sektor Usaha</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel PDRB ADHK &amp; Pertumbuhan</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/laju-pertumbuhan-pdrb.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                                <li class="flex items-center justify-between gap-2">
                                    <span class="truncate">Tabel Distribusi Persentase PDRB</span>
                                    <a class="text-[#1E5B91] font-semibold hover:underline shrink-0" href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" rel="noopener noreferrer">Unduh XLS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom Actions -->
                <div class="p-5 pt-0 flex items-center gap-2">
                    <a class="flex-1 py-2.5 px-3 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold text-center transition-colors flex items-center justify-center gap-1.5" href="https://manggaraikab.bps.go.id/id/publication/2024/04/05/pdrb-manggarai-2019-2023.html" target="_blank" rel="noopener noreferrer">
                        <span>Unduh PDF</span>
                    </a>
                    <a class="py-2.5 px-3 min-h-[40px] rounded-md bg-white hover:bg-[#F4F7FA] border border-slate-300 text-slate-700 text-xs font-semibold transition-colors flex items-center" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank" rel="noopener noreferrer">
                        <span>Tabel Dinamis</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>
<!-- END: PublikasiSection -->
