<!-- BEGIN: MaklumatAndContactSection -->
<section class="py-16 bg-slate-950 text-white" data-purpose="maklumat-and-service" id="hubungi-pst">
    <!-- Anchor aliases -->
    <div id="pst" class="absolute -top-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-slate-900 to-bps-950 p-8 sm:p-12 rounded-3xl border border-slate-800 shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                <!-- Left Column: Layanan Khusus & Booking -->
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-bps-500/20 text-bps-300 text-xs font-semibold mb-4 border border-bps-400/20">
                        <span class="w-2 h-2 rounded-full bg-bps-400"></span>
                        Pelayanan Statistik Terpadu (PST) Bebas Pungli
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Butuh Data Statistik Khusus atau Rekomendasi Kegiatan?
                    </h2>
                    <p class="text-sm text-slate-300 mt-3 leading-relaxed">
                        BPS Kabupaten Manggarai siap melayani permohonan data mikro, konsultasi statistik, rekomendasi kegiatan statistik sektoral (Romantik), dan verifikasi metadata. Seluruh pelayanan tidak dipungut biaya (gratis).
                    </p>
                    <div class="mt-6 space-y-2.5 text-xs text-slate-300">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-bps-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span><strong>Jam Pelayanan:</strong> {{ $fs['pst_jam_layanan'] ?? 'Senin s.d. Jumat, 08.00 - 15.30 WITA' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-bps-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span><strong>Ruang PST:</strong> {{ $fs['pst_alamat'] ?? 'Kantor BPS Kab. Manggarai, Jl. Ranaka, Ruteng, Flores, NTT' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-bps-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span><strong>Email Resmi:</strong> <a href="mailto:{{ $pengaduanMail }}" class="text-bps-300 hover:underline">{{ $pengaduanMail }}</a></span>
                        </div>
                    </div>

                    <!-- Booking & WhatsApp Buttons -->
                    <div class="mt-8 flex flex-wrap gap-3" id="buku-tamu">
                        <button onclick="bukaModalBukuTamu()" class="px-5 py-3 rounded-xl bg-bps-600 hover:bg-bps-500 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-md cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            <span>ISI BUKU TAMU DIGITAL</span>
                        </button>
                        <a class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/10 flex items-center gap-2" href="https://wa.me/{{ $pstWaClean }}" target="_blank">
                            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path></svg>
                            <span>CHAT WA FRONT OFFICE</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Maklumat Pelayanan BPS & Kanal Pengaduan -->
                <div class="lg:col-span-5 bg-white/5 p-6 rounded-2xl border border-white/10">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-bps-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        <h3 class="font-bold text-white text-sm uppercase tracking-wide">Maklumat Pelayanan BPS</h3>
                    </div>
                    <p class="text-[11px] text-slate-400">Standar Pelayanan Publik Zona Integritas</p>
                    <blockquote class="text-xs italic text-slate-300 mt-3 pl-3 border-l-2 border-bps-500 leading-relaxed">
                        “Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku.”
                    </blockquote>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-[11px]">
                        <div class="p-2.5 rounded-lg bg-black/30 border border-white/5">
                            <span class="text-emerald-400 font-bold block">✓ Biaya Rp 0,-</span>
                            <span class="text-slate-400">Seluruh publikasi dan data digital bebas biaya.</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-black/30 border border-white/5">
                            <span class="text-bps-400 font-bold block">✓ Cepat &amp; Tepat</span>
                            <span class="text-slate-400">Layanan konsultasi diselesaikan secara responsif.</span>
                        </div>
                    </div>

                    <!-- Whistleblowing / Kanal Pengaduan -->
                    <div class="mt-5 pt-4 border-t border-white/10">
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-200 mb-2">
                            <span>KANAL RESMI PENGADUAN PUBLIK</span>
                            <span class="text-[10px] text-amber-400">Zona Integritas WBK/WBBM</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-3">Laporkan pelanggaran disiplin, pungutan liar, atau ketidaksesuaian standar pelayanan. Identitas pelapor dijamin aman dan rahasia.</p>
                        <div class="grid grid-cols-3 gap-2 text-center text-[10px]">
                            <a class="p-2 rounded bg-white/5 hover:bg-white/10 transition-colors border border-white/5" href="https://www.lapor.go.id" target="_blank">
                                <span class="font-bold text-white block">SP4N-LAPOR!</span>
                                <span class="text-slate-400 text-[9px]">Kanal Nasional</span>
                            </a>
                            <a class="p-2 rounded bg-white/5 hover:bg-white/10 transition-colors border border-white/5" href="https://wbs.bps.go.id" target="_blank">
                                <span class="font-bold text-white block">WBS BPS RI</span>
                                <span class="text-slate-400 text-[9px]">Whistleblowing</span>
                            </a>
                            <a class="p-2 rounded bg-white/5 hover:bg-white/10 transition-colors border border-white/5" href="https://wa.me/{{ $pstWaClean }}" target="_blank">
                                <span class="font-bold text-white block">Hotline WA</span>
                                <span class="text-slate-400 text-[9px]">{{ $pstWa }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: MaklumatAndContactSection -->
