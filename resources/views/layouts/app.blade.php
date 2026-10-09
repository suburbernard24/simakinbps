<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIMAKINBPS Manggarai</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js for High Performance Modern Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        bps: {
                            blue: '#1565C0',
                            dark: '#0D47A1',
                            light: '#1E88E5',
                            faint: '#E8F1FD',
                            pale: '#BEDAF0',
                        },
                        simakin: {
                            green: '#2E7D32',
                            green_l: '#43A047',
                            orange: '#E65100',
                            orange_l: '#F57C00',
                            orange_xl: '#FFA726',
                        }
                    },
                    boxShadow: {
                        'glass': '0 8px 30px rgba(0, 0, 0, 0.05)',
                        'glass-lg': '0 20px 45px rgba(21, 101, 192, 0.08)',
                        'blue-glow': '0 0 25px rgba(21, 101, 192, 0.25)',
                    }
                }
            }
        }
    </script>

    <!-- Theme & Customization Init (Immediate execution to prevent white/wrong theme flashing) -->
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

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .glass-navbar {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .glass-sidebar {
            background: #E8F1FD;
            border-right: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Smooth Micro-interactions */
        .hover-lift {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(21, 101, 192, 0.15);
        }

        /* Typewriter Cursor Animation */
        @keyframes twBlink {
            0%, 49% { opacity: 1; }
            50%, 100% { opacity: 0; }
        }
        .typewriter-cursor {
            animation: twBlink 0.75s infinite;
        }

        /* Dark Theme Global Rules */
        html.dark body {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }
        html.dark .glass-navbar {
            background: rgba(15, 23, 42, 0.92) !important;
            border-bottom: 1px solid rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .glass-sidebar {
            background: #1e293b !important;
            border-right: 1px solid rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .glass-sidebar .bg-white,
        html.dark header.bg-white,
        html.dark footer.bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .glass-panel {
            background: rgba(30, 41, 59, 0.90) !important;
            border: 1px solid rgba(51, 65, 85, 0.7) !important;
        }

        /* Headings in Dark Mode */
        html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark h6 {
            color: #f8fafc !important;
        }

        /* Buttons, badges, and elements with bright amber or yellow backgrounds MUST ALWAYS keep dark text in all themes */
        .bg-amber-400, .bg-amber-300, .bg-amber-500,
        .bg-yellow-400, .bg-yellow-300,
        [class*="bg-amber-400"], [class*="bg-amber-300"],
        html.dark .bg-amber-400, html.dark .bg-amber-300, html.dark .bg-amber-500,
        html.dark .bg-yellow-400, html.dark .bg-yellow-300,
        html.dark [class*="bg-amber-400"], html.dark [class*="bg-amber-300"] {
            color: #0f172a !important;
        }
        .bg-amber-400 *, .bg-amber-300 *,
        html.dark .bg-amber-400 *, html.dark .bg-amber-300 *,
        html.dark [class*="bg-amber-400"] *, html.dark [class*="bg-amber-300"] * {
            color: #0f172a !important;
        }

        /* Form Controls in Dark Mode: select, input, textarea */
        html.dark select,
        html.dark input:not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]),
        html.dark textarea {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark select option {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }

        /* Dashboard Filter Periode Card (Bulan, Tahun, Terapkan) */
        .filter-card-periode select {
            color: #1e293b !important;
        }
        html.dark .filter-card-periode {
            background-color: rgba(15, 23, 42, 0.75) !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
        }
        html.dark .filter-card-periode select {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            border-color: #475569 !important;
        }
        html.dark .filter-card-periode select option {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
        .filter-card-periode button[type="submit"],
        html.dark .filter-card-periode button[type="submit"] {
            background-color: #fbbf24 !important;
            color: #0f172a !important;
            font-weight: 900 !important;
        }
        .filter-card-periode button[type="submit"] *,
        html.dark .filter-card-periode button[type="submit"] * {
            color: #0f172a !important;
        }

        /* Context-safe text color adaptation in Dark Mode */
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-900,
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-800 {
            color: #f8fafc;
        }
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-700 {
            color: #e2e8f0;
        }
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-600 {
            color: #cbd5e1;
        }
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-500 {
            color: #94a3b8;
        }
        html.dark :not(button):not(input):not(select):not(option):not(a):not([class*="bg-amber"]):not([class*="bg-yellow"]):not([class*="bg-emerald"]):not([class*="bg-rose"]):not([class*="bg-bps"]).text-slate-400 {
            color: #64748b;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-200\/80,
        html.dark .border-slate-100 {
            border-color: #334155 !important;
        }
        html.dark .bg-slate-50,
        html.dark .bg-slate-50\/50 {
            background-color: #0f172a !important;
        }
        html.dark .bg-slate-100 {
            background-color: #334155 !important;
        }
        html.dark .hover\\:bg-slate-50:hover,
        html.dark .hover\\:bg-slate-100:hover {
            background-color: #334155 !important;
        }
        html.dark .bg-bps-faint {
            background-color: rgba(30, 58, 138, 0.25) !important;
        }
        html.dark .border-bps-pale {
            border-color: rgba(30, 58, 138, 0.45) !important;
        }
        html.dark [x-show="profileDropdown"] {
            background-color: #1e293b !important;
            border-color: #334155 !important;
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
            width: 340px;
            max-width: calc(100vw - 3rem);
        }

        .toast-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            padding: 1rem 1rem 0 1rem;
            border-radius: 0.875rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            position: relative;
            animation: toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        .toast-item.toast-success { border-left: 4px solid #22c55e; }
        .toast-item.toast-error   { border-left: 4px solid #ef4444; }
        .toast-item.toast-info    { border-left: 4px solid #3b82f6; }
        .toast-item.toast-warning { border-left: 4px solid #f59e0b; }

        html.dark .toast-item {
            background: #1e293b;
            border-color: #334155;
        }

        .toast-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.875rem;
        }

        .toast-success .toast-icon { background: #dcfce7; color: #16a34a; }
        .toast-error   .toast-icon { background: #fee2e2; color: #dc2626; }
        .toast-info    .toast-icon { background: #dbeafe; color: #2563eb; }
        .toast-warning .toast-icon { background: #fef3c7; color: #d97706; }

        .toast-body { flex: 1; padding-bottom: 0.75rem; }
        .toast-title {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .toast-success .toast-title { color: #15803d; }
        .toast-error   .toast-title { color: #b91c1c; }
        .toast-info    .toast-title { color: #1d4ed8; }
        .toast-warning .toast-title { color: #b45309; }
        html.dark .toast-success .toast-title { color: #4ade80; }
        html.dark .toast-error   .toast-title { color: #f87171; }
        html.dark .toast-info    .toast-title { color: #60a5fa; }
        html.dark .toast-warning .toast-title { color: #fbbf24; }

        .toast-message {
            font-size: 0.8125rem;
            color: #475569;
            margin-top: 0.125rem;
            line-height: 1.45;
        }
        html.dark .toast-message { color: #94a3b8; }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            margin-top: -0.125rem;
            color: #94a3b8;
            line-height: 1;
            font-size: 0.875rem;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .toast-close:hover { color: #475569; }
        html.dark .toast-close:hover { color: #e2e8f0; }

        .toast-progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 0.875rem 0.875rem;
            animation: toastProgress linear forwards;
        }
        .toast-success .toast-progress-bar { background: #22c55e; }
        .toast-error   .toast-progress-bar { background: #ef4444; }
        .toast-info    .toast-progress-bar { background: #3b82f6; }
        .toast-warning .toast-progress-bar { background: #f59e0b; }

        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(100%) scale(0.9); }
            to   { opacity: 1; transform: translateX(0)    scale(1);   }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0)    scale(1);   max-height: 120px; margin-bottom: 0; }
            to   { opacity: 0; transform: translateX(110%) scale(0.9); max-height: 0;     margin-bottom: -0.75rem; }
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
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 selection:bg-bps-blue selection:text-white bg-slate-50/50 dark:bg-slate-900" 
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
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-72 glass-sidebar transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-xl lg:shadow-none">
        
        <!-- Sidebar Brand Header -->
        <div class="py-6 flex flex-col items-center justify-center border-b border-slate-200/80 relative">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center group w-full">
                <img src="{{ asset('images/simakin.png') }}" alt="Logo SIMAKIN" class="logo-simakin h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
            </a>

            <!-- Close button for mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar User Mini Badge -->
        <a href="{{ route('profile.show') }}" title="Lihat Profil Saya" class="p-4 mx-4 my-4 rounded-xl bg-bps-faint border border-bps-pale backdrop-blur-sm flex items-center gap-3 shadow-sm hover:opacity-95 transition group cursor-pointer block">
            <div class="flex items-center gap-3">
                <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1565C0&color=ffffff' }}" 
                     alt="{{ Auth::user()->name }}" 
                     class="w-10 h-10 rounded-lg object-cover ring-2 ring-bps-blue/30 shadow-sm" />
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-bps-blue transition">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-simakin-green animate-pulse"></span>
                        <p class="text-[10px] font-semibold text-bps-blue uppercase tracking-wider">
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
                        </p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Navigation Menu -->
        <div class="px-4 py-2 flex-1 overflow-y-auto space-y-6">
            <div>
                <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase mb-2">Menu Utama</p>
                <nav class="space-y-1.5">
                    
                    {{-- ====================================================
                         MENU KHUSUS ADMINISTRATOR
                         Hanya: Dashboard, Pegawai, Tim, Hari Kerja, Tampilan Frontend
                    ====================================================== --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-gauge-high text-sm"></i>
                            </div>
                            <span>Dashboard Admin</span>
                        </a>

                        <a href="{{ route('admin.pegawai.index') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.pegawai.*') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.pegawai.*') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-users-gear text-sm"></i>
                            </div>
                            <span>Kelola Pegawai</span>
                        </a>

                        <a href="{{ route('admin.tim.index') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.tim.*') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.tim.*') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-sitemap text-sm"></i>
                            </div>
                            <span>Kelola Tim BPS</span>
                        </a>

                        <a href="{{ route('admin.hari_kerja.index') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.hari_kerja.*') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.hari_kerja.*') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-business-time text-sm"></i>
                            </div>
                            <span>Hari Kerja Aktif</span>
                        </a>

                        <a href="{{ route('admin.frontend.index') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('admin.frontend.*') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.frontend.*') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-paint-roller text-sm"></i>
                            </div>
                            <span>Tampilan Frontend</span>
                        </a>

                        <!-- e-Surat BPS Dropdown (Admin) -->
                        <div x-data="{ suratAdminOpen: {{ request()->routeIs('surat.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="suratAdminOpen = !suratAdminOpen"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'bg-bps-faint text-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'bg-bps-blue/10 text-bps-blue' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-stamp text-sm"></i>
                                    </div>
                                    <span>e-Surat BPS</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-slate-400" :class="suratAdminOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="suratAdminOpen" x-transition.origin.top class="pl-11 pr-2 space-y-1 py-1">
                                <a href="{{ route('surat.master.jenis_surat.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.jenis_surat.*') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-folder-tree text-[11px]"></i>
                                    <span>Jenis Surat</span>
                                </a>
                                <a href="{{ route('surat.master.template.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.template.*') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-file-contract text-[11px]"></i>
                                    <span>Template Surat</span>
                                </a>
                                <a href="{{ route('surat.master.penomoran.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.master.penomoran.*') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-list-ol text-[11px]"></i>
                                    <span>Penomoran</span>
                                </a>
                                <a href="{{ route('surat.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.index', 'surat.show', 'surat.edit') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-envelope-open-text text-[11px]"></i>
                                    <span>Daftar Surat</span>
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- ====================================================
                             MENU NON-ADMIN (Kepala BPS, Ketua Tim, Pegawai)
                        ====================================================== --}}
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-chart-pie text-sm"></i>
                            </div>
                            <span>Dashboard</span>
                        </a>

                        @if(Auth::user()->isKepala())
                        {{-- ====================================================
                             MENU e-SURAT BPS (KEPALA BPS)
                             Daftar Surat
                        ====================================================== --}}
                        <a href="{{ route('surat.index') }}" 
                           class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                <i class="fa-solid fa-envelope-open-text text-sm"></i>
                            </div>
                            <span>Daftar Surat</span>
                        </a>
                        @else
                        {{-- ====================================================
                             MODUL e-SURAT BPS (PRD 21.2: PEMOHON - KETUA TIM & PEGAWAI)
                        ====================================================== --}}
                        <div x-data="{ esuratOpen: {{ request()->routeIs('surat.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="esuratOpen = !esuratOpen"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('surat.*') ? 'bg-bps-faint text-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('surat.*') ? 'bg-bps-blue/10 text-bps-blue' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-stamp text-sm"></i>
                                    </div>
                                    <span>e-Surat BPS</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-slate-400" :class="esuratOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="esuratOpen" x-transition.origin.top class="pl-11 pr-2 space-y-1 py-1">
                                <!-- Buat Surat Baru (PRD 21.2) -->
                                <a href="{{ route('surat.create') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('surat.create') ? 'bg-bps-blue text-white shadow-sm' : 'text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-circle-plus text-[11px]"></i>
                                    <span>Buat Surat</span>
                                </a>

                                <!-- Semua Surat (PRD 21.2) -->
                                <a href="{{ route('surat.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('surat.index', 'surat.show', 'surat.edit') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-envelope-open-text text-[11px]"></i>
                                    <span>Semua Surat</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Alokasi Pekerjaan Dropdown -->
                        <div x-data="{ alokasiOpen: {{ request()->routeIs('alokasi.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="alokasiOpen = !alokasiOpen"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('alokasi.*') ? 'bg-bps-faint text-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('alokasi.*') ? 'bg-bps-blue/10 text-bps-blue' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-tasks text-sm"></i>
                                    </div>
                                    <span>Alokasi Pekerjaan</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-slate-400" :class="alokasiOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="alokasiOpen" x-transition.origin.top class="pl-11 pr-2 space-y-1 py-1">
                                {{-- Input Alokasi: HANYA Ketua Tim --}}
                                @if(Auth::user()->isKetuaTim())
                                <a href="{{ route('alokasi.input') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.input', 'alokasi.edit') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-plus-circle text-[11px]"></i>
                                    <span>Input Alokasi Pekerjaan</span>
                                </a>
                                @endif

                                {{-- Alokasi Tim Saya --}}
                                <a href="{{ route('alokasi.tim') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.tim') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-users text-[11px]"></i>
                                    <span>Alokasi Tim Saya</span>
                                </a>

                                {{-- Alokasi Pekerjaan Pegawai (Kepala, Ketua Tim, & Anggota Tim) --}}
                                <a href="{{ route('alokasi.pegawai') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('alokasi.pegawai') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-user-tag text-[11px]"></i>
                                    <span>Alokasi Pekerjaan Pegawai</span>
                                </a>
                            </div>
                        </div>

                        {{-- Capaian Kinerja Pegawai Dropdown --}}
                        @if(Auth::user()->isPegawai() || Auth::user()->isKetuaTim() || Auth::user()->isKepala())
                        <div x-data="{ ckpOpen: {{ request()->routeIs('ckp.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="ckpOpen = !ckpOpen"
                                    class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('ckp.*') ? 'bg-bps-faint text-bps-blue' : 'text-slate-600 hover:text-bps-blue hover:bg-bps-faint' }}">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('ckp.*') ? 'bg-bps-blue/10 text-bps-blue' : 'text-slate-400 group-hover:text-bps-blue' }}">
                                        <i class="fa-solid fa-chart-column text-sm"></i>
                                    </div>
                                    <span>Capaian Kinerja Pegawai</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200 text-slate-400" :class="ckpOpen ? 'rotate-180 text-bps-blue' : ''"></i>
                            </button>

                            <div x-show="ckpOpen" x-transition.origin.top class="pl-11 pr-2 space-y-1 py-1">
                                {{-- 1. CKP Saya (Khusus Pegawai & Ketua Tim) --}}
                                @if(Auth::user()->isPegawai() || Auth::user()->isKetuaTim())
                                <a href="{{ route('ckp.index') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.index') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-list-check text-[11px]"></i>
                                    <span>CKP Saya</span>
                                </a>
                                @endif

                                {{-- 2. Penilaian Pekerjaan Pegawai (Ketua Tim & Kepala BPS) --}}
                                @if(Auth::user()->isKetuaTim() || Auth::user()->isKepala())
                                <a href="{{ route('ckp.nilai') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.nilai') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-clipboard-check text-[11px]"></i>
                                    <span>Penilaian Pekerjaan Pegawai</span>
                                </a>
                                @endif

                                {{-- 3. CKP Pegawai (Semua Role: Pegawai, Ketua Tim, & Kepala BPS) --}}
                                <a href="{{ route('ckp.laporan') }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('ckp.laporan') ? 'bg-bps-blue text-white shadow-sm' : 'text-slate-500 hover:text-bps-blue hover:bg-bps-faint' }}">
                                    <i class="fa-solid fa-file-invoice text-[11px]"></i>
                                    <span>CKP Pegawai</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    @endif
                </nav>
            </div>

            {{-- ====================================================
                 MENU AKUN & PENGATURAN (SEMUA ROLE)
                 Profil Saya & Pengaturan Tampilan / Tema
            ====================================================== --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-[10px] font-extrabold tracking-wider text-slate-400 uppercase mb-2">Akun & Preferensi</p>
                <nav class="space-y-1.5">
                    {{-- Profil Saya --}}
                    <a href="{{ route('profile.show') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('profile.show') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-bps-faint' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('profile.show') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                            <i class="fa-solid fa-id-badge text-sm"></i>
                        </div>
                        <span>Profil Saya</span>
                    </a>

                    {{-- Pengaturan Tampilan & Tema --}}
                    <a href="{{ route('profile.settings') }}" 
                       class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group {{ request()->routeIs('profile.settings') ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/30 border border-bps-blue' : 'text-slate-600 dark:text-slate-300 hover:text-bps-blue hover:bg-bps-faint' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('profile.settings') ? 'bg-white/20 text-white' : 'text-slate-400 group-hover:text-bps-blue' }}">
                            <i class="fa-solid fa-palette text-sm"></i>
                        </div>
                        <span>Pengaturan & Tema</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Sidebar Footer & Logout -->
        <div class="p-4 border-t border-slate-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-bold text-red-600 hover:text-white bg-red-50 hover:bg-red-600 border border-red-100 hover:border-red-600 transition-all duration-200 group">
                    <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-0.5 transition-transform"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- TOP NAVBAR -->
        <header class="sticky top-0 z-30 h-20 glass-navbar flex items-center justify-between px-4 sm:px-8 shadow-sm bg-white">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" 
                        class="lg:hidden p-2.5 rounded-xl text-slate-600 hover:text-bps-blue hover:bg-bps-faint focus:outline-none transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <nav class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 font-medium">
                        <a href="{{ route('dashboard') }}" class="hover:text-bps-blue">Home</a>
                        <span>/</span>
                        <span class="text-bps-blue font-semibold">@yield('page_title', 'Dashboard')</span>
                    </nav>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">
                
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-bps-faint border border-bps-pale text-xs font-semibold text-bps-dark">
                    <i class="fa-regular fa-calendar text-bps-blue"></i>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="relative" @click.outside="profileDropdown = false">
                    <button @click="profileDropdown = !profileDropdown" 
                            class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl hover:bg-slate-100 border border-transparent hover:border-slate-200 transition-all duration-200 focus:outline-none">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=1565C0&color=ffffff' }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-9 h-9 rounded-xl object-cover ring-2 ring-bps-blue/30" />
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate max-w-[140px]">{{ Auth::user()->jabatan ?? 'Pegawai BPS' }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="profileDropdown ? 'rotate-180 text-bps-blue' : ''"></i>
                    </button>

                    <div x-show="profileDropdown" 
                         x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                        
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-slate-500 font-mono mt-0.5">NIP: {{ Auth::user()->nip ?? '-' }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-bps-faint text-bps-blue border border-bps-pale">
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
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold transition">
                                <i class="fa-solid fa-id-badge w-4 text-bps-blue"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('profile.settings') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold transition">
                                <i class="fa-solid fa-palette w-4 text-bps-blue"></i>
                                <span>Pengaturan & Tema</span>
                            </a>
                        </div>
                        <div class="pt-1 border-t border-slate-100 dark:border-slate-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-red-600 hover:bg-red-50 font-bold transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-[#F0F6FF]">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="py-6 px-8 border-t border-slate-200 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-bps-blue"></span>
                <p>SIMAKINBPS &copy; {{ date('Y') }} — <strong>BPS Kabupaten Manggarai</strong>.</p>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-400">
                <span>Platform Digital Manajemen Kinerja</span>
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
                    default:  { bg: isDark ? '#0F172A' : '#F0F6FF', style: '' },
                    white:    { bg: isDark ? '#1E293B' : '#FFFFFF', style: '' },
                    slate:    { bg: isDark ? '#0B0F19' : '#F1F5F9', style: '' },
                    dark:     { bg: '#0F172A', style: '' },
                    gradient: { bg: '', style: isDark ? 'linear-gradient(135deg,#0F172A,#1E1B4B,#0F172A)' : 'linear-gradient(135deg,#EEF2FF,#F0F9FF,#F0FDF4)' },
                    navy:     { bg: '', style: isDark ? 'linear-gradient(135deg,#020617,#0F172A)' : 'linear-gradient(135deg,#EFF6FF,#DBEAFE)' },
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

        // Trigger server-side flash messages as toasts on DOMContentLoaded
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

            // Check reduced motion preference
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
                    // Jeda 3.5 detik agar pengguna dapat membaca dengan nyaman sebelum siklus ulang
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
                    // Jeda 600ms sebelum mulai siklus pengetikan berikutnya
                    setTimeout(startTypeGreeting, 600);
                }
            }

            // Mulai animasi pengetikan pertama
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
