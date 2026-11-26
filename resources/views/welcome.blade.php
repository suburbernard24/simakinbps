<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $brandName ?? 'NATAS BPS MANGGARAI' }} - {{ $brandTagline ?? 'Satu Ruang, Beragam Layanan Statistik.' }} | {{ $headerNama ?? 'BPS Kabupaten Manggarai' }}</title>
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
                  DEFAULT: '#123B63',
                  50: '#F4F7FA',
                  100: '#E3ECF4',
                  200: '#C5D8E9',
                  300: '#9BBBD9',
                  400: '#6798C6',
                  500: '#3686C8',
                  600: '#1E5B91',
                  700: '#184A77',
                  800: '#123B63',
                  900: '#0C2844',
                  950: '#07192C',
                },
                secondary: {
                  DEFAULT: '#1E5B91',
                  50: '#EEF5FB',
                  100: '#D6E7F5',
                  200: '#B0D0EB',
                  300: '#7CB0DE',
                  400: '#4C92CF',
                  500: '#3686C8',
                  600: '#1E5B91',
                  700: '#184975',
                  800: '#123B63',
                  900: '#0D2A47',
                },
                accent: {
                  DEFAULT: '#3686C8',
                  50: '#EFF6FC',
                  100: '#DCEBF8',
                  200: '#BAD7F1',
                  300: '#8ABBE7',
                  400: '#589CD9',
                  500: '#3686C8',
                  600: '#1E5B91',
                  700: '#184A77',
                  800: '#123B63',
                  900: '#0E2D4C',
                },
                bps: {
                  50: '#F4F7FA',
                  100: '#E3ECF4',
                  200: '#C5D8E9',
                  300: '#9BBBD9',
                  400: '#6798C6',
                  500: '#3686C8',
                  600: '#1E5B91',
                  700: '#1E5B91',
                  800: '#123B63',
                  900: '#123B63',
                  950: '#07192C',
                },
                navy: {
                  DEFAULT: '#123B63',
                  700: '#1E5B91',
                  800: '#123B63',
                  900: '#0C2844',
                  950: '#07192C'
                },
                surface: '#FFFFFF'
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
        a[href], button:not(:disabled), select:not(:disabled), summary, label[for] {
          cursor: pointer;
        }
        button:disabled {
          cursor: not-allowed;
        }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
          outline: 2px solid #1E5B91;
          outline-offset: 2px;
        }
        @media (prefers-reduced-motion: reduce) {
          *, *::before, *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
          }
        }
        .custom-scrollbar::-webkit-scrollbar {
          height: 6px;
          width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
          background: #f1f5f9;
          border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
          background: #cbd5e1;
          border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
          background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#F4F7FA] text-slate-800 antialiased selection:bg-[#1E5B91] selection:text-white">

<!-- BEGIN: MainNavbar -->
<header class="sticky top-0 z-40 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-6">
        <!-- Brand & Identity (NATAS BPS MANGGARAI) -->
        <a class="flex items-center gap-3 shrink-0" href="{{ url('/') }}" title="{{ $brandName }} - {{ $brandTagline }}">
            <img src="{{ $logoNatas }}" alt="{{ $brandName }}" width="180" height="44" class="h-9 sm:h-11 w-auto max-w-[240px] object-contain">
        </a>

        <!-- Primary Navigation Links (Dynamic dari Dashboard Admin) -->
        <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-1 text-[13px] font-medium text-slate-600">
            @foreach($navMenus as $nav)
                @if(!empty($nav['is_active']))
                    <a class="px-3 py-2 rounded-md transition-colors {{ $loop->first ? 'text-[#123B63] font-semibold bg-[#F4F7FA]' : 'hover:text-[#1E5B91] hover:bg-[#F4F7FA]' }}"
                       href="{{ $nav['url'] }}"
                       target="{{ $nav['target'] ?? '_self' }}">
                        {{ $nav['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- User Action / Portal Access -->
        <div class="flex items-center gap-2.5">
            @if(auth()->check())
                <a class="inline-flex items-center justify-center px-4 py-2.5 min-h-[40px] rounded-md text-xs font-semibold bg-[#1E5B91] text-white hover:bg-[#123B63] transition-colors" href="{{ route('dashboard') }}">
                    <span>Dashboard</span>
                </a>
            @else
                <a class="inline-flex items-center justify-center px-4 py-2.5 min-h-[40px] rounded-md text-xs font-semibold bg-[#1E5B91] text-white hover:bg-[#123B63] transition-colors" href="{{ route('login') }}">
                    <span>{{ $btnLabel }}</span>
                </a>
            @endif

            <!-- Mobile Menu Toggle Button -->
            <button type="button" onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-md text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Nav Dropdown -->
    <div id="mobile-nav-menu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
        @foreach($navMenus as $nav)
            @if(!empty($nav['is_active']))
                <a class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-[#1E5B91] hover:bg-[#F4F7FA] rounded-md transition-colors"
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

<main>
<!-- BEGIN: HeroSection -->
<section class="py-12 sm:py-16 bg-white border-b border-slate-200" data-purpose="hero-section" id="beranda">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            <div class="lg:col-span-8">
                <p class="text-xs font-semibold text-[#1E5B91] mb-3">
                    {{ $brandName }} · {{ $brandSub }}
                </p>

                <h1 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-[#123B63] tracking-tight leading-[1.15]">
                    Satu Ruang, <span class="text-[#1E5B91]">Beragam Layanan Statistik.</span>
                </h1>

                <p class="mt-4 text-sm sm:text-base text-slate-600 max-w-2xl leading-relaxed">
                    {{ $brandDesc }}
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button type="button" onclick="bukaModalKonsultasi()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 min-h-[44px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs sm:text-sm font-semibold transition-colors">
                        <i class="fa-solid fa-calendar-plus text-xs" aria-hidden="true"></i>
                        <span>Booking Konsultasi Data</span>
                    </button>
                    <a href="#indikator-terkini" class="inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] rounded-md bg-[#F4F7FA] hover:bg-slate-200/70 text-[#123B63] border border-slate-200 text-xs sm:text-sm font-semibold transition-colors">
                        Lihat Indikator Terkini
                    </a>
                </div>
            </div>

            <aside class="lg:col-span-4 bg-[#F4F7FA] border border-slate-200 rounded-lg p-5">
                <h2 class="text-xs font-bold text-[#123B63] uppercase tracking-wide pb-3 border-b border-slate-200">
                    Informasi Layanan PST
                </h2>
                <dl class="mt-3 space-y-2.5 text-xs">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Jam Kantor</dt>
                        <dd class="font-semibold text-slate-800 text-right">{{ $fs['pst_jam_layanan'] ?? 'Senin - Jumat, 08.00 - 15.30 WITA' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Booking Online</dt>
                        <dd class="font-semibold text-[#1E5B91] text-right">Aktif 24 Jam / 7 Hari</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">WhatsApp PST</dt>
                        <dd class="font-semibold text-slate-800 text-right">
                            <a href="https://wa.me/{{ $pstWaClean }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#1E5B91] underline underline-offset-2">{{ $pstWa }}</a>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Wilayah Kerja</dt>
                        <dd class="font-semibold text-slate-800 text-right">{{ $headerLokasi }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
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
</main>

{{-- 8. Footer --}}
@include('frontend.partials.footer')

{{-- 11. Modals (Booking Konsultasi 24/7, Buku Tamu Digilib, WebAPI Token, Toast) --}}
@include('frontend.partials.modals')

{{-- 12. Back to Top Floating & Interactive Scripts --}}
@include('frontend.partials.scripts')

</body>
</html>
