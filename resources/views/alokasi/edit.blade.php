@extends('layouts.app')

@section('title', 'Edit Alokasi Pekerjaan')
@section('page_title', 'Edit Alokasi Pekerjaan')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb / Back -->
    <div class="flex items-center gap-3 bg-white p-4 rounded-2xl shadow-glass border border-slate-200/60">
        <a href="{{ route('alokasi.input') }}" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-bps-faint hover:text-bps-blue text-slate-500 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-sm font-bold text-slate-800">Edit Alokasi Pekerjaan</h2>
            <p class="text-[11px] text-slate-500">Perbarui data pekerjaan yang telah dialokasikan.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                <i class="fa-solid fa-pen-to-square text-sm"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Form Edit: <span class="text-bps-blue">{{ $item->nama_kegiatan }}</span></h3>
        </div>

        <form method="POST" action="{{ route('alokasi.update', $item->id) }}" class="p-6 space-y-5 text-sm text-slate-700">
            @csrf

            @if($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Nama Kegiatan -->
            <div>
                <label class="block text-xs font-bold mb-1.5">Nama Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $item->nama_kegiatan) }}" required
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Satuan -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', $item->satuan) }}" placeholder="Contoh: Dokumen, Laporan" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
                <!-- Target Kuantitas -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Target Kuantitas <span class="text-rose-500">*</span></label>
                    <input type="number" name="target_kuantitas" value="{{ old('target_kuantitas', $item->target_kuantitas) }}" required min="1"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Asal Tim -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Asal Tim Penugasan <span class="text-rose-500">*</span></label>
                    <input type="text" name="asal_tim" value="{{ old('asal_tim', $item->asal_tim) }}" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
                <!-- Pegawai -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Pegawai yang Ditugaskan <span class="text-rose-500">*</span></label>
                    {{-- Searchable Dropdown dengan Alpine.js --}}
                    <div x-data="{
                        open: false,
                        search: '',
                        selectedId: '{{ old('pegawai_id', $item->pegawai_id) }}',
                        selectedName: '',
                        pegawais: @json($pegawais->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'label' => $p->id === auth()->id() ? '(Diri Sendiri)' : '('.$p->role_display.($p->tim ? ' — '.$p->tim->nama_tim : '').')'])),
                        get filtered() {
                            if (!this.search) return this.pegawais;
                            const q = this.search.toLowerCase();
                            return this.pegawais.filter(p => p.name.toLowerCase().includes(q) || p.label.toLowerCase().includes(q));
                        },
                        select(p) {
                            this.selectedId = p.id;
                            this.selectedName = p.name + ' ' + p.label;
                            this.search = '';
                            this.open = false;
                        },
                        init() {
                            if (this.selectedId) {
                                const found = this.pegawais.find(p => p.id == this.selectedId);
                                if (found) this.selectedName = found.name + ' ' + found.label;
                            }
                        }
                    }" @click.outside="open = false" class="relative">
                        <input type="hidden" name="pegawai_id" :value="selectedId" required>
                        {{-- Trigger button --}}
                        <button type="button" @click="open = !open"
                                class="w-full px-3 py-2.5 border rounded-xl text-left text-xs flex items-center justify-between gap-2 bg-white transition-all"
                                :class="open ? 'border-bps-blue ring-2 ring-bps-blue/10' : 'border-slate-200 hover:border-slate-300'">
                            <span :class="selectedName ? 'text-slate-800 font-semibold' : 'text-slate-400'"
                                  x-text="selectedName || '-- Ketik atau pilih pegawai --'"></span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        {{-- Dropdown panel --}}
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                            {{-- Search input --}}
                            <div class="p-2 border-b border-slate-100">
                                <div class="relative">
                                    <i class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                    <input type="text" x-model="search" @keydown.escape="open = false"
                                           placeholder="Cari nama pegawai..."
                                           class="w-full pl-7 pr-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-bps-blue focus:ring-1 focus:ring-bps-blue/30"
                                           @click.stop>
                                </div>
                            </div>
                            {{-- Options list --}}
                            <div class="max-h-48 overflow-y-auto">
                                <template x-if="filtered.length === 0">
                                    <p class="px-3 py-3 text-xs text-slate-400 text-center">Tidak ada pegawai ditemukan.</p>
                                </template>
                                <template x-for="p in filtered" :key="p.id">
                                    <button type="button" @click="select(p)"
                                            class="w-full px-3 py-2.5 text-left text-xs flex items-center gap-2.5 hover:bg-bps-faint transition-colors"
                                            :class="selectedId == p.id ? 'bg-bps-faint text-bps-blue font-bold' : 'text-slate-700'">
                                        <span class="w-5 h-5 rounded-full bg-bps-blue/10 text-bps-blue flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-user text-[8px]"></i>
                                        </span>
                                        <span>
                                            <span x-text="p.name" class="font-semibold"></span>
                                            <span x-text="p.label" class="text-[10px] text-slate-400 ml-1"></span>
                                        </span>
                                        <i x-show="selectedId == p.id" class="fa-solid fa-check text-bps-blue ml-auto text-[10px]"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold mb-1.5">Catatan/Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">{{ old('keterangan', $item->keterangan) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Estimasi Waktu -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Estimasi Waktu (Jam) <span class="text-rose-500">*</span></label>
                    <input type="number" name="estimasi_waktu" value="{{ old('estimasi_waktu', $item->estimasi_waktu) }}" required min="1" step="0.5"
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $item->tanggal_mulai->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-xs font-bold mb-1.5">Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $item->tanggal_selesai->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 transition-all">
                </div>
            </div>


            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('alokasi.input') }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-xl shadow-md shadow-bps-blue/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
