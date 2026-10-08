<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMAKINBPS - Sistem Informasi Manajemen Kinerja BPS Kabupaten Manggarai.">
    <title>SIMAKINBPS - Sistem Informasi Manajemenen Kinerja Pegawai</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @php
        use App\Models\FrontendSetting;
        $fs = FrontendSetting::allKeyed();

        // Teks
        $brandTagline  = $fs['brand_tagline']   ?? 'Sistem Informasi Manajemen Kinerja';
        $brandSub      = $fs['brand_sub']        ?? 'BPS Kabupaten Manggarai';
        $brandDesc     = $fs['brand_desc']       ?? 'SIMAKINBPS adalah platform digital manajemen kinerja pegawai di BPS Kabupaten Manggarai yang dirancang untuk mendukung perencanaan, pemantauan, dan evaluasi kinerja secara terintegrasi, transparan, dan efektif.';
        $btnLabel      = $fs['btn_masuk_label']  ?? 'Masuk';
        $headerNama    = $fs['header_nama']      ?? 'BPS KABUPATEN';
        $headerLokasi  = $fs['header_lokasi']    ?? 'MANGGARAI';

        // Warna
        $bgLeftColor   = $fs['bg_left_color']    ?? '#BEDAF0';
        $btnColor      = $fs['btn_color']        ?? '#1565C0';

        // Gambar
        $logoSimakin = isset($fs['img_logo_simakin']) ? asset('storage/' . $fs['img_logo_simakin']) : asset('images/simakin.png');
        $logoBps     = isset($fs['img_logo_bps'])     ? asset('storage/' . $fs['img_logo_bps'])     : asset('images/logo_bps.png');
        $logoKantor  = isset($fs['img_kantor'])       ? asset('storage/' . $fs['img_kantor'])       : asset('images/logo_kantor.png');
    @endphp

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: {{ $bgLeftColor }};
            overflow: hidden;
        }
        .logo-simakin {
            display: inline-block;
            transition: all 0.3s ease;
            max-width: 100%;
            height: auto;
        }
        .landing-page {
            display: flex;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
        .landing-left {
            flex: 0 0 44%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 56px;
            background: {{ $bgLeftColor }};
            position: relative;
            z-index: 2;
        }
        .brand-logo { margin-bottom: 32px; }
        .brand-logo img {
            height: 120px;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .brand-tagline {
            font-size: 21px;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1.4;
            margin-bottom: 6px;
        }
        .brand-sub {
            font-size: 15px;
            font-weight: 500;
            color: #3a4a6b;
            margin-bottom: 24px;
        }
        .brand-desc {
            font-size: 14.5px;
            color: #4a5a7b;
            line-height: 1.6;
            margin-bottom: 36px;
            max-width: 420px;
        }
        .brand-divider {
            width: 80px;
            height: 2px;
            background: {{ $btnColor }};
            border-radius: 2px;
            margin-bottom: 36px;
            opacity: 0.45;
        }
        .btn-masuk {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 44px;
            background: {{ $btnColor }};
            color: #ffffff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            letter-spacing: 0.02em;
            box-shadow: 0 6px 24px {{ $btnColor }}59;
            transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
            width: fit-content;
        }
        .btn-masuk:hover {
            filter: brightness(0.88);
            box-shadow: 0 10px 32px {{ $btnColor }}72;
            transform: translateY(-2px);
        }
        .btn-masuk:active { transform: translateY(0); }
        .landing-right {
            flex: 0 0 56%;
            position: relative;
            overflow: hidden;
            background: linear-gradient(165deg, #5BBDE8 0%, #2E90D0 25%, #1A72BB 55%, #2080C5 80%, #4AAAD8 100%);
        }
        .landing-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 500px 400px at 80% 55%, rgba(255,255,255,0.07) 0%, transparent 65%),
                radial-gradient(ellipse 300px 300px at 15% 15%, rgba(255,255,255,0.05) 0%, transparent 60%);
            z-index: 1;
            pointer-events: none;
        }
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
        .right-header-text {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }
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
            width: 92%;
            max-width: 700px;
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
        @media (max-width: 900px) {
            .landing-left { flex: 0 0 50%; padding: 48px 40px; }
            .landing-right { flex: 0 0 50%; }
        }
        @media (max-width: 700px) {
            body { overflow: auto; }
            .landing-page { flex-direction: column; height: auto; min-height: 100vh; }
            .landing-left { flex: none; }
            .landing-right { flex: none; height: 60vw; min-height: 260px; }
        }
        @media (max-width: 480px) {
            .landing-left { padding: 40px 24px; }
            .btn-masuk { width: 100%; }
        }
    </style>
</head>
<body>

<div class="landing-page">

    <!-- LEFT PANEL -->
    <div class="landing-left">
        <div class="brand-logo">
            <img src="{{ $logoSimakin }}" alt="Logo SIMAKINBPS" class="logo-simakin">
        </div>

        <p class="brand-tagline">{{ $brandTagline }}</p>
        <p class="brand-sub">{{ $brandSub }}</p>

        <p class="brand-desc">{{ $brandDesc }}</p>

        <div class="brand-divider"></div>

        <a href="{{ route('login') }}" class="btn-masuk" id="landing-masuk-btn">
            {{ $btnLabel }}
        </a>
    </div>

    <!-- RIGHT PANEL -->
    <div class="landing-right">
        <div class="right-header">
            <div class="right-header-logo">
                <img src="{{ $logoBps }}" alt="Logo BPS Kabupaten Manggarai">
            </div>
            <div class="right-header-text">
                <span class="right-header-name">{{ $headerNama }}</span>
                <span class="right-header-loc">{{ $headerLokasi }}</span>
            </div>
        </div>

        <div class="right-building">
            <img src="{{ $logoKantor }}" alt="Gedung BPS Kabupaten Manggarai">
        </div>
    </div>

</div>

</body>
</html>
