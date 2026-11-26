<!-- BEGIN: QuickServicesGrid (4 Layanan Utama BPS Manggarai dalam Lingkaran) -->
<section class="bg-white border-b border-slate-200/80 py-14 sm:py-16" data-purpose="layanan-bps" id="layanan-bps">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center max-w-xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-[#123B63] tracking-tight">Layanan Utama BPS Manggarai</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                Pilih layanan sesuai kebutuhan data dan konsultasi statistik Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 items-stretch">
            <!-- 1. Layanan Konsultasi / Permintaan Data -->
            <div class="flex flex-col items-center text-center h-full group">
                <button type="button" onclick="bukaModalKonsultasi()" aria-label="Buka Form Layanan Konsultasi dan Permintaan Data" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_konsultasi.png') }}" alt="Layanan Konsultasi / Permintaan Data" width="128" height="128" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>
                </button>

                <button type="button" onclick="bukaModalKonsultasi()" class="text-center mt-4">
                    <h3 class="font-semibold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Konsultasi / Permintaan Data
                    </h3>
                </button>

                <div class="mt-auto pt-4">
                    <button type="button" onclick="bukaModalKonsultasi()" class="inline-flex items-center justify-center px-4 py-2 min-h-[38px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                        Isi Form Booking
                    </button>
                </div>
            </div>

            <!-- 2. Layanan Perpustakaan Digital (Direct OPAC) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" aria-label="Buka Layanan Perpustakaan Digital OPAC" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_perpustakaan.png') }}" alt="Layanan Perpustakaan Digital" width="128" height="128" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="text-center mt-4">
                    <h3 class="font-semibold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Perpustakaan Digital
                    </h3>
                </a>

                <div class="mt-auto pt-4">
                    <a href="https://perpustakaan.bps.go.id/opac/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 min-h-[38px] rounded-lg bg-[#F4F7FA] hover:bg-[#123B63] text-[#123B63] hover:text-white text-xs font-semibold transition-colors">
                        <span>Akses OPAC</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Layanan Rekomendasi Statistik (Direct ROMANTIK) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" aria-label="Buka Portal ROMANTIK Rekomendasi Statistik" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_romantik.png') }}" alt="Layanan Rekomendasi Statistik" width="128" height="128" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="text-center mt-4">
                    <h3 class="font-semibold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Layanan Rekomendasi Statistik
                    </h3>
                </a>

                <div class="mt-auto pt-4">
                    <a href="https://romantik.web.bps.go.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 min-h-[38px] rounded-lg bg-[#F4F7FA] hover:bg-[#123B63] text-[#123B63] hover:text-white text-xs font-semibold transition-colors">
                        <span>Portal ROMANTIK</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- 4. Layanan Pembelian Data Mikro/Peta (Direct SILASTIK) -->
            <div class="flex flex-col items-center text-center h-full group">
                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" aria-label="Buka Portal SILASTIK Pembelian Data Mikro dan Peta" class="rounded-full transition-opacity group-hover:opacity-90">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-[#F4F7FA]">
                        <img src="{{ asset('images/layanan/layanan_silastik.png') }}" alt="Pembelian Data Mikro / Peta" width="128" height="128" loading="lazy" decoding="async" class="w-full h-full object-cover">
                    </div>
                </a>

                <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="text-center mt-4">
                    <h3 class="font-semibold text-[#123B63] text-sm sm:text-base group-hover:text-[#1E5B91] transition-colors">
                        Pembelian Data Mikro / Peta
                    </h3>
                </a>

                <div class="mt-auto pt-4">
                    <a href="https://silastik.bps.go.id/v3/index.php/site/login/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 min-h-[38px] rounded-lg bg-[#F4F7FA] hover:bg-[#123B63] text-[#123B63] hover:text-white text-xs font-semibold transition-colors">
                        <span>Portal SILASTIK</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: QuickServicesGrid -->
