<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $brandName ?? 'NATAS BPS MANGGARAIi' }} — {{ $brandTagline ?? 'Satu Ruang, Beragam Layanan Statistik.' }} | {{ $headerNama ?? 'BPS Kabupaten Manggarai' }}</title>
    <meta name="description" content="{{ $brandName ?? 'NATAS BPS MANGGARAI' }} ({{ $brandSub ?? 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai' }}): {{ $brandTagline ?? 'Satu Ruang, Beragam Layanan Statistik.' }} Portal Pelayanan Statistik Terpadu (PST), Informasi Indikator Makro, dan Publikasi Resmi BPS Kabupaten Manggarai.">

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
        $brandName     = $fs['brand_name']      ?? 'NATAS BPS MANGGARAI';
        $brandSub      = $fs['brand_sub']       ?? 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai';
        $brandTagline  = $fs['brand_tagline']   ?? 'Satu Ruang, Beragam Layanan Statistik.';
        $brandDesc     = $fs['brand_desc']      ?? 'NATAS (Navigasi Akses Terpadu Layanan Statistik) BPS Kabupaten Manggarai adalah portal satu ruang untuk beragam layanan statistik terpadu, publikasi resmi, data indikator makro, rekomendasi kegiatan, dan konsultasi interaktif secara terintegrasi, transparan, dan terpercaya.';
        $btnLabel      = $fs['btn_masuk_label'] ?? 'Masuk';
        $headerNama    = $fs['header_nama']     ?? 'BPS Kabupaten Manggarai';
        $headerLokasi  = $fs['header_lokasi']   ?? 'Kabupaten Manggarai';

        // Kontak & Layanan
        $pstWa         = $fs['pst_whatsapp']    ?? '08113904990';
        $pstWaClean    = preg_replace('/[^0-9]/', '', $pstWa);
        if (str_starts_with($pstWaClean, '0')) {
            $pstWaClean = '62' . substr($pstWaClean, 1);
        }
        $pengaduanMail = $fs['pengaduan_email'] ?? 'bps5310@bps.go.id';
        $bpsDomain     = $fs['bps_domain']      ?? '5310';
        $bpsApiKey     = $fs['bps_api_key']     ?? '';

        // Favicon & Gambar
        $logoFavicon   = !empty($fs['img_logo_natas']) ? asset('storage/' . $fs['img_logo_natas']) : asset('images/LogoFE_natas.webp');
        $logoBps       = !empty($fs['img_logo_bps']) ? asset('storage/' . $fs['img_logo_bps']) : asset('images/logo_bps.png');
        $logoSimakin   = !empty($fs['img_logo_simakin']) ? asset('storage/' . $fs['img_logo_simakin']) : asset('images/simakin.png');
        $logoNatas     = !empty($fs['img_logo_natas']) ? asset('storage/' . $fs['img_logo_natas']) : asset('images/LogoFE_natas.webp');

        // Navigasi Menu Halaman Utama
        $navMenus       = FrontendSetting::getNavMenu();

        // Konten Dinamis dari Database
        $infografisList = \App\Models\FrontendInfografis::where('is_active', true)->orderBy('urutan')->get();
        $quotesList     = \App\Models\FrontendQuote::where('is_active', true)->orderBy('urutan')->get();
        $indikatorsList = \App\Models\FrontendIndikator::where('is_active', true)->orderBy('urutan')->get();
    @endphp

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $logoFavicon }}?v=4">
    <link rel="shortcut icon" href="{{ $logoFavicon }}?v=4">

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
<div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 relative z-50 border-b border-slate-800" data-purpose="announcement-banner">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5 truncate text-[11px] sm:text-xs">
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold text-[10px] uppercase tracking-wider border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Front Office
            </span>
            <span class="truncate text-slate-300">Selamat datang di <strong class="text-white font-medium">{{ $brandName }}</strong> — {{ $brandTagline }}</span>
        </div>
        <a class="inline-flex items-center gap-1 text-slate-300 hover:text-white font-medium text-[11px] sm:text-xs transition-colors shrink-0 group" href="https://wa.me/{{ $pstWaClean }}" target="_blank" rel="noopener noreferrer">
            <span>Konsultasi Online</span>
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>
    </div>
</div>
<!-- END: AnnouncementBar -->

<!-- BEGIN: MainNavbar -->
<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/60 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-18 flex items-center justify-between gap-6">
        <!-- Brand & Identity (NATAS BPS MANGGARAI) -->
        <a class="flex items-center gap-3 group" href="{{ url('/') }}" title="{{ $brandName }} — {{ $brandTagline }}">
            <img src="{{ $logoNatas }}" alt="{{ $brandName }}" class="h-9 sm:h-11 w-auto max-w-[240px] object-contain transition-transform duration-200 group-hover:opacity-90">
        </a>

        <!-- Primary Navigation Links (Dynamic dari Dashboard Admin) -->
        <nav class="hidden lg:flex items-center gap-1 text-[13px] font-medium text-slate-600">
            @foreach($navMenus as $nav)
                @if(!empty($nav['is_active']))
                    <a class="px-3 py-1.5 rounded-lg transition-colors {{ $loop->first ? 'text-[#0B4F82] font-semibold bg-slate-100/80' : 'hover:text-[#0B4F82] hover:bg-slate-50' }}"
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
                <a class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-xs active:scale-[0.98]" href="{{ route('dashboard') }}">
                    <span>Dashboard</span>
                    <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </a>
            @else
                <a class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-[#0B4F82] text-white hover:bg-[#083E67] transition-all shadow-xs active:scale-[0.98]" href="{{ route('login') }}">
                    <span>{{ $btnLabel }}</span>
                    <svg class="w-3.5 h-3.5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </a>
            @endif

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none" aria-label="Buka Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Nav Dropdown -->
    <div id="mobile-nav-menu" class="hidden lg:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md px-4 pt-2 pb-4 space-y-1 shadow-lg">
        @foreach($navMenus as $nav)
            @if(!empty($nav['is_active']))
                <a class="block px-3 py-2 text-xs font-medium text-slate-700 hover:text-[#0B4F82] hover:bg-slate-50 rounded-lg transition-colors"
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
<section class="relative pt-16 pb-20 overflow-hidden bg-gradient-to-b from-white via-slate-50/70 to-[#F4F7FB] border-b border-slate-200/60" data-purpose="hero-section" id="beranda">
    <!-- Subtle Dot Grid Blueprint Background -->
    <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px] opacity-40 pointer-events-none -z-10 [mask-image:radial-gradient(ellipse_60%_50%_at_50%_15%,#000_70%,transparent_100%)]"></div>
    
    <!-- Soft Ambient Light Glows -->
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[720px] h-[320px] bg-gradient-to-b from-sky-200/30 via-indigo-100/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <!-- Floating Micro Breadcrumb Badge with Pulsing Beacon -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/90 backdrop-blur-md border border-slate-200/90 shadow-2xs mb-6 text-xs text-slate-700 font-medium transition-all hover:border-[#0B4F82]/30">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-slate-800 font-bold tracking-tight">{{ $brandName }}</span>
            <span class="text-slate-300">•</span>
            <span class="text-[#0B4F82] font-semibold tracking-tight">{{ $brandSub }}</span>
        </div>

        <!-- Main Headline with Gradient Text -->
        <h1 class="text-3xl sm:text-5xl lg:text-[50px] font-extrabold text-slate-900 tracking-tight leading-[1.15]">
            Satu Ruang, <span class="bg-gradient-to-r from-[#0B4F82] via-sky-600 to-[#062E4D] bg-clip-text text-transparent">Beragam Layanan Statistik.</span>
        </h1>
        <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl mx-auto font-normal leading-relaxed">
            {{ $brandDesc }}
        </p>
    </div>
</section>
<!-- END: HeroSection -->


<!-- =========================================================================
     MODULAR SECTIONS (FRONTEND PARTIALS) SESUAI URUTAN MENU BERANDA
========================================================================= -->

{{-- 1. Indikator Terkini Carousel (Tabel Dinamis BPS - Maksimal 4 & Geser >) --}}
@include('frontend.partials.indikator-carousel')

{{-- 2. 4 Kotak Layanan BPS (Konsultasi 24/7, OPAC, ROMANTIK, SILASTIK) --}}
@include('frontend.partials.quick-actions')

{{-- 4. Buku Tamu BPS (Standar Perpustakaan Digital Digilib Guestbook) --}}
@include('frontend.partials.buku-tamu')

{{-- 5. Infografis 6 Kotak Utama / Terbaru (Diinput Admin) --}}
@include('frontend.partials.infografis')

{{-- 6. Kutipan / Quote Inspiratif & Integritas Data --}}
@include('frontend.partials.quotes')

{{-- 7. Publikasi Resmi & Tabel Statistik --}}
@include('frontend.partials.publikasi')

{{-- 8. Footer --}}
@include('frontend.partials.footer')

{{-- 11. Modals (Booking Konsultasi 24/7, Buku Tamu Digilib, WebAPI Token, Toast) --}}
@include('frontend.partials.modals')

{{-- 12. Back to Top Floating & Interactive Scripts --}}
@include('frontend.partials.scripts')

</body>
</html>
