<!-- BEGIN: QuickServicesGrid (4 Layanan Utama BPS Manggarai dalam Lingkaran) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 relative z-10" data-purpose="layanan-bps" id="layanan-bps">
    <!-- 4 Services Circular Grid (Reference: bps-manggarai-site) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6">
        <!-- 1. Layanan Konsultasi / Permintaan Data (Form Booking Online 24/7) -->
        <div class="flex flex-col items-center text-center group">
            <button type="button" onclick="bukaModalKonsultasi()" class="group-hover:scale-105 transition-all duration-300 focus:outline-none cursor-pointer">
                <!-- Outer Circular Ring -->
                <div class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 rounded-full p-2 bg-gradient-to-tr from-[#087E8B] via-[#36A6A0] to-emerald-400 shadow-md group-hover:shadow-2xl group-hover:shadow-[#087E8B]/25 transition-all duration-300 flex items-center justify-center">
                    <!-- Inner Circular Image -->
                    <div class="w-full h-full rounded-full overflow-hidden bg-white p-1 border-2 border-white/90 shadow-inner flex items-center justify-center">
                        <img src="{{ asset('images/layanan/layanan_konsultasi.png') }}" alt="Layanan Konsultasi / Permintaan Data" class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </button>

            <!-- Title & Description -->
            <button type="button" onclick="bukaModalKonsultasi()" class="focus:outline-none cursor-pointer text-center mt-4">
                <h3 class="font-bold text-slate-900 text-base group-hover:text-[#087E8B] transition-colors">
                    Layanan Konsultasi / Permintaan Data
                </h3>
            </button>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed max-w-[240px]">
                Form pemesanan jadwal konsultasi statistik dan permintaan data resmi BPS.
            </p>

            <!-- Action Button -->
            <div class="mt-4">
                <button type="button" onclick="bukaModalKonsultasi()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#087E8B] hover:bg-[#153448] text-white text-xs font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                    <i class="fa-solid fa-calendar-plus text-xs"></i>
                    <span>Isi Form Booking Konsultasi</span>
                </button>
            </div>
        </div>

        <!-- 2. Layanan Perpustakaan Digital (Direct OPAC) -->
        <div class="flex flex-col items-center text-center group">
            <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="group-hover:scale-105 transition-all duration-300 focus:outline-none">
                <!-- Outer Circular Ring -->
                <div class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 rounded-full p-2 bg-gradient-to-tr from-[#153448] via-[#087E8B] to-sky-400 shadow-md group-hover:shadow-2xl group-hover:shadow-[#087E8B]/25 transition-all duration-300 flex items-center justify-center">
                    <!-- Inner Circular Image -->
                    <div class="w-full h-full rounded-full overflow-hidden bg-white p-1 border-2 border-white/90 shadow-inner flex items-center justify-center">
                        <img src="{{ asset('images/layanan/layanan_perpustakaan.png') }}" alt="Layanan Perpustakaan Digital" class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </a>

            <!-- Title & Description -->
            <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="focus:outline-none text-center mt-4">
                <h3 class="font-bold text-slate-900 text-base group-hover:text-[#087E8B] transition-colors">
                    Layanan Perpustakaan Digital
                </h3>
            </a>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed max-w-[240px]">
                Katalog Publik Akses Online (OPAC) Perpustakaan BPS untuk publikasi &amp; sensus.
            </p>

            <!-- Action Button -->
            <div class="mt-4">
                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#153448] hover:bg-[#087E8B] text-white text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Perpustakaan OPAC</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 3. Layanan Rekomendasi Statistik (Direct ROMANTIK) -->
        <div class="flex flex-col items-center text-center group">
            <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="group-hover:scale-105 transition-all duration-300 focus:outline-none">
                <!-- Outer Circular Ring -->
                <div class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 rounded-full p-2 bg-gradient-to-tr from-[#153448] via-indigo-600 to-[#36A6A0] shadow-md group-hover:shadow-2xl group-hover:shadow-indigo-500/25 transition-all duration-300 flex items-center justify-center">
                    <!-- Inner Circular Image -->
                    <div class="w-full h-full rounded-full overflow-hidden bg-white p-1 border-2 border-white/90 shadow-inner flex items-center justify-center">
                        <img src="{{ asset('images/layanan/layanan_romantik.png') }}" alt="Layanan Rekomendasi Statistik" class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </a>

            <!-- Title & Description -->
            <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="focus:outline-none text-center mt-4">
                <h3 class="font-bold text-slate-900 text-base group-hover:text-[#153448] transition-colors">
                    Layanan Rekomendasi Statistik
                </h3>
            </a>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed max-w-[240px]">
                Portal ROMANTIK Online permohonan rekomendasi kegiatan statistik sektoral OPD.
            </p>

            <!-- Action Button -->
            <div class="mt-4">
                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#153448] hover:bg-[#087E8B] text-white text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Portal ROMANTIK</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- 4. Layanan Pembelian Data Mikro/Peta (Direct SILASTIK BPS Provinsi) -->
        <div class="flex flex-col items-center text-center group">
            <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="group-hover:scale-105 transition-all duration-300 focus:outline-none">
                <!-- Outer Circular Ring -->
                <div class="w-36 h-36 sm:w-40 sm:h-40 md:w-44 md:h-44 rounded-full p-2 bg-gradient-to-tr from-[#D9972E] via-[#F0B44D] to-amber-300 shadow-md group-hover:shadow-2xl group-hover:shadow-[#F0B44D]/25 transition-all duration-300 flex items-center justify-center">
                    <!-- Inner Circular Image -->
                    <div class="w-full h-full rounded-full overflow-hidden bg-white p-1 border-2 border-white/90 shadow-inner flex items-center justify-center">
                        <img src="{{ asset('images/layanan/layanan_silastik.png') }}" alt="Pembelian Data Mikro / Peta" class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </a>

            <!-- Title & Description -->
            <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="focus:outline-none text-center mt-4">
                <h3 class="font-bold text-slate-900 text-base group-hover:text-[#D9972E] transition-colors">
                    Pembelian Data Mikro / Peta
                </h3>
            </a>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed max-w-[240px]">
                Portal SILASTIK resmi permohonan data mikro dan peta digital tematik PNBP.
            </p>

            <!-- Action Button -->
            <div class="mt-4">
                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-[#F0B44D] hover:bg-[#D9972E] text-slate-900 text-xs font-bold shadow-xs hover:shadow transition-all">
                    <span>Akses Portal SILASTIK</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- END: QuickServicesGrid -->
