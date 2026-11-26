<!-- BEGIN: InfografisSection (6 Kotak Utama / Terbaru) -->
<section class="py-14 sm:py-18 bg-white border-b border-slate-200/80" data-purpose="infographics" id="infografis">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#123B63] tracking-tight">
                    Infografis 6 Data Utama &amp; Terbaru
                </h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">
                    Ringkasan visual data statistik pilihan Kabupaten Manggarai.
                </p>
            </div>

            <a href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] transition-colors shrink-0">
                <span>Lihat Semua Infografis</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            </a>
        </div>

        <!-- Categories Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <button type="button" onclick="filterInfografis('semua', this)" class="info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-semibold bg-[#123B63] text-white transition-colors">
                Semua Infografis (6)
            </button>
            <button type="button" onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-medium bg-[#F4F7FA] text-slate-700 hover:bg-slate-200/70 transition-colors">
                Ekonomi &amp; PDRB
            </button>
            <button type="button" onclick="filterInfografis('sosial', this)" class="info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-medium bg-[#F4F7FA] text-slate-700 hover:bg-slate-200/70 transition-colors">
                Sosial &amp; Kemiskinan
            </button>
            <button type="button" onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-medium bg-[#F4F7FA] text-slate-700 hover:bg-slate-200/70 transition-colors">
                Pertanian &amp; Sensus
            </button>
            <button type="button" onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-4 py-2 min-h-[38px] rounded-lg text-xs font-medium bg-[#F4F7FA] text-slate-700 hover:bg-slate-200/70 transition-colors">
                Transportasi &amp; Wilayah
            </button>
        </div>

        @php
            $activeInfografis = $infografisList->take(6);
            $countActive = $activeInfografis->count();
        @endphp

        <!-- 6 Infographic Boxes Grid (3x2 Layout) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($countActive > 0)
                @foreach($activeInfografis as $info)
                    <div class="info-card-item info-cat-{{ strtolower($info->kategori ?? 'sosial') }} bg-[#F4F7FA]/60 rounded-xl border border-slate-200/80 overflow-hidden hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                        @if($info->image_url)
                            <div class="h-56 relative overflow-hidden bg-[#123B63]">
                                <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" width="400" height="224" loading="lazy" decoding="async" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="p-6 flex-1 flex flex-col justify-between bg-white">
                                <div>
                                    @if($info->badge_text)
                                        <span class="text-[11px] font-medium text-[#1E5B91]">{{ $info->badge_text }}</span>
                                    @endif
                                    <h4 class="font-bold text-base text-[#123B63] mt-1">{{ $info->judul }}</h4>
                                    @if(!empty($info->data_points_array))
                                        <div class="mt-4 space-y-2 text-xs">
                                            @foreach($info->data_points_array as $dp)
                                                <div class="flex justify-between border-b border-slate-100 pb-1.5 text-slate-600">
                                                    <span>{{ $dp['label'] ?? '' }}</span>
                                                    <span class="tabular-nums font-bold text-[#123B63]">{{ $dp['value'] ?? '' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-4 text-[11px] text-slate-400">{{ $info->sumber ?? 'BPS Kabupaten Manggarai' }} · {{ $info->tahun ?? date('Y') }}</p>
                            </div>
                        @endif
                        <div class="px-6 py-3.5 bg-white flex items-center justify-between border-t border-slate-100">
                            <span class="text-xs font-semibold text-[#123B63] line-clamp-1">{{ $info->judul }}</span>
                            <a class="text-xs text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1 shrink-0" href="{{ $info->link_url ?: 'https://manggaraikab.bps.go.id/id/infographic' }}" target="_blank" rel="noopener noreferrer">
                                <span>Lihat</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Jika data dari admin kurang dari 6 kotak, lengkapi hingga tepat 6 kotak pilihan resmi --}}
            @if($countActive < 6)
                <!-- Box Fallback 1: Sensus Pertanian ST2023 -->
                @if($countActive <= 0)
                <div class="info-card-item info-cat-pertanian bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">ST2023 Tahap II</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Potret Sensus Pertanian (ST2023)</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Usaha Pertanian (UTP)</span>
                                <span class="tabular-nums font-bold text-[#123B63]">54.210 Unit</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Komoditas Unggulan</span>
                                <span class="font-bold text-[#123B63]">Kopi Robusta &amp; Arabika</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Publikasi ST2023</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 2: Pertumbuhan Ekonomi & PDRB -->
                @if($countActive <= 1)
                <div class="info-card-item info-cat-ekonomi bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">Indikator Ekonomi</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Struktur PDRB Kabupaten Manggarai</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Sektor Pertanian</span>
                                <span class="tabular-nums font-bold text-[#1E5B91]">34,12%</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Perdagangan Besar &amp; Eceran</span>
                                <span class="tabular-nums font-bold text-[#123B63]">16,45%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Booklet Statistik 2021</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 3: Kemiskinan & Garis Kemiskinan -->
                @if($countActive <= 2)
                <div class="info-card-item info-cat-sosial bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">Susenas Maret</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Profil Kemiskinan Manggarai</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Persentase Miskin (P0)</span>
                                <span class="tabular-nums font-bold text-[#123B63]">19,84%</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Garis Kemiskinan</span>
                                <span class="tabular-nums font-bold text-[#123B63]">Rp 397.436</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Booklet 2021</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 4: IPM & Pendidikan -->
                @if($countActive <= 3)
                <div class="info-card-item info-cat-sosial bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">IPM &amp; Pendidikan</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Indeks Pembangunan Manusia</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Capaian IPM</span>
                                <span class="tabular-nums font-bold text-[#1E5B91]">67,03 Poin</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Harapan Lama Sekolah</span>
                                <span class="tabular-nums font-bold text-[#123B63]">13,85 Tahun</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Booklet Statistik 2021</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 5: Transportasi Darat -->
                @if($countActive <= 4)
                <div class="info-card-item info-cat-transportasi bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">Sarana Transportasi</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Transportasi Kabupaten Manggarai</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Sepeda Motor</span>
                                <span class="tabular-nums font-bold text-[#123B63]">78,4% Dominasi</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Mobil Penumpang &amp; Barang</span>
                                <span class="tabular-nums font-bold text-[#123B63]">21,6%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Dishub &amp; Kepolisian</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Box Fallback 6: Ketenagakerjaan & TPAK -->
                @if($countActive <= 5)
                <div class="info-card-item info-cat-sosial bg-white rounded-xl border border-slate-200/80 p-6 hover:border-[#3686C8] transition-colors flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-[#1E5B91]">Sakernas</span>
                        <h4 class="font-bold text-base text-[#123B63] mt-1">Angkatan Kerja &amp; TPAK Manggarai</h4>
                        <div class="mt-4 space-y-2 text-xs">
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Tingkat Partisipasi (TPAK)</span>
                                <span class="tabular-nums font-bold text-[#1E5B91]">74,68%</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2 text-slate-600">
                                <span>Pengangguran Terbuka (TPT)</span>
                                <span class="tabular-nums font-bold text-[#123B63]">2,82%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">Sakernas / Booklet 2021</span>
                        <a class="text-[#1E5B91] hover:text-[#123B63] font-semibold inline-flex items-center gap-1" href="https://manggaraikab.bps.go.id/id/infographic" target="_blank" rel="noopener noreferrer">
                            <span>Lihat</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</section>
<!-- END: InfografisSection -->
