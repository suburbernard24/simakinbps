@extends('layouts.app')

@section('title', 'Daftar Surat - e-Surat BPS')
@section('page_title', 'Daftar Administrasi Surat')

@section('content')
<div x-data="{ showImportModal: false, selectedJenisKode: 'ST', selectedJenisId: '' }" class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-envelopes-bulk text-bps-blue"></i>
                <span>{{ $scope === 'mine' ? 'Surat Saya' : 'Semua Administrasi Surat' }}</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola pembuatan, verifikasi, nomor surat, dan pengunduhan dokumen</p>
        </div>

        <div class="flex items-center gap-3">
            @if(Auth::user()->canCreateSurat())
            <button @click="showImportModal = true" 
                    type="button"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-transform active:scale-95 cursor-pointer">
                <i class="fa-solid fa-file-excel text-sm"></i>
                <span>Upload Excel</span>
            </button>
            @endif

            @if(Auth::user()->canCreateSurat())
            <a href="{{ route('surat.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs shadow-md shadow-bps-blue/20 transition-transform active:scale-95">
                <i class="fa-solid fa-plus-circle text-sm"></i>
                <span>Buat Surat Baru</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Status Tabs (PRD 21.2) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold border-b border-slate-200">
        <a href="{{ route('surat.index', ['scope' => $scope, 'jenis' => $jenis, 'q' => $q]) }}" 
           class="px-4 py-2 rounded-t-xl transition border-b-2 {{ empty($status) || $status === 'all' ? 'border-bps-blue text-bps-blue bg-bps-faint' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Semua
        </a>
        <a href="{{ route('surat.index', ['status' => 'draft', 'scope' => $scope, 'jenis' => $jenis, 'q' => $q]) }}" 
           class="px-4 py-2 rounded-t-xl transition border-b-2 {{ $status === 'draft' ? 'border-bps-blue text-bps-blue bg-bps-faint' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Draft
        </a>
        <a href="{{ route('surat.index', ['status' => 'selesai', 'scope' => $scope, 'jenis' => $jenis, 'q' => $q]) }}" 
           class="px-4 py-2 rounded-t-xl transition border-b-2 {{ $status === 'selesai' ? 'border-bps-blue text-bps-blue bg-bps-faint' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            Selesai (Dinomori)
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="glass-panel p-4 rounded-2xl border border-slate-200/80 bg-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('surat.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto flex-1">
            @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            @if($scope) <input type="hidden" name="scope" value="{{ $scope }}"> @endif

            <!-- Search -->
            <div class="relative flex-1 min-w-55">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari nomor surat, perihal, atau pemohon..." 
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
            </div>

            <!-- Filter Jenis -->
            <select name="jenis" onchange="this.form.submit()" 
                    class="py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-bps-blue focus:border-bps-blue">
                <option value="">-- Semua Jenis Surat --</option>
                @foreach($jenisSurats as $js)
                <option value="{{ $js->kode }}" {{ $jenis == $js->kode ? 'selected' : '' }}>
                    {{ $js->nama }} ({{ $js->kode }})
                </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition">
                Filter
            </button>

            @if($q || $jenis || $status)
            <a href="{{ route('surat.index', ['scope' => $scope]) }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition">
                Reset
            </a>
            @endif
        </form>

        <!-- Scope switch for admin/approver -->
        @if(Auth::user()->isApprover())
        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl text-xs font-semibold">
            <a href="{{ route('surat.index', ['scope' => 'all', 'status' => $status, 'jenis' => $jenis, 'q' => $q]) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $scope !== 'mine' ? 'bg-white text-bps-blue shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                Semua Satker
            </a>
            <a href="{{ route('surat.index', ['scope' => 'mine', 'status' => $status, 'jenis' => $jenis, 'q' => $q]) }}" 
               class="px-3 py-1.5 rounded-lg transition {{ $scope === 'mine' ? 'bg-white text-bps-blue shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                Surat Saya
            </a>
        </div>
        @endif
    </div>

    <!-- Table of Surat -->
    <div class="glass-panel rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Jenis Surat</th>
                        <th class="py-3.5 px-4">Nomor & Tanggal</th>
                        <th class="py-3.5 px-4">Perihal / Penugasan</th>
                        <th class="py-3.5 px-4">Pemohon & Fungsi</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($surats as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition group">
                        <td class="py-3.5 px-4 text-slate-400 font-mono">
                            {{ $surats->firstItem() + $index }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-bps-faint text-bps-blue font-bold text-[11px]">
                                <i class="fa-regular fa-file-lines text-[10px]"></i>
                                {{ $item->jenisSurat->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($item->nomor_surat)
                                <p class="font-mono font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                    <i class="fa-solid fa-stamp text-emerald-600 text-[10px]"></i>
                                    {{ $item->nomor_surat }}
                                </p>
                            @else
                                <p class="text-slate-400 italic text-[11px]">[Nomor belum terbit]</p>
                            @endif
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($item->tanggal_surat)->translatedFormat('d F Y') }}
                            </p>
                        </td>
                        <td class="py-3.5 px-4 max-w-xs">
                            <a href="{{ route('surat.show', $item->id) }}" class="font-bold text-slate-800 hover:text-bps-blue line-clamp-2">
                                {{ $item->perihal }}
                            </a>
                            @if($item->pegawais->isNotEmpty())
                            <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-users text-slate-400 text-[10px]"></i>
                                <span>{{ $item->pegawais->count() }} Pegawai ditugaskan</span>
                            </p>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <p class="font-semibold text-slate-800">{{ $item->pemohon->name ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400">{{ $item->fungsi ?? ($item->unitKerja->nama ?? 'BPS Kab. Manggarai') }}</p>
                        </td>
                        <td class="py-3.5 px-4">
                            @php $badge = $item->status_badge; @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badge['bg'] }}">
                                {{ $badge['label'] }}
                            </span>
                            @if($item->catatan && in_array($item->status, ['dikembalikan', 'ditolak']))
                            <p class="text-[10px] text-red-600 italic mt-1 line-clamp-1" title="{{ $item->catatan }}">
                                Catatan: {{ $item->catatan }}
                            </p>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Detail -->
                                <a href="{{ route('surat.show', $item->id) }}" 
                                   title="Lihat Detail & Riwayat" 
                                   class="p-2 rounded-lg bg-slate-100 hover:bg-bps-blue hover:text-white text-slate-600 transition">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                <!-- Cetak / PDF -->
                                @if($item->isSelesai() || $item->nomor_surat || !$item->isDraft())
                                <a href="{{ route('surat.cetak', $item->id) }}" 
                                   target="_blank"
                                   title="Cetak Naskah Dinas Resmi (PDF)" 
                                   class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 transition">
                                    <i class="fa-solid fa-print text-xs"></i>
                                </a>
                                @endif

                                <!-- Edit jika draft atau dikembalikan -->
                                @if(($item->canEdit() && $item->user_id === Auth::id()) || Auth::user()->isAdmin())
                                <a href="{{ route('surat.edit', $item->id) }}" 
                                   title="Edit Surat" 
                                   class="p-2 rounded-lg bg-amber-50 hover:bg-amber-500 hover:text-white text-amber-700 transition">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                @endif

                                <!-- Hapus jika draft -->
                                @if(($item->isDraft() && $item->user_id === Auth::id()) || Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('surat.destroy', $item->id) }}" onsubmit="return confirm('Hapus draft surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Draft" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 transition">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 rounded-full bg-slate-100 mx-auto mb-2 flex items-center justify-center text-slate-400">
                                <i class="fa-regular fa-folder-open text-xl"></i>
                            </div>
                            <p class="font-semibold text-slate-600 text-sm">Tidak ada surat ditemukan</p>
                            <p class="text-xs mt-0.5">Silakan sesuaikan filter pencarian{{ Auth::user()->canCreateSurat() ? ' atau buat surat baru.' : '.' }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($surats->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $surats->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Upload / Import Surat via Excel -->
    <div x-show="showImportModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="showImportModal = false">
        <div @click.away="showImportModal = false" 
             x-transition 
             class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Upload Surat via Excel</h3>
                        <p class="text-xs text-slate-400">Impor data naskah surat dari spreadsheet Excel atau CSV</p>
                    </div>
                </div>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Download Template Callout -->
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 space-y-2.5">
                <div class="flex items-center gap-3 text-xs text-emerald-900">
                    <i class="fa-solid fa-circle-info text-emerald-600 text-base shrink-0"></i>
                    <div>
                        <p class="font-bold">Unduh Format Template Resmi Excel (.xlsx)</p>
                        <p class="text-[11px] text-emerald-700">Pilih template sesuai naskah dinas yang ingin Anda buat dari Excel:</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <a href="{{ route('surat.template_excel', ['jenis' => 'BA']) }}" 
                       class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-[11px] shadow-sm transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-download"></i>
                        <span>Template Berita Acara (BA)</span>
                    </a>
                    <a href="{{ route('surat.template_excel', ['jenis' => 'ST']) }}" 
                       class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] shadow-sm transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-download"></i>
                        <span>Template Surat Tugas (ST)</span>
                    </a>
                    <a href="{{ route('surat.template_excel', ['jenis' => 'B']) }}" 
                       class="px-3 py-1.5 rounded-xl bg-slate-700 hover:bg-slate-800 text-white font-semibold text-[11px] shadow-sm transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-download"></i>
                        <span>Template Surat Keluar (B)</span>
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('surat.import_excel') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Jenis Surat <span class="text-red-500">*</span></label>
                    <select name="jenis_surat_id" required 
                            x-model="selectedJenisId"
                            @change="selectedJenisKode = $event.target.options[$event.target.selectedIndex].dataset.kode || 'ST'"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-emerald-500 mb-3">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($jenisSurats as $js)
                        <option value="{{ $js->id }}" data-kode="{{ $js->kode }}">{{ $js->nama }} ({{ $js->kode }})</option>
                        @endforeach
                    </select>

                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Berkas Excel / CSV <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-emerald-300 rounded-2xl p-5 text-center bg-emerald-50/20 hover:border-emerald-500 transition">
                        <i class="fa-solid fa-file-arrow-up text-3xl text-emerald-600 mb-2"></i>
                        <p class="text-xs font-bold text-slate-800">Pilih file spreadsheet (.xlsx, .xls, .csv)</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Maksimal ukuran file 10MB</p>
                        <input type="file" name="file_excel" required accept=".xlsx,.xls,.csv,.txt"
                               class="mt-3 text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Dokumen Setelah Diunggah:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <input type="radio" name="status_default" value="selesai" checked class="text-emerald-600">
                            <span>Selesai (Diterbitkan)</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <input type="radio" name="status_default" value="draft" class="text-emerald-600">
                            <span>Simpan sebagai Draft</span>
                        </label>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showImportModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition inline-flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Unggah & Proses Excel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
