@extends('layouts.app')

@section('title', 'Kelola Tampilan Frontend')
@section('page_title', 'Manajemen Tampilan Halaman Depan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Pengaturan Tampilan Halaman Depan</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ubah teks, gambar, dan warna pada halaman login publik (landing page) SIMAKINBPS</p>
        </div>
        <a href="/" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Preview Halaman Depan</span>
        </a>
    </div>


    <form method="POST" action="{{ route('admin.frontend.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================================================================
                 KOLOM KIRI: Teks & Warna
            ================================================================ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Grup: Konten Teks --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-bps-blue flex items-center justify-center">
                            <i class="fa-solid fa-font text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Konten Teks Panel Kiri</h3>
                            <p class="text-xs text-slate-500">Tagline, deskripsi, dan tombol masuk</p>
                        </div>
                    </div>

                    @foreach($settings as $key => $field)
                        @if($field['group'] === 'teks' && in_array($key, ['brand_tagline','brand_sub','btn_masuk_label']))
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ $field['label'] }}</label>
                            <input type="text" name="{{ $key }}" value="{{ $field['value'] }}"
                                class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        </div>
                        @endif
                    @endforeach

                    @if(isset($settings['brand_desc']))
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ $settings['brand_desc']['label'] }}</label>
                        <textarea name="brand_desc" rows="4"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none resize-y leading-relaxed">{{ $settings['brand_desc']['value'] }}</textarea>
                    </div>
                    @endif
                </div>

                {{-- Grup: Teks Panel Kanan --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="fa-solid fa-building text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Teks Header Panel Kanan</h3>
                            <p class="text-xs text-slate-500">Nama dan lokasi instansi di panel kanan atas</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($settings as $key => $field)
                            @if($field['group'] === 'teks' && in_array($key, ['header_nama','header_lokasi']))
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ $field['label'] }}</label>
                                <input type="text" name="{{ $key }}" value="{{ $field['value'] }}"
                                    class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Grup: Warna --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-palette text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Pengaturan Warna</h3>
                            <p class="text-xs text-slate-500">Kustomisasi warna panel kiri dan tombol masuk</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($settings as $key => $field)
                            @if($field['group'] === 'warna')
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">{{ $field['label'] }}</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="{{ $key }}" value="{{ $field['value'] ?: '#000000' }}"
                                        id="color-{{ $key }}"
                                        class="w-12 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                    <input type="text" id="colortext-{{ $key }}" value="{{ $field['value'] }}"
                                        class="flex-1 text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none font-mono"
                                        maxlength="7" pattern="#[0-9A-Fa-f]{6}"
                                        onchange="document.getElementById('color-{{ $key }}').value = this.value">
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Preview Panel --}}
                    <div class="mt-2 rounded-2xl overflow-hidden border border-slate-200" style="height:140px;">
                        <div id="preview-panel" class="h-full flex items-center justify-center transition-all duration-300"
                             style="background-color: {{ $settings['bg_left_color']['value'] ?: '#BEDAF0' }};">
                            <div class="text-center px-6">
                                <p id="preview-tagline" class="font-bold text-slate-800 text-sm">{{ $settings['brand_tagline']['value'] }}</p>
                                <p id="preview-sub" class="text-slate-600 text-xs mt-1">{{ $settings['brand_sub']['value'] }}</p>
                                <span id="preview-btn" class="mt-3 inline-block px-5 py-1.5 rounded-lg text-white text-xs font-bold"
                                    style="background-color: {{ $settings['btn_color']['value'] ?: '#1565C0' }};">
                                    {{ $settings['btn_masuk_label']['value'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 -mt-1 text-center">Preview tampilan panel kiri (perkiraan)</p>
                </div>

            </div>

            {{-- ================================================================
                 KOLOM KANAN: Gambar
            ================================================================ --}}
            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-images text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Gambar & Logo</h3>
                            <p class="text-xs text-slate-500">Upload gambar untuk halaman depan</p>
                        </div>
                    </div>

                    @php
                        $defaultImages = [
                            'img_logo_simakin' => asset('images/simakin.png'),
                            'img_logo_bps'     => asset('images/logo_bps.png'),
                            'img_kantor'       => asset('images/logo_kantor.png'),
                        ];
                    @endphp

                    @foreach($settings as $key => $field)
                        @if($field['group'] === 'gambar')
                        @php
                            $currentSrc = $field['value']
                                ? asset('storage/' . $field['value'])
                                : ($defaultImages[$key] ?? null);
                            $isCustom = !empty($field['value']);
                        @endphp
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ $field['label'] }}</label>

                            {{-- Preview Gambar --}}
                            <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center"
                                 style="min-height: 100px;">
                                @if($currentSrc)
                                    <img id="preview-{{ $key }}" src="{{ $currentSrc }}" alt="{{ $field['label'] }}"
                                         class="max-h-28 max-w-full object-contain p-2">
                                @else
                                    <div id="empty-{{ $key }}" class="flex flex-col items-center gap-2 py-6 text-slate-400">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                        <span class="text-xs font-semibold">Belum ada gambar</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                                    {{ $isCustom ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $isCustom ? 'Custom' : 'Default' }}
                                </span>
                            </div>

                            {{-- Input upload --}}
                            <label class="block cursor-pointer">
                                <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 border-dashed border-slate-200 hover:border-bps-blue hover:bg-blue-50/50 transition group">
                                    <i class="fa-solid fa-upload text-slate-400 group-hover:text-bps-blue text-sm transition"></i>
                                    <span class="text-xs font-semibold text-slate-500 group-hover:text-bps-blue transition">Pilih gambar baru...</span>
                                </div>
                                <input type="file" name="{{ $key }}" accept="image/*" class="hidden"
                                       onchange="previewImage(this, 'preview-{{ $key }}', '{{ $key }}')">
                            </label>
                            <p class="text-[10px] text-slate-400">Format: JPG, PNG, WebP. Maks: 6MB</p>

                            {{-- Tombol Reset --}}
                            @if($isCustom)
                                <form method="POST" action="{{ route('admin.frontend.reset_image') }}"
                                      onsubmit="return confirm('Reset gambar ini ke bawaan sistem?')">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold hover:bg-rose-100 transition border border-rose-100">
                                        <i class="fa-solid fa-rotate-left"></i>
                                        Reset ke Bawaan
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if(!$loop->last)
                            <hr class="border-slate-100">
                        @endif
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Tombol Simpan --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200 transition">Kembali</a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-bps-blue text-white text-sm font-bold shadow-sm hover:bg-bps-light transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Semua Perubahan
            </button>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    // Sync color picker ↔ text input + live preview
    function syncColor(name, value) {
        const picker = document.getElementById('color-' + name);
        const text   = document.getElementById('colortext-' + name);
        if (picker) picker.value = value;
        if (text)   text.value  = value;

        if (name === 'bg_left_color') {
            document.getElementById('preview-panel').style.backgroundColor = value;
        }
        if (name === 'btn_color') {
            document.getElementById('preview-btn').style.backgroundColor = value;
        }
    }

    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.addEventListener('input', function() {
            syncColor(this.name, this.value);
        });
    });

    // Preview gambar sebelum upload
    function previewImage(input, previewId, key) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                } else {
                    // Buat img baru
                    const container = input.closest('.space-y-3').querySelector('[style*="min-height"]');
                    if (container) {
                        const img = document.createElement('img');
                        img.id = previewId;
                        img.src = e.target.result;
                        img.className = 'max-h-28 max-w-full object-contain p-2';
                        const empty = document.getElementById('empty-' + key);
                        if (empty) empty.remove();
                        container.appendChild(img);
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Live preview teks
    document.querySelectorAll('input[name="brand_tagline"]').forEach(el => {
        el.addEventListener('input', () => {
            const p = document.getElementById('preview-tagline');
            if (p) p.textContent = el.value;
        });
    });
    document.querySelectorAll('input[name="brand_sub"]').forEach(el => {
        el.addEventListener('input', () => {
            const p = document.getElementById('preview-sub');
            if (p) p.textContent = el.value;
        });
    });
    document.querySelectorAll('input[name="btn_masuk_label"]').forEach(el => {
        el.addEventListener('input', () => {
            const p = document.getElementById('preview-btn');
            if (p) p.textContent = el.value;
        });
    });
</script>
@endpush
