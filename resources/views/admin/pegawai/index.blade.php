@extends('layouts.app')

@section('title', 'Kelola Pegawai')
@section('page_title', 'Kelola Pegawai BPS')

@section('content')
<div class="space-y-6" x-data="{
    deleteModal: false,
    deleteId: null,
    deleteName: '',
    deleteUsername: '',
    deleteRole: '',
    pilihMetodeModal: false,
    importModal: false,
    openDropdown: false,
    confirmDelete(id, name, username, role) {
        this.deleteId = id;
        this.deleteName = name;
        this.deleteUsername = username;
        this.deleteRole = role;
        this.deleteModal = true;
    }
}">

    <!-- Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-lg mt-0.5 flex-shrink-0"></i>
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs" id="import-error-alert">
            <p class="font-bold flex items-center gap-2 mb-2"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</p>
            @if(session('import_errors'))
                <ul class="list-disc pl-5 space-y-1 max-h-48 overflow-y-auto">
                    @foreach(session('import_errors') as $importErr)
                        <li>{{ $importErr }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <!-- Top Bar & Filters -->
    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Daftar Pegawai &amp; Role</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun, role, tim kerja, dan status aktif seluruh pegawai BPS Kabupaten Manggarai</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Dropdown Pilihan Tambah Pegawai (Satu Pegawai atau Excel Banyak Pegawai) -->
            <div class="relative" @click.outside="openDropdown = false">
                <button @click="openDropdown = !openDropdown" 
                        type="button" 
                        id="btn-tambah-pegawai"
                        class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pegawai Baru</span>
                    <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200" :class="openDropdown ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="openDropdown" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200/90 p-2 z-50">
                    
                    <!-- Pilihan 1: Satu Pegawai (Seperti Biasa) -->
                    <a href="{{ route('admin.pegawai.create') }}" 
                       @click="openDropdown = false"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-bps-faint/60 transition group text-left">
                        <div class="w-10 h-10 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center flex-shrink-0 group-hover:bg-bps-blue group-hover:text-white transition">
                            <i class="fa-solid fa-user-pen text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs">Satu Pegawai (Formulir)</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Input formulir langsung seperti biasa (1 pegawai)</p>
                        </div>
                    </a>

                    <div class="my-1 border-t border-slate-100"></div>

                    <!-- Pilihan 2: Excel (Banyak Pegawai Sekaligus) -->
                    <button type="button" 
                            @click="openDropdown = false; importModal = true"
                            class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-50 transition group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-file-excel text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span>Banyak Pegawai Sekaligus</span>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-emerald-100 text-emerald-700">Excel</span>
                            </p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Unduh template &amp; import data Excel sekaligus</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, username, jabatan..." class="w-full text-xs font-semibold px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
            </div>

            <div class="w-40">
                <select name="role" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kepala_bps" {{ request('role') === 'kepala_bps' ? 'selected' : '' }}>Kepala BPS</option>
                    <option value="ketua_tim" {{ request('role') === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                    <option value="pegawai" {{ request('role') === 'pegawai' ? 'selected' : '' }}>Anggota Tim</option>
                </select>
            </div>

            <div class="w-48">
                <select name="tim_id" class="w-full text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                    <option value="">Semua Tim</option>
                    @foreach($teams as $t)
                        <option value="{{ $t->id }}" {{ request('tim_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tim }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'tim_id']))
                <a href="{{ route('admin.pegawai.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Pegawai</th>
                        <th class="py-3.5 px-4">NIP & Jabatan</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Tim Kerja</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pegawais as $p)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->avatar_url }}" alt="{{ $p->name }}" class="w-9 h-9 rounded-xl object-cover ring-1 ring-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $p->name }}</p>
                                        <p class="text-[11px] text-slate-400">@ {{ $p->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-mono text-slate-700 font-semibold">{{ $p->nip ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $p->jabatan ?? '-' }} {{ $p->golongan ? '('.$p->golongan.')' : '' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider
                                    @if($p->role === 'admin') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($p->role === 'kepala_bps') bg-rose-50 text-rose-700 border border-rose-200
                                    @elseif($p->role === 'ketua_tim' || $p->is_ketua_tim) bg-blue-50 text-bps-blue border border-blue-200
                                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                    {{ $p->role_display }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->tim)
                                    <span class="font-bold text-slate-800">{{ $p->tim->nama_tim }}</span>
                                    @if($p->is_ketua_tim)
                                        <span class="ml-1 text-[10px] font-extrabold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">Ketua</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Belum ada tim</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $p->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.pegawai.edit', $p->id) }}" class="p-2 rounded-lg text-slate-500 hover:text-bps-blue hover:bg-bps-faint transition" title="Edit Pegawai">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    @if($p->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pegawai.toggle', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status aktif pegawai ini?')">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg {{ $p->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition" title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fa-solid {{ $p->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <button type="button" 
                                            @click="confirmDelete({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->username) }}', '{{ addslashes($p->role_display) }}')" 
                                            class="p-2 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" 
                                            title="Hapus Pegawai">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada data pegawai yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pegawais->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $pegawais->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus User -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="deleteModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mx-auto shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div class="text-center space-y-1.5">
                <h3 class="text-lg font-black text-slate-900">Konfirmasi Hapus User</h3>
                <p class="text-xs text-slate-500">
                    Apakah Anda yakin ingin menghapus akun pegawai berikut?
                </p>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-left mt-3">
                    <p class="text-xs font-bold text-slate-900" x-text="deleteName"></p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5" x-text="'@' + deleteUsername"></p>
                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-rose-50 text-rose-700 border border-rose-200" x-text="deleteRole"></span>
                </div>
                <div class="p-3 bg-amber-50/70 border border-amber-200/70 rounded-xl text-left mt-2 space-y-1">
                    <p class="text-[11px] text-amber-800 font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation"></i> Dampak Penghapusan User:
                    </p>
                    <ul class="text-[11px] text-amber-700 list-disc list-inside space-y-0.5 pl-1">
                        <li>Akun login &amp; sesi aktif user akan langsung dihapus.</li>
                        <li>User ini tidak akan dapat login lagi ke dalam sistem.</li>
                        <li>Data pekerjaan khusus pegawai ini akan dibersihkan.</li>
                    </ul>
                </div>
            </div>

            <form :action="'{{ url('admin/pegawai') }}/' + deleteId" method="POST" class="pt-2 flex items-center justify-end gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-md shadow-rose-600/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Ya, Hapus User</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL: PILIH METODE TAMBAH PEGAWAI ==================== --}}
    <div x-show="pilihMetodeModal" x-cloak style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="pilihMetodeModal = false"
             x-show="pilihMetodeModal" x-transition.duration.300ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            {{-- Header Modal --}}
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-bps-blue/5 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Tambah Pegawai Baru</h3>
                        <p class="text-[10px] text-slate-500">Pilih metode penambahan pegawai</p>
                    </div>
                </div>
                <button @click="pilihMetodeModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 space-y-3">
                {{-- Opsi 1: Satu Pegawai (Formulir Web) --}}
                <a href="{{ route('admin.pegawai.create') }}"
                   class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-bps-blue hover:bg-bps-faint/50 transition-all duration-200 text-left group">
                    <div class="w-12 h-12 rounded-xl bg-bps-faint group-hover:bg-bps-blue/10 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-user-pen text-bps-blue text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm">Satu Pegawai (Formulir Web)</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Tambahkan satu pegawai melalui formulir isian langsung</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-bps-blue ml-auto transition-colors"></i>
                </a>

                {{-- Opsi 2: Banyak Pegawai Sekaligus via Excel --}}
                <button @click="pilihMetodeModal = false; importModal = true"
                        class="w-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all duration-200 text-left group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center flex-shrink-0 transition-colors">
                        <i class="fa-solid fa-file-excel text-emerald-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm">Banyak Pegawai Sekaligus (Excel)</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Unduh template, isi data banyak pegawai, lalu unggah sekaligus</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-emerald-500 ml-auto transition-colors"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL: IMPORT EXCEL PEGAWAI ==================== --}}
    <div x-show="importModal" x-cloak style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="importModal = false"
             x-show="importModal" x-transition.duration.300ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

            {{-- Header Modal Import --}}
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-emerald-50 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-file-excel text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Import Banyak Pegawai via Excel</h3>
                        <p class="text-[10px] text-slate-500">Tambahkan banyak pegawai sekaligus</p>
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
                        <p class="text-[11px] text-slate-500 mb-3">Unduh template terlebih dahulu. Template berisi kolom: <strong>Nama &amp; Gelar, Username, Password, Tim Kerja, NIP, Email, Jabatan, Golongan, Role, Status Akun, Ketua Tim</strong>. Isi mulai baris ke-4.</p>
                        <a href="{{ route('admin.pegawai.template') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all">
                            <i class="fa-solid fa-download"></i> Unduh Template (.xlsx)
                        </a>
                    </div>
                </div>

                {{-- Langkah 2: Upload Excel --}}
                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-bps-blue text-white flex items-center justify-center flex-shrink-0 text-xs font-bold">2</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 text-sm mb-1">Unggah File yang Sudah Diisi</p>
                        <form method="POST" action="{{ route('admin.pegawai.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1.5">Pilih File (.xlsx / .xls, maks. 5MB)</label>
                                <input type="file" name="file_excel" accept=".xlsx,.xls" required
                                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue hover:file:bg-bps-blue hover:file:text-white file:transition-colors cursor-pointer border border-slate-200 rounded-lg py-1.5 bg-white">
                            </div>
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-[11px] text-amber-700 space-y-1">
                                <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Perhatian:</p>
                                <ul class="list-disc pl-4 space-y-0.5">
                                    <li>Gunakan hanya template resmi yang diunduh dari sistem ini.</li>
                                    <li>Kolom Tim, Role, Status &amp; Ketua Tim wajib diisi dari opsi dropdown di template.</li>
                                    <li>Username dan Email harus unik (belum terdaftar di sistem).</li>
                                    <li>Baris contoh (baris 4) akan dilewati otomatis jika tidak diubah.</li>
                                </ul>
                            </div>
                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" @click="importModal = false"
                                        class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                                <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-xs font-bold text-white shadow-sm flex items-center gap-2 transition-colors">
                                    <i class="fa-solid fa-upload"></i> Import Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
