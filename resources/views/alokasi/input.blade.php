@extends('layouts.app')

@section('title', 'Input Alokasi Pekerjaan')
@section('page_title', 'Input Alokasi Pekerjaan')

@section('content')
<div class="space-y-6" x-data="{
    pilihMetodeModal: false,
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    importModal: false,
    deleteId: null,
    deleteModal: false
}">


    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-5 rounded-2xl shadow-glass border border-slate-200/60">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800">Manajemen Alokasi Pekerjaan</h2>
                <p class="text-[11px] text-slate-500">Kelola daftar pekerjaan untuk anggota tim Anda.</p>
            </div>
        </div>
        {{-- Tombol buka modal pilih metode --}}
        <button @click="pilihMetodeModal = true"
                class="px-5 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-xl shadow-md shadow-bps-blue/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Alokasi Pekerjaan
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-200/60">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari kegiatan..." oninput="filterTable()"
                   class="pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue outline-none w-64">
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600" id="alokasiTable">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] uppercase font-extrabold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-4 py-4 w-12 text-center">No</th>
                        <th class="px-4 py-4 min-w-[200px]">Nama Kegiatan</th>
                        <th class="px-4 py-4">Satuan &amp; Target</th>
                        <th class="px-4 py-4">Pegawai Ditugaskan</th>
                        <th class="px-4 py-4">Waktu (Estimasi)</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alokasi as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition-colors table-row" data-kegiatan="{{ strtolower($item->nama_kegiatan) }}">
                        <td class="px-4 py-4 text-center font-medium">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <p class="font-bold text-slate-800 text-xs mb-0.5">{{ $item->nama_kegiatan }}</p>
                            <p class="text-[10px] text-slate-400">{{ $item->asal_tim }}</p>
                            @if($item->keterangan)
                            <p class="text-[10px] text-slate-400 mt-1 line-clamp-1 italic"><i class="fa-regular fa-comment-dots mr-1"></i>{{ $item->keterangan }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-1.5 font-bold text-bps-blue">
                                <span>{{ $item->target_kuantitas }}</span>
                                <span class="text-[10px] font-semibold text-slate-500">{{ $item->satuan }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <img src="{{ $item->pegawai->avatar_url }}" alt="{{ $item->pegawai->name }}" class="w-6 h-6 rounded-full object-cover">
                                <span class="font-semibold text-slate-700">{{ $item->pegawai->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-semibold text-slate-700"><i class="fa-regular fa-clock text-slate-400 mr-1"></i>{{ $item->estimasi_waktu }} Jam</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M') : '-' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d M Y') : '-' }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('alokasi.edit', $item->id) }}"
                                   class="w-7 h-7 rounded-lg bg-bps-faint hover:bg-bps-blue hover:text-white text-bps-blue flex items-center justify-center transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-[11px]"></i>
                                </a>
                                <button @click="deleteModal = true; deleteId = {{ $item->id }}"
                                    class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                                <p>Belum ada data alokasi pekerjaan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200/80 bg-slate-50 flex items-center justify-between text-[11px] text-slate-500">
            <span>Menampilkan {{ $alokasi->count() }} data</span>
        </div>
    </div>

    {{-- ==================== MODAL: PILIH METODE TAMBAH ==================== --}}
    <div x-show="pilihMetodeModal" x-cloak style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="pilihMetodeModal = false"
             x-show="pilihMetodeModal" x-transition.duration.300ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-bps-blue/5 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Tambah Alokasi Pekerjaan</h3>
                        <p class="text-[10px] text-slate-500">Pilih metode penugasan</p>
                    </div>
                </div>
                <button @click="pilihMetodeModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-3">
                {{-- Opsi 1: Satu Anggota --}}
                <button @click="pilihMetodeModal = false; modalOpen = true"
                        class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-bps-blue hover:bg-bps-faint/50 transition-all duration-200 text-left group">
                    <div class="w-12 h-12 rounded-xl bg-bps-faint group-hover:bg-bps-blue/10 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-user-plus text-bps-blue text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm">Satu Anggota Tim</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Alokasikan pekerjaan untuk satu anggota melalui formulir web</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-bps-blue ml-auto transition-colors"></i>
                </button>

                {{-- Opsi 2: Banyak Anggota via Excel --}}
                <button @click="pilihMetodeModal = false; importModal = true"
                        class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all duration-200 text-left group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-file-excel text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm">Banyak Anggota Tim (Excel)</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Unduh template, isi data, lalu unggah untuk input massal sekaligus</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-emerald-500 ml-auto transition-colors"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: TAMBAH SATU ALOKASI ==================== --}}
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalOpen = false" x-show="modalOpen" x-transition.duration.300ms class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Tambah Alokasi Pekerjaan — Satu Anggota</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="POST" action="{{ route('alokasi.store') }}" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-sm text-slate-700">

                    @if($errors->any())
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs font-semibold">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1">Satuan <span class="text-rose-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="Contoh: Dokumen, Laporan" required class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Target Kuantitas <span class="text-rose-500">*</span></label>
                            <input type="number" name="target_kuantitas" value="{{ old('target_kuantitas') }}" required min="1" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1">Asal Tim Penugasan <span class="text-rose-500">*</span></label>
                            <input type="text" name="asal_tim" value="{{ old('asal_tim', $timSaya->nama_tim ?? '') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Pegawai yang Ditugaskan <span class="text-rose-500">*</span></label>
                            {{-- Searchable Dropdown --}}
                            <div x-data="pegawaiDropdownInput()" @click.outside="open = false" class="relative">
                                <input type="hidden" name="pegawai_id" :value="selectedId">
                                <button type="button" @click="open = !open"
                                        class="w-full px-3 py-2 border rounded-lg text-left text-xs flex items-center justify-between gap-2 bg-white transition-all"
                                        :class="open ? 'border-bps-blue ring-2 ring-bps-blue/20' : 'border-slate-200 hover:border-slate-300'">
                                    <span :class="selectedName ? 'text-slate-800 font-semibold' : 'text-slate-400'"
                                          x-text="selectedName || '-- Ketik atau pilih pegawai --'"></span>
                                    <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                    <div class="p-2 border-b border-slate-100">
                                        <div class="relative">
                                            <i class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                                            <input type="text" x-model="search" @keydown.escape="open = false"
                                                   placeholder="Cari nama pegawai..."
                                                   class="w-full pl-7 pr-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-bps-blue focus:ring-1 focus:ring-bps-blue/30"
                                                   @click.stop x-ref="searchInput">
                                        </div>
                                    </div>
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

                    <div>
                        <label class="block text-xs font-bold mb-1">Catatan/Keterangan Pekerjaan</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan instruksi atau spesifikasi pekerjaan..." class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1">Estimasi Waktu (Jam) <span class="text-rose-500">*</span></label>
                            <input type="number" step="any" name="estimasi_waktu" value="{{ old('estimasi_waktu', 10) }}" required min="0.5" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                            <p class="text-[10px] text-slate-400 mt-0.5">Satuan JAM kerja</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:border-bps-blue">
                        </div>
                    </div>

                </div>
                <div class="p-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bps-blue rounded-xl text-xs font-bold text-white shadow-md hover:bg-bps-dark">Simpan Alokasi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL: IMPORT EXCEL (BANYAK ANGGOTA) ==================== --}}
    <div x-show="importModal" x-cloak style="display:none;" class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="importModal = false" x-show="importModal" x-transition.duration.300ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-file-excel text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Import Alokasi via Excel</h3>
                        <p class="text-[10px] text-slate-500">Untuk banyak anggota tim sekaligus</p>
                    </div>
                </div>
                <button @click="importModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                {{-- Langkah 1: Unduh Template --}}
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-bps-blue text-white flex items-center justify-center flex-shrink-0 text-xs font-bold">1</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm mb-1">Unduh Template Excel</p>
                        <p class="text-[11px] text-slate-500 mb-3">Unduh template terlebih dahulu, isi data sesuai kolom yang tersedia. Kolom No, Nama Kegiatan, Satuan, Target, Asal Tim, Pegawai, Estimasi Waktu, Tanggal Mulai, Tanggal Selesai.</p>
                        <a href="{{ route('alokasi.template') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all">
                            <i class="fa-solid fa-download"></i> Unduh Template
                        </a>
                    </div>
                </div>

                {{-- Langkah 2: Upload Excel --}}
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-bps-blue text-white flex items-center justify-center flex-shrink-0 text-xs font-bold">2</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm mb-1">Unggah File Excel yang Sudah Diisi</p>
                        <form method="POST" action="{{ route('alokasi.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1.5">Pilih File (.xlsx / .xls, maks. 5MB)</label>
                                <input type="file" name="file_excel" accept=".xlsx,.xls" required
                                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-blue hover:file:text-white file:transition-colors cursor-pointer border border-slate-200 rounded-lg py-1.5 bg-white">
                                @error('file_excel')
                                <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-[11px] text-amber-700">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                <strong>Perhatian:</strong> Nama pegawai di Excel harus sama persis dengan nama pegawai yang terdaftar di sistem.
                            </div>
                            <div class="flex justify-end gap-3 pt-1">
                                <button type="button" @click="importModal = false"
                                        class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                                <button type="submit"
                                        class="px-4 py-2 bg-bps-blue hover:bg-bps-dark rounded-xl text-xs font-bold text-white shadow-md transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-upload"></i> Proses Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: KONFIRMASI HAPUS ==================== --}}
    <div x-show="deleteModal" style="display: none;" class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModal = false" x-show="deleteModal" x-transition.duration.200ms class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
                </div>
                <h3 class="font-extrabold text-slate-800 text-base mb-1">Hapus Alokasi?</h3>
                <p class="text-xs text-slate-500">Data alokasi pekerjaan ini akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-center">
                <button @click="deleteModal = false" class="px-5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <form :action="'/alokasi/' + deleteId" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        <i class="fa-solid fa-trash mr-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
// Data pegawai dari server — diisolasi di script tag agar tidak konflik kutip di x-data
const _pegawaisInputData = {!! json_encode($pegawais->map(function($p) {
    $authId = auth()->id();
    $label = $p->id === $authId
        ? '(Diri Sendiri)'
        : '(' . $p->role_display . ($p->tim ? ' — ' . $p->tim->nama_tim : '') . ')';
    return ['id' => $p->id, 'name' => $p->name, 'label' => $label];
})->values()) !!};

const _selectedInputId = {{ old('pegawai_id') ? (int) old('pegawai_id') : 'null' }};

function pegawaiDropdownInput() {
    return {
        open: false,
        search: '',
        selectedId: _selectedInputId,
        selectedName: '',
        pegawais: _pegawaisInputData,
        get filtered() {
            if (!this.search) return this.pegawais;
            const q = this.search.toLowerCase();
            return this.pegawais.filter(p =>
                p.name.toLowerCase().includes(q) || p.label.toLowerCase().includes(q)
            );
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
    };
}

function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#alokasiTable .table-row').forEach(row => {
        const kegiatan = row.dataset.kegiatan || '';
        row.style.display = kegiatan.includes(search) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
