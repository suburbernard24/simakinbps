<!-- BEGIN: InfografisSection -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-200/70 relative" data-purpose="infographics" id="infografis">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#0B4F82]/10 text-[#0B4F82] tracking-wide uppercase">
                Visualisasi Statistik
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#172B4D] tracking-tight mt-2">
                Galeri Infografis BPS
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed">
                Sajian Visualisasi Data Statistik Menarik, Ringkas, dan Edukatif untuk Publik
            </p>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <button type="button" onclick="filterInfografis('semua', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-[#0B4F82] text-white shadow-xs cursor-pointer transition-all">Semua Infografis</button>
            <button type="button" onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Transportasi</button>
            <button type="button" onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Ekonomi &amp; PDRB</button>
            <button type="button" onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Pertanian ST2023</button>
            <button type="button" onclick="filterInfografis('sosial', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">Sosial &amp; Pendidikan</button>
        </div>

        <!-- Infographic Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($infografisList->isNotEmpty())
                @foreach($infografisList as $info)
                    <div class="info-card-item info-cat-{{ strtolower($info->kategori) }} bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        @if($info->image_url)
                            <div class="h-56 relative overflow-hidden bg-slate-900 group">
                                <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($info->badge_text)
                                    <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-xs">{{ $info->badge_text }}</span>
                                @endif
                            </div>
                        @else
                            <div class="p-6 bg-[#172B4D] text-white">
                                <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                                    <span class="text-slate-300">BPS MANGGARAI</span>
                                    @if($info->badge_text)
                                        <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">{{ $info->badge_text }}</span>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </div>
                                <h4 class="font-bold text-base uppercase leading-snug text-white">{{ $info->judul }}</h4>
                                @if(!empty($info->data_points_array))
                                    <div class="mt-4 space-y-2 text-xs">
                                        @foreach($info->data_points_array as $dp)
                                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                                <span>{{ $dp['label'] ?? '' }}</span>
                                                <span class="font-mono font-bold text-white">{{ $dp['value'] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                                    <span>Sumber: BPS Manggarai</span>
                                    <span>{{ date('Y') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-800 line-clamp-1">{{ $info->judul }}</span>
                            <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1 shrink-0" href="{{ $info->link_url ?: 'https://manggaraikab.bps.go.id/id/infographic' }}" target="_blank" rel="noopener noreferrer">
                                <span>Lihat</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Info 1: Transportasi -->
                <div class="info-card-item info-cat-transportasi bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#172B4D] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-slate-300">BPS MANGGARAI</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">TRANSPORTASI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">TRANSPORTASI DARAT MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Sepeda Motor</span>
                                <span class="font-mono font-bold text-white">78,4%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Mobil Penumpang</span>
                                <span class="font-mono font-bold text-white">14,2%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                            <span>Sumber: Kepolisian &amp; Dishub</span>
                            <span>2024</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Transportasi Darat Manggarai</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 2: Pertumbuhan Ekonomi -->
                <div class="info-card-item info-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#0B4F82] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-sky-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">INDIKATOR EKONOMI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">PERTUMBUHAN EKONOMI DAERAH</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Sektor Pertanian</span>
                                <span class="font-mono font-bold text-white">34,12%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Perdagangan</span>
                                <span class="font-mono font-bold text-white">16,45%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-sky-200">
                            <span>Sumber: PDRB Harga Berlaku</span>
                            <span>Rilis 2024</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Pertumbuhan Ekonomi Daerah</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 3: Sensus Pertanian -->
                <div class="info-card-item info-cat-pertanian bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#172B4D] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-slate-300">BPS MANGGARAI</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">SENSUS PERTANIAN</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">SENSUS PERTANIAN (ST2023)</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Komoditas Kopi</span>
                                <span class="font-mono font-bold text-white">Unggulan</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Usaha Tani</span>
                                <span class="font-mono font-bold text-white">54.210 RTP</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                            <span>Sumber: ST2023 Tahap II</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Sensus Pertanian (ST2023)</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 4: Pendidikan & IPM -->
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#172B4D] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-slate-300">BPS MANGGARAI</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">PENDIDIKAN &amp; IPM</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">PENDIDIKAN &amp; IPM MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Skor IPM 2023</span>
                                <span class="font-mono font-bold text-white">67,03 Poin</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Harapan Lama Sekolah</span>
                                <span class="font-mono font-bold text-white">13,85 Tahun</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                            <span>Sumber: Metode Baru IPM</span>
                            <span>Kategori: Sedang</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Pendidikan &amp; IPM</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 5: Struktur Penduduk -->
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#0B4F82] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-sky-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">DEMOGRAFI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center mb-3 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">STRUKTUR KOMPOSISI PENDUDUK</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Usia Produktif</span>
                                <span class="font-mono font-bold text-white">65,4%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Rasio Ketergantungan</span>
                                <span class="font-mono font-bold text-white">52,8%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-sky-200">
                            <span>Sumber: Sensus Penduduk SP2020</span>
                            <span>Proyeksi 2023</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Struktur &amp; Komposisi Penduduk</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 6: Pariwisata -->
                <div class="info-card-item info-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/40 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div class="p-6 bg-[#172B4D] text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-slate-300">BPS MANGGARAI</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">PARIWISATA</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-white">PARIWISATA &amp; OKUPANSI HOTEL</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>TPK Hotel</span>
                                <span class="font-mono font-bold text-white">41,25%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Rata-rata Menginap</span>
                                <span class="font-mono font-bold text-white">1,82 Hari</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                            <span>Sumber: Survei Akomodasi</span>
                            <span>Ruteng &amp; Sekitarnya</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Pariwisata &amp; Okupansi Hotel</span>
                        <a class="text-xs text-[#0B4F82] group-hover:text-[#08385d] font-bold flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- More Infographics Button -->
        <div class="text-center mt-10">
            <a class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-white hover:bg-[#F4F7FB] border border-slate-200 text-xs font-bold text-[#172B4D] hover:text-[#0B4F82] transition-colors shadow-2xs" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                <span>LEBIH BANYAK INFOGRAFIS</span>
                <svg class="w-4 h-4 text-[#0B4F82]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </a>
        </div>
    </div>
</section>
<!-- END: InfografisSection -->
