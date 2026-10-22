<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PTSP SIMAKIN BPS Kabupaten Manggarai — Portal Satu Pintu Data &amp; Layanan Statistik</title>

    <!-- Plus Jakarta Sans & JetBrains Mono Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet"/>

    <!-- Font Awesome Icons 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script data-purpose="tailwind-config">
        tailwind.config = {
          theme: {
            extend: {
              fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                mono: ['"JetBrains Mono"', 'monospace'],
              },
              colors: {
                primary: {
                  DEFAULT: '#0B4F82',
                  50: '#F0F6FA',
                  100: '#DCE8F2',
                  200: '#B9D1E5',
                  300: '#8FB5D4',
                  400: '#5A94C0',
                  500: '#2E75AC',
                  600: '#0B4F82',
                  700: '#083E67',
                  800: '#062E4D',
                  900: '#041F34',
                },
                bps: {
                  50: '#F0F6FA',
                  100: '#DCE8F2',
                  200: '#B9D1E5',
                  300: '#8FB5D4',
                  400: '#5A94C0',
                  500: '#2E75AC',
                  600: '#0B4F82',
                  700: '#083E67',
                  800: '#062E4D',
                  900: '#172B4D',
                  950: '#0F1D34',
                },
                navy: {
                  DEFAULT: '#172B4D',
                  700: '#243C64',
                  800: '#1C3257',
                  900: '#172B4D',
                  950: '#0F1D34'
                },
                surface: '#F4F7FB'
              }
            }
          }
        }
    </script>

    @php
        use App\Models\FrontendSetting;
        use App\Services\BpsApiService;

        $fs = FrontendSetting::allKeyed();
        $bpsService = app(BpsApiService::class);

        // Teks & Branding dari Pengaturan Admin
        $brandTagline  = $fs['brand_tagline']   ?? 'Sistem Informasi Manajemen Kinerja';
        $brandSub      = $fs['brand_sub']        ?? 'BPS Kabupaten Manggarai';
        $brandDesc     = $fs['brand_desc']       ?? 'SIMAKINBPS adalah platform digital manajemen kinerja pegawai di BPS Kabupaten Manggarai yang dirancang untuk mendukung perencanaan, pemantauan, dan evaluasi kinerja secara terintegrasi, transparan, dan efektif.';
        $btnLabel      = $fs['btn_masuk_label']  ?? 'Masuk';
        $headerNama    = $fs['header_nama']      ?? 'BPS Kabupaten Manggarai';
        $headerLokasi  = $fs['header_lokasi']    ?? 'Kabupaten Manggarai';

        // Kontak & Layanan
        $pstWa         = $fs['pst_whatsapp']     ?? '08113904990';
        $pstWaClean    = preg_replace('/[^0-9]/', '', $pstWa);
        if (str_starts_with($pstWaClean, '0')) {
            $pstWaClean = '62' . substr($pstWaClean, 1);
        }
        $pengaduanMail = $fs['pengaduan_email']  ?? 'bps5310@bps.go.id';
        $bpsDomain     = $fs['bps_domain']       ?? '5310';
        $bpsApiKey     = $fs['bps_api_key']      ?? '';

        // Favicon & Gambar
        $logoFavicon   = !empty($fs['img_logo_simakin']) ? asset('storage/' . $fs['img_logo_simakin']) : asset('images/simakin-favicon.png');
        $logoBps       = !empty($fs['img_logo_bps']) ? asset('storage/' . $fs['img_logo_bps']) : asset('images/logo_bps.png');
        $logoSimakin   = !empty($fs['img_logo_simakin']) ? asset('storage/' . $fs['img_logo_simakin']) : asset('images/simakin.png');

        // Navigasi Menu Halaman Utama
        $navMenus       = FrontendSetting::getNavMenu();

        // Konten Dinamis dari Database
        $infografisList = \App\Models\FrontendInfografis::where('is_active', true)->orderBy('urutan')->get();
        $quotesList     = \App\Models\FrontendQuote::where('is_active', true)->orderBy('urutan')->get();
        $kegiatansList  = \App\Models\FrontendKegiatan::where('is_active', true)->orderBy('urutan')->get();
        $indikatorsList = \App\Models\FrontendIndikator::where('is_active', true)->orderBy('urutan')->get();
    @endphp

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $logoFavicon }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    <!-- Micro-Styles -->
    <style data-purpose="base-enhancements">
        body {
          font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }
        .custom-scrollbar::-webkit-scrollbar {
          height: 6px;
          width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
          background: #f1f5f9;
          border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
          background: #cbd5e1;
          border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
          background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#F4F7FB] text-slate-800 antialiased selection:bg-[#0B4F82] selection:text-white">

<!-- BEGIN: AnnouncementBar -->
<div class="bg-[#172B4D] text-white text-xs py-2.5 px-4 relative z-50 border-b border-slate-700/60" data-purpose="announcement-banner">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5 truncate">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-[#0B4F82]/40 text-sky-200 font-semibold text-[10px] uppercase tracking-wider border border-sky-400/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Pengumuman
            </span>
            <p class="truncate text-slate-200 text-xs">
                {{ $fs['ticker_text'] ?? 'Selamat datang di Website Resmi Pelayanan Statistik Terpadu BPS Kabupaten Manggarai — Hubungi PST Online melalui WhatsApp untuk permohonan data & konsultasi.' }}
            </p>
        </div>
        <a class="inline-flex items-center gap-1 text-sky-300 hover:text-white font-medium transition-colors shrink-0 group" href="#hubungi-pst">
            <span>PST BPS Manggarai</span>
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>
    </div>
</div>
<!-- END: AnnouncementBar -->

<!-- BEGIN: MainNavbar -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-6">
        <!-- Brand & Identity -->
        <a class="flex items-center gap-3.5 group" href="{{ url('/') }}">
            <div class="h-11 w-11 flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                <img src="{{ $logoBps }}" alt="Logo BPS" class="h-11 w-auto max-w-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-extrabold text-[#172B4D] tracking-tight flex items-center gap-1.5 leading-tight">
                    SIMAKIN
                </span>
                 <span class="text-[11px] font-bold tracking-widest text-[#0B4F82] uppercase leading-tight">BPS Kabupaten Manggarai</span>
            </div>
        </a>

        <!-- Primary Navigation Links (Dynamic dari Dashboard Admin) -->
        <nav class="hidden lg:flex items-center gap-1 text-sm font-medium text-slate-600">
            @foreach($navMenus as $nav)
                @if(!empty($nav['is_active']))
                    <a class="px-3.5 py-2 {{ $loop->first ? 'text-[#0B4F82] font-semibold rounded-lg bg-[#F0F6FA]' : 'hover:text-[#0B4F82] hover:bg-slate-50 rounded-lg transition-colors' }}"
                       href="{{ $nav['url'] }}"
                       target="{{ $nav['target'] ?? '_self' }}">
                        {{ $nav['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- User Action / Portal Access -->
        <div class="flex items-center gap-3">
            @if(auth()->check())
                <a class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm hover:shadow active:scale-[0.98]" href="{{ route('dashboard') }}">
                    <span>Buka Dashboard</span>
                    <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </a>
            @else
                <a class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-[#0B4F82] text-white hover:bg-[#083E67] transition-all shadow-sm hover:shadow active:scale-[0.98]" href="{{ route('login') }}">
                    <span>{{ $btnLabel }}</span>
                    <svg class="w-4 h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </a>
            @endif

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none" aria-label="Buka Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Nav Dropdown -->
    <div id="mobile-nav-menu" class="hidden lg:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md px-4 pt-2 pb-4 space-y-1 shadow-lg">
        @foreach($navMenus as $nav)
            @if(!empty($nav['is_active']))
                <a class="block px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#0B4F82] hover:bg-slate-50 rounded-lg transition-colors"
                   href="{{ $nav['url'] }}"
                   target="{{ $nav['target'] ?? '_self' }}"
                   onclick="document.getElementById('mobile-nav-menu').classList.add('hidden')">
                    {{ $nav['label'] }}
                </a>
            @endif
        @endforeach
    </div>
</header>
<!-- END: MainNavbar -->

<!-- BEGIN: HeroSection -->
<section class="relative pt-12 pb-16 overflow-hidden bg-[#F4F7FB] border-b border-slate-200/60" data-purpose="hero-section" id="beranda">
    <!-- Subtle Background Glow Ornaments -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[800px] h-[350px] bg-gradient-to-b from-[#0B4F82]/10 via-sky-100/10 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Micro Breadcrumb Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 shadow-xs mb-6 text-xs text-slate-600 font-medium">
            <svg class="w-3.5 h-3.5 text-[#0B4F82]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            <span>User Front-Office</span>
            <span class="text-slate-300">•</span>
            <span class="text-[#0B4F82] font-semibold">Pelayanan Statistik Terpadu (PST)</span>
        </div>

        <!-- Main Headline (32-40px desktop) -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#172B4D] tracking-tight leading-[1.2]">
            Pelayanan Terpadu Satu Pintu (PTSP)<br/>
            <span class="text-[#0B4F82]">BPS Kabupaten Manggarai</span>
        </h1>
        <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-3xl mx-auto font-normal leading-relaxed">
            {{ $brandDesc }}
        </p>

        <!-- Unified Modern Search Bar -->
        <div class="mt-8 max-w-3xl mx-auto">
            <div class="p-2 bg-white rounded-2xl shadow-sm border border-slate-200/90 flex flex-col sm:flex-row items-center gap-2 transition-all focus-within:border-[#0B4F82] focus-within:ring-2 focus-within:ring-[#0B4F82]/20">
                <!-- Category Selector Dropdown -->
                <div class="relative w-full sm:w-auto shrink-0 border-b sm:border-b-0 sm:border-r border-slate-200">
                    <select id="hero-search-category" aria-label="Pilih Kategori Pencarian" class="w-full sm:w-44 py-2.5 pl-3 pr-8 text-xs font-semibold text-slate-700 bg-transparent border-0 focus:ring-0 cursor-pointer">
                        <option value="all">Semua Kategori</option>
                        <option value="indikator">Indikator Strategis</option>
                        <option value="publikasi">Publikasi Resmi</option>
                        <option value="tabel">Tabel Dinamis</option>
                        <option value="infografis">Infografis</option>
                    </select>
                </div>
                <!-- Main Input Field -->
                <div class="relative flex-1 w-full flex items-center">
                    <svg class="w-5 h-5 text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <input id="hero-search-query" onkeydown="if(event.key === 'Enter') handleHeroSearch()" class="w-full py-2 px-3 text-sm text-slate-800 placeholder-slate-400 bg-transparent border-0 focus:ring-0" placeholder="Cari indikator: PDRB, inflasi, kemiskinan, sensus, DDA..." type="text"/>
                </div>
                <!-- Submit Button -->
                <button onclick="handleHeroSearch()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#0B4F82] hover:bg-[#083E67] text-white font-medium text-sm transition-colors shadow-xs shrink-0 flex items-center justify-center gap-2">
                    <span>Cari Data</span>
                </button>
            </div>

            <!-- Trending Keywords -->
            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1 font-medium text-slate-600">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Pencarian Populer:
                </span>
                <a class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-[#0B4F82] rounded-full border border-slate-200 transition-colors" href="#data-pokok">PDRB 2024</a>
                <a class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-[#0B4F82] rounded-full border border-slate-200 transition-colors" href="#data-pokok">Kemiskinan</a>
                <a class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-[#0B4F82] rounded-full border border-slate-200 transition-colors" href="#data-pokok">Inflasi Manggarai</a>
                <a class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-[#0B4F82] rounded-full border border-slate-200 transition-colors" href="#infografis">Sensus Pertanian ST2023</a>
                <a class="px-2.5 py-1 bg-white hover:bg-slate-100 hover:text-[#0B4F82] rounded-full border border-slate-200 transition-colors" href="#publikasi">DDA 2024</a>
            </div>

            <!-- Quick Access Shortcuts -->
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-[#0B4F82] hover:text-[#0B4F82] text-slate-700 text-xs font-semibold transition-all shadow-xs" href="#indikator-terkini">
                    <svg class="w-4 h-4 text-[#0B4F82]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Indikator Makro</span>
                </a>
                <a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-[#0B4F82] hover:text-[#0B4F82] text-slate-700 text-xs font-semibold transition-all shadow-xs" href="#publikasi">
                    <svg class="w-4 h-4 text-[#0B4F82]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Publikasi DDA</span>
                </a>
                <a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-[#0B4F82] hover:text-[#0B4F82] text-slate-700 text-xs font-semibold transition-all shadow-xs" href="#buku-tamu">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    <span>Buku Tamu Digital</span>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- END: HeroSection -->


<!-- =========================================================================
     MODULAR SECTIONS (FRONTEND PARTIALS)
========================================================================= -->

{{-- 1. Akses Cepat PST, Romantik, Kalender & WebAPI Bar --}}
@include('frontend.partials.quick-actions')

{{-- 2. Indikator Terkini Kabupaten Manggarai --}}
@include('frontend.partials.indikator-carousel')

{{-- 3. Publikasi Resmi & Tabel Statistik --}}
@include('frontend.partials.publikasi')

{{-- 4. 8 Data Pokok & Indikator Strategis --}}
@include('frontend.partials.data-pokok')

{{-- 5. Galeri Infografis BPS --}}
@include('frontend.partials.infografis')

{{-- 6. Quotes of the Day --}}
@include('frontend.partials.quotes')

{{-- 7. Dokumentasi & Warta Kegiatan --}}
@include('frontend.partials.kegiatan')

{{-- 8. Pelayanan Statistik Terpadu (PST) & Maklumat Pengaduan --}}
@include('frontend.partials.pst-service')

{{-- 9. Footer --}}
@include('frontend.partials.footer')

{{-- 10. Modals (Buku Tamu, WebAPI Token, Toast) --}}
@include('frontend.partials.modals')

{{-- 11. Back to Top Floating & Interactive Scripts --}}
@include('frontend.partials.scripts')

</body>
</html>
