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
    <title>Antrian Layanan PST - {{ $brandName }} | {{ $headerNama }}</title>
    <meta name="description" content="Sistem antrian dan pemanggilan layanan Pelayanan Statistik Terpadu (PST) BPS Kabupaten Manggarai.">

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
        .songke-border {
          height: 12px;
          background-color: #0B2540;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='12' viewBox='0 0 48 12'%3E%3Crect width='48' height='12' fill='%230B2540'/%3E%3Cpath d='M0 6L6 1L12 6L6 11Z' fill='%23F0B44D'/%3E%3Cpath d='M6 3.5L8.5 6L6 8.5L3.5 6Z' fill='%23123B63'/%3E%3Cpath d='M12 6L18 1L24 6L18 11Z' fill='%233686C8'/%3E%3Cpath d='M18 4L20 6L18 8L16 6Z' fill='%23FFFFFF'/%3E%3Cpath d='M24 6L30 1L36 6L30 11Z' fill='%23D9534F'/%3E%3Cpath d='M30 3.5L32.5 6L30 8.5L27.5 6Z' fill='%23F0B44D'/%3E%3Cpath d='M36 6L42 1L48 6L42 11Z' fill='%233686C8'/%3E%3Cpath d='M42 4L44 6L42 8L40 6Z' fill='%23FFFFFF'/%3E%3C/svg%3E");
          background-repeat: repeat-x;
          background-size: 48px 12px;
        }
        .songke-corner-pattern {
          background-color: #123B63;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cg fill='none' stroke='%23FFFFFF' stroke-opacity='0.06' stroke-width='1'%3E%3Cpath d='M20 2L38 20L20 38L2 20Z'/%3E%3Cpath d='M20 9L31 20L20 31L9 20Z'/%3E%3Ccircle cx='20' cy='20' r='2.5' fill='%23F0B44D' fill-opacity='0.14' stroke='none'/%3E%3C/g%3E%3C/svg%3E");
        }
        @media print {
          body { background: #fff !important; }
          header, footer, #antrian-page-header, #antrian-form-card, #antrian-display-card, #tiket-antrian-empty, #tiket-actions, #toast-notif, a[aria-label="Kembali ke atas"] {
            display: none !important;
          }
          section#antrian {
            border: none !important;
            padding: 0 !important;
          }
          #tiket-antrian-card {
            display: block !important;
            max-width: 320px !important;
            margin: 24px auto !important;
            border: 1px solid #94a3b8 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
          }
          #tiket-antrian-card .bg-\[\#123B63\],
          #tiket-antrian-card .bg-\[\#3686C8\] {
            background: #ffffff !important;
            color: #0f172a !important;
            border-bottom: 1px dashed #cbd5e1 !important;
          }
          #tiket-antrian-card .text-\[\#9BBBD9\] {
            color: #475569 !important;
          }
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
                        $isActiveNav = request()->is('antrian') && str_contains($nav['url'] ?? '', 'antrian');
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
    <section class="py-12 sm:py-16 bg-[#F4F7FA] border-b border-slate-200" data-purpose="antrian" id="antrian">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="antrian-page-header" class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold text-[#1E5B91] mb-1.5">
                        Pelayanan Statistik Terpadu (PST)
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">
                        Antrian Layanan PST
                    </h1>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1.5 max-w-2xl">
                        Isi data singkat Anda untuk mengambil nomor antrian dan pantau nomor yang sedang dipanggil di Loket.
                    </p>
                </div>

                <div class="text-xs text-slate-600 bg-white border border-slate-200 rounded-lg px-4 py-2.5 shrink-0">
                    <span class="font-semibold text-[#123B63]">Jam Layanan:</span>
                    <span>{{ $fs['pst_jam_layanan'] ?? 'Senin - Jumat, 08.00 - 15.30 WITA' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Form Ambil Antrian (7 cols) -->
                <div id="antrian-form-card" class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 sm:p-8">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <h2 class="text-base font-bold text-[#123B63]">
                            Ambil Nomor Antrian Baru
                        </h2>
                        <span class="text-[11px] font-medium text-slate-400">Langkah 1 dari 2</span>
                    </div>

                    <form id="form-antrian-pst" onsubmit="event.preventDefault(); ambilNomorAntrian();" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="antrian-nama" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-600">*</span></label>
                                <input type="text" id="antrian-nama" required placeholder="Masukkan nama lengkap" class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91]">
                            </div>
                            <div>
                                <label for="antrian-wa" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / HP <span class="text-rose-600">*</span></label>
                                <input type="tel" id="antrian-wa" required placeholder="08xxxxxxxxxx" class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91]">
                            </div>
                        </div>

                        <div>
                            <label for="antrian-instansi" class="block text-xs font-semibold text-slate-700 mb-1.5">Instansi / Asal</label>
                            <input type="text" id="antrian-instansi" placeholder="Nama instansi, universitas, atau umum" class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91]">
                        </div>

                        <div>
                            <label for="antrian-keperluan" class="block text-xs font-semibold text-slate-700 mb-1.5">Keperluan Singkat <span class="text-rose-600">*</span></label>
                            <textarea id="antrian-keperluan" rows="2" required placeholder="Tuliskan data atau layanan yang dibutuhkan..." class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] resize-none"></textarea>
                        </div>

                        <div id="antrian-error-msg" class="hidden p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs"></div>

                        <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                            <button type="button" onclick="bukaModalKonsultasi()" class="text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                Butuh jadwal konsultasi online (Zoom/WA)?
                            </button>
                            <button type="submit" id="btn-ambil-antrian" class="px-5 py-2.5 min-h-[42px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs sm:text-sm font-semibold transition-colors">
                                Ambil Nomor Antrian
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Sedang Dipanggil + Tiket Anda (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Sedang Dipanggil (Aksen Songke Manggarai) -->
                    <div id="antrian-display-card" class="songke-corner-pattern text-white rounded-xl overflow-hidden border border-[#0B2540] shadow-sm">
                        <div class="songke-border w-full"></div>
                        <div class="p-6 sm:p-7">
                            <div class="flex items-center justify-between border-b border-white/15 pb-3.5">
                                <div>
                                    <p class="text-[11px] font-semibold text-[#F0B44D]">Layar Monitor PST · Manggarai</p>
                                    <h2 class="text-sm font-bold text-white mt-0.5">Nomor Antrian Sedang Dipanggil</h2>
                                </div>
                                <span id="display-jumlah-menunggu" class="text-[11px] font-semibold bg-[#3686C8] text-white px-2.5 py-1 rounded-md">
                                    0 Menunggu
                                </span>
                            </div>

                            <div class="my-5 py-6 px-4 text-center bg-[#0B2540]/85 rounded-lg border border-white/10">
                                <p id="display-nomor" class="text-5xl sm:text-6xl font-extrabold tracking-tight tabular-nums text-white leading-none">
                                    --
                                </p>
                                <div class="mt-3.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#F0B44D]/15 border border-[#F0B44D]/40">
                                    <p id="display-loket" class="text-xs sm:text-sm font-bold text-[#F0B44D]">
                                        Belum ada panggilan
                                    </p>
                                </div>
                                <p id="display-layanan" class="text-xs text-slate-200 mt-2">
                                    Menunggu panggilan petugas PST
                                </p>
                            </div>

                            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-slate-300">
                                <span>Pembaruan otomatis</span>
                                <span id="display-waktu">-</span>
                            </div>
                        </div>
                        <div class="songke-border w-full"></div>
                    </div>

                    <!-- Tiket Saya -->
                    <div id="tiket-antrian-empty" class="bg-white rounded-xl border border-slate-200 p-6 text-center">
                        <p class="text-xs font-semibold text-[#1E5B91] mb-1">Tiket Antrian Anda</p>
                        <h3 class="text-sm font-bold text-[#123B63]">Belum Ada Nomor Antrian</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Pilih loket dan isi formulir di samping untuk mencetak nomor antrian Anda.
                        </p>
                    </div>

                    <div id="tiket-antrian-card" class="hidden bg-white rounded-xl border-2 border-[#1E5B91] overflow-hidden">
                        <div class="songke-border w-full print:hidden"></div>
                        <div class="px-6 py-4 bg-[#123B63] text-white flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-semibold text-[#9BBBD9]">Tiket Antrian Anda</p>
                                <h3 class="text-sm font-bold text-white">{{ $headerNama }}</h3>
                            </div>
                            <span id="tiket-loket" class="text-[11px] font-semibold bg-[#3686C8] text-white px-2.5 py-1 rounded-md">-</span>
                        </div>

                        <div class="p-6 text-center border-b border-slate-100">
                            <p id="tiket-nomor" class="text-5xl font-extrabold text-[#123B63] tabular-nums tracking-tight">-</p>
                            <p id="tiket-layanan" class="text-xs font-semibold text-[#1E5B91] mt-2">-</p>
                        </div>

                        <dl class="p-6 space-y-2 text-xs">
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Pemohon</dt>
                                <dd id="tiket-nama" class="font-semibold text-[#123B63] text-right">-</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Status</dt>
                                <dd id="tiket-status" class="font-semibold text-[#1E5B91] text-right">Menunggu</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Keperluan</dt>
                                <dd id="tiket-keperluan" class="font-medium text-slate-700 text-right max-w-[200px] truncate">-</dd>
                            </div>
                        </dl>

                        <div id="tiket-actions" class="px-6 pb-6 flex items-center gap-2">
                            <button type="button" onclick="window.print()" class="flex-1 py-2.5 px-3 min-h-[40px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                                Cetak Bukti
                            </button>
                            <button type="button" onclick="resetTiketLokal()" class="py-2.5 px-3 min-h-[40px] rounded-lg bg-[#F4F7FA] hover:bg-slate-200/70 text-[#123B63] border border-slate-200 text-xs font-semibold transition-colors">
                                Ambil Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('frontend.partials.footer')
@include('frontend.partials.modals')
@include('frontend.partials.scripts')

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
let suaraAktif = true;
let lastAnnouncedKey = null;

function toggleSuaraPanggilan() {
    suaraAktif = !suaraAktif;
    const label = document.getElementById('label-suara');
    const icon  = document.getElementById('icon-suara');
    if (label && icon) {
        label.textContent = suaraAktif ? 'Suara Panggilan: Aktif' : 'Suara Panggilan: Nonaktif';
        icon.className = suaraAktif ? 'fa-solid fa-volume-high text-[#1E5B91]' : 'fa-solid fa-volume-xmark text-slate-400';
    }
}

function playHospitalChime() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const notes = [523.25, 659.25]; // C5 -> E5 soft chime
        notes.forEach((freq, i) => {
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = freq;
            gain.gain.setValueAtTime(0.001, ctx.currentTime + i * 0.22);
            gain.gain.exponentialRampToValueAtTime(0.18, ctx.currentTime + i * 0.22 + 0.04);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.22 + 0.45);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(ctx.currentTime + i * 0.22);
            osc.stop(ctx.currentTime + i * 0.22 + 0.5);
        });
    } catch (e) {}
}

function ucapkanPanggilan(antrian) {
    if (!suaraAktif || !antrian) return;
    playHospitalChime();

    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const ejaanNomor = antrian.nomor_antrian
            .replace('-', ' ')
            .split('')
            .map(ch => ch === '0' ? 'nol' : ch)
            .join(' ');
        const teks = `Nomor antrian ${ejaanNomor}, atas nama ${antrian.nama_pemohon}, silakan menuju ${antrian.loket}, ${antrian.nama_layanan}.`;
        const utter = new SpeechSynthesisUtterance(teks);
        utter.lang = 'id-ID';
        utter.rate = 0.92;
        setTimeout(() => window.speechSynthesis.speak(utter), 500);
    }
}

function formatJam(iso) {
    if (!iso) return '-';
    return new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WITA';
}

function renderStatusAntrian(data) {
    const active   = data.sedang_dipanggil;
    const menunggu = data.menunggu || [];
    const riwayat  = data.riwayat_dipanggil || [];

    document.getElementById('display-jumlah-menunggu').textContent = `${data.jumlah_menunggu || 0} Menunggu`;

    if (active) {
        document.getElementById('display-nomor').textContent   = active.nomor_antrian;
        document.getElementById('display-loket').textContent   = active.loket;
        document.getElementById('display-layanan').textContent = active.nama_layanan;
        document.getElementById('display-waktu').textContent   = 'Dipanggil pukul ' + formatJam(active.called_at);
    } else {
        document.getElementById('display-nomor').textContent   = '--';
        document.getElementById('display-loket').textContent   = 'Belum ada panggilan';
        document.getElementById('display-layanan').textContent = 'Menunggu panggilan petugas PST';
        document.getElementById('display-waktu').textContent   = '-';
    }

    // Sinkronkan status tiket lokal jika ada
    try {
        const myTicketId = localStorage.getItem('natas_my_antrian_id');
        if (myTicketId) {
            const found = [...(active ? [active] : []), ...menunggu, ...riwayat].find(x => String(x.id) === String(myTicketId));
            if (found) {
                renderTiketAntrian(found);
            }
        }
    } catch (e) {}
}

function renderTiketAntrian(item) {
    document.getElementById('tiket-nomor').textContent     = item.nomor_antrian;
    document.getElementById('tiket-loket').textContent     = item.loket;
    document.getElementById('tiket-layanan').textContent   = item.nama_layanan;
    document.getElementById('tiket-nama').textContent      = item.nama_pemohon;
    document.getElementById('tiket-keperluan').textContent = item.keperluan;

    const statusLabel = item.status === 'dipanggil' ? 'Sedang Dipanggil' : (item.status === 'selesai' ? 'Selesai' : 'Menunggu');
    document.getElementById('tiket-status').textContent = statusLabel;

    document.getElementById('tiket-antrian-empty')?.classList.add('hidden');
    document.getElementById('tiket-antrian-card')?.classList.remove('hidden');
}

async function muatStatusAntrian() {
    try {
        const res = await fetch('/antrian/status', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();
        renderStatusAntrian(data);
    } catch (e) {}
}

async function ambilNomorAntrian() {
    const errEl = document.getElementById('antrian-error-msg');
    const btn   = document.getElementById('btn-ambil-antrian');
    errEl.classList.add('hidden');

    const payload = {
        kode_layanan: 'A',
        nama_pemohon: document.getElementById('antrian-nama').value.trim(),
        no_wa:        document.getElementById('antrian-wa').value.trim(),
        instansi:     document.getElementById('antrian-instansi').value.trim(),
        keperluan:    document.getElementById('antrian-keperluan').value.trim(),
    };

    btn.disabled = true;
    btn.textContent = 'Memproses...';

    try {
        const res = await fetch('/antrian/ambil', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) {
            errEl.textContent = data.message || 'Gagal mengambil nomor antrian. Periksa kembali isian Anda.';
            errEl.classList.remove('hidden');
            return;
        }

        localStorage.setItem('natas_my_antrian_id', String(data.antrian.id));
        renderTiketAntrian(data.antrian);
        document.getElementById('form-antrian-pst').reset();
        await muatStatusAntrian();
        if (typeof showToast === 'function') {
            showToast('Nomor Antrian Dibuat', `Nomor antrian Anda ${data.antrian.nomor_antrian} (${data.antrian.loket}).`);
        }
    } catch (e) {
        errEl.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Ambil Nomor Antrian';
    }
}

function resetTiketLokal() {
    localStorage.removeItem('natas_my_antrian_id');
    document.getElementById('tiket-antrian-card')?.classList.add('hidden');
    document.getElementById('tiket-antrian-empty')?.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    muatStatusAntrian();
    setInterval(muatStatusAntrian, 5000);
});
</script>

</body>
</html>
