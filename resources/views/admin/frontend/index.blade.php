@extends('layouts.app')

@section('title', 'Kelola Tampilan Frontend')
@section('page_title', 'Manajemen Tampilan Halaman Depan')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: '{{ request('tab', 'hero') }}',
    // Modals state
    modalIndikatorAdd: false,
    modalIndikatorEdit: false,
    indikatorEditData: { id: '', judul: '', sub_judul: '', nilai: '', periode: '', deskripsi: '', badge_text: '', link_tabel: '', icon: '', urutan: 0, is_active: 1 },

    modalInfografisAdd: false,
    modalInfografisEdit: false,
    infografisEditData: { id: '', judul: '', kategori: 'umum', badge_text: '', bg_gradient: 'from-blue-600 to-indigo-700', icon: 'chart-simple', data_point_label_1: '', data_point_val_1: '', data_point_label_2: '', data_point_val_2: '', sumber: '', tahun: '', link_url: '', urutan: 0, is_active: 1 },

    modalQuoteAdd: false,
    modalQuoteEdit: false,
    quoteEditData: { id: '', nama: '', jabatan: '', quote: '', urutan: 0, is_active: 1 },

    modalKegiatanAdd: false,
    modalKegiatanEdit: false,
    kegiatanEditData: { id: '', judul: '', kategori: 'Umum', tanggal_display: '', deskripsi: '', badge_color: 'blue', icon: 'newspaper', link_url: '', urutan: 0, is_active: 1 }
}">

    {{-- Header --}}
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-blue-50 text-bps-blue border border-blue-200">Front Office: NATAS BPS MANGGARAI</span>
                <span class="text-xs text-slate-400">• BPS Kab. Manggarai</span>
            </div>
            <h2 class="text-lg font-black text-slate-900 mt-1">Manajemen Tampilan Web Front Office (NATAS)</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola identitas, konten, dan publikasi portal NATAS (Navigasi Akses Terpadu Layanan Statistik) melalui sub-menu di bawah ini.</p>
        </div>
        <a href="/?preview=1" target="_blank" class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shrink-0 shadow-sm shadow-blue-500/20">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Preview Web Front Office</span>
        </a>
    </div>

    {{-- Sub-Menu Tab Bar --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200/80 custom-scrollbar">
        <button type="button" @click="activeTab = 'hero'" :class="activeTab === 'hero' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-font text-sm"></i>
            <span>2. Hero & Branding</span>
        </button>
        <button type="button" @click="activeTab = 'pengumuman'" :class="activeTab === 'pengumuman' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-bullhorn text-sm"></i>
            <span>3. Pengumuman Ticker</span>
        </button>
        <button type="button" @click="activeTab = 'data'" :class="activeTab === 'data' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-chart-pie text-sm"></i>
            <span>4. 8 Data Pokok</span>
        </button>
        <button type="button" @click="activeTab = 'indikator'" :class="activeTab === 'indikator' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-chart-line text-sm"></i>
            <span>5. Indikator Carousel</span>
        </button>
        <button type="button" @click="activeTab = 'publikasi'" :class="activeTab === 'publikasi' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-book-bookmark text-sm"></i>
            <span>6. Publikasi & WebAPI</span>
        </button>
        <button type="button" @click="activeTab = 'infografis'" :class="activeTab === 'infografis' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-image text-sm"></i>
            <span>7. Galeri Infografis</span>
        </button>
        <button type="button" @click="activeTab = 'quotes'" :class="activeTab === 'quotes' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-quote-left text-sm"></i>
            <span>8. Quotes of the Day</span>
        </button>
        <button type="button" @click="activeTab = 'kegiatan'" :class="activeTab === 'kegiatan' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-calendar-check text-sm"></i>
            <span>9. Berita & Kegiatan</span>
        </button>
        <button type="button" @click="activeTab = 'pst'" :class="activeTab === 'pst' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-headset text-sm"></i>
            <span>10. Layanan PST & Aduan</span>
        </button>
        <button type="button" @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-bps-blue text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-palette text-sm"></i>
            <span>11. Logo & Warna</span>
        </button>
    </div>

    {{-- =========================================================================
         FORM PENGATURAN UMUM FRONTEND (MENU, HERO, TICKER, DATA, PUBLIKASI, PST, MEDIA)
    ========================================================================= --}}
    <form method="POST" action="{{ route('admin.frontend.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- SUB MENU 2: HERO & BRANDING --}}
        <div x-show="activeTab === 'hero'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-font text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Identitas Front Office & Hero Section</h3>
                        <p class="text-xs text-slate-500">Sesuaikan nama web front office, kepanjangan, tagline, dan deskripsi portal</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Web Front Office</label>
                        <input type="text" name="brand_name" value="{{ $settings['brand_name']['value'] ?? 'NATAS BPS MANGGARAI' }}"
                            class="w-full text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kepanjangan / Sub-Judul</label>
                        <input type="text" name="brand_sub" value="{{ $settings['brand_sub']['value'] ?? 'Navigasi Akses Terpadu Layanan Statistik BPS Manggarai' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tagline Hero Banner</label>
                        <input type="text" name="brand_tagline" value="{{ $settings['brand_tagline']['value'] ?? 'Satu Ruang, Beragam Layanan Statistik.' }}"
                            class="w-full text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Label Tombol Masuk SIMAKIN</label>
                        <input type="text" name="btn_masuk_label" value="{{ $settings['btn_masuk_label']['value'] ?? 'Masuk' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat Portal (Hero Subtitle)</label>
                        <textarea name="brand_desc" rows="3"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none leading-relaxed">{{ $settings['brand_desc']['value'] ?? 'NATAS (Navigasi Akses Terpadu Layanan Statistik) BPS Kabupaten Manggarai adalah portal satu ruang untuk beragam layanan statistik terpadu, publikasi resmi, data indikator makro, rekomendasi kegiatan, dan konsultasi interaktif secara terintegrasi, transparan, dan terpercaya.' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Instansi (Header)</label>
                        <input type="text" name="header_nama" value="{{ $settings['header_nama']['value'] ?? 'BPS KABUPATEN' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi Lembaga (Header)</label>
                        <input type="text" name="header_lokasi" value="{{ $settings['header_lokasi']['value'] ?? 'MANGGARAI' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- SUB MENU 3: PENGUMUMAN TICKER --}}
        <div x-show="activeTab === 'pengumuman'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Pengumuman & Ticker Berjalan</h3>
                        <p class="text-xs text-slate-500">Teks pengumuman penting yang tampil di bar biru bawah hero beranda</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teks Pengumuman Ticker</label>
                    <textarea name="ticker_text" rows="3"
                        class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none leading-relaxed">{{ $settings['ticker_text']['value'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- SUB MENU 4: 8 DATA POKOK --}}
        <div x-show="activeTab === 'data'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-chart-pie text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">8 Data Pokok & Indikator Strategis Manggarai</h3>
                        <p class="text-xs text-slate-500">Sesuaikan angka, persentase, dan periode 8 indikator kunci yang ditampilkan pada grid beranda</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">1. Pertumbuhan Ekonomi Daerah</span>
                        <input type="text" name="data_ekonomi_val" value="{{ $settings['data_ekonomi_val']['value'] ?? '5,02%' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_ekonomi_sub" value="{{ $settings['data_ekonomi_sub']['value'] ?? 'Laju PDRB ADHK Tahun 2023' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">2. Indeks Pembangunan Manusia (IPM)</span>
                        <input type="text" name="data_ipm_val" value="{{ $settings['data_ipm_val']['value'] ?? '67,03' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_ipm_sub" value="{{ $settings['data_ipm_sub']['value'] ?? 'Metode Baru IPM 2023 (Kategori Sedang)' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">3. Angka Kemiskinan</span>
                        <input type="text" name="data_kemiskinan_val" value="{{ $settings['data_kemiskinan_val']['value'] ?? '19,84%' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_kemiskinan_sub" value="{{ $settings['data_kemiskinan_sub']['value'] ?? 'Persentase Penduduk Miskin 2023' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">4. Tingkat Pengangguran Terbuka (TPT)</span>
                        <input type="text" name="data_tpt_val" value="{{ $settings['data_tpt_val']['value'] ?? '2,48%' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_tpt_sub" value="{{ $settings['data_tpt_sub']['value'] ?? 'Keadaan Sakernas Agustus 2023' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">5. Jumlah Penduduk Manggarai</span>
                        <input type="text" name="data_penduduk_val" value="{{ $settings['data_penduduk_val']['value'] ?? '325.419' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_penduduk_sub" value="{{ $settings['data_penduduk_sub']['value'] ?? 'Jiwa (Hasil Proyeksi SP2020)' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">6. Luas Wilayah Kabupaten</span>
                        <input type="text" name="data_luas_val" value="{{ $settings['data_luas_val']['value'] ?? '1.669,92' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_luas_sub" value="{{ $settings['data_luas_sub']['value'] ?? 'Kilometer Persegi (12 Kecamatan)' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">7. Pengeluaran Per Kapita</span>
                        <input type="text" name="data_pengeluaran_val" value="{{ $settings['data_pengeluaran_val']['value'] ?? 'Rp 8.924.000' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_pengeluaran_sub" value="{{ $settings['data_pengeluaran_sub']['value'] ?? 'Disesuaikan per Tahun (2023)' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200 space-y-3 bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-900 uppercase">8. Usaha Pertanian Perorangan (ST2023)</span>
                        <input type="text" name="data_tani_val" value="{{ $settings['data_tani_val']['value'] ?? '54.210' }}" class="w-full text-sm px-3 py-2 rounded-xl border border-slate-200">
                        <input type="text" name="data_tani_sub" value="{{ $settings['data_tani_sub']['value'] ?? 'Rumah Tangga Usaha Pertanian' }}" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
            </div>
        </div>

        {{-- SUB MENU 6: PUBLIKASI & WEBAPI BPS --}}
        <div x-show="activeTab === 'publikasi'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                        <i class="fa-solid fa-book-bookmark text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Koleksi Publikasi & Integrasi WebAPI BPS</h3>
                        <p class="text-xs text-slate-500">Konfigurasi API Key WebAPI BPS Resmi (webapi.bps.go.id) dan kode domain wilayah</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Domain Wilayah BPS</label>
                        <input type="text" name="bps_domain" value="{{ $settings['bps_domain']['value'] ?? '5310' }}"
                            placeholder="5310"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none font-mono">
                        <p class="text-[11px] text-slate-400 mt-1">Default <code>5310</code> untuk BPS Kabupaten Manggarai.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">BPS WebAPI Key (App Key)</label>
                        <input type="password" name="bps_api_key" value="{{ $settings['bps_api_key']['value'] ?? '' }}"
                            placeholder="Masukkan WebAPI Key dari webapi.bps.go.id"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none font-mono">
                        <p class="text-[11px] text-slate-400 mt-1">Dapatkan di portal resmi WebAPI BPS untuk menyinkronkan rilis publikasi otomatis.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUB MENU 10: LAYANAN PST & PENGADUAN --}}
        <div x-show="activeTab === 'pst'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="fa-solid fa-headset text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Pelayanan Statistik Terpadu (PST) & Saluran Pengaduan</h3>
                        <p class="text-xs text-slate-500">Kontak WhatsApp Halo PST, jam pelayanan, alamat, dan email saluran pengaduan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Halo PST</label>
                        <input type="text" name="pst_whatsapp" value="{{ $settings['pst_whatsapp']['value'] ?? '08113904990' }}"
                            placeholder="08113904990"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Pengaduan Publik</label>
                        <input type="email" name="pengaduan_email" value="{{ $settings['pengaduan_email']['value'] ?? 'bps5310@bps.go.id' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Operasional Pelayanan PST</label>
                        <input type="text" name="pst_jam_layanan" value="{{ $settings['pst_jam_layanan']['value'] ?? 'Senin s.d. Jumat, 08.00 - 15.30 WITA' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Ruang PST</label>
                        <input type="text" name="pst_alamat" value="{{ $settings['pst_alamat']['value'] ?? 'Kantor BPS Kab. Manggarai, Jl. Ranaka, Ruteng, Flores, NTT' }}"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Link Survei Kepuasan Masyarakat (SKM)</label>
                        <input type="url" name="skm_link" value="{{ $settings['skm_link']['value'] ?? '' }}"
                            placeholder="https://s.bps.go.id/skm-manggarai-2026"
                            class="w-full text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <p class="text-[11px] text-slate-500 mt-1">Direct link tujuan saat pengunjung mengklik menu <strong>SKM</strong> di halaman depan (kosongkan jika ingin memakai URL bawaan menu).</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUB MENU 11: LOGO, GAMBAR & WARNA --}}
        <div x-show="activeTab === 'media'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-palette text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Skema Warna</h3>
                            <p class="text-xs text-slate-500">Warna panel dan tombol</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Warna Utama (Primary)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color_primary" id="color-color_primary" value="{{ $settings['color_primary']['value'] ?? '#0058A8' }}" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                <input type="text" id="colortext-color_primary" value="{{ $settings['color_primary']['value'] ?? '#0058A8' }}" class="flex-1 text-xs font-mono px-3 py-2 rounded-xl border border-slate-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Warna Sekunder (Secondary)</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color_secondary" id="color-color_secondary" value="{{ $settings['color_secondary']['value'] ?? '#0D274C' }}" class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                <input type="text" id="colortext-color_secondary" value="{{ $settings['color_secondary']['value'] ?? '#0D274C' }}" class="flex-1 text-xs font-mono px-3 py-2 rounded-xl border border-slate-200">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Upload Logo & Identitas Visual</h3>
                            <p class="text-xs text-slate-500">Pilih berkas gambar format PNG/WEBP/SVG untuk logo portal NATAS dan SIMAKIN</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Logo Web Front Office (NATAS)</label>
                            <div class="mb-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200 inline-block">
                                <img src="{{ !empty($settings['img_logo_natas']['value']) ? asset('storage/' . $settings['img_logo_natas']['value']) : asset('images/LogoFE_natas.webp') }}" alt="Logo NATAS" class="h-10 w-auto object-contain">
                            </div>
                            <input type="file" name="img_logo_natas" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">Logo utama portal NATAS. Standar default: <code>LogoFE_natas.webp</code>.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Logo Resmi SIMAKIN (Internal)</label>
                            @if(!empty($settings['img_logo_simakin']['value']))
                                <div class="mb-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200 inline-block">
                                    <img src="{{ asset('storage/' . $settings['img_logo_simakin']['value']) }}" alt="Logo SIMAKIN" class="h-10 w-auto object-contain">
                                </div>
                            @endif
                            <input type="file" name="img_logo_simakin" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">Logo untuk panel login dan aplikasi internal SIMAKIN BPS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating Save Bar for General Settings Tabs --}}
        <div x-show="['menu', 'hero', 'pengumuman', 'data', 'publikasi', 'pst', 'media'].includes(activeTab)" class="pt-4 flex items-center justify-end">
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-bps-blue text-white text-sm font-bold shadow-md shadow-blue-500/20 hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Pengaturan</span>
            </button>
        </div>
    </form>


    {{-- =========================================================================
         SUB MENU 5: INDIKATOR TERKINI CAROUSEL (DEDICATED CRUD)
    ========================================================================= --}}
    <div x-show="activeTab === 'indikator'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Kelola Indikator Makro Carousel Beranda</h3>
                        <p class="text-xs text-slate-500">Indikator makro yang bergeser pada bagian atas portal publik beranda</p>
                    </div>
                </div>
                <button type="button" @click="modalIndikatorAdd = true" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-blue-500/20">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Tambah Indikator Baru</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3 text-center w-14">Urutan</th>
                            <th class="p-3 min-w-[200px]">Indikator & Sub Judul</th>
                            <th class="p-3 w-32">Nilai & Periode</th>
                            <th class="p-3 min-w-[140px]">Badge & Tautan</th>
                            <th class="p-3 text-center w-24">Status</th>
                            <th class="p-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($indikators as $ind)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="p-3 text-center font-bold text-slate-400">{{ $ind->urutan }}</td>
                            <td class="p-3">
                                <div class="font-bold text-slate-900 text-xs">{{ $ind->judul }}</div>
                                @if($ind->sub_judul)
                                <div class="text-[11px] text-blue-600 font-medium">{{ $ind->sub_judul }}</div>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="font-extrabold text-blue-700 text-sm">{{ $ind->nilai }}</div>
                                <div class="text-[10px] text-slate-400">{{ $ind->periode ?: '-' }}</div>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ $ind->badge_text ?: 'Tabel Dinamis' }}</span>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5 truncate max-w-[150px]">{{ $ind->link_tabel ?: '#datapokok' }}</div>
                            </td>
                            <td class="p-3 text-center">
                                @if($ind->is_active)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" 
                                        @click="indikatorEditData = { 
                                            id: '{{ $ind->id }}', 
                                            judul: '{{ addslashes($ind->judul) }}', 
                                            sub_judul: '{{ addslashes($ind->sub_judul ?? '') }}', 
                                            nilai: '{{ addslashes($ind->nilai) }}', 
                                            periode: '{{ addslashes($ind->periode ?? '') }}', 
                                            deskripsi: '{{ addslashes($ind->deskripsi ?? '') }}', 
                                            badge_text: '{{ addslashes($ind->badge_text ?? 'Tabel Dinamis') }}', 
                                            link_tabel: '{{ addslashes($ind->link_tabel ?? '#datapokok') }}', 
                                            icon: '{{ addslashes($ind->icon ?? '') }}', 
                                            urutan: {{ $ind->urutan }}, 
                                            is_active: {{ $ind->is_active ? 1 : 0 }} 
                                        }; modalIndikatorEdit = true" 
                                        title="Edit Indikator" 
                                        class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.frontend.indikator.destroy', $ind->id) }}" onsubmit="return confirm('Hapus indikator {{ addslashes($ind->judul) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Indikator" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada indikator carousel. Silakan klik tombol "+ Tambah Indikator Baru".</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- =========================================================================
         SUB MENU 7: GALERI INFOGRAFIS (DEDICATED CRUD)
    ========================================================================= --}}
    <div x-show="activeTab === 'infografis'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-image text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Kelola Galeri Infografis Beranda</h3>
                        <p class="text-xs text-slate-500">Sajian visualisasi data statistik menarik, ringkas, dan edukatif di portal publik</p>
                    </div>
                </div>
                <button type="button" @click="modalInfografisAdd = true" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-blue-500/20">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Tambah Infografis Baru</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($infografis as $info)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        @if($info->image_url)
                        <div class="h-44 relative bg-slate-900 overflow-hidden">
                            <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover">
                            @if($info->badge_text)
                            <span class="absolute top-2 right-2 bg-black/60 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">{{ $info->badge_text }}</span>
                            @endif
                        </div>
                        @else
                        <div class="h-44 bg-gradient-to-br {{ $info->bg_gradient ?: 'from-blue-600 to-indigo-700' }} text-white p-4 flex flex-col justify-between">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold tracking-wider">BPS MANGGARAI</span>
                                <span class="bg-black/20 px-2 py-0.5 rounded">{{ $info->badge_text ?: strtoupper($info->kategori) }}</span>
                            </div>
                            <div class="text-center my-auto">
                                <h4 class="font-extrabold text-xs uppercase leading-tight">{{ $info->judul }}</h4>
                                @if(!empty($info->data_points_array))
                                <div class="mt-2 bg-white/10 rounded p-1.5 text-[10px] text-left max-w-[200px] mx-auto">
                                    @foreach(array_slice($info->data_points_array, 0, 2) as $dp)
                                    <div class="flex justify-between"><span>{{ $dp['label'] ?? '' }}</span><span class="font-bold">{{ $dp['value'] ?? '' }}</span></div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="flex justify-between text-[9px] text-white/80 border-t border-white/20 pt-1">
                                <span>{{ $info->sumber ?: 'BPS Manggarai' }}</span>
                                <span>{{ $info->tahun ?: '-' }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-50 text-blue-700">{{ $info->kategori }}</span>
                                @if($info->is_active)
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">Aktif</span>
                                @else
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold text-slate-400 bg-slate-100">Nonaktif</span>
                                @endif
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 line-clamp-1">{{ $info->judul }}</h4>
                            <p class="text-[11px] text-slate-500 font-mono truncate">Link: {{ $info->link_url ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between mt-2">
                        <span class="text-[10px] text-slate-400 font-bold">Urutan: {{ $info->urutan }}</span>
                        <div class="flex items-center gap-1.5">
                            @php
                                $dpArr = $info->data_points_array;
                                $dp1Label = $dpArr[0]['label'] ?? '';
                                $dp1Val   = $dpArr[0]['value'] ?? '';
                                $dp2Label = $dpArr[1]['label'] ?? '';
                                $dp2Val   = $dpArr[1]['value'] ?? '';
                            @endphp
                            <button type="button" 
                                @click="infografisEditData = { 
                                    id: '{{ $info->id }}', 
                                    judul: '{{ addslashes($info->judul) }}', 
                                    kategori: '{{ addslashes($info->kategori) }}', 
                                    badge_text: '{{ addslashes($info->badge_text ?? '') }}', 
                                    bg_gradient: '{{ addslashes($info->bg_gradient ?? 'from-blue-600 to-indigo-700') }}', 
                                    icon: '{{ addslashes($info->icon ?? 'chart-simple') }}', 
                                    data_point_label_1: '{{ addslashes($dp1Label) }}', 
                                    data_point_val_1: '{{ addslashes($dp1Val) }}', 
                                    data_point_label_2: '{{ addslashes($dp2Label) }}', 
                                    data_point_val_2: '{{ addslashes($dp2Val) }}', 
                                    sumber: '{{ addslashes($info->sumber ?? '') }}', 
                                    tahun: '{{ addslashes($info->tahun ?? '') }}', 
                                    link_url: '{{ addslashes($info->link_url ?? '') }}', 
                                    urutan: {{ $info->urutan }}, 
                                    is_active: {{ $info->is_active ? 1 : 0 }} 
                                }; modalInfografisEdit = true" 
                                title="Edit Infografis" 
                                class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <form method="POST" action="{{ route('admin.frontend.infografis.destroy', $info->id) }}" onsubmit="return confirm('Hapus infografis {{ addslashes($info->judul) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Infografis" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    Belum ada infografis. Klik "+ Tambah Infografis Baru" untuk menambahkan infografis baru.
                </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- =========================================================================
         SUB MENU 8: QUOTES OF THE DAY (DEDICATED CRUD)
    ========================================================================= --}}
    <div x-show="activeTab === 'quotes'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-quote-left text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Kelola Quotes of the Day</h3>
                        <p class="text-xs text-slate-500">Kutipan inspiratif pimpinan BPS yang ditampilkan bergantian pada portal beranda</p>
                    </div>
                </div>
                <button type="button" @click="modalQuoteAdd = true" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-purple-500/20">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Tambah Quote Baru</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($quotes as $q)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-14 h-14 rounded-full border-2 border-purple-200 overflow-hidden bg-slate-100 shrink-0">
                                <img src="{{ $q->foto_url }}" alt="{{ $q->nama }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $q->nama }}</h4>
                                <p class="text-[11px] text-purple-700 font-bold uppercase mt-0.5">{{ $q->jabatan }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] text-slate-400 font-bold">Urutan: {{ $q->urutan }}</span>
                                    @if($q->is_active)
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">Aktif</span>
                                    @else
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold text-slate-400 bg-slate-100">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <blockquote class="text-xs text-slate-600 italic bg-slate-50 rounded-xl p-3 border border-slate-100 leading-relaxed">
                            "{{ $q->quote }}"
                        </blockquote>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5 mt-4">
                        <button type="button" 
                            @click="quoteEditData = { 
                                id: '{{ $q->id }}', 
                                nama: '{{ addslashes($q->nama) }}', 
                                jabatan: '{{ addslashes($q->jabatan ?? '') }}', 
                                quote: '{{ addslashes($q->quote) }}', 
                                urutan: {{ $q->urutan }}, 
                                is_active: {{ $q->is_active ? 1 : 0 }} 
                            }; modalQuoteEdit = true" 
                            title="Edit Quote" 
                            class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.frontend.quotes.destroy', $q->id) }}" onsubmit="return confirm('Hapus quote dari {{ addslashes($q->nama) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Quote" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    Belum ada quote terdaftar. Klik "+ Tambah Quote Baru" untuk menambahkan kutipan pimpinan.
                </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- =========================================================================
         SUB MENU 9: BERITA & KEGIATAN BPS (DEDICATED CRUD)
    ========================================================================= --}}
    <div x-show="activeTab === 'kegiatan'" class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Kelola Berita & Kegiatan Statistik</h3>
                        <p class="text-xs text-slate-500">Publikasikan dokumentasi sensus, survei, pelatihan, dan kegiatan BPS di section Kegiatan Beranda</p>
                    </div>
                </div>
                <button type="button" @click="modalKegiatanAdd = true" class="px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-teal-500/20">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Tambah Kegiatan Baru</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="p-3 text-center w-14">Urutan</th>
                            <th class="p-3 w-40">Kategori & Badge</th>
                            <th class="p-3 min-w-[220px]">Judul & Tanggal</th>
                            <th class="p-3 min-w-[240px]">Ringkasan Deskripsi</th>
                            <th class="p-3 text-center w-24">Status</th>
                            <th class="p-3 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kegiatans as $keg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="p-3 text-center font-bold text-slate-400">{{ $keg->urutan }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase">{{ $keg->kategori }}</span>
                                <div class="text-[10px] text-slate-400 mt-0.5">Warna: {{ $keg->badge_color }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-bold text-slate-900 text-xs leading-snug">{{ $keg->judul }}</div>
                                <div class="text-[10px] text-blue-600 mt-0.5">{{ $keg->tanggal_display ?: $keg->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="p-3">
                                <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $keg->deskripsi }}</p>
                                @if($keg->link_url)
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5 truncate max-w-[200px]">Link: {{ $keg->link_url }}</div>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($keg->is_active)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" 
                                        @click="kegiatanEditData = { 
                                            id: '{{ $keg->id }}', 
                                            judul: '{{ addslashes($keg->judul) }}', 
                                            kategori: '{{ addslashes($keg->kategori) }}', 
                                            tanggal_display: '{{ addslashes($keg->tanggal_display ?? '') }}', 
                                            deskripsi: '{{ addslashes($keg->deskripsi) }}', 
                                            badge_color: '{{ addslashes($keg->badge_color ?? 'blue') }}', 
                                            icon: '{{ addslashes($keg->icon ?? 'newspaper') }}', 
                                            link_url: '{{ addslashes($keg->link_url ?? '') }}', 
                                            urutan: {{ $keg->urutan }}, 
                                            is_active: {{ $keg->is_active ? 1 : 0 }} 
                                        }; modalKegiatanEdit = true" 
                                        title="Edit Kegiatan" 
                                        class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.frontend.kegiatan.destroy', $keg->id) }}" onsubmit="return confirm('Hapus kegiatan {{ addslashes($keg->judul) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Kegiatan" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada kegiatan/berita. Klik tombol "+ Tambah Kegiatan Baru" untuk menambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- =========================================================================
         MODALS UNTUK INDIKATOR CAROUSEL
    ========================================================================= --}}
    {{-- Modal Tambah Indikator --}}
    <div x-show="modalIndikatorAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalIndikatorAdd = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Indikator Carousel Baru</h3>
                <button type="button" @click="modalIndikatorAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.frontend.indikator.store') }}" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Indikator *</label>
                    <input type="text" name="judul" required placeholder="Contoh: Pertumbuhan Ekonomi" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sub Judul</label>
                    <input type="text" name="sub_judul" placeholder="Contoh: (y on y) atau SP2020" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nilai Indikator *</label>
                        <input type="text" name="nilai" required placeholder="Contoh: 5,01" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-bold text-blue-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Periode</label>
                        <input type="text" name="periode" placeholder="Contoh: Triwulan II 2026" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Badge Text</label>
                        <input type="text" name="badge_text" value="Tabel Dinamis" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Tabel / URL</label>
                        <input type="text" name="link_tabel" value="#datapokok" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon</label>
                        <select name="icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="chart-line">Chart Line 📈</option>
                            <option value="heart-pulse">Heart Pulse 💓</option>
                            <option value="venus">Venus (Perempuan) ♀</option>
                            <option value="mars">Mars (Laki-laki) ♂</option>
                            <option value="users">Users 👥</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" value="1" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="ind_active_add" value="1" checked class="w-4 h-4 rounded text-blue-600">
                    <label for="ind_active_add" class="text-xs font-medium text-slate-700">Tampilkan indikator ini di carousel beranda</label>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalIndikatorAdd = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow">Simpan Indikator</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Indikator --}}
    <div x-show="modalIndikatorEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalIndikatorEdit = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Edit Indikator Carousel</h3>
                <button type="button" @click="modalIndikatorEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" :action="`/admin/frontend/indikator/${indikatorEditData.id}/update`" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Indikator *</label>
                    <input type="text" name="judul" required x-model="indikatorEditData.judul" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sub Judul</label>
                    <input type="text" name="sub_judul" x-model="indikatorEditData.sub_judul" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nilai Indikator *</label>
                        <input type="text" name="nilai" required x-model="indikatorEditData.nilai" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-bold text-blue-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Periode</label>
                        <input type="text" name="periode" x-model="indikatorEditData.periode" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Badge Text</label>
                        <input type="text" name="badge_text" x-model="indikatorEditData.badge_text" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Tabel / URL</label>
                        <input type="text" name="link_tabel" x-model="indikatorEditData.link_tabel" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon</label>
                        <select name="icon" x-model="indikatorEditData.icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="chart-line">Chart Line 📈</option>
                            <option value="heart-pulse">Heart Pulse 💓</option>
                            <option value="venus">Venus (Perempuan) ♀</option>
                            <option value="mars">Mars (Laki-laki) ♂</option>
                            <option value="users">Users 👥</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" x-model="indikatorEditData.urutan" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" id="ind_active_edit" value="1" :checked="indikatorEditData.is_active == 1" class="w-4 h-4 rounded text-blue-600">
                    <label for="ind_active_edit" class="text-xs font-medium text-slate-700">Tampilkan indikator ini di carousel beranda</label>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalIndikatorEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow">Perbarui Indikator</button>
                </div>
            </form>
        </div>
    </div>


    {{-- =========================================================================
         MODALS UNTUK GALERI INFOGRAFIS
    ========================================================================= --}}
    {{-- Modal Tambah Infografis --}}
    <div x-show="modalInfografisAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalInfografisAdd = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Infografis Baru</h3>
                <button type="button" @click="modalInfografisAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.frontend.infografis.store') }}" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Infografis *</label>
                    <input type="text" name="judul" required placeholder="Contoh: Transportasi Darat Manggarai" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Filter *</label>
                        <select name="kategori" required class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="transportasi">Transportasi</option>
                            <option value="ekonomi">Ekonomi & PDRB</option>
                            <option value="pertanian">Pertanian ST2023</option>
                            <option value="sosial">Sosial & Pendidikan</option>
                            <option value="demografi">Demografi & Gender</option>
                            <option value="pariwisata">Pariwisata</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Badge Text</label>
                        <input type="text" name="badge_text" placeholder="Contoh: STATISTIK TRANSPORTASI" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-[11px] font-extrabold uppercase text-slate-700 block">Tampilan Visual Kartu (CSS Gradient)</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Warna Latar Gradient</label>
                            <select name="bg_gradient" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                                <option value="from-blue-600 to-indigo-700">Blue to Indigo (Biru)</option>
                                <option value="from-amber-500 to-amber-600">Amber to Orange (Kuning)</option>
                                <option value="from-teal-600 to-cyan-700">Teal to Cyan (Toska)</option>
                                <option value="from-emerald-600 to-green-700">Emerald to Green (Hijau)</option>
                                <option value="from-rose-600 to-red-700">Rose to Red (Merah)</option>
                                <option value="from-indigo-600 to-blue-700">Indigo to Blue (Ungu/Biru)</option>
                                <option value="from-orange-500 to-amber-700">Orange to Amber (Cokelat)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Icon Kartu</label>
                            <select name="icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                                <option value="truck">Truk / Transportasi 🚚</option>
                                <option value="chart-pie">Pie Chart 🥧</option>
                                <option value="tree">Pertanian / Pohon 🌲</option>
                                <option value="graduation-cap">Topi Sarjana 🎓</option>
                                <option value="users">Penduduk / Warga 👥</option>
                                <option value="hotel">Hotel / Wisata 🏨</option>
                                <option value="chart-simple">Grafik Statistik 📊</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Atau Upload Gambar Poster Infografis (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-0.5">Jika diupload, kartu akan menampilkan poster gambar ini.</p>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <span class="text-[11px] font-extrabold uppercase text-slate-700 block">Titik Data Sorotan (Highlight Data Points)</span>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="data_point_label_1" placeholder="Label 1 (contoh: Sepeda Motor)" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200">
                        <input type="text" name="data_point_val_1" placeholder="Nilai 1 (contoh: 78,4%)" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="data_point_label_2" placeholder="Label 2 (contoh: Mobil Penumpang)" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200">
                        <input type="text" name="data_point_val_2" placeholder="Nilai 2 (contoh: 14,2%)" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sumber Data</label>
                        <input type="text" name="sumber" placeholder="Contoh: Kepolisian & Dishub" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun / Keterangan</label>
                        <input type="text" name="tahun" placeholder="Contoh: Tahun 2024" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link URL Lengkap</label>
                        <input type="text" name="link_url" value="https://manggaraikab.bps.go.id" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" value="1" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="info_active_add" value="1" checked class="w-4 h-4 rounded text-blue-600">
                    <label for="info_active_add" class="text-xs font-medium text-slate-700">Tampilkan infografis ini di galeri beranda</label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalInfografisAdd = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow">Simpan Infografis</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Infografis --}}
    <div x-show="modalInfografisEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalInfografisEdit = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Edit Infografis</h3>
                <button type="button" @click="modalInfografisEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" :action="`/admin/frontend/infografis/${infografisEditData.id}/update`" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Infografis *</label>
                    <input type="text" name="judul" required x-model="infografisEditData.judul" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Filter *</label>
                        <select name="kategori" required x-model="infografisEditData.kategori" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="transportasi">Transportasi</option>
                            <option value="ekonomi">Ekonomi & PDRB</option>
                            <option value="pertanian">Pertanian ST2023</option>
                            <option value="sosial">Sosial & Pendidikan</option>
                            <option value="demografi">Demografi & Gender</option>
                            <option value="pariwisata">Pariwisata</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Badge Text</label>
                        <input type="text" name="badge_text" x-model="infografisEditData.badge_text" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-[11px] font-extrabold uppercase text-slate-700 block">Tampilan Visual Kartu (CSS Gradient)</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Warna Latar Gradient</label>
                            <select name="bg_gradient" x-model="infografisEditData.bg_gradient" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                                <option value="from-blue-600 to-indigo-700">Blue to Indigo (Biru)</option>
                                <option value="from-amber-500 to-amber-600">Amber to Orange (Kuning)</option>
                                <option value="from-teal-600 to-cyan-700">Teal to Cyan (Toska)</option>
                                <option value="from-emerald-600 to-green-700">Emerald to Green (Hijau)</option>
                                <option value="from-rose-600 to-red-700">Rose to Red (Merah)</option>
                                <option value="from-indigo-600 to-blue-700">Indigo to Blue (Ungu/Biru)</option>
                                <option value="from-orange-500 to-amber-700">Orange to Amber (Cokelat)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Icon Kartu</label>
                            <select name="icon" x-model="infografisEditData.icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                                <option value="truck">Truk / Transportasi 🚚</option>
                                <option value="chart-pie">Pie Chart 🥧</option>
                                <option value="tree">Pertanian / Pohon 🌲</option>
                                <option value="graduation-cap">Topi Sarjana 🎓</option>
                                <option value="users">Penduduk / Warga 👥</option>
                                <option value="hotel">Hotel / Wisata 🏨</option>
                                <option value="chart-simple">Grafik Statistik 📊</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Ganti Gambar Poster (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 cursor-pointer">
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <span class="text-[11px] font-extrabold uppercase text-slate-700 block">Titik Data Sorotan (Highlight Data Points)</span>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="data_point_label_1" x-model="infografisEditData.data_point_label_1" placeholder="Label 1" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200">
                        <input type="text" name="data_point_val_1" x-model="infografisEditData.data_point_val_1" placeholder="Nilai 1" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="data_point_label_2" x-model="infografisEditData.data_point_label_2" placeholder="Label 2" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200">
                        <input type="text" name="data_point_val_2" x-model="infografisEditData.data_point_val_2" placeholder="Nilai 2" class="text-xs px-3 py-1.5 rounded-xl border border-slate-200 font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sumber Data</label>
                        <input type="text" name="sumber" x-model="infografisEditData.sumber" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun / Keterangan</label>
                        <input type="text" name="tahun" x-model="infografisEditData.tahun" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link URL Lengkap</label>
                        <input type="text" name="link_url" x-model="infografisEditData.link_url" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" x-model="infografisEditData.urutan" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="info_active_edit" value="1" :checked="infografisEditData.is_active == 1" class="w-4 h-4 rounded text-blue-600">
                    <label for="info_active_edit" class="text-xs font-medium text-slate-700">Tampilkan infografis ini di galeri beranda</label>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalInfografisEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white shadow">Perbarui Infografis</button>
                </div>
            </form>
        </div>
    </div>


    {{-- =========================================================================
         MODALS UNTUK QUOTES OF THE DAY
    ========================================================================= --}}
    {{-- Modal Tambah Quote --}}
    <div x-show="modalQuoteAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalQuoteAdd = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Quotes Baru</h3>
                <button type="button" @click="modalQuoteAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.frontend.quotes.store') }}" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Tokoh / Pimpinan *</label>
                    <input type="text" name="nama" required placeholder="Contoh: Dr. Ir. Habibie" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" required placeholder="Contoh: KEPALA BPS KABUPATEN MANGGARAI" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Isi Kutipan (Quote) *</label>
                    <textarea name="quote" rows="3" required placeholder="Data yang akurat dan berkualitas adalah fondasi..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 italic leading-relaxed"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload Foto Avatar (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 cursor-pointer">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" value="1" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" name="is_active" id="q_active_add" value="1" checked class="w-4 h-4 rounded text-purple-600">
                        <label for="q_active_add" class="text-xs font-medium text-slate-700">Tampilkan di Beranda</label>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalQuoteAdd = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-xs font-bold text-white shadow">Simpan Quote</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Quote --}}
    <div x-show="modalQuoteEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalQuoteEdit = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Edit Quotes</h3>
                <button type="button" @click="modalQuoteEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" :action="`/admin/frontend/quotes/${quoteEditData.id}/update`" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Tokoh / Pimpinan *</label>
                    <input type="text" name="nama" required x-model="quoteEditData.nama" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan *</label>
                    <input type="text" name="jabatan" required x-model="quoteEditData.jabatan" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Isi Kutipan (Quote) *</label>
                    <textarea name="quote" rows="3" required x-model="quoteEditData.quote" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 italic leading-relaxed"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Foto Avatar (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 cursor-pointer">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan Tampil</label>
                        <input type="number" name="urutan" x-model="quoteEditData.urutan" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" name="is_active" id="q_active_edit" value="1" :checked="quoteEditData.is_active == 1" class="w-4 h-4 rounded text-purple-600">
                        <label for="q_active_edit" class="text-xs font-medium text-slate-700">Tampilkan di Beranda</label>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalQuoteEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-xs font-bold text-white shadow">Perbarui Quote</button>
                </div>
            </form>
        </div>
    </div>


    {{-- =========================================================================
         MODALS UNTUK BERITA & KEGIATAN BPS
    ========================================================================= --}}
    {{-- Modal Tambah Kegiatan --}}
    <div x-show="modalKegiatanAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalKegiatanAdd = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Kegiatan / Berita Baru</h3>
                <button type="button" @click="modalKegiatanAdd = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.frontend.kegiatan.store') }}" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Kegiatan *</label>
                    <input type="text" name="judul" required placeholder="Contoh: Pelaksanaan Survei Susenas Maret 2024" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori / Label *</label>
                        <input type="text" name="kategori" required placeholder="Contoh: Survei Sosial Ekonomi" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" name="tanggal_display" placeholder="Contoh: 15 Maret 2024" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Badge</label>
                        <select name="badge_color" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="blue">Blue (Biru)</option>
                            <option value="purple">Purple (Ungu)</option>
                            <option value="teal">Teal (Toska)</option>
                            <option value="orange">Orange (Oranye)</option>
                            <option value="emerald">Emerald (Hijau)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon</label>
                        <select name="icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="users">Users 👥</option>
                            <option value="book">Buku / Pelatihan 📖</option>
                            <option value="comments">FGD / Diskusi 💬</option>
                            <option value="check-circle">Diseminasi / Ceklis ✔️</option>
                            <option value="bolt">Petir / Kinerja ⚡</option>
                            <option value="newspaper">Koran / Berita 📰</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ringkasan / Ulasan Kegiatan *</label>
                    <textarea name="deskripsi" rows="3" required placeholder="Petugas pencacah lapangan BPS Kabupaten Manggarai melakukan pendataan langsung..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 leading-relaxed"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Tautan Selengkapnya</label>
                        <input type="text" name="link_url" placeholder="https://manggaraikab.bps.go.id" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" value="1" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="keg_active_add" value="1" checked class="w-4 h-4 rounded text-teal-600">
                    <label for="keg_active_add" class="text-xs font-medium text-slate-700">Tampilkan kegiatan ini di beranda</label>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalKegiatanAdd = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-xs font-bold text-white shadow">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kegiatan --}}
    <div x-show="modalKegiatanEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.outside="modalKegiatanEdit = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Edit Berita & Kegiatan</h3>
                <button type="button" @click="modalKegiatanEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <form method="POST" :action="`/admin/frontend/kegiatan/${kegiatanEditData.id}/update`" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Kegiatan *</label>
                    <input type="text" name="judul" required x-model="kegiatanEditData.judul" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-semibold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori / Label *</label>
                        <input type="text" name="kategori" required x-model="kegiatanEditData.kategori" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" name="tanggal_display" x-model="kegiatanEditData.tanggal_display" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Badge</label>
                        <select name="badge_color" x-model="kegiatanEditData.badge_color" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="blue">Blue (Biru)</option>
                            <option value="purple">Purple (Ungu)</option>
                            <option value="teal">Teal (Toska)</option>
                            <option value="orange">Orange (Oranye)</option>
                            <option value="emerald">Emerald (Hijau)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon</label>
                        <select name="icon" x-model="kegiatanEditData.icon" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 bg-white">
                            <option value="users">Users 👥</option>
                            <option value="book">Buku / Pelatihan 📖</option>
                            <option value="comments">FGD / Diskusi 💬</option>
                            <option value="check-circle">Diseminasi / Ceklis ✔️</option>
                            <option value="bolt">Petir / Kinerja ⚡</option>
                            <option value="newspaper">Koran / Berita 📰</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ringkasan / Ulasan Kegiatan *</label>
                    <textarea name="deskripsi" rows="3" required x-model="kegiatanEditData.deskripsi" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 leading-relaxed"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Link Tautan Selengkapnya</label>
                        <input type="text" name="link_url" x-model="kegiatanEditData.link_url" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Urutan</label>
                        <input type="number" name="urutan" x-model="kegiatanEditData.urutan" min="0" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="keg_active_edit" value="1" :checked="kegiatanEditData.is_active == 1" class="w-4 h-4 rounded text-teal-600">
                    <label for="keg_active_edit" class="text-xs font-medium text-slate-700">Tampilkan kegiatan ini di beranda</label>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="modalKegiatanEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-xs font-bold text-white shadow">Perbarui Kegiatan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Sync color picker ↔ text input
    function syncColor(name, value) {
        const picker = document.getElementById('color-' + name);
        const text   = document.getElementById('colortext-' + name);
        if (picker) picker.value = value;
        if (text)   text.value  = value;
    }

    document.querySelectorAll('input[type="color"]').forEach(picker => {
        picker.addEventListener('input', function() {
            syncColor(this.name, this.value);
        });
    });

    // Nav Menu Helper Functions
    function reindexNavRows() {
        const rows = document.querySelectorAll('#nav-menu-container .nav-row');
        rows.forEach((row, idx) => {
            row.setAttribute('data-index', idx);
            const chk = row.querySelector('input[type="checkbox"]');
            if (chk) chk.name = `nav_menu[${idx}][is_active]`;

            const txtLabel = row.querySelector('input[placeholder="Label Menu"]');
            if (txtLabel) txtLabel.name = `nav_menu[${idx}][label]`;

            const txtUrl = row.querySelector('input[placeholder*="#indikator"]');
            if (txtUrl) txtUrl.name = `nav_menu[${idx}][url]`;

            const selTarget = row.querySelector('select');
            if (selTarget) selTarget.name = `nav_menu[${idx}][target]`;
        });
    }

    function addNavRow() {
        const container = document.getElementById('nav-menu-container');
        const count = container.querySelectorAll('.nav-row').length;
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-slate-50/70 transition nav-row bg-indigo-50/20';
        tr.setAttribute('data-index', count);
        tr.innerHTML = `
            <td class="p-3 text-center">
                <input type="checkbox" name="nav_menu[${count}][is_active]" value="1" checked
                    class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer">
            </td>
            <td class="p-3">
                <input type="text" name="nav_menu[${count}][label]" value="" required
                    placeholder="Label Menu"
                    class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="p-3">
                <input type="text" name="nav_menu[${count}][url]" value="#" required
                    placeholder="#indikator / https://..."
                    class="w-full text-xs font-mono px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </td>
            <td class="p-3">
                <select name="nav_menu[${count}][target]"
                    class="w-full text-xs px-2.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="_self" selected>Tab Sama</option>
                    <option value="_blank">Tab Baru ↗</option>
                </select>
            </td>
            <td class="p-3 text-center">
                <div class="flex items-center justify-center gap-1">
                    <button type="button" onclick="moveNavRow(this, -1)" title="Pindah ke atas" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <i class="fa-solid fa-chevron-up text-[10px]"></i>
                    </button>
                    <button type="button" onclick="moveNavRow(this, 1)" title="Pindah ke bawah" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </button>
                </div>
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="deleteNavRow(this)" title="Hapus menu ini" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
        reindexNavRows();
        tr.querySelector('input[placeholder="Label Menu"]').focus();
    }

    function deleteNavRow(btn) {
        const row = btn.closest('tr');
        if (!row) return;
        const total = document.querySelectorAll('#nav-menu-container .nav-row').length;
        if (total <= 1) {
            alert('Minimal harus tersisa 1 baris menu navigasi.');
            return;
        }
        row.remove();
        reindexNavRows();
    }

    function moveNavRow(btn, direction) {
        const row = btn.closest('tr');
        if (!row) return;
        if (direction === -1 && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
            reindexNavRows();
        } else if (direction === 1 && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
            reindexNavRows();
        }
    }
</script>
@endpush
