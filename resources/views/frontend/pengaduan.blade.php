<!DOCTYPE html>
<html class="scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        use App\Models\FrontendSetting;

        $fs = FrontendSetting::allKeyed();

        $brandName     = $fs['brand_name']      ?? 'NATAS BPS MANGGARAI';
        $brandSub      = $fs['brand_sub']       ?? 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai';
        $brandTagline  = $fs['brand_tagline']   ?? 'Satu Ruang, Beragam Layanan Statistik.';
        $btnLabel      = $fs['btn_masuk_label'] ?? 'Masuk';
        $headerNama    = $fs['header_nama']     ?? 'BPS Kabupaten Manggarai';

        $pstWa         = $fs['pst_whatsapp']    ?? '08113904990';
        $pstWaClean    = preg_replace('/[^0-9]/', '', $pstWa);
        if (str_starts_with($pstWaClean, '0')) {
            $pstWaClean = '62' . substr($pstWaClean, 1);
        }
        $pengaduanMail = $fs['pengaduan_email'] ?? 'bps5310@bps.go.id';

        $logoFavicon   = !empty($fs['img_logo_natas']) ? asset('storage/' . $fs['img_logo_natas']) : asset('images/LogoFE_natas.webp');
        $logoBps       = !empty($fs['img_logo_bps']) ? asset('storage/' . $fs['img_logo_bps']) : asset('images/logo_bps.png');
        $logoNatas     = !empty($fs['img_logo_natas']) ? asset('storage/' . $fs['img_logo_natas']) : asset('images/LogoFE_natas.webp');

        $navMenus      = FrontendSetting::getNavMenu();
    @endphp
    <title>Saluran Pengaduan Resmi - {{ $brandName }} | {{ $headerNama }}</title>
    <meta name="description" content="Saluran pengaduan layanan publik resmi BPS Kabupaten Manggarai, BPS Provinsi NTT, dan BPS Republik Indonesia.">

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
                primary: { DEFAULT: '#123B63' },
                secondary: { DEFAULT: '#1E5B91' },
                accent: { DEFAULT: '#3686C8' },
                surface: '#FFFFFF'
              }
            }
          }
        }
    </script>

    <link rel="icon" type="image/png" href="{{ $logoFavicon }}?v=4">
    <link rel="shortcut icon" href="{{ $logoFavicon }}?v=4">

    <style data-purpose="base-enhancements">
        html { scroll-behavior: smooth; scroll-padding-top: 72px; }
        body { font-feature-settings: "cv02", "cv03", "cv04", "cv11"; }
        a[href], button:not(:disabled), select:not(:disabled), summary, label[for] { cursor: pointer; }
        button:disabled { cursor: not-allowed; }
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
        @view-transition {
          navigation: auto;
        }
        @keyframes pageEnter {
          from { opacity: 0; transform: translateY(8px); }
          to { opacity: 1; transform: translateY(0); }
        }
        main {
          animation: pageEnter 0.28s cubic-bezier(0.22, 1, 0.36, 1);
          transition: opacity 0.18s ease, transform 0.18s ease;
        }
        body.page-leaving main {
          opacity: 0;
          transform: translateY(6px);
        }
    </style>
</head>
<body class="bg-[#F4F7FA] text-slate-800 antialiased selection:bg-[#1E5B91] selection:text-white">

<!-- BEGIN: MainNavbar -->
<header class="sticky top-0 z-40 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-6">
        <a class="flex items-center gap-3 shrink-0" href="{{ url('/') }}" title="{{ $brandName }} - {{ $brandTagline }}">
            <img src="{{ $logoNatas }}" alt="{{ $brandName }}" width="180" height="44" class="h-9 sm:h-11 w-auto max-w-[240px] object-contain">
        </a>

        <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-1 text-[13px] font-medium text-slate-600">
            @foreach($navMenus as $nav)
                @if(!empty($nav['is_active']))
                    @php
                        $isSkmMenu   = strtolower(trim($nav['label'] ?? '')) === 'skm';
                        $navHref     = ($isSkmMenu && !empty($fs['skm_link'])) ? $fs['skm_link'] : ($nav['url'] ?? '#');
                        $navTarget   = ($isSkmMenu && !empty($fs['skm_link'])) ? '_blank' : ($nav['target'] ?? '_self');
                        $isActiveNav = request()->is('pengaduan') && str_contains($nav['url'] ?? '', 'pengaduan');
                    @endphp
                    <a class="px-3 py-2 rounded-md transition-colors {{ $isActiveNav ? 'text-[#123B63] font-semibold bg-[#F4F7FA]' : 'hover:text-[#1E5B91] hover:bg-[#F4F7FA]' }}"
                       href="{{ $navHref }}"
                       target="{{ $navTarget }}"
                       @if($navTarget === '_blank') rel="noopener noreferrer" @endif>
                        {{ $nav['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

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

            <button type="button" onclick="document.getElementById('mobile-nav-menu').classList.toggle('hidden')" class="lg:hidden p-2.5 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-md text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Buka Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav-menu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
        @foreach($navMenus as $nav)
            @if(!empty($nav['is_active']))
                @php
                    $isSkmMenu = strtolower(trim($nav['label'] ?? '')) === 'skm';
                    $navHref   = ($isSkmMenu && !empty($fs['skm_link'])) ? $fs['skm_link'] : ($nav['url'] ?? '#');
                    $navTarget = ($isSkmMenu && !empty($fs['skm_link'])) ? '_blank' : ($nav['target'] ?? '_self');
                @endphp
                <a class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-[#1E5B91] hover:bg-[#F4F7FA] rounded-md transition-colors"
                   href="{{ $navHref }}"
                   target="{{ $navTarget }}"
                   @if($navTarget === '_blank') rel="noopener noreferrer" @endif
                   onclick="document.getElementById('mobile-nav-menu').classList.add('hidden')">
                    {{ $nav['label'] }}
                </a>
            @endif
        @endforeach
    </div>
</header>
<!-- END: MainNavbar -->

<main>
    <section class="py-14 sm:py-18 bg-white border-b border-slate-200" data-purpose="pengaduan" id="pengaduan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-xs font-semibold text-[#1E5B91] mb-1.5">
                    Layanan Pengaduan Masyarakat
                </p>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">
                    Saluran Pengaduan Resmi BPS
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1.5 leading-relaxed">
                    Sampaikan saran, masukan, atau pengaduan pelayanan statistik secara langsung melalui kanal resmi BPS Kabupaten Manggarai, BPS Provinsi NTT, maupun BPS RI.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- 1. BPS Kabupaten Manggarai (6 cols) -->
                <article class="lg:col-span-6 bg-white rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 bg-[#123B63] text-white">
                        <p class="text-[11px] font-semibold text-[#9BBBD9]">Tingkat Kabupaten</p>
                        <h2 class="text-lg font-bold text-white mt-0.5">
                            Pengaduan yang Ditangani oleh BPS Kabupaten Manggarai
                        </h2>
                    </div>

                    <div class="p-6 divide-y divide-slate-100 text-xs sm:text-sm">
                        <div class="pb-4 flex items-start gap-3.5">
                            <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">1</span>
                            <div>
                                <h3 class="font-semibold text-[#123B63]">Secara Langsung</h3>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">
                                    Kotak saran &amp; pengaduan pada unit PST BPS Kabupaten Manggarai.
                                </p>
                            </div>
                        </div>

                        <div class="py-4 flex items-start gap-3.5">
                            <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">2</span>
                            <div class="space-y-2">
                                <h3 class="font-semibold text-[#123B63]">Melalui Website</h3>
                                <div class="flex flex-wrap gap-2">
                                    <a href="https://www.lapor.go.id" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                                        <span>lapor.go.id</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                                    </a>
                                    <a href="https://s.bps.go.id/bpsmanggarai" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-[#F4F7FA] hover:bg-slate-200/70 text-[#123B63] border border-slate-200 text-xs font-semibold transition-colors">
                                        <span>Form Input (s.bps.go.id/bpsmanggarai)</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="py-4 flex items-start gap-3.5">
                            <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">3</span>
                            <div>
                                <h3 class="font-semibold text-[#123B63]">Via E-mail</h3>
                                <a href="mailto:ipds5313@bps.go.id" class="inline-block mt-1 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                    ipds5313@bps.go.id
                                </a>
                            </div>
                        </div>

                        <div class="pt-4 flex items-start gap-3.5">
                            <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">4</span>
                            <div>
                                <h3 class="font-semibold text-[#123B63]">WhatsApp Only BPS Kabupaten Manggarai</h3>
                                <a href="https://wa.me/6285190805313" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 mt-1.5 px-3 py-1.5 rounded-md bg-[#F4F7FA] hover:bg-[#1E5B91] text-[#123B63] hover:text-white border border-slate-200 text-xs font-semibold transition-colors">
                                    <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                                    <span>0851-9080-5313</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Right Column: BPS RI & BPS Provinsi NTT (6 cols) -->
                <div class="lg:col-span-6 space-y-6">
                    <!-- 2. BPS RI -->
                    <article class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 bg-[#F4F7FA] border-b border-slate-200">
                            <p class="text-[11px] font-semibold text-[#1E5B91]">Tingkat Pusat</p>
                            <h2 class="text-base font-bold text-[#123B63] mt-0.5">
                                Pengaduan yang Ditangani oleh BPS RI
                            </h2>
                        </div>

                        <div class="p-6 divide-y divide-slate-100 text-xs sm:text-sm">
                            <div class="pb-3.5 flex items-start gap-3.5">
                                <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">1</span>
                                <div>
                                    <h3 class="font-semibold text-[#123B63]">Melalui Website</h3>
                                    <a href="https://webapps.bps.go.id/pengaduan" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 mt-1 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                        <span>https://webapps.bps.go.id/pengaduan</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="py-3.5 flex items-start gap-3.5">
                                <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">2</span>
                                <div>
                                    <h3 class="font-semibold text-[#123B63]">Via E-mail</h3>
                                    <a href="mailto:bpshq@bps.go.id" class="inline-block mt-1 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                        bpshq@bps.go.id
                                    </a>
                                </div>
                            </div>

                            <div class="pt-3.5 flex items-start gap-3.5">
                                <span class="w-6 h-6 rounded-md bg-[#F4F7FA] text-[#123B63] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">3</span>
                                <div>
                                    <h3 class="font-semibold text-[#123B63]">Kontak SMS Layanan BPS RI</h3>
                                    <a href="sms:+6281119500500" class="inline-block mt-1 text-xs font-semibold text-[#123B63] hover:text-[#1E5B91] tabular-nums">
                                        0811-1950-0500
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- 3. BPS Provinsi NTT -->
                    <article class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 bg-[#F4F7FA] border-b border-slate-200">
                            <p class="text-[11px] font-semibold text-[#1E5B91]">Tingkat Provinsi</p>
                            <h2 class="text-base font-bold text-[#123B63] mt-0.5">
                                Pengaduan yang Ditangani oleh BPS Provinsi NTT
                            </h2>
                        </div>

                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs sm:text-sm font-semibold text-[#123B63]">Akses Website Pengaduan BPS NTT</h3>
                                <p class="text-xs text-slate-500 mt-0.5">https://pengaduan.bpsntt.id/</p>
                            </div>
                            <a href="https://pengaduan.bpsntt.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 min-h-[40px] rounded-md bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors shrink-0">
                                <span>Buka Portal Pengaduan</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>

@include('frontend.partials.footer')
@include('frontend.partials.modals')
@include('frontend.partials.scripts')

</body>
</html>
