<!-- BEGIN: QuickServicesGrid (4 Layanan Utama BPS Manggarai dalam Lingkaran) -->
<section class="bg-white border-b border-slate-200 py-14 sm:py-16" data-purpose="layanan-bps" id="layanan-bps">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 pb-5 border-b border-slate-200">
            <p class="text-xs font-semibold text-[#1E5B91] mb-1">Pelayanan Statistik Terpadu</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">Layanan Utama BPS Manggarai</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1.5 max-w-2xl leading-relaxed">
                Pilih kanal layanan sesuai kebutuhan: konsultasi data, perpustakaan digital, rekomendasi survei, atau data mikro.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 items-stretch">
            <!-- 1. Layanan Konsultasi / Permintaan Data (Form Booking Online 24/7) -->
            <div class="flex flex-col items-center text-center h-full group">
                <button type="button" onclick="bukaModalKonsultasi()" aria-label="Buka Form Layanan Konsultasi dan Permintaan Data" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-36 h-36 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_konsultasi.png') }}" alt="Layanan Konsultasi / Permintaan Data" width="144" height="144" class="w-full h-full object-cover">
                    </div>
                </button>

                <button type="button" onclick="bukaModalKonsultasi()" class="text-center mt-5">
                    <h3 class="font-bold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Konsultasi / Permintaan Data
                    </h3>
                </button>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed max-w-[240px]">
                    Form pemesanan jadwal konsultasi statistik dan permintaan data resmi BPS.
                </p>

                <div class="mt-auto pt-5 w-full flex justify-center">
                    <button type="button" onclick="bukaModalKonsultasi()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                        <i class="fa-solid fa-calendar-plus text-xs" aria-hidden="true"></i>
                        <span>Isi Form Booking Konsultasi</span>
                    </button>
                </div>
            </div>

            <!-- 2. Layanan Perpustakaan Digital (Direct OPAC) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" aria-label="Buka Layanan Perpustakaan Digital OPAC" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-36 h-36 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_perpustakaan.png') }}" alt="Layanan Perpustakaan Digital" width="144" height="144" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="text-center mt-5">
                    <h3 class="font-bold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Perpustakaan Digital
                    </h3>
                </a>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed max-w-[240px]">
                    Katalog Publik Akses Online (OPAC) Perpustakaan BPS untuk publikasi &amp; sensus.
                </p>

                <div class="mt-auto pt-5 w-full flex justify-center">
                    <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[40px] rounded-md bg-white hover:bg-[#123B63] text-[#123B63] hover:text-white border border-slate-200 text-xs font-semibold transition-colors">
                        <span>Akses Perpustakaan OPAC</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Layanan Rekomendasi Statistik (Direct ROMANTIK) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" aria-label="Buka Portal ROMANTIK Rekomendasi Statistik" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-36 h-36 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_romantik.png') }}" alt="Layanan Rekomendasi Statistik" width="144" height="144" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="text-center mt-5">
                    <h3 class="font-bold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Rekomendasi Statistik
                    </h3>
                </a>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed max-w-[240px]">
                    Portal ROMANTIK Online permohonan rekomendasi kegiatan statistik sektoral OPD.
                </p>

                <div class="mt-auto pt-5 w-full flex justify-center">
                    <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[40px] rounded-md bg-white hover:bg-[#123B63] text-[#123B63] hover:text-white border border-slate-200 text-xs font-semibold transition-colors">
                        <span>Akses Portal ROMANTIK</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- 4. Layanan Pembelian Data Mikro/Peta (Direct SILASTIK BPS Provinsi) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" aria-label="Buka Portal SILASTIK Pembelian Data Mikro dan Peta" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-36 h-36 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_silastik.png') }}" alt="Pembelian Data Mikro / Peta" width="144" height="144" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="text-center mt-5">
                    <h3 class="font-bold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Pembelian Data Mikro / Peta
                    </h3>
                </a>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed max-w-[240px]">
                    Portal SILASTIK resmi permohonan data mikro dan peta digital tematik PNBP.
                </p>

                <div class="mt-auto pt-5 w-full flex justify-center">
                    <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[40px] rounded-md bg-white hover:bg-[#123B63] text-[#123B63] hover:text-white border border-slate-200 text-xs font-semibold transition-colors">
                        <span>Akses Portal SILASTIK</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: QuickServicesGrid -->
