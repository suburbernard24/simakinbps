<!-- BEGIN: QuickServicesGrid (4 Kotak Layanan BPS Manggarai) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 relative z-10" data-purpose="layanan-bps" id="layanan-bps">
    <!-- 4 Services Cards Grid (Reference: bps-manggarai-site) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- 1. Layanan Konsultasi / Permintaan Data (Form Booking Online 24/7) -->
        <div class="bg-white rounded-2xl border-2 border-slate-200/90 hover:border-emerald-500 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
            <div>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-xl shrink-0 transition-all duration-300 shadow-xs border border-emerald-100">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Booking Siaga 24/7
                    </span>
                </div>

                <h3 class="font-bold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">
                    Layanan Konsultasi / Permintaan Data
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Form pemesanan jadwal konsultasi statistik dan permintaan data resmi BPS. Dapat diakses kapan saja selama 24 jam sehari, 7 hari seminggu.
                </p>

                <!-- Service Features -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                        <span>Tatap Muka, Zoom, atau Tertulis</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                        <span>Pilihan Dalam / Luar Jam Layanan</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <button type="button" onclick="bukaModalKonsultasi()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                    <i class="fa-solid fa-calendar-plus text-xs"></i>
                    <span>Isi Form Booking Konsultasi</span>
                </button>
            </div>
        </div>

        <!-- 2. Layanan Perpustakaan Digital (Direct OPAC) -->
        <div class="bg-white rounded-2xl border-2 border-slate-200/90 hover:border-sky-500 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-500 to-[#0B4F82]"></div>
            <div>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="w-13 h-13 rounded-2xl bg-sky-50 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center text-xl shrink-0 transition-all duration-300 shadow-xs border border-sky-100">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-[#0B4F82] bg-sky-50 px-2.5 py-1 rounded-full border border-sky-100">
                        <i class="fa-solid fa-globe text-[10px]"></i>
                        OPAC BPS RI
                    </span>
                </div>

                <h3 class="font-bold text-slate-900 text-base group-hover:text-[#0B4F82] transition-colors">
                    Layanan Perpustakaan Digital
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Katalog Publik Akses Online (OPAC) Perpustakaan BPS untuk menelusuri ribuan judul publikasi, buku statistik daerah, dan laporan sensus terlengkap.
                </p>

                <!-- Service Features -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-sky-600 text-[10px]"></i>
                        <span>Akses Koleksi Buku Bebas Biaya</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-sky-600 text-[10px]"></i>
                        <span>Katalog Seluruh Wilayah BPS</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#0B4F82] hover:bg-[#083E67] text-white text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Perpustakaan OPAC</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 3. Layanan Rekomendasi Statistik (Direct ROMANTIK) -->
        <div class="bg-white rounded-2xl border-2 border-slate-200/90 hover:border-indigo-500 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            <div>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="w-13 h-13 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center text-xl shrink-0 transition-all duration-300 shadow-xs border border-indigo-100">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                        <i class="fa-solid fa-building-columns text-[10px]"></i>
                        OPD &amp; Instansi
                    </span>
                </div>

                <h3 class="font-bold text-slate-900 text-base group-hover:text-indigo-700 transition-colors">
                    Layanan Rekomendasi Statistik
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Portal ROMANTIK Online untuk permohonan rekomendasi kegiatan statistik sektoral oleh instansi pemerintah daerah dalam kerangka Satu Data Indonesia.
                </p>

                <!-- Service Features -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-indigo-500 text-[10px]"></i>
                        <span>Pemeriksaan Rancangan Survei OPD</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-indigo-500 text-[10px]"></i>
                        <span>Pemberian Surat Rekomendasi BPS</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Portal ROMANTIK</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 4. Layanan Pembelian Data Mikro/Peta (Direct SILASTIK BPS Provinsi) -->
        <div class="bg-white rounded-2xl border-2 border-slate-200/90 hover:border-amber-500 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500"></div>
            <div>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center text-xl shrink-0 transition-all duration-300 shadow-xs border border-amber-100">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        Admin BPS Provinsi
                    </span>
                </div>

                <h3 class="font-bold text-slate-900 text-base group-hover:text-amber-700 transition-colors">
                    Pembelian Data Mikro / Peta
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Portal SILASTIK resmi untuk permohonan data mikro (raw data mentah), tabulasi khusus, serta peta digital tematik berbayar sesuai ketentuan PNBP.
                </p>

                <!-- Service Features -->
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-[11px] text-slate-600">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-amber-500 text-[10px]"></i>
                        <span>Data Mikro Susenas, Sakernas, Sensus</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-amber-500 text-[10px]"></i>
                        <span>Peta Digital SHP Wilayah Statistik</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Portal SILASTIK</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- END: QuickServicesGrid -->
