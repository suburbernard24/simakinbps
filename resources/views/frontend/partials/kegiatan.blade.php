<!-- BEGIN: ActivitiesSection -->
<section class="py-20 bg-white" data-purpose="recent-activities" id="kegiatan">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold text-bps-600 uppercase tracking-widest">Dokumentasi &amp; Warta</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">KEGIATAN</h2>
            <p class="text-sm text-slate-500 mt-2">Dokumentasi dan Berita Kegiatan Terkini BPS Kabupaten Manggarai</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($kegiatansList->isNotEmpty())
                @foreach($kegiatansList as $keg)
                    @php
                        $badgeClasses = match($keg->badge_color) {
                            'purple' => 'bg-purple-50 text-purple-700 border-purple-100',
                            'teal' => 'bg-teal-50 text-teal-700 border-teal-100',
                            'orange', 'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'emerald', 'green' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'rose', 'red' => 'bg-rose-50 text-rose-700 border-rose-100',
                            default => 'bg-bps-50 text-bps-700 border-bps-100',
                        };
                    @endphp
                    <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded {{ $badgeClasses }} text-[10px] font-bold uppercase tracking-wider border">
                                    {{ $keg->kategori }}
                                </span>
                                <span class="text-slate-400 text-xs font-mono">{{ $keg->tanggal_display ?? $keg->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                                {{ $keg->judul }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                                {{ $keg->deskripsi }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="{{ $keg->link_url ?: '#' }}" {{ str_starts_with($keg->link_url ?? '', 'http') ? 'target="_blank"' : '' }}>
                                <span>Baca Selengkapnya</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            @else
                <!-- Activity 1: Susenas -->
                <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider border border-rose-100">Survei Sosial Ekonomi</span>
                            <span class="text-slate-400 text-xs font-mono">15 Maret 2024</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                            Pelaksanaan Survei Sosial Ekonomi Nasional (Susenas) Maret 2024
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                            Petugas pencacah lapangan BPS Kabupaten Manggarai melakukan pendataan langsung ke rumah tangga terpilih guna mengumpulkan data riil mengenai kondisi sosial...
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="#">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 2: Sakernas -->
                <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded bg-bps-50 text-bps-700 text-[10px] font-bold uppercase tracking-wider border border-bps-100">Penguatan Kapasitas</span>
                            <span class="text-slate-400 text-xs font-mono">28 Februari 2024</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                            Pelatihan Calon Petugas Survei Angkatan Kerja Nasional (Sakernas)
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                            Pembekalan konsep dan definisi ketenagakerjaan bagi seluruh pencacah lapangan untuk menghasilkan data Tingkat Pengangguran Terbuka (TPT) dan partisipasi...
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="#">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 3: FGD DDA -->
                <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded bg-amber-50 text-amber-800 text-[10px] font-bold uppercase tracking-wider border border-amber-100">Koordinasi Sektoral</span>
                            <span class="text-slate-400 text-xs font-mono">14 Februari 2024</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                            Focus Group Discussion (FGD): Penyusunan Publikasi Daerah Dalam Angka
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                            Kolaborasi strategis BPS Kabupaten Manggarai bersama Bappeda dan seluruh Organisasi Perangkat Daerah (OPD) dalam rangka harmonisasi, verifikasi, serta sinkronisasi data...
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="#">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 4: ST2023 -->
                <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded bg-teal-50 text-teal-800 text-[10px] font-bold uppercase tracking-wider border border-teal-100">Diseminasi Statistik</span>
                            <span class="text-slate-400 text-xs font-mono">18 Januari 2024</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                            Diseminasi Hasil Sensus Pertanian 2023 Tahap II BPS Manggarai
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                            Pemaparan hasil sensus pertanian mengenai karakteristik usaha pertanian perorangan, demografi petani milenial, dan potensi subsektor perkebunan kopi kepada stakeholder...
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="#">
                            <span>Baca Selengkapnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </article>

                <!-- Activity 5: SIMAKIN -->
                <article class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-bps-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded bg-purple-50 text-purple-800 text-[10px] font-bold uppercase tracking-wider border border-purple-100">Manajemen Kinerja</span>
                            <span class="text-slate-400 text-xs font-mono">08 Januari 2024</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-bps-700 transition-colors">
                            Implementasi SIMAKIN untuk Peningkatan Akuntabilitas Pegawai
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-3">
                            Evaluasi capaian Sasaran Kinerja Pegawai (SKP), verifikasi alokasi pekerjaan tim, dan implementasi aplikasi SIMAKIN untuk mewujudkan birokrasi BPS yang adaptif, lincah, dan...
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a class="text-xs font-bold text-bps-600 hover:text-bps-800 transition-colors flex items-center gap-1" href="#">
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
