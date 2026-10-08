@extends('layouts.app')

@section('title', 'Master Template Surat - e-Surat BPS')
@section('page_title', 'Master Template Naskah Dinas')

@section('content')
<div class="space-y-6" x-data="{
    createModal: false,
    editModal: false,
    editId: null,
    editJenisId: '',
    editKodeTpl: '',
    editNamaTpl: '',
    editIsi: '',
    editPenutup: '',
    editAktif: true,
    openEdit(item) {
        this.editId = item.id;
        this.editJenisId = item.jenis_surat_id;
        this.editKodeTpl = item.kode_template;
        this.editNamaTpl = item.nama_template;
        this.editIsi = item.isi_template;
        this.editPenutup = item.penutup || '';
        this.editAktif = item.aktif;
        this.editModal = true;
    }
}">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-file-contract text-bps-blue"></i>
                <span>Master Template Dokumen (PRD 10.6 & 15)</span>
            </h2>
            <p class="text-xs text-slate-500">Kelola draft template naskah dinas dengan variabel otomatis</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Template</span>
        </button>
    </div>

    <!-- Guide: Placeholder Variables (PRD 15) -->
    <div class="glass-panel p-4 rounded-2xl border border-bps-pale bg-bps-faint/60 text-xs">
        <p class="font-bold text-bps-dark mb-1 flex items-center gap-1.5">
            <i class="fa-solid fa-circle-info text-bps-blue"></i>
            <span>Variabel Otomatis yang Didukung dalam Template (PRD 15):</span>
        </p>
        <div class="flex flex-wrap gap-2 text-[11px] font-mono mt-2">
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{nomor_surat}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{tanggal_surat}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{nama}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{nip}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{jabatan}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{unit_kerja}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{tujuan}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{perihal}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{tempat}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{tanggal_kegiatan}</span>
            <span class="px-2 py-0.5 rounded bg-white border border-bps-pale text-bps-blue font-bold">{uraian_kegiatan}</span>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($templates as $tpl)
        <div class="glass-panel p-5 rounded-2xl border border-slate-200/80 bg-white shadow-sm space-y-3 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase bg-bps-faint text-bps-blue">
                        {{ $tpl->kode_template }} &bull; {{ $tpl->jenisSurat->nama }}
                    </span>
                    <button type="button" @click="openEdit({{ json_encode($tpl) }})" 
                            class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50" title="Edit Template">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                </div>
                <h3 class="font-bold text-sm text-slate-800 mt-2">{{ $tpl->nama_template }}</h3>
                <div class="mt-2 p-3 rounded-xl bg-slate-50 border border-slate-100 font-sans text-xs text-slate-600 max-h-36 overflow-y-auto whitespace-pre-line">
                    {{ $tpl->isi_template }}
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Digunakan: <strong>{{ $tpl->surats_count }}</strong> naskah</span>
                @if($tpl->aktif)
                <span class="text-emerald-700 font-bold flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                </span>
                @else
                <span class="text-slate-400 font-bold">Nonaktif</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- MODAL: Tambah -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl" @click.outside="createModal = false">
            <h3 class="font-bold text-sm text-slate-900">Tambah Template Baru</h3>
            <form method="POST" action="{{ route('surat.master.template.store') }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Naskah <span class="text-red-500">*</span></label>
                        <select name="jenis_surat_id" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                            @foreach($jenisSurats as $js)
                            <option value="{{ $js->id }}">{{ $js->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Template <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_template" required placeholder="Contoh: TPL-ST-02" class="w-full p-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Template <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_template" required placeholder="Contoh: Template Surat Tugas Pelatihan" class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Dokumen Template <span class="text-red-500">*</span></label>
                    <textarea name="isi_template" rows="5" required placeholder="Gunakan variabel {nomor_surat}, {tanggal_surat}, {tempat}, {uraian_kegiatan}..." class="w-full p-2.5 text-xs rounded-xl border border-slate-200 font-sans"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs rounded-xl shadow">Simpan Template</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl" @click.outside="editModal = false">
            <h3 class="font-bold text-sm text-slate-900">Edit Template Dokumen</h3>
            <form :action="'{{ url('surat/master/template') }}/' + editId" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Naskah</label>
                        <select name="jenis_surat_id" x-model="editJenisId" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                            @foreach($jenisSurats as $js)
                            <option value="{{ $js->id }}">{{ $js->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Template</label>
                        <input type="text" name="kode_template" x-model="editKodeTpl" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Template</label>
                    <input type="text" name="nama_template" x-model="editNamaTpl" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Dokumen</label>
                    <textarea name="isi_template" x-model="editIsi" rows="6" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="aktif" id="editAktifTpl" x-model="editAktif" class="rounded border-slate-300 text-bps-blue">
                    <label for="editAktifTpl" class="text-xs font-semibold text-slate-700">Aktif Digunakan</label>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs rounded-xl shadow">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
