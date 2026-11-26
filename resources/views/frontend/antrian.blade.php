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
    <meta name="description" content="Ambil nomor antrian online Pelayanan Statistik Terpadu (PST) BPS Kabupaten Manggarai.">

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
    <section class="py-14 sm:py-18 bg-white border-b border-slate-200" data-purpose="antrian" id="antrian">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-xs font-semibold text-[#1E5B91] mb-1.5">
                    Pelayanan Statistik Terpadu (PST)
                </p>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#123B63] tracking-tight">
                    Antrian Layanan PST BPS Manggarai
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1.5 leading-relaxed">
                    Ambil nomor antrian kunjungan atau konsultasi layanan statistik BPS Kabupaten Manggarai secara online sebelum datang ke ruang PST.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Form Ambil Antrian (7 cols) -->
                <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 sm:p-8">
                    <h2 class="text-base font-bold text-[#123B63] pb-4 border-b border-slate-100 mb-5">
                        Formulir Pengambilan Nomor Antrian
                    </h2>

                    <form id="form-antrian-pst" onsubmit="event.preventDefault(); ambilNomorAntrian();" class="space-y-4">
                        <div>
                            <label for="antrian-layanan" class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Jenis Layanan <span class="text-rose-600">*</span></label>
                            <select id="antrian-layanan" required class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-lg border border-slate-200 focus:border-[#1E5B91] focus:ring-1 focus:ring-[#1E5B91] bg-white">
                                <option value="">Pilih Jenis Layanan PST</option>
                                <option value="K|Konsultasi Statistik">Konsultasi Statistik (Kode K)</option>
                                <option value="D|Permintaan Data & Publikasi">Permintaan Data &amp; Publikasi (Kode D)</option>
                                <option value="R|Rekomendasi Statistik (ROMANTIK)">Rekomendasi Statistik / ROMANTIK (Kode R)</option>
                                <option value="M|Layanan Data Mikro & Peta (SILASTIK)">Layanan Data Mikro &amp; Peta / SILASTIK (Kode M)</option>
                            </select>
                        </div>

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

                        <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                            <button type="button" onclick="bukaModalKonsultasi()" class="text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                Butuh jadwal konsultasi online (Zoom/WA)?
                            </button>
                            <button type="submit" class="px-5 py-2.5 min-h-[42px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs sm:text-sm font-semibold transition-colors">
                                Ambil Nomor Antrian
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Tiket Antrian & Info Jam Layanan (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Kartu Nomor Antrian (Kosong / Terisi) -->
                    <div id="tiket-antrian-empty" class="bg-[#F4F7FA] rounded-xl border border-slate-200 p-6 sm:p-8 text-center">
                        <p class="text-xs font-semibold text-[#1E5B91] mb-1">Tiket Antrian Anda</p>
                        <h3 class="text-base font-bold text-[#123B63]">Belum Ada Nomor Antrian</h3>
                        <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                            Silakan isi formulir di samping untuk mendapatkan nomor antrian layanan PST BPS Kabupaten Manggarai.
                        </p>
                    </div>

                    <div id="tiket-antrian-card" class="hidden bg-white rounded-xl border-2 border-[#1E5B91] overflow-hidden">
                        <div class="px-6 py-4 bg-[#123B63] text-white flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-semibold text-[#9BBBD9]">Nomor Antrian PST</p>
                                <h3 class="text-sm font-bold text-white">{{ $headerNama }}</h3>
                            </div>
                            <span id="tiket-status" class="text-[11px] font-semibold bg-[#3686C8] text-white px-2.5 py-1 rounded-md">Terdaftar</span>
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
                                <dt class="text-slate-500">Waktu Daftar</dt>
                                <dd id="tiket-waktu" class="font-semibold text-slate-700 text-right">-</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Keperluan</dt>
                                <dd id="tiket-keperluan" class="font-medium text-slate-700 text-right max-w-[200px] truncate">-</dd>
                            </div>
                        </dl>

                        <div class="px-6 pb-6 flex items-center gap-2">
                            <button type="button" onclick="window.print()" class="flex-1 py-2.5 px-3 min-h-[40px] rounded-lg bg-[#1E5B91] hover:bg-[#123B63] text-white text-xs font-semibold transition-colors">
                                Cetak Bukti
                            </button>
                            <button type="button" onclick="resetAntrian()" class="py-2.5 px-3 min-h-[40px] rounded-lg bg-[#F4F7FA] hover:bg-slate-200/70 text-[#123B63] border border-slate-200 text-xs font-semibold transition-colors">
                                Ambil Baru
                            </button>
                        </div>
                    </div>

                    <!-- Jam Layanan PST -->
                    <div class="bg-[#F4F7FA] rounded-xl border border-slate-200 p-6 space-y-3 text-xs">
                        <h3 class="font-bold text-[#123B63] text-sm">Jam Operasional Unit PST</h3>
                        <p class="text-slate-600 leading-relaxed">
                            <strong>Waktu Layanan:</strong> {{ $fs['pst_jam_layanan'] ?? 'Senin - Jumat, 08.00 - 15.30 WITA' }}<br>
                            <strong>Lokasi:</strong> {{ $fs['pst_alamat'] ?? 'Jl. Bougenville Utara, Kelurahan Pau, Ruteng - Manggarai' }}
                        </p>
                        <div class="pt-1">
                            <a href="https://wa.me/{{ $pstWaClean }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#1E5B91] hover:text-[#123B63] underline underline-offset-2">
                                <span>Konfirmasi via WhatsApp PST ({{ $pstWa }})</span>
                            </a>
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
function renderTiketAntrian(data) {
    const emptyEl = document.getElementById('tiket-antrian-empty');
    const cardEl  = document.getElementById('tiket-antrian-card');
    if (!emptyEl || !cardEl) return;

    document.getElementById('tiket-nomor').textContent     = data.nomor;
    document.getElementById('tiket-layanan').textContent   = data.layanan;
    document.getElementById('tiket-nama').textContent      = data.nama;
    document.getElementById('tiket-waktu').textContent     = data.waktu;
    document.getElementById('tiket-keperluan').textContent = data.keperluan;

    emptyEl.classList.add('hidden');
    cardEl.classList.remove('hidden');
}

function ambilNomorAntrian() {
    const layananVal = document.getElementById('antrian-layanan')?.value || '';
    const nama       = document.getElementById('antrian-nama')?.value.trim() || '';
    const wa         = document.getElementById('antrian-wa')?.value.trim() || '';
    const keperluan  = document.getElementById('antrian-keperluan')?.value.trim() || '';

    if (!layananVal || !nama || !wa || !keperluan) return;

    const [kode, layananNama] = layananVal.split('|');
    const todayKey = new Date().toISOString().slice(0, 10);
    const counterKey = `natas_antrian_seq_${todayKey}_${kode}`;
    const nextSeq = (parseInt(localStorage.getItem(counterKey) || '0', 10) + 1);
    localStorage.setItem(counterKey, String(nextSeq));

    const padded = String(nextSeq).padStart(3, '0');
    const nowStr = new Date().toLocaleString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
    }) + ' WITA';

    const ticket = {
        date: todayKey,
        nomor: `${kode}-${padded}`,
        layanan: layananNama,
        nama: nama,
        waktu: nowStr,
        keperluan: keperluan
    };

    localStorage.setItem('natas_active_antrian', JSON.stringify(ticket));
    renderTiketAntrian(ticket);
    if (typeof showToast === 'function') {
        showToast('Nomor Antrian Dibuat', `Nomor antrian Anda ${ticket.nomor} (${ticket.layanan}).`);
    }
}

function resetAntrian() {
    localStorage.removeItem('natas_active_antrian');
    document.getElementById('form-antrian-pst')?.reset();
    document.getElementById('tiket-antrian-card')?.classList.add('hidden');
    document.getElementById('tiket-antrian-empty')?.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        const saved = JSON.parse(localStorage.getItem('natas_active_antrian') || 'null');
        const todayKey = new Date().toISOString().slice(0, 10);
        if (saved && saved.date === todayKey) {
            renderTiketAntrian(saved);
        }
    } catch (e) {}
});
</script>

</body>
</html>
