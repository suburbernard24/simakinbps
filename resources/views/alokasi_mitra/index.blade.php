@extends('layouts.app')

@section('title', 'Alokasi Pekerjaan Mitra')
@section('page_title', 'Alokasi Pekerjaan Mitra')

@section('content')
<div class="space-y-6" x-data="{
    pilihMetodeModal: false,
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    importModal: false,
    deleteId: null,
    deleteModal: false,
    deleteNama: '',
    selectedMitraId: '',
    honorInput: '',
    formatRupiah(val) {
        let clean = (val + '').replace(/\D/g, '');
        if (!clean) return '';
        return new Intl.NumberFormat('id-ID').format(clean);
    }
}">

    {{-- Action & Info Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">Alokasi Pekerjaan Mitra Statistik</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola penugasan survei dan honor bulanan mitra statistik tim Anda.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-xs">
                <span class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-bold block">Batas Maksimal Honor:</span>
                <span class="font-black text-bps-blue dark:text-blue-400 text-sm">Rp {{ number_format($maksimalHonor, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400">/ bulan / mitra</span>
            </div>

            <button @click="pilihMetodeModal = true"
                    class="px-5 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-2xl shadow-md shadow-bps-blue/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Alokasi Mitra</span>
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        
        <form method="GET" action="{{ route('kinerja-mitra.alokasi.index') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <select name="bulan" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="tahun" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ $currentYear == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="mitra_id" onchange="this.form.submit()" 
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none max-w-[200px]">
                    <option value="">Semua Mitra</option>
                    @foreach($mitras as $m)
                        <option value="{{ $m->id }}" {{ request('mitra_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="relative min-w-[220px]">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari survei / mitra..." oninput="filterTable()"
                   class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
        </div>
    </div>

    {{-- Tabel Alokasi Mitra --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="alokasiMitraTable">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-black text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 min-w-[190px]">Mitra Statistik</th>
                        <th class="px-4 py-3.5 min-w-[200px]">Survei &amp; Kegiatan</th>
                        <th class="px-4 py-3.5">Posisi</th>
                        <th class="px-4 py-3.5">Satuan &amp; Target</th>
                        <th class="px-4 py-3.5 min-w-[130px]">Honor (IDR)</th>
                        <th class="px-4 py-3.5 min-w-[140px]">Jadwal Pelaksanaan</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($alokasiList as $idx => $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors table-row"
                        data-search="{{ strtolower($item->nama_kegiatan . ' ' . $item->kegiatan . ' ' . ($item->mitra->nama ?? '')) }}">
                        <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                        
                        {{-- Mitra --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $item->mitra->foto_url ?? '' }}" alt="{{ $item->mitra->nama ?? '' }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                <div>
                                    <a href="{{ route('kinerja-mitra.mitra.show', $item->mitra_id) }}" class="font-bold text-slate-900 dark:text-white hover:text-bps-blue text-xs block line-clamp-1">
                                        {{ $item->mitra->nama ?? '-' }}
                                    </a>
                                    <span class="font-mono text-[10px] text-slate-400 block">{{ $item->mitra->sobat_id ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Survei & Kegiatan --}}
                        <td class="px-4 py-4">
                            <p class="font-extrabold text-slate-900 dark:text-white text-xs">{{ $item->nama_kegiatan }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ $item->kegiatan }}</p>
                            @if($item->keterangan)
                            <p class="text-[10px] text-slate-400 italic mt-0.5"><i class="fa-regular fa-comment-dots mr-1"></i>{{ $item->keterangan }}</p>
                            @endif
                        </td>

                        {{-- Posisi --}}
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px] whitespace-nowrap">
                                {{ $item->posisi }}
                            </span>
                        </td>

                        {{-- Satuan & Target --}}
                        <td class="px-4 py-4">
                            <span class="font-extrabold text-bps-blue dark:text-blue-400 text-xs">{{ $item->target_kuantitas }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium ml-1">{{ $item->satuan }}</span>
                        </td>

                        {{-- Honor --}}
                        <td class="px-4 py-4 font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                            Rp {{ number_format($item->honor, 0, ',', '.') }}
                        </td>

                        {{-- Jadwal --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <p class="font-semibold text-slate-700 dark:text-slate-300">
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M') : '-' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d M Y') : '-' }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4 text-center">
                            @php
                                $badgeColor = match($item->status) {
                                    'selesai', 'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                    'berjalan'            => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'terlambat'           => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                    default               => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('kinerja-mitra.alokasi.edit', $item->id) }}"
                                   class="w-7 h-7 rounded-lg bg-bps-faint dark:bg-slate-800 hover:bg-bps-blue hover:text-white text-bps-blue dark:text-blue-400 flex items-center justify-center transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </a>
                                <button type="button" 
                                        @click="deleteModal = true; deleteId = {{ $item->id }}; deleteNama = '{{ addslashes($item->nama_kegiatan) }} ({{ addslashes($item->mitra->nama ?? 'Mitra') }})'"
                                        class="w-7 h-7 rounded-lg bg-red-50 dark:bg-red-950/30 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition-colors cursor-pointer" title="Hapus">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xl text-slate-400 mb-2">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <p class="font-bold text-slate-700 dark:text-slate-300 text-sm">Belum Ada Alokasi Pekerjaan Mitra</p>
                                <p class="text-xs text-slate-400 mt-0.5">Silakan klik "Tambah Alokasi Mitra" untuk menugaskan pekerjaan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 flex items-center justify-between text-xs text-slate-500">
            <span>Total: {{ $alokasiList->count() }} alokasi pekerjaan mitra</span>
        </div>
    </div>

    {{-- ==================== MODAL: PILIH METODE TAMBAH ==================== --}}
    <div x-show="pilihMetodeModal" x-cloak style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="pilihMetodeModal = false"
             x-show="pilihMetodeModal" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-md overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-blue-500/10 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Tambah Alokasi Mitra</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400">Pilih metode pengalokasian</p>
                    </div>
                </div>
                <button @click="pilihMetodeModal = false" class="text-slate-400 hover:text-rose-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-3">
                {{-- Opsi 1: Satu Mitra --}}
                <button @click="pilihMetodeModal = false; modalOpen = true"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-bps-blue hover:bg-bps-faint/30 dark:hover:bg-slate-800 transition text-left group cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-bps-faint dark:bg-slate-800 group-hover:bg-bps-blue/10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-user-plus text-bps-blue text-base"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm">1 Orang Mitra</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Alokasikan pekerjaan untuk 1 mitra via formulir langsung</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-bps-blue ml-auto"></i>
                </button>

                {{-- Opsi 2: Banyak Mitra via Excel --}}
                <button @click="pilihMetodeModal = false; importModal = true"
                        class="w-full flex items-center gap-4 p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 hover:border-emerald-500 hover:bg-emerald-50/40 dark:hover:bg-slate-800 transition text-left group cursor-pointer">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 group-hover:bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-excel text-emerald-600 dark:text-emerald-400 text-base"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm">Banyak Mitra Sekaligus (Excel)</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Unduh template Excel, lengkapi data, lalu import massal</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-emerald-500 ml-auto"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: FORM 1 ORANG MITRA ==================== --}}
    <div x-show="modalOpen" x-cloak style="display: none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalOpen = false" x-show="modalOpen" x-transition.duration.200ms 
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-bps-blue dark:text-blue-300 flex items-center justify-center">
                        <i class="fa-solid fa-user-check text-xs"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm">Tambah Alokasi Pekerjaan — 1 Orang Mitra</h3>
                </div>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="POST" action="{{ route('kinerja-mitra.alokasi.store') }}" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 dark:text-slate-300">

                    {{-- Pilihan Mitra --}}
                    <div>
                        <label class="block font-bold mb-1">Mitra Statistik yang Ditugaskan <span class="text-rose-500">*</span></label>
                        <select name="mitra_id" required 
                                x-model="selectedMitraId"
                                class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-medium">
                            <option value="">-- Pilih Mitra Statistik Terdaftar --</option>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id }}" {{ old('mitra_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama }} (Sobat-ID: {{ $m->sobat_id }}) — {{ $m->kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama Kegiatan Survei & Rincian Kegiatan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Survei / Kegiatan Utama <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required 
                                   placeholder="Contoh: Survei Angkatan Kerja Nasional (Sakernas)"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Rincian Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="kegiatan" value="{{ old('kegiatan') }}" required 
                                   placeholder="Contoh: Pencacahan Lapangan Blok Sensus"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    {{-- Posisi, Satuan, Target Kuantitas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Posisi Penugasan <span class="text-rose-500">*</span></label>
                            <select name="posisi" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                                <option value="Petugas Pencacah Lapangan (PCL)">Petugas Pencacah Lapangan (PCL)</option>
                                <option value="Petugas Pemeriksa Lapangan (PML)">Petugas Pemeriksa Lapangan (PML)</option>
                                <option value="Petugas Pengolahan Data">Petugas Pengolahan Data</option>
                                <option value="Petugas Listing / Pemetaan">Petugas Listing / Pemetaan</option>
                                <option value="Operator Entri Data">Operator Entri Data</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Satuan Target <span class="text-rose-500">*</span></label>
                            <input type="text" name="satuan" value="{{ old('satuan', 'Dokumen') }}" required 
                                   placeholder="Dokumen, BS, Rumah Tangga"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Target Kuantitas <span class="text-rose-500">*</span></label>
                            <input type="number" step="any" min="0.01" name="target_kuantitas" value="{{ old('target_kuantitas', 1) }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    {{-- Honor IDR & Status Batas Maksimal Honor --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block font-bold">Honor Mitra (IDR) <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                Batas honor bulanan: <strong class="text-bps-blue dark:text-blue-400">Rp {{ number_format($maksimalHonor, 0, ',', '.') }}</strong>
                            </span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="text" 
                                   name="honor" 
                                   x-model="honorInput"
                                   @input="honorInput = formatRupiah($event.target.value)"
                                   value="{{ old('honor') }}" 
                                   required 
                                   placeholder="Contoh: 2.500.000"
                                   class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-bold text-sm text-slate-800 dark:text-slate-100">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">
                            * Total honor yang diterima mitra pada bulan yang sama tidak boleh melebihi batas maksimal honor yang diatur oleh Admin.
                        </p>
                    </div>

                    {{-- Tanggal Mulai, Tanggal Selesai, Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-01')) }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-t')) }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Status Penugasan</label>
                            <select name="status" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                                <option value="terjadwal">Terjadwal</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="diterima">Diterima</option>
                            </select>
                        </div>
                    </div>

                    {{-- Keterangan Opsional --}}
                    <div>
                        <label class="block font-bold mb-1">Catatan / Keterangan Penugasan</label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan wilayah blok sensus atau instruksi teknis..." 
                                  class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">{{ old('keterangan') }}</textarea>
                    </div>

                </div>

                <div class="p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 flex justify-end gap-3 shrink-0">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold shadow-md">Simpan Alokasi Mitra</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL: IMPORT EXCEL (BANYAK MITRA) ==================== --}}
    <div x-show="importModal" x-cloak style="display:none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="importModal = false" x-show="importModal" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg overflow-hidden">
            
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-emerald-500/10 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-file-excel text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Import Alokasi Banyak Mitra via Excel</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400">Input penugasan massal sekaligus</p>
                    </div>
                </div>
                <button @click="importModal = false" class="text-slate-400 hover:text-rose-500">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                {{-- Langkah 1: Unduh Template --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-bps-blue text-white flex items-center justify-center text-xs font-black shrink-0">1</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs mb-1">Unduh Template Excel Alokasi Mitra</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Kolom: Sobat-ID/Nama Mitra, Survei, Kegiatan, Posisi, Satuan, Target, Honor (IDR), Tanggal Mulai, Tanggal Selesai.</p>
                        <a href="{{ route('kinerja-mitra.alokasi.template') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                            <i class="fa-solid fa-download"></i>
                            <span>Unduh Template Excel</span>
                        </a>
                    </div>
                </div>

                {{-- Langkah 2: Upload File --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-bps-blue text-white flex items-center justify-center text-xs font-black shrink-0">2</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs mb-1">Unggah Berkas yang Sudah Diisi</p>
                        <form method="POST" action="{{ route('kinerja-mitra.alokasi.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <input type="file" name="file_excel" accept=".xlsx,.xls" required
                                       class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-blue hover:file:text-white file:transition-colors cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 bg-white dark:bg-slate-900">
                            </div>

                            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 rounded-xl text-[11px] text-amber-800 dark:text-amber-200">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                <strong>Validasi Batas Honor:</strong> Jika total honor mitra melebihi batas maksimal bulan terkait, baris tersebut akan ditolak otomatis dan dilaporkan.
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="importModal = false" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold shadow-md">Proses Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ==================== MODAL: KONFIRMASI HAPUS ==================== --}}
    <div x-show="deleteModal" x-cloak style="display: none;" 
         class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModal = false" x-show="deleteModal" x-transition.duration.200ms 
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 rounded-full bg-rose-100 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Hapus Alokasi Mitra?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="deleteNama"></p>
                <p class="text-[11px] text-rose-500 font-semibold mt-2">Data alokasi dan histori penilaian kegiatan ini akan dihapus permanen.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="deleteModal = false" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold">Batal</button>
                <form :action="'{{ url('kinerja-mitra/alokasi') }}/' + deleteId" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                        Ya, Hapus Alokasi
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#alokasiMitraTable .table-row').forEach(row => {
        const text = row.dataset.search || '';
        row.style.display = text.includes(search) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
