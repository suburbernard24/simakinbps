<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BPS Kabupaten Manggarai - Pelayanan Statistik Terpadu (PST) &amp; Portal Data SIMAKINBPS</title>

    <!-- Google Fonts for Modern Ultra-Clean Typography -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">

    <!-- Font Awesome Icons 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS v3 with Forms & Container-Queries -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Tailwind Configuration -->
    <script data-purpose="tailwind-config">
        tailwind.config = {
          theme: {
            extend: {
              fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
              },
              colors: {
                brand: {
                  50: '#eff6ff',
                  100: '#dbeafe',
                  500: '#2563eb',
                  600: '#1d4ed8',
                  700: '#1e40af',
                  800: '#1e3a8a',
                  900: '#0f2b5c',
                  navy: '#0a1e3f'
                }
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
        $brandDesc     = $fs['brand_desc']       ?? 'SIMAKIN BPS adalah platform digital manajemen kinerja pegawai di BPS Kabupaten Manggarai yang dirancang untuk mendukung perencanaan, pemantauan, dan evaluasi kinerja secara terintegrasi, transparan, dan efektif.';
        $btnLabel      = $fs['btn_masuk_label']  ?? 'Masuk SIMAKIN';
        $headerNama    = $fs['header_nama']      ?? 'Badan Pusat Statistik';
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
        $logoFavicon   = isset($fs['img_logo_simakin']) ? asset('storage/' . $fs['img_logo_simakin']) : asset('images/simakin-favicon.png');

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

    <!-- Global Custom Styles -->
    <style data-purpose="custom-styling">
        body {
          font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }
        .hero-pattern {
          background-color: #ffffff;
          background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px), radial-gradient(#e2e8f0 0.8px, #ffffff 0.8px);
          background-size: 32px 32px;
          background-position: 0 0, 16px 16px;
        }
        .custom-scrollbar::-webkit-scrollbar {
          width: 6px;
          height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
          background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
          background: #cbd5e1;
          border-radius: 4px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-600 selection:text-white">

<!-- BEGIN: MainNavbar -->
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-20">
<!-- Brand / Identity -->
<div class="flex items-center space-x-3">
<a href="#" class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-700 to-brand-900 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
<!-- BPS Style Stylized Geometric Mark -->
<svg class="w-7 h-7" fill="none" viewBox="0 0 24 24">
<path d="M4 6L12 2L20 6V18L12 22L4 18V6Z" stroke="white" stroke-linejoin="round" stroke-width="2"></path>
<path d="M12 2V22" stroke="white" stroke-width="2"></path>
<path d="M4 12H20" stroke="white" stroke-width="2"></path>
</svg>
</a>
<div>
<div class="flex items-center space-x-2">
<span class="text-xs font-extrabold uppercase tracking-widest text-blue-700">{{ $headerNama }}</span>
<span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">{{ $headerLokasi }}</span>
</div>
<h1 class="text-lg font-bold text-slate-900 leading-tight">SIMAKIN BPS Manggarai</h1>
</div>
</div>
<!-- Navigation Links (Dynamic dari Dashboard Admin) -->
<div class="hidden lg:flex items-center space-x-1">
@foreach($navMenus as $nav)
    @if(!empty($nav['is_active']))
        <a class="px-3.5 py-2 text-sm font-medium {{ ($loop->first && ($nav['url'] === '#' || $nav['url'] === '')) ? 'font-semibold text-blue-700 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} rounded-lg transition-colors"
           href="{{ $nav['url'] }}"
           target="{{ $nav['target'] ?? '_self' }}">
            {{ $nav['label'] }}
        </a>
    @endif
@endforeach
</div>
<!-- Primary Action & Mobile Menu Toggle -->
<div class="flex items-center space-x-2 sm:space-x-3">
@if(auth()->check())
<a class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition-colors" href="{{ route('dashboard') }}">
<span class="">Buka Dashboard</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
@else
<a class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-brand-900 text-white hover:bg-blue-800 shadow-sm transition-colors" href="{{ route('login') }}">
<span class="">{{ $btnLabel }}</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
@endif

<!-- Hamburger Button for Mobile -->
<button type="button" onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition focus:outline-none" aria-label="Buka Menu">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
</div>
</div>
</div>

<!-- Mobile Nav Dropdown -->
<div id="mobile-nav-menu" class="hidden lg:hidden border-t border-slate-200 bg-white/95 backdrop-blur-md px-4 pt-2 pb-4 space-y-1 shadow-lg">
@foreach($navMenus as $nav)
    @if(!empty($nav['is_active']))
        <a class="block px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
           href="{{ $nav['url'] }}"
           target="{{ $nav['target'] ?? '_self' }}"
           onclick="document.getElementById('mobile-nav-menu').classList.add('hidden')">
            {{ $nav['label'] }}
        </a>
    @endif
@endforeach
</div>
</nav>
<!-- END: MainNavbar -->

<!-- BEGIN: HeroSection -->
<section class="hero-pattern relative pt-12 pb-20 border-b border-slate-200">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
<!-- Clean Breadcrumb Pill -->
<div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200/80 text-blue-800 text-xs font-semibold mb-6 shadow-xs">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">User Front - Office</span>
<span class="text-blue-300">•</span>
<span class="">Pelayanan Statistik Terpadu (PST)</span>
</div>
<!-- Main Heading -->
<h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight max-w-4xl mx-auto leading-tight sm:leading-none mb-4">
        Pelayanan Terpadu Satu Pintu (PTSP)<br class="hidden sm:inline">
<span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-indigo-800">BPS Kabupaten Manggarai</span>
</h1>
<!-- Subtitle Description -->
<p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-600 font-normal leading-relaxed mb-8">
        {{ $brandDesc }}
</p>
<!-- Sleek Modern Unified Search -->
<div class="max-w-3xl mx-auto bg-white p-2 rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-5">
<div class="flex flex-col sm:flex-row items-center gap-2">
<!-- Category Dropdown -->
<div class="w-full sm:w-auto relative border-b sm:border-b-0 sm:border-r border-slate-200 pr-1">
<select id="hero-search-category" class="w-full sm:w-44 text-xs font-medium text-slate-700 bg-transparent py-2.5 pl-3 pr-8 rounded-lg focus:outline-none focus:ring-0 border-0 cursor-pointer">
<option value="semua">Semua Kategori</option>
<option value="publikasi">Publikasi BPS</option>
<option value="indikator">Indikator Makro</option>
<option value="brs">Berita Resmi Statistik</option>
<option value="tabel">Tabel Statistik</option>
</select>
</div>
<!-- Input Box -->
<div class="w-full flex-1 flex items-center px-3">
<svg class="w-5 h-5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<input id="hero-search-query" onkeydown="if(event.key === 'Enter') handleHeroSearch()" class="w-full text-sm text-slate-800 placeholder-slate-400 border-0 focus:outline-none focus:ring-0 py-2" placeholder="Cari indikator: PDRB, inflasi, kemiskinan, sensus, DDA..." type="text">
</div>
<!-- Submit CTA -->
<button onclick="handleHeroSearch()" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow transition-all shrink-0">
            Cari Data
          </button>
</div>
</div>
<!-- Trending Search Tags -->
<div class="flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500 mb-8">
<span class="inline-flex items-center gap-1 font-medium text-slate-600">
<svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Pencarian Populer:
        </span>
<a class="px-2.5 py-1 bg-white hover:bg-blue-50 hover:text-blue-700 rounded-md border border-slate-200 transition-colors" href="#datapokok">PDRB 2024</a>
<a class="px-2.5 py-1 bg-white hover:bg-blue-50 hover:text-blue-700 rounded-md border border-slate-200 transition-colors" href="#datapokok">Kemiskinan</a>
<a class="px-2.5 py-1 bg-white hover:bg-blue-50 hover:text-blue-700 rounded-md border border-slate-200 transition-colors" href="#datapokok">Inflasi Manggarai</a>
<a class="px-2.5 py-1 bg-white hover:bg-blue-50 hover:text-blue-700 rounded-md border border-slate-200 transition-colors" href="#infografis">Sensus Pertanian ST2023</a>
<a class="px-2.5 py-1 bg-white hover:bg-blue-50 hover:text-blue-700 rounded-md border border-slate-200 transition-colors" href="#publikasi">DDA 2024</a>
</div>
<!-- Quick Action Buttons -->
<div class="flex flex-wrap items-center justify-center gap-3">
<a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-200 hover:border-blue-400 hover:text-blue-700 text-xs font-semibold shadow-xs transition-colors" href="#indikator">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Indikator Makro
        </a>
<a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-slate-700 border border-slate-200 hover:border-blue-400 hover:text-blue-700 text-xs font-semibold shadow-xs transition-colors" href="#publikasi">
<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Publikasi DDA
        </a>
<a class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 text-xs font-semibold transition-colors" href="#pst">
<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Buku Tamu Digital
        </a>
</div>
</div>
</section>
<!-- END: HeroSection -->

<!-- BEGIN: AnnouncementTicker -->
<div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 text-white text-xs py-3 px-4 sm:px-6 shadow-sm relative z-10 border-y border-blue-600/50">
<div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
<div class="flex items-center space-x-2 shrink-0">
<span class="bg-blue-950 text-blue-200 font-extrabold px-2.5 py-1 rounded-md text-[10px] uppercase tracking-wider flex items-center gap-1.5 shadow-xs border border-blue-500/30">
    <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
    <span>Pengumuman</span>
</span>
</div>
<div class="flex-1 overflow-hidden">
<div class="text-slate-100 font-medium truncate text-xs sm:text-sm">
    {{ $fs['ticker_text'] ?? 'Selamat datang di Website Resmi Pelayanan Statistik Terpadu BPS Kabupaten Manggarai — Hubungi PST Online melalui WhatsApp untuk permohonan data dan konsultasi statistik sektoral.' }}
</div>
</div>
<div class="shrink-0 hidden md:flex items-center space-x-1.5 text-blue-200 text-xs font-semibold">
<span>PST BPS Manggarai</span>
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
</div>
</div>
<!-- END: AnnouncementTicker -->

{{-- Akses Cepat PST & Katalog Publikasi --}}
@include('frontend.partials.quick-actions')


<!-- =========================================================================
     MODULAR SECTIONS (FRONTEND PARTIALS)
========================================================================= -->

{{-- 1. Indikator Terkini Carousel --}}
@include('frontend.partials.indikator-carousel')

{{-- 2. Publikasi Resmi & Tabel Statistik --}}
@include('frontend.partials.publikasi')

{{-- 3. 8 Data Pokok & Indikator Strategis --}}
@include('frontend.partials.data-pokok')

{{-- 4. Galeri Infografis BPS --}}
@include('frontend.partials.infografis')

{{-- 5. Quotes of the Day --}}
@include('frontend.partials.quotes')

{{-- 6. Berita & Kegiatan Statistik --}}
@include('frontend.partials.kegiatan')

{{-- 7. Pelayanan Statistik Terpadu (PST) & Pengaduan --}}
@include('frontend.partials.pst-service')

{{-- 8. Footer --}}
@include('frontend.partials.footer')

{{-- 9. Modals (Buku Tamu, WebAPI Token, Floating Action, Toast) --}}
@include('frontend.partials.modals')

{{-- 10. Frontend Interactive Scripts --}}
@include('frontend.partials.scripts')

</body>
</html>
