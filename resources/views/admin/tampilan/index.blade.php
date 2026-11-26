@extends('layouts.app')

@section('title', 'Pengaturan Tampilan Backend')
@section('page_title', 'Pengaturan Tampilan Backend')

@section('content')
<div class="space-y-6" x-data="tampilanApp()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-5 rounded-2xl shadow-glass border border-slate-200/60">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                <i class="fa-solid fa-palette"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800">Pengaturan Tampilan Backend</h2>
                <p class="text-[11px] text-slate-500">Sesuaikan logo, nama aplikasi, dan warna tema SIMAKINBPS.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-semibold">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Form Settings --}}
        <div class="lg:col-span-2 space-y-5">

            <form method="POST" action="{{ route('admin.tampilan.update') }}" enctype="multipart/form-data" id="tampilanForm">
                @csrf

                {{-- Card: Identitas Aplikasi --}}
                <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-bps-blue"></i> Identitas Aplikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Nama Aplikasi --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Aplikasi</label>
                            <input type="text" name="app_name"
                                   value="{{ $settings['app_name'] ?? '' }}"
                                   placeholder="SIMAKINBPS Manggarai"
                                   x-model="appName"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10">
                            <p class="text-[10px] text-slate-400 mt-1">Kosongkan untuk menggunakan nama default.</p>
                        </div>

                        {{-- Logo --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Logo Sidebar</label>
                            <div class="flex items-center gap-4">
                                {{-- Preview logo saat ini --}}
                                <div class="w-14 h-14 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0">
                                    @if(!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="Logo" class="w-full h-full object-contain" id="logoPreview">
                                    @else
                                        <img src="{{ asset('images/simakin.png') }}" alt="Logo" class="w-full h-full object-contain" id="logoPreview">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml"
                                           class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-blue hover:file:text-white file:transition-colors cursor-pointer border border-slate-200 rounded-xl py-1.5 bg-white"
                                           onchange="previewLogo(this)">
                                    <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, SVG — maks. 2MB. Rekomendasi: rasio 1:1 (kotak).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Palet Warna --}}
                <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-swatchbook text-bps-blue"></i> Palet Warna Tema
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">

                        {{-- Preset colors --}}
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-2">Pilih Preset Warna</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                $presets = [
                                    ['label' => 'Biru BPS (Default)', 'primary' => '#1565C0', 'dark' => '#0D47A1', 'light' => '#1E88E5', 'faint' => '#E8F1FD'],
                                    ['label' => 'Indigo', 'primary' => '#4338CA', 'dark' => '#3730A3', 'light' => '#6366F1', 'faint' => '#EEF2FF'],
                                    ['label' => 'Teal', 'primary' => '#0D9488', 'dark' => '#0F766E', 'light' => '#14B8A6', 'faint' => '#F0FDFA'],
                                    ['label' => 'Violet', 'primary' => '#7C3AED', 'dark' => '#6D28D9', 'light' => '#8B5CF6', 'faint' => '#F5F3FF'],
                                    ['label' => 'Rose', 'primary' => '#E11D48', 'dark' => '#BE123C', 'light' => '#F43F5E', 'faint' => '#FFF1F2'],
                                    ['label' => 'Amber', 'primary' => '#D97706', 'dark' => '#B45309', 'light' => '#F59E0B', 'faint' => '#FFFBEB'],
                                ];
                                @endphp
                                @foreach($presets as $preset)
                                <button type="button"
                                        @click="applyPreset('{{ $preset['primary'] }}','{{ $preset['dark'] }}','{{ $preset['light'] }}','{{ $preset['faint'] }}')"
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 hover:border-slate-400 text-xs font-semibold text-slate-700 transition-all hover:shadow-sm"
                                        :class="primaryColor === '{{ $preset['primary'] }}' ? 'ring-2 ring-offset-1 ring-bps-blue border-bps-blue' : ''">
                                    <span class="w-4 h-4 rounded-full inline-block border border-white shadow-sm"
                                          style="background-color: {{ $preset['primary'] }}"></span>
                                    {{ $preset['label'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-bold text-slate-700 mb-3">Atau Atur Warna Manual</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Warna Utama</label>
                                    <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-2 py-1.5">
                                        <input type="color" name="primary_color" x-model="primaryColor"
                                               class="w-7 h-7 rounded cursor-pointer border-0 p-0 bg-transparent">
                                        <span x-text="primaryColor" class="text-xs font-mono text-slate-600"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Warna Gelap</label>
                                    <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-2 py-1.5">
                                        <input type="color" name="primary_dark" x-model="primaryDark"
                                               class="w-7 h-7 rounded cursor-pointer border-0 p-0 bg-transparent">
                                        <span x-text="primaryDark" class="text-xs font-mono text-slate-600"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Warna Terang</label>
                                    <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-2 py-1.5">
                                        <input type="color" name="primary_light" x-model="primaryLight"
                                               class="w-7 h-7 rounded cursor-pointer border-0 p-0 bg-transparent">
                                        <span x-text="primaryLight" class="text-xs font-mono text-slate-600"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Warna Pudar</label>
                                    <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-2 py-1.5">
                                        <input type="color" name="primary_faint" x-model="primaryFaint"
                                               class="w-7 h-7 rounded cursor-pointer border-0 p-0 bg-transparent">
                                        <span x-text="primaryFaint" class="text-xs font-mono text-slate-600"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sidebar Style --}}
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-bold text-slate-700 mb-2">Gaya Sidebar</p>
                            <div class="flex gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sidebar_style" value="light"
                                           {{ ($settings['sidebar_style'] ?? 'light') === 'light' ? 'checked' : '' }}
                                           class="accent-bps-blue">
                                    <span class="text-xs font-semibold text-slate-700">Terang (Light)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sidebar_style" value="dark"
                                           {{ ($settings['sidebar_style'] ?? 'light') === 'dark' ? 'checked' : '' }}
                                           class="accent-bps-blue">
                                    <span class="text-xs font-semibold text-slate-700">Gelap (Dark)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-3">
                    <form method="POST" action="{{ route('admin.tampilan.reset') }}"
                          onsubmit="return confirm('Reset semua pengaturan tampilan ke default? Ini tidak dapat diurungkan.')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Reset ke Default
                        </button>
                    </form>
                    <button type="submit" form="tampilanForm"
                            class="px-6 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-xl shadow-md shadow-bps-blue/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- RIGHT: Live Preview --}}
        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/60">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-eye text-bps-blue"></i> Live Preview
                        </h3>
                    </div>
                    <div class="p-4">
                        {{-- Mini sidebar preview --}}
                        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                            {{-- Sidebar mini --}}
                            <div class="p-3 flex flex-col gap-2"
                                 :style="'background-color: ' + sidebarBg">
                                {{-- Logo + name --}}
                                <div class="flex items-center gap-2 pb-2 border-b border-white/20">
                                    <div class="w-7 h-7 rounded-lg overflow-hidden bg-white/20">
                                        <img id="logoPreviewSidebar" src="{{ !empty($settings['logo_path']) ? Storage::url($settings['logo_path']) : asset('images/simakin.png') }}"
                                             class="w-full h-full object-contain">
                                    </div>
                                    <span class="text-[10px] font-bold text-white truncate" x-text="appName || 'SIMAKINBPS'"></span>
                                </div>
                                {{-- Nav items --}}
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg"
                                         :style="'background-color: ' + primaryFaint + '; color: ' + primaryColor">
                                        <i class="fa-solid fa-gauge-high text-[10px]"></i>
                                        <span class="text-[10px] font-bold">Dashboard</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg" style="color: rgba(255,255,255,0.75)">
                                        <i class="fa-solid fa-users text-[10px]"></i>
                                        <span class="text-[10px] font-semibold">Kelola Pegawai</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg" style="color: rgba(255,255,255,0.75)">
                                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                                        <span class="text-[10px] font-semibold">Kelola Tim</span>
                                    </div>
                                </div>
                            </div>
                            {{-- Content mini --}}
                            <div class="bg-slate-50 p-3">
                                <div class="h-5 rounded-lg mb-2" :style="'background-color: ' + primaryColor + '20'"></div>
                                <div class="flex gap-2">
                                    <div class="flex-1 h-12 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                                        <div class="w-6 h-6 rounded-full" :style="'background-color: ' + primaryColor + '30'"></div>
                                    </div>
                                    <div class="flex-1 h-12 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                                        <div class="w-6 h-6 rounded-full" :style="'background-color: ' + primaryLight + '30'"></div>
                                    </div>
                                </div>
                                <div class="mt-2 h-7 rounded-lg flex items-center justify-center text-white text-[9px] font-bold"
                                     :style="'background-color: ' + primaryColor">
                                    Tombol Utama
                                </div>
                            </div>
                        </div>

                        {{-- Color swatches --}}
                        <div class="mt-4 grid grid-cols-4 gap-2">
                            <div class="text-center">
                                <div class="w-full h-7 rounded-lg mb-1" :style="'background-color: ' + primaryColor"></div>
                                <span class="text-[9px] text-slate-500">Utama</span>
                            </div>
                            <div class="text-center">
                                <div class="w-full h-7 rounded-lg mb-1" :style="'background-color: ' + primaryDark"></div>
                                <span class="text-[9px] text-slate-500">Gelap</span>
                            </div>
                            <div class="text-center">
                                <div class="w-full h-7 rounded-lg mb-1" :style="'background-color: ' + primaryLight"></div>
                                <span class="text-[9px] text-slate-500">Terang</span>
                            </div>
                            <div class="text-center">
                                <div class="w-full h-7 rounded-lg mb-1 border border-slate-200" :style="'background-color: ' + primaryFaint"></div>
                                <span class="text-[9px] text-slate-500">Pudar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function tampilanApp() {
    const savedSettings = {
        primaryColor: '{{ $settings['primary_color'] ?? '#1565C0' }}',
        primaryDark:  '{{ $settings['primary_dark']  ?? '#0D47A1' }}',
        primaryLight: '{{ $settings['primary_light'] ?? '#1E88E5' }}',
        primaryFaint: '{{ $settings['primary_faint'] ?? '#E8F1FD' }}',
        sidebarStyle: '{{ $settings['sidebar_style'] ?? 'light' }}',
        appName:      '{{ $settings['app_name'] ?? '' }}',
    };

    return {
        primaryColor: savedSettings.primaryColor,
        primaryDark:  savedSettings.primaryDark,
        primaryLight: savedSettings.primaryLight,
        primaryFaint: savedSettings.primaryFaint,
        appName:      savedSettings.appName,
        get sidebarBg() {
            return this.primaryColor;
        },
        applyPreset(primary, dark, light, faint) {
            this.primaryColor = primary;
            this.primaryDark  = dark;
            this.primaryLight = light;
            this.primaryFaint = faint;
        },
    };
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
            const sb = document.getElementById('logoPreviewSidebar');
            if (sb) sb.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
