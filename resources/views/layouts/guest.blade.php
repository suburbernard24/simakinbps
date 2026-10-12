<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk ke Sistem') — SIMAKINBPS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/simakin-favicon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        /* ============================================================
           DESIGN TOKENS
        ============================================================ */
        :root {
            --bps-blue:        #1565C0;
            --bps-blue-dark:   #0D47A1;
            --bps-blue-light:  #1E88E5;
            --bps-blue-faint:  #E8F1FD;
            --neutral-50:  #F8FAFC;
            --neutral-100: #F1F5F9;
            --neutral-200: #E2E8F0;
            --neutral-300: #CBD5E1;
            --neutral-400: #94A3B8;
            --neutral-500: #64748B;
            --neutral-600: #475569;
            --neutral-700: #334155;
            --neutral-800: #1E293B;
            --neutral-900: #0F172A;
            --white:       #FFFFFF;
            --radius-xs:   6px;
            --radius-sm:   8px;
            --radius-md:   12px;
            --radius-lg:   16px;
            --radius-xl:   24px;
            --transition:  0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: #BEDAF0;
            overflow: hidden;
        }
        [x-cloak] { display: none !important; }

        /* ============================================================
           LOGIN PAGE LAYOUT
        ============================================================ */
        .login-page {
            display: flex;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* ============================================================
           LEFT PANEL — Form
        ============================================================ */
        .login-left {
            flex: 0 0 46%;
            display: flex;
            flex-direction: column;
            background: #BEDAF0;
            min-height: 100vh;
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }

        /* Top Area: SIMAKIN logo */
        .login-topbar {
            display: flex;
            align-items: center;
            padding: 28px 48px 0;
            flex-shrink: 0;
        }
        .login-topbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .topbar-logo {
            height: 90px;
            width: auto;
            object-fit: contain;
        }

        /* Form Area */
        .login-form-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 24px 48px 36px;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        /* Title block */
        .login-title-block {
            margin-bottom: 28px;
        }
        .login-page-title {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1.3;
            margin-bottom: 4px;
        }
        .login-page-sub {
            font-size: 13px;
            font-weight: 500;
            color: #3a4a6b;
        }

        /* Alerts */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            border: 1px solid transparent;
            animation: shake 0.4s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60%  { transform: translateX(-4px); }
            40%, 80%  { transform: translateX(4px); }
        }
        .alert-danger { background: #FFF5F5; border-color: #FED7D7; color: #B91C1C; }
        .alert-info   { background: var(--bps-blue-faint); border-color: #C3DAF9; color: var(--bps-blue-dark); }
        .alert-icon { width: 28px; height: 28px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .alert-danger .alert-icon { background: #FEE2E2; color: #B91C1C; }
        .alert-info   .alert-icon { background: #C3DAF9; color: var(--bps-blue); }
        .alert-title  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
        .alert-msg    { font-size: 12.5px; line-height: 1.5; }

        /* Form */
        .login-form { display: flex; flex-direction: column; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: 0.01em;
        }
        .form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .forgot-link {
            font-size: 12px;
            font-weight: 600;
            color: var(--bps-blue);
            cursor: pointer;
            text-decoration: none;
            transition: color var(--transition);
        }
        .forgot-link:hover { color: var(--bps-blue-dark); text-decoration: underline; }

        /* Input */
        .input-wrap { position: relative; display: flex; align-items: center; }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 400;
            color: var(--neutral-800);
            background: rgba(255,255,255,0.85);
            border: 1.5px solid rgba(255,255,255,0.6);
            border-radius: var(--radius-md);
            outline: none;
            transition: all var(--transition);
            line-height: 1.5;
        }
        .form-input::placeholder { color: var(--neutral-400); font-weight: 400; font-size: 13.5px; }
        .form-input:hover { background: rgba(255,255,255,0.95); border-color: rgba(255,255,255,0.9); }
        .form-input:focus {
            background: var(--white);
            border-color: var(--bps-blue);
            box-shadow: 0 0 0 3px rgba(21,101,192,0.12);
        }
        .form-input.is-error { border-color: #F87171; }
        .form-input.is-error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,0.18); }
        .form-input.pr-icon { padding-right: 44px; }

        /* Password toggle */
        .toggle-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--neutral-400);
            font-size: 14px;
            padding: 6px;
            border-radius: var(--radius-xs);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color var(--transition), background var(--transition);
        }
        .toggle-pass:hover { color: var(--bps-blue); }

        /* Checkbox */
        .form-remember { padding-top: 2px; }
        .checkbox-label { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
        .checkbox-input { position: absolute; opacity: 0; width: 0; height: 0; }
        .checkbox-custom {
            width: 18px; height: 18px;
            border: 1.5px solid rgba(21,101,192,0.4);
            border-radius: 5px;
            background: rgba(255,255,255,0.7);
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            transition: all var(--transition);
        }
        .checkbox-input:checked + .checkbox-custom { background: var(--bps-blue); border-color: var(--bps-blue); }
        .checkbox-input:checked + .checkbox-custom::after {
            content: ''; width: 10px; height: 6px;
            border: 2px solid white; border-top: none; border-right: none;
            transform: rotate(-45deg) translateY(-1px);
        }
        .checkbox-text { font-size: 13px; font-weight: 500; color: #1a1a2e; }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 13px 24px;
            background: var(--bps-blue);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(21,101,192,0.35);
            transition: all var(--transition);
            letter-spacing: 0.015em;
            margin-top: 4px;
            position: relative;
            overflow: hidden;
        }
        .btn-login:hover { background: var(--bps-blue-dark); box-shadow: 0 6px 22px rgba(21,101,192,0.45); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }
        .btn-login-shimmer {
            position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.16), transparent);
            transition: left 0.65s ease;
        }
        .btn-login:hover .btn-login-shimmer { left: 100%; }

        /* Footer */
        .login-footer {
            padding: 16px 48px 20px;
            flex-shrink: 0;
        }
        .login-footer p { font-size: 11.5px; color: rgba(26,26,46,0.5); text-align: center; }

        /* ============================================================
           RIGHT PANEL — Visual
        ============================================================ */
        .login-right {
            flex: 0 0 54%;
            position: relative;
            overflow: hidden;
            background: linear-gradient(165deg, #5BBDE8 0%, #2E90D0 25%, #1A72BB 55%, #2080C5 80%, #4AAAD8 100%);
        }
        .login-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 500px 400px at 80% 55%, rgba(255,255,255,0.07) 0%, transparent 65%),
                radial-gradient(ellipse 300px 300px at 15% 15%, rgba(255,255,255,0.05) 0%, transparent 60%);
            z-index: 1;
            pointer-events: none;
        }

        /* Header in right panel */
        .right-header {
            position: absolute;
            top: 28px;
            left: 32px;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .right-header-logo img {
            height: 54px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.25));
        }
        .right-header-text { display: flex; flex-direction: column; line-height: 1.25; }
        .right-header-name {
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 1px 4px rgba(0,0,0,0.2);
            letter-spacing: 0.01em;
        }
        .right-header-loc {
            font-size: 14px;
            font-weight: 700;
            color: rgba(255,255,255,0.92);
            text-shadow: 0 1px 4px rgba(0,0,0,0.15);
        }

        /* Building */
        .right-building {
            position: absolute;
            bottom: 8%;
            left: 0;
            right: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .right-building img {
            width: 95%;
            max-width: 720px;
            height: auto;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 24px 60px rgba(0,0,0,0.28));
            animation: float-building 7s ease-in-out infinite;
        }
        @keyframes float-building {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 1100px) {
            .login-left { flex: 0 0 50%; }
            .login-right { flex: 0 0 50%; }
        }
        @media (max-width: 900px) {
            .login-left { flex: 0 0 55%; }
            .login-right { flex: 0 0 45%; }
            .login-form-area { padding: 24px 36px 36px; }
            .login-topbar { padding: 24px 36px 0; }
            .login-footer { padding: 16px 36px 20px; }
        }
        @media (max-width: 768px) {
            body { overflow: auto; }
            .login-page { flex-direction: column; height: auto; min-height: 100vh; }
            .login-left { flex: none; min-height: 100vh; }
            .login-right { display: none; }
            .login-topbar { padding: 20px 24px 0; }
            .login-form-area { padding: 24px 24px 36px; }
            .login-footer { padding: 16px 24px 20px; }
        }
        @media (max-width: 480px) {
            .topbar-logo { height: 72px; }
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
