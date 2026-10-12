<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SIMAKINBPS Manggarai</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/simakin-favicon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons 6 Pro/Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js for High Performance Modern Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Tailwind CSS (Vite Build & CDN Fallback) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        bps: {
                            blue: '#1565C0',
                            dark: '#0D47A1',
                            navy: '#0A2540',
                            light: '#1E88E5',
                            faint: '#E8F1FD',
                            pale: '#BEDAF0',
                        },
                        simakin: {
                            green: '#10B981',
                            green_l: '#059669',
                            orange: '#F59E0B',
                            orange_l: '#D97706',
                            orange_xl: '#FBBF24',
                        }
                    },
                    boxShadow: {
                        'glass': '0 8px 30px rgba(0, 0, 0, 0.05)',
                        'glass-lg': '0 20px 45px rgba(21, 101, 192, 0.08)',
                        'blue-glow': '0 0 25px rgba(21, 101, 192, 0.22)',
                        'subtle-card': '0 2px 10px rgba(0,0,0,0.03), 0 1px 3px rgba(0,0,0,0.04)',
                        'elevated-card': '0 12px 32px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04)',
                    }
                }
            }
        }
    </script>

    <!-- Theme & Customization Init -->
    <script>
        (function() {
            try {
                localStorage.removeItem('simakin-theme');
                document.documentElement.classList.remove('dark');
                document.body.style.colorScheme = 'light';

                const accent = localStorage.getItem('simakin-accent') || 'blue';
                const colors = {
                    blue:   ['#1565C0', '#0D47A1', '#1E88E5', '#E8F1FD'],
                    indigo: ['#4F46E5', '#3730A3', '#6366F1', '#EEF2FF'],
                    teal:   ['#0D9488', '#115E59', '#14B8A6', '#F0FDFA'],
                    violet: ['#7C3AED', '#5B21B6', '#8B5CF6', '#F5F3FF'],
                    rose:   ['#E11D48', '#9F1239', '#F43F5E', '#FFF1F2'],
                    amber:  ['#D97706', '#92400E', '#F59E0B', '#FFFBEB'],
                };
                const [blue, dark, light, faint] = colors[accent] || colors.blue;
                const style = document.createElement('style');
                style.id = 'simakin-accent-style';
                style.textContent = `
                    :root { --accent-primary: ${blue}; }
                    .bg-bps-blue { background-color: ${blue} !important; }
                    .text-bps-blue { color: ${blue} !important; }
                    .border-bps-blue { border-color: ${blue} !important; }
                    .bg-bps-dark { background-color: ${dark} !important; }
                    .bg-bps-light { background-color: ${light} !important; }
                    .bg-bps-faint { background-color: ${faint} !important; }
                    .hover\\:bg-bps-blue:hover { background-color: ${blue} !important; }
                    .hover\\:text-bps-blue:hover { color: ${blue} !important; }
                    .hover\\:bg-bps-light:hover { background-color: ${light} !important; }
                    .ring-bps-blue { --tw-ring-color: ${blue} !important; }
                    .focus\\:ring-bps-blue:focus { --tw-ring-color: ${blue} !important; }
                    .shadow-bps-blue\\/30 { --tw-shadow-color: ${blue}4D !important; }
                    .bg-bps-blue\\/10 { background-color: ${blue}1A !important; }
                `;
                document.head.appendChild(style);

                const fontSize = localStorage.getItem('simakin-fontsize');
                if (fontSize) {
                    const sizes = { small: '13px', normal: '15px', large: '17px' };
                    if (sizes[fontSize]) document.documentElement.style.fontSize = sizes[fontSize];
                }
            } catch (e) {
                console.error(e);
            }
        })();
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Modern Glass Surfaces */
        .glass-panel {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.85);
        }

        .glass-navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .sidebar-surface {
            background: #FFFFFF;
            border-right: 1px solid rgba(226, 232, 240, 0.9);
        }

        /* Micro-interactions */
        .hover-lift {
            transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.22s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(21, 101, 192, 0.12);
        }

        /* Active Navigation Menu Highlight */
        .nav-item-active {
            background: linear-gradient(135deg, #1565C0 0%, #1E88E5 100%);
            color: #FFFFFF !important;
            box-shadow: 0 4px 16px rgba(21, 101, 192, 0.28);
        }
        .nav-item-active i {
            color: #FFFFFF !important;
        }

        /* Typewriter Cursor */
        @keyframes twBlink {
            0%, 49% { opacity: 1; }
            50%, 100% { opacity: 0; }
        }
        .typewriter-cursor {
            animation: twBlink 0.75s infinite;
        }

        /* Dark Theme Global Rules */
        html.dark body {
            background-color: #0B1120 !important;
            color: #F8FAFC !important;
        }
        html.dark .glass-navbar {
            background: rgba(11, 17, 32, 0.92) !important;
            border-bottom: 1px solid rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .sidebar-surface {
            background: #0F172A !important;
            border-right: 1px solid rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .glass-panel {
            background: rgba(30, 41, 59, 0.90) !important;
            border: 1px solid rgba(51, 65, 85, 0.7) !important;
        }
        html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark h6 {
            color: #F8FAFC !important;
        }

        /* Form Controls in Dark Mode */
        html.dark select,
        html.dark input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        html.dark textarea {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
            border-color: #334155 !important;
        }
        html.dark select option {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
        }

        /* Filter Periode Cards */
        .filter-card-periode select {
            color: #1E293B !important;
        }
        html.dark .filter-card-periode {
            background-color: rgba(15, 23, 42, 0.75) !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .filter-card-periode select {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
            border-color: #475569 !important;
        }
        html.dark .filter-card-periode select option {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
        }

        /* Buttons, badges, and elements with amber/yellow background keep dark text */
        .bg-amber-400, .bg-amber-300, .bg-amber-500,
        .bg-yellow-400, .bg-yellow-300,
        html.dark .bg-amber-400, html.dark .bg-amber-300, html.dark .bg-amber-500 {
            color: #0F172A !important;
        }
    </style>

    <style>
        /* === TOAST NOTIFICATION SYSTEM === */
        #toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: 360px;
            max-width: calc(100vw - 3rem);
        }

        .toast-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 1rem 1rem 0 1rem;
            border-radius: 1rem;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12), 0 2px 8px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            position: relative;
            animation: toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
        }

        .toast-item.toast-success { border-left: 4px solid #10B981; }
        .toast-item.toast-error   { border-left: 4px solid #EF4444; }
        .toast-item.toast-info    { border-left: 4px solid #3B82F6; }
        .toast-item.toast-warning { border-left: 4px solid #F59E0B; }

        html.dark .toast-item {
            background: #1E293B;
            border-color: #334155;
        }

        .toast-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
        }

        .toast-success .toast-icon { background: #DCFCE7; color: #16A34A; }
        .toast-error   .toast-icon { background: #FEE2E2; color: #DC2626; }
        .toast-info    .toast-icon { background: #DBEAFE; color: #2563EB; }
        .toast-warning .toast-icon { background: #FEF3C7; color: #D97706; }

        .toast-body { flex: 1; padding-bottom: 0.75rem; }
        .toast-title {
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .toast-success .toast-title { color: #15803D; }
        .toast-error   .toast-title { color: #B91C1C; }
        .toast-info    .toast-title { color: #1D4ED8; }
        .toast-warning .toast-title { color: #B45309; }

        .toast-message {
            font-size: 0.8125rem;
            color: #475569;
            margin-top: 0.125rem;
            line-height: 1.45;
        }
        html.dark .toast-message { color: #94A3B8; }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            margin-top: -0.125rem;
            color: #94A3B8;
            font-size: 0.875rem;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .toast-close:hover { color: #475569; }

        .toast-progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 1rem 1rem;
            animation: toastProgress linear forwards;
        }
        .toast-success .toast-progress-bar { background: #10B981; }
        .toast-error   .toast-progress-bar { background: #EF4444; }
        .toast-info    .toast-progress-bar { background: #3B82F6; }
        .toast-warning .toast-progress-bar { background: #F59E0B; }

        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(100%) scale(0.9); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0) scale(1); max-height: 120px; margin-bottom: 0; }
            to   { opacity: 0; transform: translateX(110%) scale(0.9); max-height: 0; margin-bottom: -0.75rem; }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to   { width: 0%;   }
        }
        .toast-item.toast-hiding {
            animation: toastSlideOut 0.3s ease-in forwards;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 selection:bg-bps-blue selection:text-white bg-[#F4F7FC] dark:bg-[#0B1120]" 
      x-data="{ sidebarOpen: false, profileDropdown: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-72 sidebar-surface transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl lg:shadow-sm">
        
        <!-- Sidebar Brand Header -->
        <div class="py-5 px-6 flex items-center justify-center border-b border-slate-100 dark:border-slate-800 relative bg-white dark:bg-slate-900">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center group w-full">
                <img src="{{ asset('images/simakin.png') }}" alt="Logo SIMAKINBPS" class="h-14 w-auto max-w-[210px] object-contain transition-transform duration-300 group-hover:scale-105" />
            </a>

            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar User Mini Badge -->
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40">
            <a href="{{ route('profile.show') }}" title="Lihat Profil Saya" 
               class="p-2.5 rounded-2xl bg-white dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/80 flex items-center gap-3 shadow-xs hover:border-bps-blue/50 hover:shadow-sm transition-all group">
                <div class="relative flex-shrink-0">
                    <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1565C0&color=ffffff' }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-10 h-10 rounded-xl object-cover ring-2 ring-bps-blue/20 dark:ring-blue-500/30 group-hover:ring-bps-blue transition" />
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-800"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-bps-blue transition">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[10px] font-bold text-bps-blue dark:text-blue-400 tracking-wide uppercase truncate">
                            @php
                                $roleLabel = match(Auth::user()->role) {
                                    'admin'                  => 'Administrator',
                                    'kepala_bps'             => 'Kepala BPS',
                                    'ketua_tim'              => 'Ketua Tim',
                                    'anggota_tim', 'pegawai' => 'Anggota Tim',
                                    default                  => ucfirst(str_replace('_', ' ', Auth::user()->role)),
                                };
                            @endphp
                            {{ $roleLabel }}
                        </span>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 dark:text-slate-600 group-hover:text-bps-blue group-hover:translate-x-0.5 transition-all"></i>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="px-3.5 py-4 flex-1 overflow-y-auto space-y-5">
            
            {{-- ====================================================
                 A. MENU KHUSUS ADMINISTRATOR
            ====================================================== --}}
            @if(Auth::user()->isAdmin())
                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Utama</p>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-chart-line text-xs"></i>
                            </div>
                            <span>Dashboard Admin</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Kelola Sistem</p>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.pegawai.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.pegawai.*') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.pegawai.*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-users-gear text-xs"></i>
                            </div>
                            <span>Kelola Pegawai</span>
                        </a>

                        <a href="{{ route('admin.tim.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.tim.*') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.tim.*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-sitemap text-xs"></i>
                            </div>
                            <span>Kelola Tim BPS</span>
                        </a>

                        <a href="{{ route('admin.hari_kerja.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.hari_kerja.*') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.hari_kerja.*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-business-time text-xs"></i>
                            </div>
                            <span>Hari Kerja Aktif</span>
                        </a>

                        <a href="{{ route('admin.frontend.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.frontend.*') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.frontend.*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-sliders text-xs"></i>
                            </div>
                            <span>Tampilan Frontend</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Layanan Surat</p>
                    <div x-data="{ suratAdminOpen: {{ request()->routeIs('surat.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="suratAdminOpen = !suratAdminOpen"
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'text-bps-blue dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                    <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                                </div>
                                <span>e-Surat BPS</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="suratAdminOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                        </button>

                        <div x-show="suratAdminOpen" x-transition.origin.top class="pl-10 pr-2 space-y-1 py-1">
                            <a href="{{ route('surat.master.jenis_surat.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.jenis_surat.*') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-folder-tree text-[10px]"></i>
                                <span>Jenis Surat</span>
                            </a>
                            <a href="{{ route('surat.master.template.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.template.*') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-file-contract text-[10px]"></i>
                                <span>Template Surat</span>
                            </a>
                            <a href="{{ route('surat.master.penomoran.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.penomoran.*') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-list-ol text-[10px]"></i>
                                <span>Penomoran</span>
                            </a>
                            <a href="{{ route('surat.index') }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.index', 'surat.show', 'surat.edit') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <i class="fa-solid fa-envelope-open-text text-[10px]"></i>
                                <span>Daftar Surat</span>
                            </a>
                        </div>
                    </div>
                </div>

            @else
                {{-- ====================================================
                     B. MENU NON-ADMIN (Kepala BPS, Ketua Tim, Pegawai)
                ====================================================== --}}
                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Utama</p>
                    <nav class="space-y-1">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-chart-pie text-xs"></i>
                            </div>
                            <span>Dashboard Kinerja</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Kinerja Pegawai</p>
                    <nav class="space-y-1">
                        
                        <!-- Dropdown Alokasi Pekerjaan -->
                        <div x-data="{ alokasiOpen: {{ request()->routeIs('alokasi.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="alokasiOpen = !alokasiOpen"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('alokasi.*') ? 'bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('alokasi.*') ? 'text-bps-blue dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-clipboard-list text-xs"></i>
                                    </div>
                                    <span>Alokasi Pekerjaan</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="alokasiOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="alokasiOpen" x-transition.origin.top class="pl-10 pr-2 space-y-1 py-1">
                                @if(Auth::user()->isKetuaTim())
                                <a href="{{ route('alokasi.input') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.input', 'alokasi.edit') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-circle-plus text-[10px]"></i>
                                    <span>Input Alokasi</span>
                                </a>
                                @endif

                                <a href="{{ route('alokasi.tim') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.tim') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-users text-[10px]"></i>
                                    <span>Alokasi Tim Saya</span>
                                </a>

                                <a href="{{ route('alokasi.pegawai') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.pegawai') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-user-tag text-[10px]"></i>
                                    <span>Alokasi Pegawai</span>
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown Capaian Kinerja Pegawai (CKP) -->
                        <div x-data="{ ckpOpen: {{ request()->routeIs('ckp.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="ckpOpen = !ckpOpen"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('ckp.*') ? 'bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('ckp.*') ? 'text-bps-blue dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-award text-xs"></i>
                                    </div>
                                    <span>Capaian Kinerja (CKP)</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="ckpOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="ckpOpen" x-transition.origin.top class="pl-10 pr-2 space-y-1 py-1">
                                @if(Auth::user()->isPegawai() || Auth::user()->isKetuaTim())
                                <a href="{{ route('ckp.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.index') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-pen-nib text-[10px]"></i>
                                    <span>CKP Saya (Realisasi)</span>
                                </a>
                                @endif

                                @if(Auth::user()->isKetuaTim() || Auth::user()->isKepala())
                                <a href="{{ route('ckp.nilai') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.nilai') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-check-double text-[10px]"></i>
                                    <span>Penilaian Kinerja</span>
                                </a>
                                @endif

                                <a href="{{ route('ckp.laporan') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.laporan') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-file-invoice text-[10px]"></i>
                                    <span>Laporan CKP Bulanan</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Layanan Surat</p>
                    @if(Auth::user()->isKepala())
                        <a href="{{ route('surat.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-envelope-open-text text-xs"></i>
                            </div>
                            <span>Daftar Surat Dinas</span>
                        </a>
                    @else
                        <div x-data="{ esuratOpen: {{ request()->routeIs('surat.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="esuratOpen = !esuratOpen"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'bg-blue-50 dark:bg-slate-800 text-bps-blue dark:text-blue-400' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'text-bps-blue dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-envelope text-xs"></i>
                                    </div>
                                    <span>e-Surat BPS</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="esuratOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="esuratOpen" x-transition.origin.top class="pl-10 pr-2 space-y-1 py-1">
                                <a href="{{ route('surat.create') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('surat.create') ? 'nav-item-active' : 'text-bps-blue hover:bg-blue-50 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-circle-plus text-[10px]"></i>
                                    <span>Buat Surat Baru</span>
                                </a>
                                <a href="{{ route('surat.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.index', 'surat.show', 'surat.edit') ? 'nav-item-active' : 'text-slate-500 dark:text-slate-400 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <i class="fa-solid fa-folder-open text-[10px]"></i>
                                    <span>Semua Surat</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Preference & Settings -->
            <div>
                <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Akun & Pengaturan</p>
                <nav class="space-y-1">
                    <a href="{{ route('profile.show') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('profile.show') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('profile.show') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                            <i class="fa-solid fa-id-badge text-xs"></i>
                        </div>
                        <span>Profil Saya</span>
                    </a>

                    <a href="{{ route('profile.settings') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('profile.settings') ? 'nav-item-active' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ request()->routeIs('profile.settings') ? 'text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-bps-blue' }}">
                            <i class="fa-solid fa-palette text-xs"></i>
                        </div>
                        <span>Preferensi & Tema</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Sidebar Footer & Logout -->
        <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-rose-600 hover:text-white bg-rose-50/70 hover:bg-rose-600 dark:bg-rose-950/30 dark:hover:bg-rose-600 border border-rose-100 dark:border-rose-900/50 hover:border-rose-600 transition-all duration-200 group cursor-pointer shadow-2xs">
                    <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-0.5 transition-transform text-xs"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- TOP NAVBAR -->
        <header class="sticky top-0 z-30 h-16 glass-navbar flex items-center justify-between px-4 sm:px-8 shadow-xs">
            
            <div class="flex items-center gap-3 sm:gap-4">
                <button @click="sidebarOpen = true" 
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-bps-blue hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <nav class="hidden sm:flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 font-semibold">
                        <a href="{{ route('dashboard') }}" class="hover:text-bps-blue transition">SIMAKINBPS</a>
                        <span>/</span>
                        <span class="text-bps-blue dark:text-blue-400 font-bold truncate max-w-[200px]">@yield('page_title', 'Dashboard')</span>
                    </nav>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                
                <!-- Date Pill Badge -->
                <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100/80 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-2xs">
                    <i class="fa-regular fa-calendar-days text-bps-blue dark:text-blue-400"></i>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <!-- Profile Dropdown Trigger -->
                <div class="relative" @click.outside="profileDropdown = false">
                    <button @click="profileDropdown = !profileDropdown" 
                            class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200/80 dark:hover:border-slate-700 transition-all duration-200 focus:outline-none cursor-pointer">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1565C0&color=ffffff' }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-8 h-8 rounded-lg object-cover ring-2 ring-bps-blue/20 dark:ring-blue-500/30" />
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[120px]">{{ Auth::user()->jabatan ?? 'Pegawai BPS' }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200 ml-0.5" :class="profileDropdown ? 'rotate-180 text-bps-blue' : ''"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileDropdown" 
                         x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                         class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/90 dark:border-slate-700/90 py-2 z-50 overflow-hidden">
                        
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 font-mono mt-0.5">NIP: {{ Auth::user()->nip ?? '-' }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-blue-50 text-bps-blue dark:bg-blue-900/40 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                @php
                                    echo match(Auth::user()->role) {
                                        'admin'       => 'Administrator',
                                        'kepala_bps'  => 'Kepala BPS',
                                        'ketua_tim'   => 'Ketua Tim',
                                        'anggota_tim' => 'Anggota Tim',
                                        default       => ucfirst(str_replace('_', ' ', Auth::user()->role)),
                                    };
                                @endphp
                            </span>
                        </div>
                        
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition">
                                <i class="fa-solid fa-id-badge w-4 text-bps-blue dark:text-blue-400"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('profile.settings') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 font-semibold transition">
                                <i class="fa-solid fa-palette w-4 text-bps-blue dark:text-blue-400"></i>
                                <span>Pengaturan & Tema</span>
                            </a>
                        </div>
                        
                        <div class="pt-1 border-t border-slate-100 dark:border-slate-800">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-bold transition cursor-pointer">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                    <span>Keluar Sistem</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN BODY CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-[#F4F7FC] dark:bg-[#0B1120]">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="py-5 px-6 sm:px-8 border-t border-slate-200/80 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-bps-blue animate-pulse"></span>
                <p>SIMAKINBPS &copy; {{ date('Y') }} — <strong>BPS Kabupaten Manggarai</strong></p>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-400">
                <span>Sistem Informasi Manajemen Kinerja Pegawai Terintegrasi</span>
            </div>
        </footer>

    </div>

    <!-- ===== TOAST NOTIFICATION CONTAINER ===== -->
    <div id="toast-container"></div>

    <!-- Apply saved main background on page load -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const bg = localStorage.getItem('simakin-bg') || 'default';
                const isDark = document.documentElement.classList.contains('dark');
                const bgs = {
                    default:  { bg: isDark ? '#0B1120' : '#F4F7FC', style: '' },
                    white:    { bg: isDark ? '#1E293B' : '#FFFFFF', style: '' },
                    slate:    { bg: isDark ? '#080E1A' : '#F1F5F9', style: '' },
                    dark:     { bg: '#0B1120', style: '' },
                    gradient: { bg: '', style: isDark ? 'linear-gradient(135deg,#0B1120,#1E1B4B,#0B1120)' : 'linear-gradient(135deg,#EEF2FF,#F0F9FF,#F0FDF4)' },
                    navy:     { bg: '', style: isDark ? 'linear-gradient(135deg,#020617,#0B1120)' : 'linear-gradient(135deg,#EFF6FF,#DBEAFE)' },
                };
                const chosen = bgs[bg] || bgs.default;
                const mainEl = document.querySelector('main');
                if (mainEl) {
                    if (chosen.style) {
                        mainEl.style.background = chosen.style;
                        mainEl.style.backgroundColor = '';
                    } else {
                        mainEl.style.background = '';
                        mainEl.style.backgroundColor = chosen.bg;
                    }
                }
            } catch (e) {
                console.error(e);
            }
        });
    </script>

    <!-- ===== TOAST NOTIFICATION SYSTEM ===== -->
    <script>
    (function() {
        var _toastDuration = 5000;

        function showToast(type, title, message, duration) {
            duration = duration || _toastDuration;
            var container = document.getElementById('toast-container');
            if (!container) return;

            var icons = {
                success: 'fa-solid fa-circle-check',
                error:   'fa-solid fa-circle-xmark',
                warning: 'fa-solid fa-triangle-exclamation',
                info:    'fa-solid fa-circle-info'
            };
            var labels = {
                success: 'Berhasil',
                error:   'Gagal',
                warning: 'Peringatan',
                info:    'Informasi'
            };

            var toast = document.createElement('div');
            toast.className = 'toast-item toast-' + type;
            toast.innerHTML = [
                '<div class="toast-icon"><i class="' + (icons[type] || icons.info) + '"></i></div>',
                '<div class="toast-body">',
                '  <p class="toast-title">' + (title || labels[type] || 'Notifikasi') + '</p>',
                '  <p class="toast-message">' + message + '</p>',
                '</div>',
                '<button class="toast-close" onclick="window._dismissToast(this.parentElement)">',
                '  <i class="fa-solid fa-xmark"></i>',
                '</button>',
                '<div class="toast-progress-bar" style="animation-duration:' + duration + 'ms"></div>'
            ].join('');

            container.appendChild(toast);

            var timer = setTimeout(function() {
                window._dismissToast(toast);
            }, duration);

            toast._dismissTimer = timer;
        }

        window._dismissToast = function(el) {
            if (!el || el.classList.contains('toast-hiding')) return;
            clearTimeout(el._dismissTimer);
            el.classList.add('toast-hiding');
            setTimeout(function() {
                if (el.parentNode) el.parentNode.removeChild(el);
            }, 320);
        };

        window.showToast = showToast;

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            showToast('success', null, @json(session('success')));
            @endif
            @if(session('error'))
            showToast('error', null, @json(session('error')));
            @endif
            @if(session('warning'))
            showToast('warning', null, @json(session('warning')));
            @endif
            @if(session('info'))
            showToast('info', null, @json(session('info')));
            @endif
        });
    })();
    </script>

    <!-- Typewriter Effect for Dashboard Hero (Continuous Loop) -->
    <script>
    (function() {
        function initTypewriter() {
            const greetingEl = document.querySelector('.typewriter-greeting');
            const descEl = document.querySelector('.typewriter-desc');
            const titleCursor = document.querySelector('.title-cursor');
            const descCursor = document.querySelector('.desc-cursor');

            if (!greetingEl || !descEl) return;

            const greetingText = greetingEl.getAttribute('data-text') || '';
            const descText = descEl.getAttribute('data-text') || '';

            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                greetingEl.textContent = greetingText;
                descEl.innerHTML = descText.replace(/SIMAKINBPS/g, '<strong>SIMAKINBPS</strong>');
                if (titleCursor) titleCursor.remove();
                if (descCursor) descCursor.remove();
                return;
            }

            let i = 0;
            let j = 0;

            function startTypeGreeting() {
                i = 0;
                greetingEl.textContent = '';
                descEl.innerHTML = '';
                if (titleCursor) titleCursor.classList.remove('hidden');
                if (descCursor) descCursor.classList.add('hidden');
                typeGreeting();
            }

            function typeGreeting() {
                if (i < greetingText.length) {
                    greetingEl.textContent += greetingText.charAt(i);
                    i++;
                    setTimeout(typeGreeting, 40);
                } else {
                    setTimeout(startTypeDesc, 250);
                }
            }

            function startTypeDesc() {
                j = 0;
                if (titleCursor) titleCursor.classList.add('hidden');
                if (descCursor) descCursor.classList.remove('hidden');
                typeDesc();
            }

            function typeDesc() {
                if (j < descText.length) {
                    j++;
                    let current = descText.substring(0, j);
                    current = current.replace(/SIMAKINBPS/g, '<strong>SIMAKINBPS</strong>');
                    descEl.innerHTML = current;
                    setTimeout(typeDesc, 22);
                } else {
                    descEl.innerHTML = descText.replace(/SIMAKINBPS/g, '<strong>SIMAKINBPS</strong>');
                    setTimeout(deleteDesc, 3500);
                }
            }

            function deleteDesc() {
                if (j > 0) {
                    j--;
                    let current = descText.substring(0, j);
                    current = current.replace(/SIMAKINBPS/g, '<strong>SIMAKINBPS</strong>');
                    descEl.innerHTML = current;
                    setTimeout(deleteDesc, 12);
                } else {
                    descEl.innerHTML = '';
                    if (descCursor) descCursor.classList.add('hidden');
                    if (titleCursor) titleCursor.classList.remove('hidden');
                    setTimeout(deleteGreeting, 200);
                }
            }

            function deleteGreeting() {
                if (i > 0) {
                    i--;
                    greetingEl.textContent = greetingText.substring(0, i);
                    setTimeout(deleteGreeting, 20);
                } else {
                    greetingEl.textContent = '';
                    setTimeout(startTypeGreeting, 600);
                }
            }

            setTimeout(startTypeGreeting, 250);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTypewriter);
        } else {
            initTypewriter();
        }
    })();
    </script>

    @stack('scripts')
</body>
</html>
