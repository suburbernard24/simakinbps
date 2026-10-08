@extends('layouts.app')

@section('title', 'Pengaturan Tampilan')
@section('page_title', '⚙️ Pengaturan Tampilan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-800 via-slate-700 to-slate-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-56 h-56 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold uppercase tracking-wider mb-3 border border-white/20">
                <i class="fa-solid fa-gear text-amber-300 animate-spin" style="animation-duration:3s"></i>
                <span>Personalisasi Antarmuka</span>
            </div>
            <h2 class="text-2xl font-black tracking-tight">Pengaturan Tampilan & Tema</h2>
            <p class="text-slate-300 text-sm mt-1">Sesuaikan warna aksen utama, latar belakang area konten, dan preferensi antarmuka dashboard Anda.</p>
        </div>
    </div>

    {{-- Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="themeSettings()">

        {{-- CARD 1: Warna Aksen Utama --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-bps-blue flex items-center justify-center">
                            <i class="fa-solid fa-palette text-sm"></i>
                        </div>
                        Warna Aksen Utama
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Pilih warna aksen identitas antarmuka yang sesuai preferensi Anda.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="(color, key) in accentColors" :key="key">
                            <button @click="setAccent(key)"
                                    :class="accent === key ? 'ring-2 ring-offset-2 scale-105 shadow-md border-transparent' : 'border-slate-200/80 hover:scale-105'"
                                    :style="`--ring-color: ${color.ring};`"
                                    class="p-3 rounded-2xl border transition-all duration-200 cursor-pointer flex flex-col items-center gap-2 text-center"
                                    :style="accent === key ? `outline: 2px solid ${color.ring}; outline-offset: 2px;` : ''">
                                <div class="w-10 h-10 rounded-xl shadow-xs" :style="`background: ${color.bg}`"></div>
                                <p class="text-[10px] font-bold text-slate-700" x-text="color.label"></p>
                                <div x-show="accent === key" class="w-4 h-4 rounded-full flex items-center justify-center" :style="`background: ${color.bg}`">
                                    <i class="fa-solid fa-check text-white text-[9px]"></i>
                                </div>
                            </button>
                        </template>
                    </div>

                    {{-- Current accent preview --}}
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50">
                        <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-2">Pratinjau Warna Aktif</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl shadow-xs transition-all duration-300" :style="`background: ${accentColors[accent].bg}`"></div>
                            <div>
                                <p class="text-sm font-bold text-slate-800" x-text="accentColors[accent].label"></p>
                                <p class="text-xs text-slate-500 font-mono" x-text="accentColors[accent].bg"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: Latar Belakang Area Konten --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-bps-blue flex items-center justify-center">
                            <i class="fa-solid fa-image text-sm"></i>
                        </div>
                        Latar Belakang Area Konten
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Sesuaikan tampilan latar belakang pada area kerja dashboard.</p>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <template x-for="(bg, key) in backgrounds" :key="key">
                        <button @click="setBg(key)"
                                class="relative rounded-2xl overflow-hidden border-2 transition-all duration-200 cursor-pointer h-24"
                                :class="background === key ? 'border-bps-blue ring-2 ring-bps-blue ring-offset-1 shadow-md' : 'border-slate-200 hover:border-slate-300'">
                            <div class="absolute inset-0 transition-all" :style="`background: ${bg.preview}`"></div>
                            <div class="absolute inset-x-0 bottom-0 p-1.5 bg-black/40 backdrop-blur-xs">
                                <p class="text-[10px] font-bold text-white text-center" x-text="bg.label"></p>
                            </div>
                            <div x-show="background === key" class="absolute top-2 right-2 w-5 h-5 rounded-full bg-bps-blue flex items-center justify-center shadow">
                                <i class="fa-solid fa-check text-white text-[9px]"></i>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- CARD 3: Preferensi Lainnya --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-bps-blue flex items-center justify-center">
                        <i class="fa-solid fa-sliders text-sm"></i>
                    </div>
                    Preferensi Lainnya
                </h3>
                <p class="text-xs text-slate-500 mt-1">Pengaturan ukuran teks dan setelan awal bawaan sistem.</p>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                {{-- Font Size --}}
                <div>
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Ukuran Teks</p>
                    <div class="flex gap-2">
                        <button @click="setFontSize('small')"
                                :class="fontSize === 'small' ? 'bg-bps-blue text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">Kecil</button>
                        <button @click="setFontSize('normal')"
                                :class="fontSize === 'normal' ? 'bg-bps-blue text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="flex-1 py-2.5 rounded-xl text-sm font-bold transition cursor-pointer">Normal</button>
                        <button @click="setFontSize('large')"
                                :class="fontSize === 'large' ? 'bg-bps-blue text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="flex-1 py-2.5 rounded-xl text-base font-bold transition cursor-pointer">Besar</button>
                    </div>
                </div>

                {{-- Reset Button --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Reset ke Bawaan</p>
                        <p class="text-[11px] text-slate-500">Kembalikan semua preferensi ke standar awal.</p>
                    </div>
                    <button @click="resetAll()"
                            class="px-4 py-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white text-xs font-bold transition border border-rose-200 shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function themeSettings() {
    return {
        accent: localStorage.getItem('simakin-accent') || 'blue',
        background: localStorage.getItem('simakin-bg') || 'default',
        fontSize: localStorage.getItem('simakin-fontsize') || 'normal',

        accentColors: {
            blue:   { label: 'BPS Biru',   bg: '#1565C0', ring: '#1565C0' },
            indigo: { label: 'Indigo',      bg: '#4F46E5', ring: '#4F46E5' },
            teal:   { label: 'Teal',        bg: '#0D9488', ring: '#0D9488' },
            violet: { label: 'Violet',      bg: '#7C3AED', ring: '#7C3AED' },
            rose:   { label: 'Rose',        bg: '#E11D48', ring: '#E11D48' },
            amber:  { label: 'Amber',       bg: '#D97706', ring: '#D97706' },
        },

        backgrounds: {
            default:  { label: 'Biru Muda',      preview: '#F0F6FF' },
            white:    { label: 'Putih Bersih',   preview: '#FFFFFF' },
            slate:    { label: 'Abu-abu',         preview: '#F1F5F9' },
            gradient: { label: 'Gradasi Lembut', preview: 'linear-gradient(135deg,#EEF2FF,#F0F9FF,#F0FDF4)' },
            navy:     { label: 'Biru Lembut',    preview: 'linear-gradient(135deg,#EFF6FF,#DBEAFE)' },
        },

        init() {
            // Hapus sisa konfigurasi tema gelap
            localStorage.removeItem('simakin-theme');
            document.documentElement.classList.remove('dark');
            document.body.style.colorScheme = 'light';
            this.applyAll();
        },

        setAccent(key) {
            this.accent = key;
            localStorage.setItem('simakin-accent', key);
            this.applyAccent();
        },

        setBg(key) {
            this.background = key;
            localStorage.setItem('simakin-bg', key);
            this.applyBg();
        },

        setFontSize(size) {
            this.fontSize = size;
            localStorage.setItem('simakin-fontsize', size);
            this.applyFontSize();
        },

        applyAll() {
            this.applyAccent();
            this.applyBg();
            this.applyFontSize();
        },

        applyAccent() {
            const colors = {
                blue:   ['#1565C0', '#0D47A1', '#1E88E5', '#E8F1FD'],
                indigo: ['#4F46E5', '#3730A3', '#6366F1', '#EEF2FF'],
                teal:   ['#0D9488', '#0F766E', '#14B8A6', '#F0FDFA'],
                violet: ['#7C3AED', '#5B21B6', '#8B5CF6', '#F5F3FF'],
                rose:   ['#E11D48', '#9F1239', '#F43F5E', '#FFF1F2'],
                amber:  ['#D97706', '#92400E', '#F59E0B', '#FFFBEB'],
            };
            const [blue, dark, light, faint] = colors[this.accent] || colors.blue;
            const r = document.documentElement;
            r.style.setProperty('--color-bps-blue',  blue);
            r.style.setProperty('--color-bps-dark',  dark);
            r.style.setProperty('--color-bps-light', light);
            r.style.setProperty('--color-bps-faint', faint);

            // Patch Tailwind inline vars via style tag
            let styleTag = document.getElementById('simakin-accent-style');
            if (!styleTag) {
                styleTag = document.createElement('style');
                styleTag.id = 'simakin-accent-style';
                document.head.appendChild(styleTag);
            }
            styleTag.textContent = `
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
        },

        applyBg() {
            const bgs = {
                default:  { bg: '#F0F6FF', style: '' },
                white:    { bg: '#FFFFFF', style: '' },
                slate:    { bg: '#F1F5F9', style: '' },
                gradient: { bg: '', style: 'linear-gradient(135deg,#EEF2FF,#F0F9FF,#F0FDF4)' },
                navy:     { bg: '', style: 'linear-gradient(135deg,#EFF6FF,#DBEAFE)' },
            };
            const chosen = bgs[this.background] || bgs.default;
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
        },

        applyFontSize() {
            const sizes = { small: '13px', normal: '15px', large: '17px' };
            document.documentElement.style.fontSize = sizes[this.fontSize] || '15px';
        },

        resetAll() {
            this.accent = 'blue';
            this.background = 'default';
            this.fontSize = 'normal';
            localStorage.removeItem('simakin-theme');
            localStorage.removeItem('simakin-accent');
            localStorage.removeItem('simakin-bg');
            localStorage.removeItem('simakin-fontsize');

            // Reset inline styles
            document.documentElement.removeAttribute('style');
            document.documentElement.classList.remove('dark');
            document.body.style.colorScheme = 'light';
            const mainEl = document.querySelector('main');
            if (mainEl) {
                mainEl.style.background = '';
                mainEl.style.backgroundColor = '';
            }
            const styleTag = document.getElementById('simakin-accent-style');
            if (styleTag) styleTag.textContent = '';
        }
    }
}
</script>
@endpush
