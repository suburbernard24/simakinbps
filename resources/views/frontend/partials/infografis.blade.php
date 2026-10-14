<!-- BEGIN: InfografisSection -->
<section class="py-20 bg-slate-50/50" data-purpose="infographics" id="infografis">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="text-xs font-bold text-bps-600 uppercase tracking-widest">Visualisasi Statistik</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">GALERI INFOGRAFIS</h2>
            <p class="text-sm text-slate-500 mt-2">Sajian Visualisasi Data Statistik Menarik, Ringkas, dan Edukatif</p>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-12">
            <button onclick="filterInfografis('semua', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-bps-700 text-white shadow-sm">Semua Infografis</button>
            <button onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">Transportasi</button>
            <button onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">Ekonomi &amp; PDRB</button>
            <button onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">Pertanian ST2023</button>
            <button onclick="filterInfografis('sosial', this)" class="info-filter-btn px-5 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">Sosial &amp; Pendidikan</button>
        </div>

        <!-- 6 Clean Infographic Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($infografisList->isNotEmpty())
                @foreach($infografisList as $info)
                    <div class="info-card-item info-cat-{{ strtolower($info->kategori) }} bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                        @if($info->image_url)
                            <div class="h-56 relative overflow-hidden bg-slate-900 group">
                                <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($info->badge_text)
                                    <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-xs">{{ $info->badge_text }}</span>
                                @endif
                            </div>
                        @else
                            <div class="p-5 bg-gradient-to-br {{ $info->bg_gradient ?: 'from-bps-800 to-sky-700' }} text-white">
                                <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                                    <span>BPS MANGGARAI</span>
                                    @if($info->badge_text)
                                        <span class="bg-black/20 px-2 py-0.5 rounded">{{ $info->badge_text }}</span>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </div>
                                <h4 class="font-bold text-base uppercase leading-snug">{{ $info->judul }}</h4>
                                @if(!empty($info->data_points_array))
                                    <div class="mt-4 space-y-1.5 text-xs">
                                        @foreach($info->data_points_array as $dp)
                                            <div class="flex justify-between border-b border-white/20 pb-1">
                                                <span>{{ $dp['label'] ?? '' }}</span>
                                                <span class="font-mono font-bold">{{ $dp['value'] ?? '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-4 flex justify-between items-center text-[10px] text-white/80">
                                    <span>Sumber: BPS Manggarai</span>
                                    <span>{{ date('Y') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="p-4 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">{{ $info->judul }}</span>
                            <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="{{ $info->link_url ?: '#' }}">
                                <span>Lihat</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Info 1: Transportasi -->
                <div class="info-card-item info-cat-transportasi bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-amber-500 to-amber-600 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">STATISTIK TRANSPORTASI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">TRANSPORTASI DARAT MANGGARAI</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Sepeda Motor</span>
                                <span class="font-mono font-bold">78,4%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Mobil Penumpang</span>
                                <span class="font-mono font-bold">14,2%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-amber-100">
                            <span>Sumber: Kepolisian &amp; Dishub</span>
                            <span>2024</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Transportasi Darat Manggarai</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 2: Pertumbuhan Ekonomi -->
                <div class="info-card-item info-cat-ekonomi bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-teal-600 to-emerald-700 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">INDIKATOR EKONOMI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">PERTUMBUHAN EKONOMI DAERAH</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Sektor Pertanian</span>
                                <span class="font-mono font-bold">34,12%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Perdagangan</span>
                                <span class="font-mono font-bold">16,45%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-teal-100">
                            <span>Sumber: PDRB Harga Berlaku</span>
                            <span>Rilis 2024</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Pertumbuhan Ekonomi Daerah</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 3: Sensus Pertanian -->
                <div class="info-card-item info-cat-pertanian bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-green-700 to-emerald-800 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">SENSUS PERTANIAN</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">SENSUS PERTANIAN (ST2023)</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Komoditas Kopi</span>
                                <span class="font-mono font-bold">Unggulan</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Usaha Tani</span>
                                <span class="font-mono font-bold">54.210 RTP</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-green-100">
                            <span>Sumber: ST2023 Tahap II</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Sensus Pertanian (ST2023)</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 4: Pendidikan & IPM -->
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-indigo-700 to-bps-800 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">INDIKATOR PENDIDIKAN</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">PENDIDIKAN &amp; IPM</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Skor IPM 2023</span>
                                <span class="font-mono font-bold">67,03 Poin</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Harapan Lama Sekolah</span>
                                <span class="font-mono font-bold">13,85 Tahun</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-indigo-200">
                            <span>Sumber: Metode Baru IPM</span>
                            <span>Kategori: Sedang</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Pendidikan &amp; IPM</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 5: Struktur Penduduk -->
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-rose-600 to-rose-700 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">DEMOGRAFI &amp; GENDER</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">STRUKTUR &amp; KOMPOSISI PENDUDUK</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Usia Produktif</span>
                                <span class="font-mono font-bold">65,4%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Rasio Ketergantungan</span>
                                <span class="font-mono font-bold">52,8%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-rose-100">
                            <span>Sumber: Sensus Penduduk SP2020</span>
                            <span>Proyeksi 2023</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Struktur &amp; Komposisi Penduduk</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Info 6: Pariwisata -->
                <div class="info-card-item info-cat-ekonomi bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="p-5 bg-gradient-to-br from-amber-600 to-orange-700 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span>BPS MANGGARAI</span>
                            <span class="bg-black/20 px-2 py-0.5 rounded">PARIWISATA MANGGARAI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h4 class="font-bold text-base">PARIWISATA &amp; OKUPANSI HOTEL</h4>
                        <div class="mt-4 space-y-1.5 text-xs">
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>TPK Hotel</span>
                                <span class="font-mono font-bold">41,25%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/20 pb-1">
                                <span>Lama Menginap</span>
                                <span class="font-mono font-bold">1,82 Hari</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-amber-100">
                            <span>Sumber: Survei Akomodasi</span>
                            <span>Ruteng &amp; Sekitarnya</span>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">Pariwisata &amp; Okupansi Hotel</span>
                        <a class="text-xs text-bps-600 hover:text-bps-800 font-semibold flex items-center gap-1" href="#">
                            <span>Lihat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- More Infographics Button -->
        <div class="text-center mt-10">
            <a class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 transition-colors shadow-xs" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank">
                <span>LEBIH BANYAK INFOGRAFIS</span>
                <svg class="w-4 h-4 text-bps-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </a>
        </div>
    </div>
</section>
<!-- END: InfografisSection -->
