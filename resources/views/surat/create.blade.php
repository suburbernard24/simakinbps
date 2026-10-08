@extends('layouts.app')

@section('title', 'Buat Surat Baru - e-Surat BPS')
@section('page_title', 'Buat Naskah Dinas / Surat Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    jenisId: '{{ $selectedJenisId ?? ($jenisSurats->first()->id ?? '') }}',
    jenisKode: '{{ $selectedJenisId ? ($jenisSurats->firstWhere('id', $selectedJenisId)?->kode ?? 'ST') : ($jenisSurats->first()->kode ?? 'ST') }}',
    sumberSurat: '{{ old('sumber_surat', 'sistem') }}',
    penomoranMode: '{{ old('penomoran_mode', 'otomatis') }}',
    fileUploadedName: '',
    pegawais: [
        { id: '', peran: 'Ketua Tim / Koordinator', ket: '' }
    ],
    addPegawai() {
        this.pegawais.push({ id: '', peran: 'Anggota', ket: '' });
    },
    removePegawai(index) {
        if (this.pegawais.length > 1) {
            this.pegawais.splice(index, 1);
        }
    },
    updateJenis(id, kode) {
        this.jenisId = id;
        this.jenisKode = kode;
    }
}">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('surat.index') }}" class="text-xs font-semibold text-bps-blue hover:underline inline-flex items-center gap-1.5 mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Surat
            </a>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-pen-nib text-bps-blue"></i>
                <span>Formulir Pengajuan & Registrasi Surat</span>
            </h2>
            <p class="text-xs text-slate-500">Pilih pembuatan naskah langsung di sistem atau upload berkas surat yang dibuat di luar sistem.</p>
        </div>
    </div>

    <!-- METODE PEMBUATAN SURAT: SISTEM vs UPLOAD LUAR -->
    <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
            <i class="fa-solid fa-layer-group text-bps-blue mr-1"></i> 1. Pilih Metode Pembuatan Naskah 
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Mode 1: Buat di Sistem -->
            <label @click="sumberSurat = 'sistem'"
                   class="relative flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all"
                   :class="sumberSurat === 'sistem' ? 'border-bps-blue bg-blue-50/40 shadow-sm ring-2 ring-bps-blue/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                     :class="sumberSurat === 'sistem' ? 'bg-bps-blue text-white shadow-md shadow-bps-blue/20' : 'bg-slate-100 text-slate-500'">
                    <i class="fa-solid fa-file-pen text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wide text-slate-800">Buat di Dalam Sistem</span>
                        <input type="radio" name="sumber_surat_radio" value="sistem" x-model="sumberSurat" class="text-bps-blue focus:ring-bps-blue">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        Ketik dan susun surat menggunakan form & template dinas BPS. Format, kop surat, dan tata letak dokumen di-generate otomatis oleh sistem.
                    </p>
                </div>
            </label>

            <!-- Mode 2: Upload dari Luar -->
            <label @click="sumberSurat = 'luar'"
                   class="relative flex items-start gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all"
                   :class="sumberSurat === 'luar' ? 'border-indigo-600 bg-indigo-50/40 shadow-sm ring-2 ring-indigo-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                     :class="sumberSurat === 'luar' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'bg-slate-100 text-slate-500'">
                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wide text-slate-800">Upload Surat dari Luar</span>
                        <input type="radio" name="sumber_surat_radio" value="luar" x-model="sumberSurat" class="text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                        Surat sudah dibuat atau diketik di luar (Microsoft Word / PDF scan jadi). Cukup unggah berkas surat dan daftarkan nomor resminya.
                    </p>
                </div>
            </label>
        </div>
    </div>

    <!-- Jenis Surat Selector Cards (PRD 8) -->
    <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white">
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">2. Pilih Jenis Surat</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($jenisSurats as $js)
            <button type="button" 
                    @click="updateJenis('{{ $js->id }}', '{{ $js->kode }}')"
                    class="p-3 rounded-xl border text-left transition-all flex flex-col justify-between"
                    :class="jenisId == '{{ $js->id }}' ? 'border-bps-blue bg-bps-faint text-bps-blue ring-2 ring-bps-blue/20 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-700'">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded"
                          :class="jenisId == '{{ $js->id }}' ? 'bg-bps-blue text-white' : 'bg-slate-100 text-slate-600'">
                        {{ $js->kode }}
                    </span>
                    <i class="fa-solid fa-circle-check text-xs" :class="jenisId == '{{ $js->id }}' ? 'text-bps-blue' : 'text-slate-200'"></i>
                </div>
                <p class="text-xs font-bold leading-tight">{{ $js->nama }}</p>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="sumber_surat" :value="sumberSurat">
        <input type="hidden" name="jenis_surat_id" :value="jenisId">

        <!-- SECTION KHUSUS: UPLOAD BERKAS NASKAH SURAT JIKA SUMBER LUAR -->
        <div x-show="sumberSurat === 'luar'" x-transition class="glass-panel p-6 rounded-2xl border-2 border-indigo-200 bg-indigo-50/20 space-y-4">
            <div class="flex items-center gap-2 border-b border-indigo-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-file-arrow-up"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Unggah Berkas Naskah Surat (PDF / Word / Excel / Gambar) <span class="text-red-500">*</span></h3>
                    <p class="text-[11px] text-slate-500">Unggah file dokumen surat yang telah dibuat di luar sistem (Format: PDF, Word, Excel .xlsx/.xls/.csv, scan surat resmi maks 20MB).</p>
                </div>
            </div>

            <div class="border-2 border-dashed border-indigo-300 rounded-2xl p-6 text-center hover:border-indigo-500 transition bg-white/90">
                <div class="flex items-center justify-center gap-2 mb-2 text-2xl">
                    <i class="fa-solid fa-file-pdf text-rose-500"></i>
                    <i class="fa-solid fa-file-word text-blue-600"></i>
                    <i class="fa-solid fa-file-excel text-emerald-600"></i>
                </div>
                <p class="text-xs font-bold text-slate-800" x-text="fileUploadedName ? 'File terpilih: ' + fileUploadedName : 'Pilih Berkas Naskah Dokumen'"></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Mendukung format PDF, Microsoft Word (.doc, .docx), Microsoft Excel (.xlsx, .xls, .csv), atau gambar naskah</p>
                <input type="file" name="file_dokumen_surat" accept=".pdf,.doc,.docx,.xlsx,.xls,.csv,.jpg,.jpeg,.png"
                       :required="sumberSurat === 'luar'"
                       @change="fileUploadedName = $event.target.files[0]?.name || ''"
                       class="mt-3 text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
            </div>

            <!-- Opsi Penomoran Dokumen Luar -->
            <div class="bg-white p-4 rounded-xl border border-indigo-100 space-y-3">
                <label class="block text-xs font-bold text-slate-700">Opsi Penomoran Dokumen:</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label @click="penomoranMode = 'otomatis'" class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition text-xs font-medium"
                           :class="penomoranMode === 'otomatis' ? 'border-bps-blue bg-blue-50/50 text-bps-blue font-bold ring-1 ring-bps-blue' : 'border-slate-200 text-slate-600'">
                        <input type="radio" name="penomoran_mode" value="otomatis" x-model="penomoranMode" class="text-bps-blue">
                        <span>Generate Nomor Resmi Otomatis dari Sistem</span>
                    </label>
                    <label @click="penomoranMode = 'manual'" class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition text-xs font-medium"
                           :class="penomoranMode === 'manual' ? 'border-bps-blue bg-blue-50/50 text-bps-blue font-bold ring-1 ring-bps-blue' : 'border-slate-200 text-slate-600'">
                        <input type="radio" name="penomoran_mode" value="manual" x-model="penomoranMode" class="text-bps-blue">
                        <span>Input Nomor Surat Manual (Sudah Ada Nomor)</span>
                    </label>
                </div>
                <div x-show="penomoranMode === 'manual'" x-transition class="pt-2">
                    <label class="block text-[11px] font-bold uppercase text-slate-600 mb-1">Nomor Surat dari Luar</label>
                    <input type="text" name="nomor_surat_manual" value="{{ old('nomor_surat_manual') }}"
                           placeholder="Contoh: B-012/53131/VS.330/2026 atau nomor naskah eksternal"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>
        </div>

        <!-- Form Section: Data Pokok Surat -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">3</div>
                <h3 class="text-sm font-bold text-slate-800">Informasi Dasar Surat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Perihal -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Perihal / Hal Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="perihal" required value="{{ old('perihal') }}"
                           placeholder="Contoh: Surat Tugas Pendataan Survei Sosial Ekonomi Nasional (Susenas) 2026"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_surat" required value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                </div>

                <!-- Unit Kerja / Fungsi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja / Fungsi Pengusul</label>
                    <select name="unit_kerja_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                        <option value="">-- Pilih Unit Kerja / Fungsi --</option>
                        @foreach($unitKerjas as $uk)
                        <option value="{{ $uk->id }}" {{ old('unit_kerja_id', Auth::user()->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                            {{ $uk->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Template Surat (Hanya jika dibuat di sistem) -->
                <div x-show="sumberSurat === 'sistem'">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Gunakan Template Dokumen</label>
                    <select name="template_surat_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                        <option value="">-- Template Standar --</option>
                        @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}" {{ old('template_surat_id') == $tpl->id ? 'selected' : '' }}>
                            {{ $tpl->nama_template }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kode Klasifikasi Arsip -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Klasifikasi Arsip</label>
                    <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', 'VS.330') }}"
                           placeholder="Contoh: VS.330, KP.510, KU.100"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                    <p class="text-[10px] text-slate-400 mt-1">Sesuai Peraturan Kearsipan BPS</p>
                </div>
            </div>
        </div>

        <!-- FORM KHUSUS: SURAT TUGAS & SK (MULTIPLE PEGAWAI - PRD 9 & 11) -->
        <div x-show="jenisKode === 'ST' || jenisKode === 'SK'" x-transition class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">3</div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Pegawai yang Ditugaskan</h3>
                        <p class="text-[11px] text-slate-400">Satu surat dapat menugaskan banyak pegawai sekaligus tanpa batasan kuota.</p>
                    </div>
                </div>
                <button type="button" @click="addPegawai()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Pegawai</span>
                </button>
            </div>

            <!-- List of dynamic pegawais -->
            <div class="space-y-3">
                <template x-for="(pegawai, index) in pegawais" :key="index">
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row items-start md:items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0" x-text="index + 1"></span>
                        
                        <!-- Select Pegawai -->
                        <div class="flex-1 w-full md:w-auto">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Nama Pegawai / NIP</label>
                            <select :name="'pegawai_ids[' + index + ']'" x-model="pegawai.id" required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-bps-blue">
                                <option value="">-- Pilih Pegawai dari Master --</option>
                                @foreach($pegawais as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (NIP: {{ $p->nip ?? '-' }}) - {{ $p->jabatan ?? 'Staf' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Peran dalam Tugas -->
                        <div class="w-full md:w-60">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Peran / Jabatan Tim</label>
                            <select :name="'pegawai_perans[' + index + ']'" x-model="pegawai.peran"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-bps-blue">
                                <option value="Ketua Tim / Koordinator">Ketua Tim / Koordinator</option>
                                <option value="Petugas Pengawas">Petugas Pengawas</option>
                                <option value="Petugas Pencacah">Petugas Pencacah</option>
                                <option value="Anggota Tim">Anggota Tim</option>
                                <option value="Narasumber / Instruktur">Narasumber / Instruktur</option>
                                <option value="Sekretaris">Sekretaris</option>
                            </select>
                        </div>

                        <!-- Keterangan Tambahan -->
                        <div class="w-full md:w-60">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Wilayah / Keterangan</label>
                            <input type="text" :name="'pegawai_keterangans[' + index + ']'" x-model="pegawai.ket"
                                   placeholder="Contoh: Desa / Wilayah Tugas"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-bps-blue">
                        </div>

                        <!-- Remove Button -->
                        <div class="self-end md:self-center pt-2 md:pt-4">
                            <button type="button" @click="removePegawai(index)" 
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Pegawai">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Detail Tempat & Tanggal Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tempat Pelaksanaan Kegiatan</label>
                    <input type="text" name="tempat_kegiatan" value="{{ old('tempat_kegiatan') }}"
                           placeholder="Contoh: Kecamatan Ruteng, Kab. Manggarai"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai Tugas</label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai Tugas</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>
        </div>

        <!-- FORM KHUSUS: SURAT KELUAR & PENERIMA (PRD 13) -->
        <div x-show="jenisKode === 'B' || jenisKode === 'UND'" x-transition class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">3</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tujuan & Penerima Surat</h3>
                    <p class="text-[11px] text-slate-400">Rincian instansi atau pihak penerima naskah dinas resmi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penerima / Pejabat Tujuan</label>
                    <input type="text" name="nama_penerima" value="{{ old('nama_penerima') }}"
                           placeholder="Contoh: Kepala Badan Perencanaan Pembangunan Daerah"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Instansi / Perusahaan</label>
                    <input type="text" name="instansi_penerima" value="{{ old('instansi_penerima') }}"
                           placeholder="Contoh: Bapelitbangda Kabupaten Manggarai"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Tujuan</label>
                    <textarea name="alamat_penerima" rows="2" placeholder="Contoh: Jl. Motang Rua No. 1, Ruteng, NTT"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">{{ old('alamat_penerima') }}</textarea>
                </div>
            </div>
        </div>

        <!-- FORM KHUSUS: BERITA ACARA (PRD 14) -->
        <div x-show="jenisKode === 'BA'" x-transition class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs font-bold">3</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Pihak yang Terlibat (Berita Acara)</h3>
                    <p class="text-[11px] text-slate-400">Rincian pihak pertama dan pihak kedua yang bersepakat/melakukan pemeriksaan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-3">
                    <p class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> Pihak Pertama (BPS)
                    </p>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Pihak Pertama</label>
                        <input type="text" name="pihak_pertama" value="{{ old('pihak_pertama', Auth::user()->name) }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Jabatan Pihak Pertama</label>
                        <input type="text" name="jabatan_pihak_pertama" value="{{ old('jabatan_pihak_pertama', Auth::user()->jabatan ?? 'Pegawai BPS') }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                </div>

                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-3">
                    <p class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Pihak Kedua (Mitra / Instansi Terkait)
                    </p>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Pihak Kedua</label>
                        <input type="text" name="pihak_kedua" value="{{ old('pihak_kedua') }}"
                               placeholder="Nama Pejabat / Perwakilan Mitra"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Jabatan / Instansi Pihak Kedua</label>
                        <input type="text" name="jabatan_pihak_kedua" value="{{ old('jabatan_pihak_kedua') }}"
                               placeholder="Contoh: Direktur PT / Kepala Bagian"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM KHUSUS: KONSIDERANS SK (PRD 12) -->
        <div x-show="jenisKode === 'SK'" x-transition class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">4</div>
                <h3 class="text-sm font-bold text-slate-800">Dasar Penetapan Keputusan (Menimbang & Mengingat)</h3>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Konsiderans / Dasar Hukum</label>
                <textarea name="dasar_penetapan" rows="3" 
                          placeholder="Menimbang: a. Bahwa... b. Bahwa...&#10;Mengingat: 1. Undang-Undang No. 16 Tahun 1997 tentang Statistik..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">{{ old('dasar_penetapan') }}</textarea>
            </div>
        </div>

        <!-- Uraian Isi / Narasi Surat & Keterangan -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">4</div>
                <h3 class="text-sm font-bold text-slate-800">Uraian / Isi Substansi Dokumen</h3>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Uraian Tugas / Isi Surat / Rincian Acara 
                    <span class="text-red-500" x-show="sumberSurat === 'sistem'">*</span>
                    <span class="text-slate-400 font-normal text-[11px]" x-show="sumberSurat === 'luar'">(Opsional jika naskah surat telah diunggah)</span>
                </label>
                <textarea name="uraian_kegiatan" rows="4" :required="sumberSurat === 'sistem'"
                          placeholder="Tuliskan narasi lengkap isi surat, rincian tugas yang diberikan, atau ringkasan dokumen..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">{{ old('uraian_kegiatan') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Tambahan / Sumber Anggaran</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                       placeholder="Contoh: Beban DIPA BPS Kabupaten Manggarai Tahun Anggaran 2026"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
            </div>
        </div>

        <!-- Upload Lampiran Dokumen (PRD 10.12) -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">5</div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Lampiran Dokumen (Opsional)</h3>
                    <p class="text-[11px] text-slate-400">Unggah kerangka acuan kerja (TOR), daftar sampel, nota dinas rujukan, matriks data Excel, atau berkas pendukung (PDF, DOCX, XLSX, XLS, CSV, JPG maksimal 10MB per file).</p>
                </div>
            </div>

            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-bps-blue transition bg-slate-50/50">
                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                <p class="text-xs font-bold text-slate-700">Pilih berkas untuk dilampirkan</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Mendukung format PDF, Word, Excel (.xlsx, .xls, .csv), gambar (dapat memilih lebih dari satu file)</p>
                <input type="file" name="lampiran[]" multiple accept=".pdf,.doc,.docx,.xlsx,.xls,.csv,.jpg,.jpeg,.png" class="mt-3 text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-pale">
            </div>
        </div>

        <!-- Form Submission Buttons (Draft or Submit - PRD 10.7) -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('surat.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                Batal
            </a>
            
            <button type="submit" name="action" value="draft" 
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center gap-2">
                <i class="fa-regular fa-floppy-disk"></i>
                <span>Simpan sebagai Draft</span>
            </button>

            <button type="submit" name="action" value="ajukan" 
                    class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-extrabold text-xs shadow-lg shadow-bps-blue/30 transition-transform active:scale-95 inline-flex items-center gap-2">
                <i class="fa-solid fa-stamp"></i>
                <span>Simpan & Terbitkan Surat</span>
            </button>
        </div>

    </form>

</div>
@endsection
