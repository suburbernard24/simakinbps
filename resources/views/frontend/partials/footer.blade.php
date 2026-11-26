<!-- BEGIN: MainFooter -->
<footer class="bg-[#123B63] text-slate-300 text-xs pt-14 pb-10 border-t border-[#1E5B91]" data-purpose="site-footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-10 border-b border-white/15 items-start">
            <!-- Col 1: Identity (4 cols) -->
            <div class="lg:col-span-4 space-y-4">
                <div>
                    <a href="{{ url('/') }}" class="inline-block bg-white px-2.5 py-1.5 rounded-md hover:opacity-95 transition-opacity">
                        <img src="{{ $logoNatas ?? asset('images/LogoFE_natas.webp') }}" alt="{{ $brandName ?? 'NATAS BPS MANGGARAI' }}" width="150" height="34" loading="lazy" decoding="async" class="h-8 w-auto max-w-[180px] object-contain">
                    </a>
                    <p class="text-xs text-slate-300 mt-3 leading-relaxed">
                        <strong>{{ $brandName ?? 'NATAS BPS MANGGARAI' }}</strong> ({{ $brandSub ?? 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai' }}): Portal resmi penyebarluasan data statistik dan layanan publik BPS Kabupaten Manggarai.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <div class="w-9 h-9 rounded-lg bg-white/10 p-1.5 flex items-center justify-center shrink-0 border border-white/10">
                        <img src="{{ $logoBps ?? asset('images/logo_bps.png') }}" alt="Logo BPS Kabupaten Manggarai" width="32" height="32" loading="lazy" decoding="async" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-xs tracking-tight">BADAN PUSAT STATISTIK</h4>
                        <p class="text-[11px] font-medium text-slate-300">KABUPATEN MANGGARAI</p>
                    </div>
                </div>
            </div>

            <!-- Col 2: Kontak & Profil Layanan (4 cols) -->
            <div class="lg:col-span-4 space-y-5">
                <div>
                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-3 border-b border-white/10 pb-2">
                        Kontak &amp; Alamat
                    </h5>
                    <div class="space-y-2 text-xs text-slate-300">
                        <p class="flex items-start gap-2.5">
                            <svg class="w-3.5 h-3.5 text-[#9BBBD9] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span>{{ $fs['pst_alamat'] ?? 'Jl. Bougenville Utara, Kelurahan Pau, Kecamatan Langke Rembong, Ruteng - Manggarai' }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-3.5 h-3.5 text-[#9BBBD9] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span>{{ $fs['pst_jam_layanan'] ?? 'Senin - Jumat: 08.00 - 15.30 WITA' }}</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <svg class="w-3.5 h-3.5 text-[#9BBBD9] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <a href="mailto:{{ $pengaduanMail }}" class="text-slate-200 hover:text-white transition-colors">{{ $pengaduanMail }}</a>
                        </p>
                    </div>
                </div>

                <div>
                    <h5 class="text-white font-bold text-xs uppercase tracking-wider mb-2.5 border-b border-white/10 pb-2">
                        Profil &amp; Layanan
                    </h5>
                    <div class="flex flex-wrap gap-2">
                        <a href="https://ppid.bps.go.id/app/konten/5313/Profil-BPS.html" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white/10 hover:bg-[#1E5B91] text-white text-xs font-medium transition-colors">
                            <span>Visi Misi BPS</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-[#9BBBD9]" aria-hidden="true"></i>
                        </a>
                        <a href="https://ppid.bps.go.id/app/konten/5313/Layanan-BPS.html" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white/10 hover:bg-[#1E5B91] text-white text-xs font-medium transition-colors">
                            <span>Motto Layanan</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-[#9BBBD9]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Col 4: Maps Lokasi Kantor (4 cols) -->
            <div class="lg:col-span-4 space-y-2.5">
                <div class="flex items-center justify-between border-b border-white/10 pb-2">
                    <h5 class="text-white font-bold text-xs uppercase tracking-wider">
                        Lokasi BPS Manggarai
                    </h5>
                    <a href="https://maps.google.com/?q=Badan+Pusat+Statistik+Kabupaten+Manggarai+Ruteng" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-[#9BBBD9] hover:text-white transition-colors">
                        Buka Google Maps
                    </a>
                </div>
                <div class="rounded-lg overflow-hidden border border-white/15 bg-[#0C2844]">
                    <iframe
                        title="Peta Lokasi BPS Kabupaten Manggarai"
                        src="https://maps.google.com/maps?q=Badan%20Pusat%20Statistik%20Kabupaten%20Manggarai%20Ruteng&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="400"
                        height="180"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-[180px] border-0">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Bottom Footer: Social Media, Copyright & Back to Top -->
        <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <!-- Copyright -->
            <p class="text-slate-300 text-center md:text-left text-[11px] sm:text-xs">
                © {{ date('Y') }} {{ $brandName ?? 'NATAS BPS MANGGARAI' }}, Badan Pusat Statistik Kabupaten Manggarai.
            </p>

            <!-- Social Media Links -->
            <div class="flex items-center gap-1.5">
                <a aria-label="Facebook BPS Manggarai" title="Facebook BPS Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://www.facebook.com/bps.manggarai.3" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-facebook-f text-xs" aria-hidden="true"></i>
                </a>
                <a aria-label="Halaman Facebook BPS Kabupaten Manggarai" title="Halaman Facebook BPS Kabupaten Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://www.facebook.com/bpskabmanggarai/" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-facebook text-xs" aria-hidden="true"></i>
                </a>
                <a aria-label="X (Twitter) BPS Manggarai" title="X BPS Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://x.com/bpsmanggarai" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-x-twitter text-xs" aria-hidden="true"></i>
                </a>
                <a aria-label="Instagram BPS Manggarai" title="Instagram BPS Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://www.instagram.com/bpsmanggarai/" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-instagram text-xs" aria-hidden="true"></i>
                </a>
                <a aria-label="YouTube BPS Manggarai" title="YouTube BPS Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://www.youtube.com/@bpsmanggarai7697/" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-youtube text-xs" aria-hidden="true"></i>
                </a>
                <a aria-label="TikTok BPS Manggarai" title="TikTok BPS Manggarai" class="w-8 h-8 rounded-md bg-white/10 hover:bg-[#1E5B91] text-slate-200 hover:text-white flex items-center justify-center transition-colors" href="https://www.tiktok.com/@bpsmanggarai" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-tiktok text-xs" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
<!-- END: MainFooter -->
