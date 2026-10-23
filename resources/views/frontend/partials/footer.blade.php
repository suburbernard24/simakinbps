<!-- BEGIN: MainFooter -->
<footer class="bg-[#172B4D] text-slate-300 text-xs pt-16 pb-12 border-t border-slate-800" data-purpose="site-footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 4-Column Categorized Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-12 border-b border-slate-700/80">
            <!-- Col 1: Identity & Office Address (4 cols) -->
            <div class="lg:col-span-4 space-y-4">
                <div>
                    <a href="{{ url('/') }}" class="inline-block bg-white px-4 py-2 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                        <img src="{{ $logoSapa ?? asset('images/sapa_bps.png') }}" alt="SAPA BPS - Informasi Statistik, Mudah Diakses" class="h-10 w-auto object-contain transition-transform group-hover:scale-105">
                    </a>
                    <p class="text-xs text-slate-300 mt-2.5 leading-relaxed max-w-sm">
                        Portal resmi Pelayanan Statistik Terpadu (PST) dan Keterbukaan Informasi Publik BPS Kabupaten Manggarai.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <div class="w-9 h-9 rounded-xl bg-white/10 p-1 flex items-center justify-center shrink-0 border border-white/10">
                        <img src="{{ $logoBps ?? asset('images/logo_bps.png') }}" alt="Logo BPS Kabupaten Manggarai" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h4 class="font-extrabold text-white text-xs tracking-tight">BADAN PUSAT STATISTIK</h4>
                        <p class="text-[11px] font-semibold text-sky-300">KABUPATEN MANGGARAI</p>
                    </div>
                </div>
        
                <div class="space-y-1.5 text-xs text-slate-300 pt-1">
                    <p><strong class="text-white">Alamat:</strong> {{ $fs['pst_alamat'] ?? 'Jl. Bougenville Utara, Kelurahan Pau, Kecamatan Langke Rembong, Ruteng - Manggarai' }}</p>
                    <p><strong class="text-white">Jam Layanan:</strong> {{ $fs['pst_jam_layanan'] ?? 'Senin - Jumat: 08.00 - 15.30 WITA' }}</p>
                    <p><strong class="text-white">Email:</strong> {{ $pengaduanMail }}</p>
                </div>
            </div>

            <!-- Col 2: Layanan & Konsultasi (3 cols) -->
            <div class="lg:col-span-3">
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">
                    Layanan &amp; Konsultasi
                </h5>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="#hubungi-pst" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Pelayanan Statistik Terpadu (PST)
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="bukaModalBukuTamu()" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Buku Tamu Digital Pengunjung
                        </a>
                    </li>
                    <li>
                        <a href="https://romantik.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Rekomendasi Statistik (Romantik)
                        </a>
                    </li>
                    <li>
                        <a href="https://silastik.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Permohonan Data Mikro &amp; Peta
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/{{ $pstWaClean }}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Konsultasi Online via WhatsApp
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Portal Data & API (3 cols) -->
            <div class="lg:col-span-3">
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">
                    Portal Data &amp; Publikasi
                </h5>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="#indikator-terkini" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Indikator Strategis Manggarai
                        </a>
                    </li>
                    <li>
                        <a href="#publikasi" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Katalog Publikasi BPS Terbaru
                        </a>
                    </li>
                    <li>
                        <a href="#infografis" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Galeri Infografis Statistik
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" onclick="bukaModalApi()" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Integrasi WebAPI BPS Resmi
                        </a>
                    </li>
                    <li>
                        <a href="https://data.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-sky-400">›</span> Portal Satu Data Indonesia
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Pengaduan & Integritas (2 cols) -->
            <div class="lg:col-span-2">
                <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">
                    Pengaduan &amp; WBK
                </h5>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="https://www.lapor.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> SP4N-LAPOR!
                        </a>
                    </li>
                    <li>
                        <a href="https://wbs.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> WBS BPS RI
                        </a>
                    </li>
                    <li>
                        <a href="https://www.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> BPS Republik Indonesia
                        </a>
                    </li>
                    <li>
                        <a href="https://ntt.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> BPS Provinsi NTT
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Footer: Social Media, Copyright & Back to Top -->
        <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <!-- Copyright -->
            <p class="text-slate-400 text-center md:text-left">
                © {{ date('Y') }} SAPA BPS — Badan Pusat Statistik Kabupaten Manggarai. Seluruh Hak Cipta Dilindungi.
            </p>

            <!-- Social Media & Back to Top -->
            <div class="flex items-center gap-4">
                <!-- Social Media Links -->
                <div class="flex items-center gap-2">
                    <a aria-label="Facebook BPS Manggarai" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#0B4F82] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path></svg>
                    </a>
                    <a aria-label="Instagram BPS Manggarai" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#0B4F82] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                    </a>
                    <a aria-label="YouTube BPS Manggarai" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#0B4F82] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://youtube.com" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path></svg>
                    </a>
                </div>

                <!-- Back to Top Button -->
                <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-[#0B4F82] text-white text-xs font-semibold transition-colors cursor-pointer" aria-label="Kembali ke atas halaman">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Ke Atas</span>
                </button>
            </div>
        </div>
    </div>
</footer>
<!-- END: MainFooter -->
