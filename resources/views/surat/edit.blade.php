@extends('layouts.app')

@section('title', 'Edit Surat - ' . $surat->perihal)
@section('page_title', 'Edit Naskah Dinas / Surat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{
    jenisKode: '{{ $surat->jenisSurat->kode ?? 'ST' }}',
    pegawais: {{ json_encode($surat->pegawais->map(fn($p) => ['id' => $p->pegawai_id, 'peran' => $p->peran, 'ket' => $p->keterangan])->toArray() ?: [['id' => '', 'peran' => 'Anggota', 'ket' => '']]) }},
    addPegawai() {
        this.pegawais.push({ id: '', peran: 'Anggota', ket: '' });
    },
    removePegawai(index) {
        if (this.pegawais.length > 1) {
            this.pegawais.splice(index, 1);
        }
    }
}">

    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('surat.show', $surat->id) }}" class="text-xs font-semibold text-bps-blue hover:underline inline-flex items-center gap-1.5 mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Surat
            </a>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-bps-blue"></i>
                <span>Edit Naskah Dinas ({{ $surat->jenisSurat->nama }})</span>
            </h2>
        </div>
    </div>

    <!-- Catatan Revisi Alert if returned -->
    @if($surat->isDikembalikan() && $surat->catatan)
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3 shadow-sm">
        <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-900 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Catatan Perbaikan dari Pemeriksa / Pimpinan</p>
            <p class="text-xs text-amber-900 mt-1 font-medium bg-amber-100/60 p-2.5 rounded-lg border border-amber-200/80">
                "{{ $surat->catatan }}"
            </p>
            <p class="text-[11px] text-amber-700 mt-1">Harap sesuaikan data surat di bawah sesuai instruksi catatan, lalu klik <strong>Ajukan Surat Ulang</strong>.</p>
        </div>
    </div>
    @endif

    <!-- Main Edit Form -->
    <form method="POST" action="{{ route('surat.update', $surat->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">1</div>
                <h3 class="text-sm font-bold text-slate-800">Informasi Pokok Surat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Perihal / Hal Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="perihal" required value="{{ old('perihal', $surat->perihal) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_surat" required value="{{ old('tanggal_surat', $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja / Fungsi Pengusul</label>
                    <select name="unit_kerja_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                        <option value="">-- Pilih Unit Kerja / Fungsi --</option>
                        @foreach($unitKerjas as $uk)
                        <option value="{{ $uk->id }}" {{ old('unit_kerja_id', $surat->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                            {{ $uk->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Template Dokumen</label>
                    <select name="template_surat_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                        <option value="">-- Template Standar --</option>
                        @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}" {{ old('template_surat_id', $surat->template_surat_id) == $tpl->id ? 'selected' : '' }}>
                            {{ $tpl->nama_template }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Klasifikasi</label>
                    <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $surat->kode_klasifikasi) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>
        </div>

        <!-- Daftar Pegawai (Surat Tugas / SK) -->
        <div x-show="jenisKode === 'ST' || jenisKode === 'SK'" class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">2</div>
                    <h3 class="text-sm font-bold text-slate-800">Daftar Pegawai yang Ditugaskan</h3>
                </div>
                <button type="button" @click="addPegawai()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Pegawai</span>
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(pegawai, index) in pegawais" :key="index">
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row items-start md:items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0" x-text="index + 1"></span>
                        
                        <div class="flex-1 w-full md:w-auto">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Nama Pegawai</label>
                            <select :name="'pegawai_ids[' + index + ']'" x-model="pegawai.id" required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-bps-blue">
                                <option value="">-- Pilih Pegawai dari Master --</option>
                                @foreach($pegawais as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (NIP: {{ $p->nip ?? '-' }}) - {{ $p->jabatan ?? 'Staf' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full md:w-60">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Peran Tim</label>
                            <input type="text" :name="'pegawai_perans[' + index + ']'" x-model="pegawai.peran"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                        </div>

                        <div class="w-full md:w-60">
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Wilayah / Keterangan</label>
                            <input type="text" :name="'pegawai_keterangans[' + index + ']'" x-model="pegawai.ket"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                        </div>

                        <div class="self-end md:self-center pt-2 md:pt-4">
                            <button type="button" @click="removePegawai(index)" 
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Detail Pelaksanaan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tempat Kegiatan</label>
                    <input type="text" name="tempat_kegiatan" value="{{ old('tempat_kegiatan', $surat->detail->tempat_kegiatan ?? '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $surat->detail?->tanggal_kegiatan ? $surat->detail->tanggal_kegiatan->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $surat->detail?->tanggal_selesai ? $surat->detail->tanggal_selesai->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
                </div>
            </div>
        </div>

        <!-- Uraian Isi Surat -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">3</div>
                <h3 class="text-sm font-bold text-slate-800">Uraian / Narasi Dokumen</h3>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Substansi / Tugas / Rincian <span class="text-red-500">*</span></label>
                <textarea name="uraian_kegiatan" rows="4" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">{{ old('uraian_kegiatan', $surat->detail->uraian_kegiatan ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Sumber Pembebanan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $surat->detail->keterangan ?? '') }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue">
            </div>
        </div>

        <!-- Lampiran Berkas -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-200/80 bg-white space-y-4">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <div class="w-7 h-7 rounded-lg bg-bps-faint text-bps-blue flex items-center justify-center text-xs font-bold">4</div>
                <h3 class="text-sm font-bold text-slate-800">Lampiran Dokumen</h3>
            </div>

            @if($surat->lampirans->isNotEmpty())
            <div class="space-y-2 mb-3">
                <p class="text-xs font-bold text-slate-700">Lampiran yang sudah diunggah:</p>
                @foreach($surat->lampirans as $lampiran)
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 truncate text-slate-700">
                        <i class="fa-solid fa-paperclip text-bps-blue"></i>
                        {{ $lampiran->nama_file }} ({{ $lampiran->ukuran }} KB)
                    </span>
                    <a href="{{ route('surat.lampiran.download', $lampiran->id) }}" class="text-bps-blue font-semibold hover:underline">
                        Unduh
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Lampiran Baru (Opsional - PDF / Word / Excel / Gambar)</label>
                <input type="file" name="lampiran[]" multiple accept=".pdf,.doc,.docx,.xlsx,.xls,.csv,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-pale">
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('surat.show', $surat->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                Batal
            </a>
            
            <button type="submit" name="action" value="draft" 
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Simpan Perubahan
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
