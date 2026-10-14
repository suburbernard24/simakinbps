<!-- BEGIN: IntegratedServiceHub (PST) -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="pst">
<div class="bg-slate-900 rounded-3xl p-8 sm:p-12 text-white border border-slate-800 shadow-2xl relative overflow-hidden">
<!-- Subtle Decorative Glow Background -->
<div class="absolute -right-24 -bottom-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
<!-- Left Column: Information & Contacts -->
<div class="lg:col-span-7">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-semibold mb-4">
<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">PELAYANAN STATISTIK TERPADU (PST) BEBAS PUNGLI</span>
</div>
<h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-3">
            Butuh Data Statistik Khusus<br>atau Rekomendasi Kegiatan?
          </h2>
<p class="text-sm text-slate-300 mb-6 leading-relaxed max-w-xl">
            BPS Kabupaten Manggarai siap melayani permohonan data mikro, konsultasi statistik, rekomendasi kegiatan statistik sektoral (Romantik), dan verifikasi metadata. Seluruh pelayanan tidak dipungut biaya (gratis).
          </p>
<div class="space-y-2.5 text-xs text-slate-300 mb-8">
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Jam Pelayanan:</strong> {{ $fs['pst_jam_layanan'] ?? 'Senin s.d. Jumat, 08.00 - 15.30 WITA' }}</span>
</div>
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Ruang PST:</strong> {{ $fs['pst_alamat'] ?? 'Kantor BPS Kab. Manggarai, Jl. Ranaka, Ruteng, Flores, NTT' }}</span>
</div>
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Email Resmi:</strong> <a class="text-blue-300 hover:underline" href="mailto:{{ $pengaduanMail }}">{{ $pengaduanMail }}</a></span>
</div>
</div>
<!-- Direct CTA Buttons -->
<div class="flex flex-wrap gap-3">
<button onclick="bukaModalBukuTamu()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs inline-flex items-center gap-2 shadow-lg shadow-emerald-900/30 transition-all">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">ISI BUKU TAMU DIGITAL</span>
</button>
<a class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-xs inline-flex items-center gap-2 transition-all" href="https://wa.me/{{ $pstWaClean }}" target="_blank">
<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">CHAT WA FRONT OFFICE</span>
</a>
</div>
</div>
<!-- Right Column: Maklumat Pelayanan BPS Card -->
<div class="lg:col-span-5 bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 border border-slate-700 text-center">
<div class="w-12 h-12 rounded-xl bg-blue-600/20 text-blue-400 mx-auto flex items-center justify-center mb-4">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h3 class="text-base font-bold text-white uppercase tracking-wider">Maklumat Pelayanan BPS</h3>
<p class="text-[11px] text-blue-300 font-medium mt-0.5">Standar Pelayanan Publik Zona Integritas</p>
<p class="text-xs text-slate-300 italic mt-4 leading-relaxed bg-slate-900/40 p-4 rounded-xl border border-slate-700/60">
            "Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."
          </p>
<div class="grid grid-cols-2 gap-3 mt-4 text-left">
<div class="bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">
<span class="text-[10px] text-emerald-400 font-bold block">✓ Biaya Rp 0,-</span>
<span class="text-[11px] text-slate-300">Seluruh publikasi dan data digital bebas biaya.</span>
</div>
<div class="bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">
<span class="text-[10px] text-blue-400 font-bold block">✓ Cepat &amp; Tepat</span>
<span class="text-[11px] text-slate-300">Layanan konsultasi diselesaikan secara responsif.</span>
</div>
</div>
<div class="mt-5 pt-4 border-t border-slate-700/80 text-left">
<div class="flex items-center justify-between mb-3">
<div class="flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-emerald-400"></span>
<span class="text-xs font-bold text-white uppercase tracking-wider">Kanal Resmi Pengaduan Publik</span>
</div>
<span class="text-[10px] text-amber-300 bg-amber-500/20 px-2 py-0.5 rounded border border-amber-400/30">Zona Integritas WBK/WBBM</span>
</div>
<p class="text-[11px] text-slate-400 mb-3 leading-relaxed">Laporkan pelanggaran disiplin, pungutan liar, atau ketidaksesuaian standar pelayanan. Identitas pelapor dijamin aman dan rahasia.</p>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-red-500/50 transition-all text-center group" href="https://www.lapor.go.id" target="_blank">
<span class="text-[11px] font-bold text-red-400 block group-hover:text-red-300">SP4N-LAPOR!</span>
<span class="text-[10px] text-slate-400">Kanal Nasional</span>
</a>
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-blue-500/50 transition-all text-center group" href="https://wbs.bps.go.id" target="_blank">
<span class="text-[11px] font-bold text-blue-400 block group-hover:text-blue-300">WBS BPS RI</span>
<span class="text-[10px] text-slate-400">Whistleblowing</span>
</a>
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-emerald-500/50 transition-all text-center group" href="https://wa.me/{{ $pstWaClean }}?text=Halo%20Admin%20Pengaduan%20BPS%20Manggarai" target="_blank">
<span class="text-[11px] font-bold text-emerald-400 block group-hover:text-emerald-300">Hotline WA</span>
<span class="text-[10px] text-slate-400">{{ $pstWa }}</span>
</a>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- END: IntegratedServiceHub -->

