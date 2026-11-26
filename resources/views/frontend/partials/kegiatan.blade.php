<!-- BEGIN: ActivitiesSection -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-200/70 relative" data-purpose="recent-activities" id="kegiatan">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#087E8B]/10 text-[#087E8B] tracking-wide uppercase">
                Dokumentasi &amp; Warta
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#153448] tracking-tight mt-2">
                Kegiatan Terkini BPS Manggarai
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed">
                Dokumentasi kegiatan lapangan, koordinasi lintas sektoral, dan penguatan kapabilitas statistik daerah
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($kegiatansList->isNotEmpty())
                @foreach($kegiatansList as $keg)
                    @php
                        $badgeClasses = match($keg->badge_color) {
                            'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'teal' => 'bg-teal-50 text-teal-700 border-teal-200',
                            'orange', 'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'emerald', 'green' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'rose', 'red' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-[#087E8B]/10 text-[#087E8B] border-[#087E8B]/20',
                        };
                    @endphp
                    <article class="p-6 rounded-2xl bg-[#F7F9FA] hover:bg-white border border-slate-200/80 hover:border-[#087E8B]/40 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-md {{ $badgeClasses }} text-[10px] font-bold uppercase tracking-wider border">
                                    {{ $keg->kategori }}
                                </span>
                                <span class="text-slate-500 text-xs font-medium">{{ $keg->tanggal_display ?? $keg->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-[#153448] text-sm sm:text-base group-hover:text-[#087E8B] transition-colors line-clamp-2">
                                {{ $keg->judul }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ $keg->deskripsi }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                            <a class="text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors flex items-center gap-1" href="{{ $keg->link_url ?: '#' }}" {{ str_starts_with($keg->link_url ?? '', 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                <span>Baca Selengkapnya</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            @else
                <!-- Activity 1: Susenas -->
                <article class="p-6 rounded-2xl bg-[#F7F9FA] hover:bg-white border border-slate-200/80 hover:border-[#087E8B]/40 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider border border-rose-200">Survei Sosial Ekonomi</span>
                            <span class="text-slate-500 text-xs font-medium">15 Maret 2024</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-sm sm:text-base group-hover:text-[#087E8B] transition-colors">
                            Pelaksanaan Survei Sosial Ekonomi Nasional (Susenas) Maret 2024
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            Petugas pencacah lapangan BPS Kabupaten Manggarai melakukan pendataan langsung ke rumah tangga terpilih guna mengumpulkan data riil mengenai kondisi sosial ekonomi.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                        <a class="text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 2: Sakernas -->
                <article class="p-6 rounded-2xl bg-[#F7F9FA] hover:bg-white border border-slate-200/80 hover:border-[#087E8B]/40 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-[#087E8B]/10 text-[#087E8B] text-[10px] font-bold uppercase tracking-wider border border-[#087E8B]/20">Ketenagakerjaan</span>
                            <span class="text-slate-500 text-xs font-medium">28 Februari 2024</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-sm sm:text-base group-hover:text-[#087E8B] transition-colors">
                            Pelatihan Petugas Survei Angkatan Kerja Nasional (Sakernas)
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            Pembekalan konsep dan metodologi ketenagakerjaan bagi seluruh pencacah lapangan untuk menghasilkan data Tingkat Pengangguran Terbuka (TPT) yang akurat.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                        <a class="text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 3: FGD DDA -->
                <article class="p-6 rounded-2xl bg-[#F7F9FA] hover:bg-white border border-slate-200/80 hover:border-[#087E8B]/40 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-800 text-[10px] font-bold uppercase tracking-wider border border-amber-200">Koordinasi Sektoral</span>
                            <span class="text-slate-500 text-xs font-medium">14 Februari 2024</span>
                        </div>
                        <h3 class="font-bold text-[#153448] text-sm sm:text-base group-hover:text-[#087E8B] transition-colors">
                            FGD Penyusunan Publikasi Kabupaten Manggarai Dalam Angka
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            Kolaborasi strategis BPS Kabupaten Manggarai bersama Bappeda dan seluruh Organisasi Perangkat Daerah (OPD) dalam rangka verifikasi dan sinkronisasi data sektoral.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                        <a class="text-xs font-bold text-[#087E8B] group-hover:text-[#153448] transition-colors flex items-center gap-1" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>
            @endif
        </div>
    </div>
</section>
<!-- END: ActivitiesSection -->
