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

<!-- BEGIN: QuickActionHub -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-14 relative z-10">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<!-- Card 1: PST Online -->
<a class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/5 transition-all group flex items-start space-x-4" href="https://wa.me/{{ $pstWaClean }}" target="_blank">
<div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div>
<span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Respon Cepat</span>
<h2 class="text-sm font-bold text-slate-900 mt-1">Konsultasi PST Online</h2>
<p class="text-xs text-slate-500 mt-1 leading-normal">Tanya jawab langsung dengan petugas statistik resmi via WhatsApp &amp; Live Desk.</p>
</div>
</a>
<!-- Card 2: Unduh Publikasi -->
<a class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/5 transition-all group flex items-start space-x-4" href="#publikasi">
<div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div>
<span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded">Katalog Data</span>
<h2 class="text-sm font-bold text-slate-900 mt-1">Unduh Publikasi Cepat</h2>
<p class="text-xs text-slate-500 mt-1 leading-normal">Dapatkan buku Kabupaten Manggarai Dalam Angka &amp; Statistik Daerah gratis.</p>
</div>
</a>
<!-- Card 3: Rekomendasi Romantik -->
<a class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/5 transition-all group flex items-start space-x-4" href="#pst">
<div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div>
<span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">Untuk OPD / Pemkab</span>
<h2 class="text-sm font-bold text-slate-900 mt-1">Rekomendasi Romantik</h2>
<p class="text-xs text-slate-500 mt-1 leading-normal">Pengajuan rekomendasi kegiatan statistik sektoral sesuai Satu Data Indonesia.</p>
</div>
</a>
<!-- Card 4: Kalender BRS -->
<a class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/5 transition-all group flex items-start space-x-4" href="#kegiatan">
<div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div>
<span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Kalender Rilis</span>
<h2 class="text-sm font-bold text-slate-900 mt-1">Jadwal Rilis BRS</h2>
<p class="text-xs text-slate-500 mt-1 leading-normal">Lihat tanggal rilis Berita Resmi Statistik untuk inflasi, PDRB, dan kemiskinan.</p>
</div>
</a>
</div>

<!-- Banner WebAPI v2 & Open Data -->
<div class="mt-4 bg-gradient-to-r from-brand-900 via-blue-900 to-indigo-900 text-white rounded-2xl p-4 sm:p-5 border border-blue-500/20 shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
<div class="flex items-start sm:items-center gap-3.5">
<div class="w-11 h-11 rounded-xl bg-blue-600/30 border border-blue-400/30 flex items-center justify-center shrink-0 text-blue-200">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div>
<div class="flex items-center gap-2 flex-wrap">
<span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-500/20 px-2 py-0.5 rounded border border-emerald-400/30">BPS WebAPI v2 &amp; Open Data</span>
<span class="text-[10px] text-blue-200 font-medium">Integrasi REST API JSON • Satu Data Indonesia</span>
</div>
<p class="text-xs text-slate-200 mt-1 leading-relaxed">Akses endpoint publikasi, indikator makro dinamis, dan katalog tabel secara programmatic untuk kebutuhan aplikasi OPD, akademisi, dan mahasiswa.</p>
</div>
</div>
<div class="flex items-center gap-2.5 shrink-0 w-full md:w-auto">
<a class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold bg-white text-brand-900 hover:bg-blue-50 shadow-sm transition-colors" href="https://webapi.bps.go.id" target="_blank">
<svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Dokumentasi WebAPI</span>
</a>
<button onclick="bukaModalApiToken()" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-blue-800/60 hover:bg-blue-800 border border-blue-400/30 text-white transition-colors">
<svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Token Pengembang</span>
</button>
</div>
</div>
</section>
<!-- END: QuickActionHub -->

<!-- BEGIN: KeyIndicatorsCarousel -->
<section class="py-12 bg-gradient-to-br from-brand-900 via-blue-900 to-indigo-950 text-white relative overflow-hidden border-b border-slate-200" id="indikator">
<!-- Decorative background elements -->
<div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
<!-- Section Header -->
<div class="text-center max-w-3xl mx-auto mb-10">
<h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Indikator Terkini Kabupaten Manggarai</h2>
<p class="text-xs sm:text-sm text-blue-200 mt-2 leading-relaxed">Data sensus, survei, dan indikator strategis resmi BPS Kabupaten Manggarai. Klik kartu untuk membuka dan mengeksplorasi langsung di Tabel Dinamis BPS.</p>
</div>
<!-- Carousel Container with Prev/Next buttons -->
<div class="relative flex items-center">
<!-- Prev Button -->
<button onclick="scrollIndicatorCarousel(-1)" aria-label="Previous Indicator" class="absolute -left-3 sm:-left-5 z-20 w-11 h-11 rounded-full bg-white text-blue-700 shadow-xl border border-slate-200 flex items-center justify-center hover:bg-blue-50 hover:scale-105 transition-all focus:outline-none">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
</button>
<!-- Cards Carousel Grid -->
<div id="indicator-carousel" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 w-full px-2 sm:px-6">
@forelse($indikatorsList as $ind)
<div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg hover:shadow-xl hover:border-blue-400 hover:scale-105 transition-all flex flex-col justify-between text-center min-h-[300px] group">
<div>
<div class="flex items-center justify-between mb-2">
<span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $ind->badge_text ?: 'Tabel Dinamis' }}</span>
<span class="text-[10px] text-slate-400 font-medium">{{ $ind->periode }}</span>
</div>
<div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"></path>
<path d="M19 14v7M15 17v4M11 19v2M7 21v0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"></path>
</svg>
</div>
<h3 class="text-xs sm:text-sm font-bold text-brand-900 leading-snug px-1">{{ $ind->judul }} @if($ind->sub_judul)<br><span class="font-semibold text-blue-700">{{ $ind->sub_judul }}</span>@endif</h3>
</div>
<div class="my-auto py-2">
<div class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">{{ $ind->nilai }}</div>
<p class="text-xs text-slate-500 font-medium mt-0.5">{{ $ind->deskripsi ?: 'BPS Kabupaten Manggarai' }}</p>
</div>
<div class="pt-3 border-t border-slate-100">
<a class="w-full py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" href="{{ $ind->link_tabel ?: '#datapokok' }}" target="_blank">
<span class="">Buka Tabel Dinamis ↗</span>
</a>
</div>
</div>
@empty
<div class="col-span-full text-center py-8 text-blue-200 text-xs">Belum ada indikator yang ditampilkan.</div>
@endforelse
</div>
<!-- Next Button -->
<button onclick="scrollIndicatorCarousel(1)" aria-label="Next Indicator" class="absolute -right-3 sm:-right-5 z-20 w-11 h-11 rounded-full bg-white text-blue-700 shadow-xl border border-slate-200 flex items-center justify-center hover:bg-blue-50 hover:scale-105 transition-all focus:outline-none">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
</button>
</div>
</div>
</section>
<!-- END: KeyIndicatorsCarousel -->

<!-- BEGIN: LatestPublicationsSection -->
<section class="py-16 bg-white border-y border-slate-200" id="publikasi">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<!-- Section Header -->
<div class="text-center max-w-2xl mx-auto mb-10">
<h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">PUBLIKASI</h2>
<p class="text-xs sm:text-sm text-slate-500 mt-1">Publikasi Resmi Terbaru Badan Pusat Statistik Kabupaten Manggarai</p>
<!-- Category Filter Pills -->
<div class="flex flex-wrap items-center justify-center gap-2 mt-6">
<button onclick="filterPubCategory('semua', this)" class="pub-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm">Semua Publikasi</button>
<button onclick="filterPubCategory('dda', this)" class="pub-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Tahunan (DDA)</button>
<button onclick="filterPubCategory('statda', this)" class="pub-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Statistik Daerah</button>
<button onclick="filterPubCategory('pdrb', this)" class="pub-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">PDRB &amp; Ekonomi</button>
</div>
</div>

<!-- Publication Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

<!-- Book 1: Manggarai Dalam Angka 2024 -->
<div class="pub-card-item pub-cat-dda bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="h-64 rounded-xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 text-white p-5 flex flex-col justify-between shadow-inner relative overflow-hidden group">
<div class="absolute inset-0 bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="flex items-center justify-between text-[11px] text-blue-200">
<span class="font-bold">BPS MANGGARAI</span>
<div class="flex items-center gap-1.5">
<span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-1.5 py-0.5 rounded text-[10px] font-mono">API Ready</span>
<span class="bg-blue-800/60 px-2 py-0.5 rounded font-mono">DDA 2024</span>
</div>
</div>
<div class="text-center my-auto">
<div class="w-10 h-10 mx-auto mb-3 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur">
<svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h4 class="text-base font-bold tracking-tight text-white leading-tight">KABUPATEN MANGGARAI<br>DALAM ANGKA</h4>
<p class="text-[10px] text-blue-200 mt-1 italic">Manggarai Regency in Figures 2024</p>
</div>
<div class="text-[9px] text-slate-300 flex justify-between border-t border-white/10 pt-2 font-mono">
<span class="">No. Katalog: 1102001.5310</span>
<span class="">ISSN: 0215-2142</span>
</div>
</div>
<div class="flex items-center justify-between text-[11px] text-slate-400 mt-4 mb-2">
<span class="inline-flex items-center gap-1 font-medium text-slate-600">
<svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg> 28 Feb 2024
</span>
<span class="font-mono">PDF • 14.8 MB</span>
</div>
<h3 class="text-base font-bold text-slate-900 leading-snug">Kabupaten Manggarai Dalam Angka 2024</h3>
<p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">Memuat data komprehensif geografi, iklim, pemerintahan, demografi, ketenagakerjaan, pertanian, dan ekonomi...</p>

<!-- Sub-Daftar Tabel dengan Link & Unduh XLS -->
<div class="mt-3.5 bg-white p-3 rounded-xl border border-slate-200/90">
<div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
<span class="flex items-center gap-1">
<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Daftar Tabel Publikasi (142 Tabel)
</span>
<span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">XLSX / Web</span>
</div>
<div class="space-y-1.5 text-[11px] text-slate-600">
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-blue-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" class="truncate pr-2">• Tabel 1.1 Luas Wilayah &amp; Geografi Manggarai</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html', 'Tabel 1.1 Luas Wilayah & Geografi')" class="text-[10px] text-slate-400 hover:text-blue-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" class="text-[10px] text-blue-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-blue-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" class="truncate pr-2">• Tabel 2.3 Jumlah Penduduk Menurut Kecamatan</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html', 'Tabel 2.3 Jumlah Penduduk Menurut Kecamatan')" class="text-[10px] text-slate-400 hover:text-blue-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/population-density.html" target="_blank" class="text-[10px] text-blue-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-blue-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="truncate pr-2">• Tabel 3.1 PDRB Menurut Lapangan Usaha</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html', 'Tabel 3.1 PDRB Menurut Lapangan Usaha')" class="text-[10px] text-slate-400 hover:text-blue-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="text-[10px] text-blue-600 hover:underline">Unduh XLS</a>
</div>
</div>
</div>
</div>

</div>
<div class="mt-4 grid grid-cols-2 gap-2">
<a class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold flex items-center justify-center gap-1.5 shadow-sm transition-colors" href="https://manggaraikab.bps.go.id/id/publication/2024/02/28/dda-manggarai-2024.html" target="_blank">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Unduh PDF</span>
</a>
<a class="w-full py-2.5 px-3 rounded-xl bg-white border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank">
<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Tabel Dinamis</span>
</a>
</div>
</div>

<!-- Book 2: Statistik Daerah Manggarai 2024 -->
<div class="pub-card-item pub-cat-statda bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="h-64 rounded-xl bg-gradient-to-br from-emerald-950 via-teal-900 to-slate-900 text-white p-5 flex flex-col justify-between shadow-inner relative overflow-hidden group">
<div class="absolute inset-0 bg-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="flex items-center justify-between text-[11px] text-emerald-200">
<span class="font-bold">BPS MANGGARAI</span>
<div class="flex items-center gap-1.5">
<span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-1.5 py-0.5 rounded text-[10px] font-mono">API Ready</span>
<span class="bg-emerald-800/60 px-2 py-0.5 rounded font-mono">STATDA 2024</span>
</div>
</div>
<div class="text-center my-auto">
<div class="w-10 h-10 mx-auto mb-3 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur">
<svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h4 class="text-base font-bold tracking-tight text-white leading-tight">STATISTIK DAERAH<br>KABUPATEN MANGGARAI</h4>
<p class="text-[10px] text-emerald-200 mt-1 italic">Tahun 2024</p>
</div>
<div class="text-[9px] text-slate-300 flex justify-between border-t border-white/10 pt-2 font-mono">
<span class="">No. Katalog: 1101002.5310</span>
<span class="">No. Publikasi: 53100.2401</span>
</div>
</div>
<div class="flex items-center justify-between text-[11px] text-slate-400 mt-4 mb-2">
<span class="inline-flex items-center gap-1 font-medium text-slate-600">
<svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg> 26 Sep 2024
</span>
<span class="font-mono">PDF • 8.2 MB</span>
</div>
<h3 class="text-base font-bold text-slate-900 leading-snug">Statistik Daerah Kabupaten Manggarai 2024</h3>
<p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">Ulasan deskriptif populer dan indikator terpilih yang membedah dinamika pembangunan daerah secara ringkas...</p>

<!-- Sub-Daftar Tabel Tematik -->
<div class="mt-3.5 bg-white p-3 rounded-xl border border-slate-200/90">
<div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
<span class="flex items-center gap-1">
<svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Daftar Tabel Tematik (58 Tabel)
</span>
<span class="text-[10px] font-semibold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded">XLSX / Web</span>
</div>
<div class="space-y-1.5 text-[11px] text-slate-600">
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-emerald-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/makro-sosial-ekonomi.html" target="_blank" class="truncate pr-2">• Tabel Indikator Kesejahteraan Rakyat</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/makro-sosial-ekonomi.html', 'Tabel Indikator Kesejahteraan Rakyat')" class="text-[10px] text-slate-400 hover:text-emerald-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/makro-sosial-ekonomi.html" target="_blank" class="text-[10px] text-emerald-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-emerald-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/ipm-manggarai-komponen.html" target="_blank" class="truncate pr-2">• Tabel Angka Harapan Hidup &amp; IPM 2023</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/ipm-manggarai-komponen.html', 'Tabel Angka Harapan Hidup & IPM 2023')" class="text-[10px] text-slate-400 hover:text-emerald-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/ipm-manggarai-komponen.html" target="_blank" class="text-[10px] text-emerald-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-emerald-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/kemiskinan-manggarai-susenas.html" target="_blank" class="truncate pr-2">• Tabel Garis Kemiskinan &amp; Gini Ratio</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/kemiskinan-manggarai-susenas.html', 'Tabel Garis Kemiskinan & Gini Ratio')" class="text-[10px] text-slate-400 hover:text-emerald-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/kemiskinan-manggarai-susenas.html" target="_blank" class="text-[10px] text-emerald-600 hover:underline">Unduh XLS</a>
</div>
</div>
</div>
</div>

</div>
<div class="mt-4 grid grid-cols-2 gap-2">
<a class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center justify-center gap-1.5 shadow-sm transition-colors" href="https://manggaraikab.bps.go.id/id/publication/2024/09/26/statistik-daerah-kabupaten-manggarai-2024.html" target="_blank">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Unduh PDF</span>
</a>
<a class="w-full py-2.5 px-3 rounded-xl bg-white border border-slate-300 hover:border-emerald-600 hover:text-emerald-700 text-slate-700 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank">
<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Tabel Dinamis</span>
</a>
</div>
</div>

<!-- Book 3: PDRB Manggarai 2019-2023 -->
<div class="pub-card-item pub-cat-pdrb bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="h-64 rounded-xl bg-gradient-to-br from-indigo-950 via-blue-900 to-slate-900 text-white p-5 flex flex-col justify-between shadow-inner relative overflow-hidden group">
<div class="absolute inset-0 bg-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
<div class="flex items-center justify-between text-[11px] text-blue-200">
<span class="font-bold">BPS MANGGARAI</span>
<div class="flex items-center gap-1.5">
<span class="bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 px-1.5 py-0.5 rounded text-[10px] font-mono">API Ready</span>
<span class="bg-indigo-800/60 px-2 py-0.5 rounded font-mono">PDRB 2019-2023</span>
</div>
</div>
<div class="text-center my-auto">
<div class="w-10 h-10 mx-auto mb-3 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur">
<svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h4 class="text-base font-bold tracking-tight text-white leading-tight">PRODUK DOMESTIK REGIONAL BRUTO</h4>
<p class="text-[10px] text-blue-200 mt-1 italic">Menurut Lapangan Usaha 2019 - 2023</p>
</div>
<div class="text-[9px] text-slate-300 flex justify-between border-t border-white/10 pt-2 font-mono">
<span class="">No. Publikasi: 53100.2405</span>
<span class="">PDRB Wilayah</span>
</div>
</div>
<div class="flex items-center justify-between text-[11px] text-slate-400 mt-4 mb-2">
<span class="inline-flex items-center gap-1 font-medium text-slate-600">
<svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg> 05 Apr 2024
</span>
<span class="font-mono">PDF • 5.4 MB</span>
</div>
<h3 class="text-base font-bold text-slate-900 leading-snug">PDRB Kabupaten Manggarai Menurut Lapangan Usaha 2019-2023</h3>
<p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">Menyajikan tabel-tabel PDRB ADHB dan ADHK 2010 beserta laju pertumbuhan sektor primer, sekunder, dan tersier...</p>

<!-- Sub-Daftar Tabel Sektoral -->
<div class="mt-3.5 bg-white p-3 rounded-xl border border-slate-200/90">
<div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
<span class="flex items-center gap-1">
<svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Daftar Tabel Sektoral (34 Tabel)
</span>
<span class="text-[10px] font-semibold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded">XLSX / Web</span>
</div>
<div class="space-y-1.5 text-[11px] text-slate-600">
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-indigo-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="truncate pr-2">• Tabel PDRB ADHB 17 Sektor Usaha</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html', 'Tabel PDRB ADHB 17 Sektor Usaha')" class="text-[10px] text-slate-400 hover:text-indigo-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="text-[10px] text-indigo-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-indigo-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/laju-pertumbuhan-pdrb.html" target="_blank" class="truncate pr-2">• Tabel PDRB ADHK &amp; Laju Pertumbuhan</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/laju-pertumbuhan-pdrb.html', 'Tabel PDRB ADHK & Laju Pertumbuhan')" class="text-[10px] text-slate-400 hover:text-indigo-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/laju-pertumbuhan-pdrb.html" target="_blank" class="text-[10px] text-indigo-600 hover:underline">Unduh XLS</a>
</div>
</div>
<div class="flex items-center justify-between p-1.5 hover:bg-slate-50 rounded text-slate-700 hover:text-indigo-700 group/item transition-colors">
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="truncate pr-2">• Tabel Distribusi Persentase PDRB</a>
<div class="flex items-center gap-1 shrink-0">
<button onclick="salinLinkTabel('https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html', 'Tabel Distribusi Persentase PDRB')" class="text-[10px] text-slate-400 hover:text-indigo-600" title="Salin Link"><i class="fa-regular fa-copy"></i></button>
<a href="https://manggaraikab.bps.go.id/id/statistics-table/1/pdrb-adhb-lapangan-usaha.html" target="_blank" class="text-[10px] text-indigo-600 hover:underline">Unduh XLS</a>
</div>
</div>
</div>
</div>

</div>
<div class="mt-4 grid grid-cols-2 gap-2">
<a class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold flex items-center justify-center gap-1.5 shadow-sm transition-colors" href="https://manggaraikab.bps.go.id/id/publication/2024/04/05/pdrb-manggarai-2019-2023.html" target="_blank">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Unduh PDF</span>
</a>
<a class="w-full py-2.5 px-3 rounded-xl bg-white border border-slate-300 hover:border-indigo-600 hover:text-indigo-700 text-slate-700 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" href="https://manggaraikab.bps.go.id/id/statistics-table" target="_blank">
<svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">Tabel Dinamis</span>
</a>
</div>
</div>

</div>
</div>
</section>
<!-- END: LatestPublicationsSection -->

<!-- BEGIN: MainDataGrid -->
<section class="py-16 bg-gradient-to-b from-blue-600 to-brand-900 text-white relative" id="datapokok">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center max-w-2xl mx-auto mb-12">
<h2 class="text-3xl font-extrabold tracking-tight">DATA – DATA</h2>
<p class="text-xs sm:text-sm text-blue-100 mt-1">8 Indikator Strategis &amp; Statistik Kunci Kabupaten Manggarai</p>
</div>
<!-- 8 Metric Cards Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

<!-- Card 1: Penduduk -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_penduduk'] ?? '338.414' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Jumlah Penduduk (Jiwa)</p>
<button onclick="salinTeksData('Jumlah Penduduk Kabupaten Manggarai: {{ $fs['stat_penduduk'] ?? '338.414' }} Jiwa')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 2: Pertumbuhan Ekonomi -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Pertumbuhan Ekonomi</p>
<button onclick="salinTeksData('Pertumbuhan Ekonomi Kabupaten Manggarai: {{ $fs['stat_pertumbuhan_ekonomi'] ?? '5,02%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 3: Tingkat Inflasi -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_inflasi'] ?? '2,15%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Tingkat Inflasi (y-on-y)</p>
<button onclick="salinTeksData('Tingkat Inflasi Kabupaten Manggarai: {{ $fs['stat_inflasi'] ?? '2,15%' }} (y-on-y)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 4: Persentase Kemiskinan -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_kemiskinan'] ?? '19,84%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Persentase Penduduk Miskin</p>
<button onclick="salinTeksData('Persentase Penduduk Miskin Kabupaten Manggarai: {{ $fs['stat_kemiskinan'] ?? '19,84%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 5: TPT -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_tpt'] ?? '2,82%' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Tingkat Pengangguran (TPT)</p>
<button onclick="salinTeksData('Tingkat Pengangguran Terbuka (TPT) Kabupaten Manggarai: {{ $fs['stat_tpt'] ?? '2,82%' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 6: IPM -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_ipm'] ?? '67,03' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Indeks Pembangunan Manusia</p>
<button onclick="salinTeksData('Indeks Pembangunan Manusia (IPM) Kabupaten Manggarai: {{ $fs['stat_ipm'] ?? '67,03' }}')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 7: Gini Ratio -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 6l3 18h12l3-18H3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_gini'] ?? '0,312' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">Gini Ratio (Ketimpangan Rendah)</p>
<button onclick="salinTeksData('Gini Ratio Kabupaten Manggarai: {{ $fs['stat_gini'] ?? '0,312' }} (Ketimpangan Rendah)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

<!-- Card 8: PDRB Kapita -->
<div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 hover:bg-white/15 transition-all text-center flex flex-col items-center justify-between">
<div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }}</div>
<p class="text-xs text-blue-200 mt-1 font-medium">PDRB Per Kapita ADHB</p>
<button onclick="salinTeksData('PDRB Per Kapita Kabupaten Manggarai: {{ $fs['stat_pdrb_kapita'] ?? 'Rp 27,64 Jt' }} (ADHB)')" class="mt-4 px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-[10px] font-semibold text-white flex items-center gap-1 border border-white/20 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
Salin Data
</button>
</div>

</div>
</div>
</section>
<!-- END: MainDataGrid -->

<!-- BEGIN: InfographicsGallery -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="infografis">
<div class="text-center max-w-2xl mx-auto mb-10">
<h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">GALERI INFOGRAFIS</h2>
<p class="text-xs sm:text-sm text-slate-500 mt-1">Sajian Visualisasi Data Statistik Menarik, Ringkas, dan Edukatif</p>
<!-- Filter Chips -->
<div class="flex flex-wrap items-center justify-center gap-2 mt-6">
<button onclick="filterInfografis('semua', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm">Semua Infografis</button>
<button onclick="filterInfografis('transportasi', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Transportasi</button>
<button onclick="filterInfografis('ekonomi', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Ekonomi &amp; PDRB</button>
<button onclick="filterInfografis('pertanian', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Pertanian ST2023</button>
<button onclick="filterInfografis('sosial', this)" class="info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">Sosial &amp; Pendidikan</button>
</div>
</div>
<!-- Dynamic Infographic Visual Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

@forelse($infografisList as $info)
<div class="info-card-item info-cat-{{ strtolower($info->kategori) }} bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all group flex flex-col justify-between">
@if($info->image_url)
    <div class="h-64 relative overflow-hidden bg-slate-900 group">
        <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @if($info->badge_text)
        <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-xs">{{ $info->badge_text }}</span>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex flex-col justify-end p-4 text-white">
            <h4 class="font-extrabold text-sm line-clamp-2">{{ $info->judul }}</h4>
        </div>
    </div>
@else
    <div class="h-64 bg-gradient-to-br {{ $info->bg_gradient ?: 'from-blue-600 to-indigo-700' }} text-white p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between text-xs">
            <span class="font-bold tracking-wider">BPS MANGGARAI</span>
            @if($info->badge_text)
            <span class="bg-black/20 px-2 py-0.5 rounded text-[10px]">{{ $info->badge_text }}</span>
            @endif
        </div>
        <div class="text-center my-auto">
            <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-white/20 flex items-center justify-center">
                @if($info->icon == 'truck')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'chart-pie')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'tree')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 12h5v8h6v-8h5L12 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'graduation-cap')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'users')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @elseif($info->icon == 'hotel')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @else
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                @endif
            </div>
            <h4 class="font-extrabold text-base uppercase leading-snug">{{ $info->judul }}</h4>
            @if(!empty($info->data_points_array))
            <div class="mt-3 bg-white/10 rounded-lg p-2 text-xs text-left max-w-xs mx-auto">
                @foreach($info->data_points_array as $dp)
                <div class="flex justify-between py-0.5"><span class="">{{ $dp['label'] ?? '' }}</span><span class="font-bold">{{ $dp['value'] ?? '' }}</span></div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="flex justify-between text-[10px] text-white/80 border-t border-white/20 pt-2">
            <span class="">{{ $info->sumber ? 'Sumber: ' . $info->sumber : 'BPS Kabupaten Manggarai' }}</span>
            <span class="">{{ $info->tahun ?? '' }}</span>
        </div>
    </div>
@endif
    <div class="p-4 flex items-center justify-between">
        <h3 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $info->judul }}</h3>
        <a href="{{ $info->link_url ?: 'https://manggaraikab.bps.go.id' }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-semibold flex items-center gap-1 cursor-pointer shrink-0">
            Lihat
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>
    </div>
</div>
@empty
<div class="col-span-full py-12 text-center text-slate-400 text-sm">
    Belum ada infografis yang ditampilkan.
</div>
@endforelse

</div>
<!-- View All Button -->
<div class="text-center mt-10">
<a href="https://manggaraikab.bps.go.id" target="_blank" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:border-blue-600 hover:text-blue-600 font-semibold text-xs transition-colors inline-flex items-center gap-2">
<span class="">LEBIH BANYAK INFOGRAFIS</span>
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
</div>
</section>
<!-- END: InfographicsGallery -->

<!-- BEGIN: QuotesOfTheDaySection -->
<section class="py-14 bg-blue-600 text-white relative overflow-hidden">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
<span class="text-xs font-bold tracking-widest uppercase text-blue-200">QUOTES OF THE DAY</span>

@if($quotesList->isNotEmpty())
<div id="quotes-carousel-container">
    @foreach($quotesList as $index => $qItem)
    <div class="quote-slide {{ $index === 0 ? '' : 'hidden' }}" id="quote-slide-{{ $index }}">
        <!-- Leader Avatar -->
        <div class="my-6">
            <div class="w-20 h-20 rounded-full border-4 border-white/40 shadow-lg mx-auto overflow-hidden bg-slate-300">
                <img alt="{{ $qItem->nama }}" class="w-full h-full object-cover" src="{{ $qItem->foto_url }}">
            </div>
            <div class="mt-3">
                <h4 class="text-base font-bold text-white tracking-wide">{{ $qItem->nama }}</h4>
                <p class="text-xs text-blue-200 font-medium">{{ $qItem->jabatan }}</p>
            </div>
        </div>
        <blockquote class="text-base sm:text-xl font-medium italic text-blue-50 leading-relaxed max-w-3xl mx-auto">
            "{{ trim($qItem->quote, '\"') }}"
        </blockquote>
    </div>
    @endforeach
</div>

@if($quotesList->count() > 1)
<!-- Pagination dots -->
<div class="flex items-center justify-center space-x-2 mt-6">
    @foreach($quotesList as $idx => $qItem)
    <button type="button" onclick="switchQuoteSlide({{ $idx }})" class="quote-dot w-2.5 h-2.5 rounded-full {{ $idx === 0 ? 'bg-white scale-125' : 'bg-white/40' }} transition-all focus:outline-none" aria-label="Quote {{ $idx + 1 }}"></button>
    @endforeach
</div>
@endif

@else
<div class="my-6">
    <div class="w-20 h-20 rounded-full border-4 border-white/40 shadow-lg mx-auto overflow-hidden bg-slate-300">
        <img alt="{{ $fs['quote_nama'] ?? 'Kepala BPS Kabupaten Manggarai' }}" class="w-full h-full object-cover" src="{{ isset($fs['img_quote_avatar']) ? asset('storage/' . $fs['img_quote_avatar']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAjXZA9MLptXHc4LghoJ0kijRke5AbNhNWkkCx1xTrmprh9StVUt9Q6xOhnsMWvUhNjiGK5UxXHLEzMnmMNm6BQlVWJMmdQAhyYP_Fvo03iCpoUgxxlDCiGxWpMuQOG98pXOD1nugLcPIzPemY1fQGA_K-0F0BbNO3UKS83gx_AfzVDR8tbvD5wZTd3hEtfzEOycA4oWrh3rTW5vJRAm1IQtTnDkXZTFFIxOfNJrlLf8ikRWaLhNCqN' }}">
    </div>
    <div class="mt-3">
        <h4 class="text-base font-bold text-white tracking-wide">{{ $fs['quote_nama'] ?? 'Dr. Ir. Habibie' }}</h4>
        <p class="text-xs text-blue-200 font-medium">{{ $fs['quote_jabatan'] ?? 'KEPALA BPS KABUPATEN MANGGARAI' }}</p>
    </div>
</div>
<blockquote class="text-base sm:text-xl font-medium italic text-blue-50 leading-relaxed max-w-3xl mx-auto">
    "{{ trim($fs['quote_text'] ?? 'Data yang akurat dan berkualitas adalah fondasi utama bagi perencanaan, evaluasi pembangunan bangsa yang berkelanjutan serta kesejahteraan masyarakat secara merata.', '\"') }}"
</blockquote>
@endif

</div>
</section>


<!-- BEGIN: StatisticalActivitiesSection -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="kegiatan">
<div class="text-center max-w-2xl mx-auto mb-12">
<h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">KEGIATAN</h2>
<p class="text-xs sm:text-sm text-slate-500 mt-1">Dokumentasi dan Berita Kegiatan Terkini BPS Kabupaten Manggarai</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

@forelse($kegiatansList as $keg)
@php
    $bgBadge = match($keg->badge_color) {
        'purple' => 'bg-purple-50 text-purple-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        default => 'bg-blue-50 text-blue-600',
    };
    $textBadge = match($keg->badge_color) {
        'purple' => 'text-purple-600',
        'teal' => 'text-teal-600',
        'orange' => 'text-orange-600',
        'emerald' => 'text-emerald-600',
        default => 'text-blue-600',
    };
@endphp
<div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all flex flex-col justify-between">
<div>
<div class="flex items-center space-x-3 mb-4">
<div class="w-10 h-10 rounded-xl {{ $bgBadge }} flex items-center justify-center shrink-0">
    @if($keg->icon == 'users')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'book')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'comments')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'check-circle')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @elseif($keg->icon == 'bolt')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @else
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    @endif
</div>
<div>
<span class="text-[10px] font-bold {{ $textBadge }} uppercase tracking-wide">{{ $keg->kategori }}</span>
<p class="text-[11px] text-slate-400">{{ $keg->tanggal_display ?? $keg->created_at->format('d M Y') }}</p>
</div>
</div>
<h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $keg->judul }}</h3>
<p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
    {{ $keg->deskripsi }}
</p>
</div>
<div class="mt-4 pt-3 border-t border-slate-100">
<a class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1" href="{{ $keg->link_url ?: '#' }}" {{ str_starts_with($keg->link_url ?? '', 'http') ? 'target="_blank"' : '' }}>
    Baca Selengkapnya
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
</div>
</div>
@empty
<div class="col-span-full py-8 text-center text-slate-400 text-xs">
    Belum ada kegiatan yang ditampilkan.
</div>
@endforelse

</div>
</section>


<!-- BEGIN: IntegratedServiceHub (PST) -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="pst">
<div class="bg-slate-900 rounded-3xl p-8 sm:p-12 text-white border border-slate-800 shadow-2xl relative overflow-hidden">
<!-- Subtle Decorative Glow Background -->
<div class="absolute -right-24 -bottom-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
<!-- Left Column: Information & Contacts -->
<div class="lg:col-span-7">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-semibold mb-4">
<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">PELAYANAN STATISTIK TERPADU (PST) BEBAS PUNGLI</span>
</div>
<h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-3">
            Butuh Data Statistik Khusus<br>atau Rekomendasi Kegiatan?
          </h2>
<p class="text-sm text-slate-300 mb-6 leading-relaxed max-w-xl">
            BPS Kabupaten Manggarai siap melayani permohonan data mikro, konsultasi statistik, rekomendasi kegiatan statistik sektoral (Romantik), dan verifikasi metadata. Seluruh pelayanan tidak dipungut biaya (gratis).
          </p>
<div class="space-y-2.5 text-xs text-slate-300 mb-8">
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Jam Pelayanan:</strong> {{ $fs['pst_jam_layanan'] ?? 'Senin s.d. Jumat, 08.00 - 15.30 WITA' }}</span>
</div>
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Ruang PST:</strong> {{ $fs['pst_alamat'] ?? 'Kantor BPS Kab. Manggarai, Jl. Ranaka, Ruteng, Flores, NTT' }}</span>
</div>
<div class="flex items-center space-x-2">
<svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class=""><strong>Email Resmi:</strong> <a class="text-blue-300 hover:underline" href="mailto:{{ $pengaduanMail }}">{{ $pengaduanMail }}</a></span>
</div>
</div>
<!-- Direct CTA Buttons -->
<div class="flex flex-wrap gap-3">
<button onclick="bukaModalBukuTamu()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs inline-flex items-center gap-2 shadow-lg shadow-emerald-900/30 transition-all">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">ISI BUKU TAMU DIGITAL</span>
</button>
<a class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 font-semibold text-xs inline-flex items-center gap-2 transition-all" href="https://wa.me/{{ $pstWaClean }}" target="_blank">
<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">CHAT WA FRONT OFFICE</span>
</a>
</div>
</div>
<!-- Right Column: Maklumat Pelayanan BPS Card -->
<div class="lg:col-span-5 bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 border border-slate-700 text-center">
<div class="w-12 h-12 rounded-xl bg-blue-600/20 text-blue-400 mx-auto flex items-center justify-center mb-4">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h3 class="text-base font-bold text-white uppercase tracking-wider">Maklumat Pelayanan BPS</h3>
<p class="text-[11px] text-blue-300 font-medium mt-0.5">Standar Pelayanan Publik Zona Integritas</p>
<p class="text-xs text-slate-300 italic mt-4 leading-relaxed bg-slate-900/40 p-4 rounded-xl border border-slate-700/60">
            "Dengan ini, kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai peraturan perundang-undangan yang berlaku."
          </p>
<div class="grid grid-cols-2 gap-3 mt-4 text-left">
<div class="bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">
<span class="text-[10px] text-emerald-400 font-bold block">✓ Biaya Rp 0,-</span>
<span class="text-[11px] text-slate-300">Seluruh publikasi dan data digital bebas biaya.</span>
</div>
<div class="bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">
<span class="text-[10px] text-blue-400 font-bold block">✓ Cepat &amp; Tepat</span>
<span class="text-[11px] text-slate-300">Layanan konsultasi diselesaikan secara responsif.</span>
</div>
</div>
<div class="mt-5 pt-4 border-t border-slate-700/80 text-left">
<div class="flex items-center justify-between mb-3">
<div class="flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-emerald-400"></span>
<span class="text-xs font-bold text-white uppercase tracking-wider">Kanal Resmi Pengaduan Publik</span>
</div>
<span class="text-[10px] text-amber-300 bg-amber-500/20 px-2 py-0.5 rounded border border-amber-400/30">Zona Integritas WBK/WBBM</span>
</div>
<p class="text-[11px] text-slate-400 mb-3 leading-relaxed">Laporkan pelanggaran disiplin, pungutan liar, atau ketidaksesuaian standar pelayanan. Identitas pelapor dijamin aman dan rahasia.</p>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-red-500/50 transition-all text-center group" href="https://www.lapor.go.id" target="_blank">
<span class="text-[11px] font-bold text-red-400 block group-hover:text-red-300">SP4N-LAPOR!</span>
<span class="text-[10px] text-slate-400">Kanal Nasional</span>
</a>
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-blue-500/50 transition-all text-center group" href="https://wbs.bps.go.id" target="_blank">
<span class="text-[11px] font-bold text-blue-400 block group-hover:text-blue-300">WBS BPS RI</span>
<span class="text-[10px] text-slate-400">Whistleblowing</span>
</a>
<a class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-700/70 hover:border-emerald-500/50 transition-all text-center group" href="https://wa.me/{{ $pstWaClean }}?text=Halo%20Admin%20Pengaduan%20BPS%20Manggarai" target="_blank">
<span class="text-[11px] font-bold text-emerald-400 block group-hover:text-emerald-300">Hotline WA</span>
<span class="text-[10px] text-slate-400">{{ $pstWa }}</span>
</a>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- END: IntegratedServiceHub -->

<!-- BEGIN: MainFooter -->
<footer class="bg-slate-950 text-slate-400 text-xs border-t border-slate-800">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<div class="flex flex-col items-center justify-center text-center">
<!-- Social Icons -->
<div class="flex items-center space-x-5 mb-6 text-slate-400">
<a class="w-8 h-8 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center hover:text-white hover:border-slate-700 transition-colors" href="https://facebook.com" target="_blank" title="Facebook">
<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path></svg>
</a>
<a class="w-8 h-8 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center hover:text-white hover:border-slate-700 transition-colors" href="https://instagram.com" target="_blank" title="Instagram">
<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
</a>
<a class="w-8 h-8 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center hover:text-white hover:border-slate-700 transition-colors" href="https://twitter.com" target="_blank" title="X (Twitter)">
<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
</a>
<a class="w-8 h-8 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center hover:text-white hover:border-slate-700 transition-colors" href="https://youtube.com" target="_blank" title="YouTube">
<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path></svg>
</a>
</div>
<h4 class="text-sm font-bold text-white uppercase tracking-wider mb-1">Badan Pusat Statistik Kabupaten Manggarai</h4>
<p class="text-slate-400 mb-1">Jl. Ranaka, Ruteng, Flores, Nusa Tenggara Timur (NTT) 86511</p>
<p class="text-slate-500 mb-4">Telepon: (0385) 21124 | Email: {{ $pengaduanMail }} | WA PST: {{ $pstWa }}</p>
<p class="text-[11px] text-slate-500">Jam Layanan Pelayanan Statistik Terpadu (PST): Senin - Jumat 08.00 - 15.30 WITA</p>
<div class="w-full max-w-4xl border-t border-slate-800 my-8"></div>
<div class="flex flex-col sm:flex-row items-center justify-between w-full max-w-4xl text-[11px] text-slate-500 gap-4">
<p class="">© {{ date('Y') }} Badan Pusat Statistik Kabupaten Manggarai. Seluruh Hak Cipta Dilindungi.</p>
<div class="flex items-center space-x-4">
<a class="hover:text-slate-300" href="https://webapi.bps.go.id" target="_blank">WebAPI BPS</a>
<span class="">•</span>
<a class="hover:text-slate-300" href="https://www.lapor.go.id" target="_blank">SP4N-LAPOR!</a>
<span class="">•</span>
<a class="hover:text-slate-300" href="https://wbs.bps.go.id" target="_blank">WBS BPS</a>
<span class="">•</span>
<a class="hover:text-slate-300" href="https://www.bps.go.id" target="_blank">BPS RI</a>
<span class="">•</span>
<a class="hover:text-slate-300" href="https://ntt.bps.go.id" target="_blank">BPS NTT</a>
<span class="">•</span>
<a class="hover:text-slate-300" href="https://data.go.id" target="_blank">Satu Data Indonesia</a>
</div>
</div>
</div>
</div>
</footer>
<!-- END: MainFooter -->

<!-- BEGIN: BackToTopFloatingButton -->
<a class="fixed bottom-6 right-6 w-11 h-11 rounded-full bg-blue-600 text-white shadow-xl hover:bg-blue-700 flex items-center justify-center transition-all z-40 border border-white/20" href="#" title="Kembali ke atas">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path></svg>
</a>
<!-- END: BackToTopFloatingButton -->

<!-- BEGIN: Modal Buku Tamu Digital PST -->
<div id="modal-buku-tamu" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
<div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
<button onclick="tutupModalBukuTamu()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition">
<i class="fa-solid fa-xmark"></i>
</button>
<div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
<div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
<i class="fa-solid fa-book-bookmark"></i>
</div>
<div>
<h3 class="text-base font-extrabold text-slate-900">Buku Tamu Digital PST</h3>
<p class="text-xs text-slate-500">Registrasi Kunjungan Konsultasi &amp; Permohonan Data</p>
</div>
</div>
<form onsubmit="event.preventDefault(); simpanBukuTamu();" class="space-y-4">
<div>
<label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
<input type="text" id="tamu-nama" required placeholder="Contoh: Maria Yosefina" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 outline-none">
</div>
<div>
<label class="block text-xs font-bold text-slate-700 uppercase mb-1">Instansi / Universitas</label>
<input type="text" id="tamu-instansi" required placeholder="Contoh: Universitas Flores / Bappeda" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 outline-none">
</div>
<div>
<label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keperluan / Kebutuhan Data</label>
<textarea id="tamu-keperluan" rows="3" required placeholder="Contoh: Konsultasi data PDRB dan kemiskinan untuk kajian skripsi..." class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 outline-none resize-none"></textarea>
</div>
<div class="pt-2 flex items-center justify-end gap-2">
<button type="button" onclick="tutupModalBukuTamu()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">Batal</button>
<button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
<i class="fa-solid fa-paper-plane text-[10px]"></i>
<span>Kirim Buku Tamu</span>
</button>
</div>
</form>
</div>
</div>
<!-- END: Modal Buku Tamu Digital PST -->

<!-- BEGIN: Modal Token API BPS -->
<div id="modal-api-token" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
<div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative">
<button onclick="tutupModalApiToken()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition">
<i class="fa-solid fa-xmark"></i>
</button>
<div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
<div class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
<i class="fa-solid fa-key"></i>
</div>
<div>
<h3 class="text-base font-extrabold text-slate-900">Akses BPS WebAPI v2</h3>
<p class="text-xs text-slate-500">Domain Wilayah: 5310 (BPS Kabupaten Manggarai)</p>
</div>
</div>
<div class="space-y-4 text-xs text-slate-600">
<p>Endpoint publikasi, indikator makro, dan katalog tabel BPS Kabupaten Manggarai terhubung langsung melalui gateway resmi <strong>webapi.bps.go.id</strong>.</p>
<div class="p-3 bg-slate-50 rounded-xl border border-slate-200 font-mono text-[11px] text-slate-700 space-y-1">
<div class="text-blue-700 font-bold">GET /api/bps/publikasi</div>
<div class="text-blue-700 font-bold">GET /api/bps/indikator</div>
<div class="text-slate-500">Domain Wilayah: 5310</div>
</div>
<p>Bagi pengembang sistem daerah (OPD/Pemkab/Akademisi), Anda dapat mendaftar dan memperoleh App Key langsung melalui portal pengembang BPS RI.</p>
<div class="pt-2 flex items-center justify-end gap-2">
<button onclick="tutupModalApiToken()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">Tutup</button>
<a href="https://webapi.bps.go.id/developer" target="_blank" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
<span>Buka WebAPI Developer</span>
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</a>
</div>
</div>
</div>
</div>
<!-- END: Modal Token API BPS -->

<!-- BEGIN: Toast Notification -->
<div id="toast-notif" class="fixed bottom-6 right-20 z-50 hidden transform transition-all duration-300">
<div class="px-4 py-3 rounded-2xl bg-slate-900 text-white shadow-2xl border border-slate-700 flex items-center gap-3">
<div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs">
<i class="fa-solid fa-check"></i>
</div>
<div>
<span class="block text-xs font-extrabold text-white" id="toast-title">Berhasil Disalin!</span>
<span class="block text-[11px] text-slate-300" id="toast-body">Data siap digunakan.</span>
</div>
</div>
</div>
<!-- END: Toast Notification -->

<!-- JavaScript Functions -->
<script>
function showToast(title, body) {
  const toast = document.getElementById('toast-notif');
  document.getElementById('toast-title').textContent = title;
  document.getElementById('toast-body').textContent = body;
  toast.classList.remove('hidden');
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 2800);
}

function salinLinkTabel(url, judul) {
  navigator.clipboard.writeText(url).then(() => {
    showToast('Tautan Disalin!', judul);
  }).catch(() => {
    showToast('Tautan Disalin!', url);
  });
}

function salinTeksData(teks) {
  navigator.clipboard.writeText(teks).then(() => {
    showToast('Data Berhasil Disalin!', teks);
  }).catch(() => {
    showToast('Data Berhasil Disalin!', teks);
  });
}

function handleHeroSearch() {
  const query = document.getElementById('hero-search-query').value.trim();
  const category = document.getElementById('hero-search-category').value;
  if (!query) {
    document.getElementById('publikasi').scrollIntoView({ behavior: 'smooth' });
    return;
  }
  showToast('Mencari Data...', `Kata kunci: "${query}" (${category})`);
  document.getElementById('publikasi').scrollIntoView({ behavior: 'smooth' });
}

function filterPubCategory(cat, btn) {
  document.querySelectorAll('.pub-filter-btn').forEach(b => {
    b.className = 'pub-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200';
  });
  btn.className = 'pub-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm';

  const cards = document.querySelectorAll('.pub-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('pub-cat-' + cat)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function filterInfografis(cat, btn) {
  document.querySelectorAll('.info-filter-btn').forEach(b => {
    b.className = 'info-filter-btn px-4 py-1.5 rounded-full text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200';
  });
  btn.className = 'info-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-600 text-white shadow-sm';

  const cards = document.querySelectorAll('.info-card-item');
  cards.forEach(card => {
    if (cat === 'semua' || card.classList.contains('info-cat-' + cat)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}

function scrollIndicatorCarousel(direction) {
  const container = document.getElementById('indicator-carousel');
  if (container) {
    container.scrollBy({ left: direction * 280, behavior: 'smooth' });
  }
}

function bukaModalBukuTamu() {
  document.getElementById('modal-buku-tamu').classList.remove('hidden');
}

function tutupModalBukuTamu() {
  document.getElementById('modal-buku-tamu').classList.add('hidden');
}

function simpanBukuTamu() {
  const nama = document.getElementById('tamu-nama').value;
  tutupModalBukuTamu();
  showToast('Terima Kasih!', `Kunjungan ${nama} telah tercatat di Buku Tamu Digital PST.`);
}

function bukaModalApiToken() {
  document.getElementById('modal-api-token').classList.remove('hidden');
}

function tutupModalApiToken() {
  document.getElementById('modal-api-token').classList.add('hidden');
}
</script>

</body>
</html>
