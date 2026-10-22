<!-- BEGIN: IndikatorTerkiniSection -->
<section class="py-16 bg-[#F4F7FB] border-b border-slate-200/70 relative" data-purpose="macro-indicators" id="indikator-terkini">
    <!-- Anchor alias for older links -->
    <div id="indikator" class="absolute -top-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title & Subtitle -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#0B4F82]/10 text-[#0B4F82] tracking-wide uppercase">
                Data Strategis Resmi
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#172B4D] tracking-tight mt-2">
                Indikator Terkini Kabupaten Manggarai
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed">
                Data sensus, survei, dan indikator pembangunan strategis resmi BPS Kabupaten Manggarai dengan akses langsung ke Tabel Dinamis.
            </p>
        </div>

        <!-- Indicators Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($indikatorsList as $ind)
                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-md border border-slate-200/80 hover:border-[#0B4F82]/40 flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">
                                {{ $ind->badge_text ?: 'Tabel Dinamis' }}
                            </span>
                            <span class="font-medium text-slate-500">{{ $ind->periode }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#0B4F82]/10 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center mb-4 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider line-clamp-2">
                            {{ $ind->judul }}
                        </h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] font-mono tracking-tight">{{ $ind->nilai }}</span>
                            @if($ind->sub_judul)
                                <span class="text-sm font-semibold text-slate-500">{{ $ind->sub_judul }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                            {{ $ind->deskripsi ?: 'Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai.' }}
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B4F82] group-hover:text-[#08385d] transition-colors" href="{{ $ind->link_tabel ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Preset Default 4 Indicators -->
                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-md border border-slate-200/80 hover:border-[#0B4F82]/40 flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">Tabel Dinamis</span>
                            <span class="font-medium text-slate-500">Triwulan II 2024</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#0B4F82]/10 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center mb-4 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pertumbuhan Ekonomi (y-on-y)</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] font-mono tracking-tight">5,01</span>
                            <span class="text-sm font-semibold text-slate-500">%</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">Pertumbuhan ekonomi y-on-y menurut lapangan usaha Kabupaten Manggarai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B4F82] group-hover:text-[#08385d] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-md border border-slate-200/80 hover:border-[#0B4F82]/40 flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">Tabel Dinamis</span>
                            <span class="font-medium text-slate-500">2023</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#0B4F82]/10 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center mb-4 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Umur Harapan Hidup Saat Lahir (UHH)</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] font-mono tracking-tight">72,16</span>
                            <span class="text-sm font-semibold text-slate-500">Tahun</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">Indikator derajat kesehatan masyarakat hasil Long Form Sensus Penduduk 2020.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B4F82] group-hover:text-[#08385d] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-md border border-slate-200/80 hover:border-[#0B4F82]/40 flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">Tabel Dinamis</span>
                            <span class="font-medium text-slate-500">SP2020 LF</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#0B4F82]/10 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center mb-4 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Umur Harapan Hidup Perempuan</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] font-mono tracking-tight">73,61</span>
                            <span class="text-sm font-semibold text-slate-500">Tahun</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">Rata-rata perkiraan banyak tahun yang dapat ditempuh oleh bayi perempuan yang baru lahir.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B4F82] group-hover:text-[#08385d] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xs hover:shadow-md border border-slate-200/80 hover:border-[#0B4F82]/40 flex flex-col justify-between transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-4">
                            <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 uppercase font-mono text-[10px] font-semibold border border-slate-200/60">Tabel Dinamis</span>
                            <span class="font-medium text-slate-500">SP2020 LF</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#0B4F82]/10 text-[#0B4F82] group-hover:bg-[#0B4F82] group-hover:text-white flex items-center justify-center mb-4 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Umur Harapan Hidup Laki-Laki</h3>
                        <div class="mt-2.5 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] font-mono tracking-tight">69,63</span>
                            <span class="text-sm font-semibold text-slate-500">Tahun</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">Rata-rata perkiraan banyak tahun yang dapat ditempuh oleh bayi laki-laki yang baru lahir.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B4F82] group-hover:text-[#08385d] transition-colors" href="https://manggaraikab.bps.go.id" target="_blank" rel="noopener noreferrer">
                            <span>Buka Tabel Dinamis</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
<!-- END: IndikatorTerkiniSection -->
