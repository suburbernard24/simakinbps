<!-- BEGIN: InfografisSection (6 Kotak Utama / Terbaru) -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-200/70 relative" data-purpose="infographics" id="infografis">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#0B4F82]/10 text-[#0B4F82] tracking-wide uppercase mb-3">
                    <i class="fa-solid fa-chart-simple text-sky-600"></i>
                    <span>Visualisasi Statistik Resmi</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#172B4D] tracking-tight">
                    Infografis 6 Data Utama &amp; Terbaru
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
                    Sajian ringkas visualisasi data statistik pilihan Kabupaten Manggarai untuk kemudahan literasi data publik, riset, dan kebijakan.
                </p>
            </div>

            <div class="shrink-0">
                <a href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-[#0B4F82] font-semibold text-xs border border-slate-200 transition-all">
                    <span>Lihat Semua Infografis</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Categories Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 mb-8 pb-2 border-b border-slate-200/80">
            <button type="button" onclick="filterInfografis('semua', this)" class="info-filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-[#0B4F82] text-white shadow-xs cursor-pointer transition-all">
                Semua Infografis (6)
            </button>
            <button type="button" onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Ekonomi &amp; PDRB
            </button>
            <button type="button" onclick="filterInfografis('sosial', this)" class="info-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Sosial &amp; Kemiskinan
            </button>
            <button type="button" onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Pertanian &amp; Sensus
            </button>
            <button type="button" onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 cursor-pointer transition-all">
                Transportasi &amp; Wilayah
            </button>
        </div>

        @php
            // Ambil maksimal 6 infografis aktif yang diinput oleh admin
            $activeInfografis = $infografisList->take(6);
            $countActive = $activeInfografis->count();
        @endphp

        <!-- 6 Infographic Boxes Grid (3x2 Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($countActive > 0)
                @foreach($activeInfografis as $info)
                    <div class="info-card-item info-cat-{{ strtolower($info->kategori ?? 'sosial') }} bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        @if($info->image_url)
                            <div class="h-60 relative overflow-hidden bg-slate-900 group">
                                <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($info->badge_text)
                                    <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-xs">{{ $info->badge_text }}</span>
                                @endif
                            </div>
                        @else
                            <div class="p-6 bg-gradient-to-br from-[#172B4D] to-[#0B4F82] text-white">
                                <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                                    <span class="text-sky-200">BPS MANGGARAI</span>
                                    @if($info->badge_text)
                                        <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">{{ $info->badge_text }}</span>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                                    <i class="fa-solid fa-chart-pie text-lg"></i>
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
                                <div class="mt-4 flex justify-between items-center text-[10px] text-slate-300">
                                    <span>Sumber: {{ $info->sumber ?? 'BPS Kabupaten Manggarai' }}</span>
                                    <span>{{ $info->tahun ?? date('Y') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-800 line-clamp-1">{{ $info->judul }}</span>
                            <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5 shrink-0" href="{{ $info->link_url ?: 'https://manggaraikab.bps.go.id/id/infographic' }}" target="_blank" rel="noopener noreferrer">
                                <span>Lihat</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Jika data dari admin kurang dari 6 kotak, lengkapi hingga tepat 6 kotak pilihan resmi --}}
            @if($countActive < 6)
                <!-- Box Fallback 1: Sensus Pertanian ST2023 -->
                @if($countActive <= 0)
                <div class="info-card-item info-cat-pertanian bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-emerald-900 to-teal-800 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-emerald-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">ST2023 TAHAP II</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-emerald-300">
                            <i class="fa-solid fa-seedling text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">POTRET SENSUS PERTANIAN (ST2023)</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-emerald-100">
                                <span>Usaha Pertanian (UTP)</span>
                                <span class="font-mono font-bold text-white">54.210 Unit</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-emerald-100">
                                <span>Komoditas Unggulan</span>
                                <span class="font-mono font-bold text-white">Kopi Robusta &amp; Arabika</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-emerald-200">
                            <span>Sumber: Publikasi ST2023</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Sensus Pertanian (ST2023)</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 2: Pertumbuhan Ekonomi & PDRB -->
                @if($countActive <= 1)
                <div class="info-card-item info-cat-ekonomi bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-[#0B4F82] to-sky-700 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-sky-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">INDIKATOR EKONOMI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center mb-3 text-white">
                            <i class="fa-solid fa-chart-line text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">STRUKTUR PDRB KABUPATEN MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Sektor Pertanian</span>
                                <span class="font-mono font-bold text-white">34,12%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-sky-100">
                                <span>Perdagangan Besar &amp; Eceran</span>
                                <span class="font-mono font-bold text-white">16,45%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-sky-200">
                            <span>Sumber: Booklet Statistik 2021</span>
                            <span>ADHB 2021</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Struktur PDRB Manggarai</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 3: Kemiskinan & Garis Kemiskinan -->
                @if($countActive <= 2)
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-rose-900 to-red-800 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-rose-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">SUSENAS MARET</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-rose-300">
                            <i class="fa-solid fa-users-viewfinder text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">PROFIL KEMISKINAN MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-rose-100">
                                <span>Persentase Miskin (P0)</span>
                                <span class="font-mono font-bold text-white">19,84%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-rose-100">
                                <span>Garis Kemiskinan</span>
                                <span class="font-mono font-bold text-white">Rp 397.436</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-rose-200">
                            <span>Sumber: Booklet 2021</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Profil Kemiskinan Daerah</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 4: IPM & Pendidikan -->
                @if($countActive <= 3)
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-indigo-950 to-blue-900 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-indigo-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">IPM &amp; PENDIDIKAN</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-sky-300">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">INDEKS PEMBANGUNAN MANUSIA</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-slate-200">
                                <span>Capaian IPM</span>
                                <span class="font-mono font-bold text-white">67,03 Poin</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-slate-200">
                                <span>Harapan Lama Sekolah</span>
                                <span class="font-mono font-bold text-white">13,85 Tahun</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-300">
                            <span>Sumber: Booklet Statistik 2021</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Indeks Pembangunan Manusia</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 5: Transportasi Darat -->
                @if($countActive <= 4)
                <div class="info-card-item info-cat-transportasi bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-slate-300">BPS MANGGARAI</span>
                            <span class="bg-white/10 px-2 py-0.5 rounded text-white font-mono">SARANA TRANSPORTASI</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-amber-300">
                            <i class="fa-solid fa-car-side text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">TRANSPORTASI KABUPATEN MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Sepeda Motor</span>
                                <span class="font-mono font-bold text-white">78,4% Dominasi</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-1.5 text-slate-200">
                                <span>Mobil Penumpang &amp; Barang</span>
                                <span class="font-mono font-bold text-white">21,6%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400">
                            <span>Sumber: Dishub &amp; Kepolisian</span>
                            <span>Booklet 2021</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Transportasi Darat Manggarai</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 6: Ketenagakerjaan & TPAK -->
                @if($countActive <= 5)
                <div class="info-card-item info-cat-sosial bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-[#0B4F82]/50 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 bg-gradient-to-br from-teal-900 to-cyan-900 text-white">
                        <div class="flex justify-between items-center text-[10px] font-semibold uppercase tracking-wider mb-3">
                            <span class="text-teal-200">BPS MANGGARAI</span>
                            <span class="bg-white/15 px-2 py-0.5 rounded text-white font-mono">SAKERNAS</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-cyan-300">
                            <i class="fa-solid fa-briefcase text-lg"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">ANGKATAN KERJA &amp; TPAK MANGGARAI</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-teal-100">
                                <span>Tingkat Partisipasi (TPAK)</span>
                                <span class="font-mono font-bold text-white">74,68%</span>
                            </div>
                            <div class="flex justify-between border-b border-white/15 pb-1.5 text-teal-100">
                                <span>Pengangguran Terbuka (TPT)</span>
                                <span class="font-mono font-bold text-white">2,82%</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center text-[10px] text-teal-200">
                            <span>Sumber: Sakernas / Booklet 2021</span>
                            <span>BPS Manggarai</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/70 flex items-center justify-between border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-800">Ketenagakerjaan &amp; TPAK</span>
                        <a class="text-xs text-[#0B4F82] hover:text-[#083E67] font-bold inline-flex items-center gap-1.5" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</section>
<!-- END: InfografisSection -->
