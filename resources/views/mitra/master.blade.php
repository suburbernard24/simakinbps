@extends('layouts.app')

@section('title', 'Master Data Mitra Statistik')
@section('page_title', 'Master Data Mitra')

@section('content')
<div class="space-y-6" x-data="{
    modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
    modalEdit: false,
    editData: { id: '', sobat_id: '', nama: '', nik: '', jenis_kelamin: '', email: '', telepon: '', posisi_default: '', alamat: '', is_active: true },
    modalImport: false,
    modalHapus: false,
    hapusId: null,
    hapusNama: '',
    bukaModalEdit(m) {
        this.editData = Object.assign({}, m);
        this.modalEdit = true;
    }
}">

    {{-- Action & Info Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-bps-faint dark:bg-slate-800 text-bps-blue dark:text-blue-400 flex items-center justify-center text-lg shadow-2xs">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">Master Data Mitra Statistik</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola basis data seluruh mitra BPS Kabupaten Manggarai (Sobat BPS).
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button @click="modalImport = true"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-file-excel"></i>
                <span>Import Excel</span>
            </button>

            <button @click="modalTambah = true"
                    class="px-5 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-2xl shadow-md shadow-bps-blue/20 transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Mitra Baru</span>
            </button>
        </div>
    </div>

    {{-- Filter & Pencarian --}}
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-800">
        <form method="GET" action="{{ route('kinerja-mitra.master_mitra.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[240px]">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, Sobat-ID, NIK..."
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
            </div>

            <div>
                <select name="status" onchange="this.form.submit()"
                        class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-bps-blue outline-none">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="px-3.5 py-2 bg-bps-blue hover:bg-bps-dark text-white rounded-xl text-xs font-bold transition">
                Filter
            </button>
        </form>

        <span class="text-xs font-bold text-slate-500">
            Total: {{ $mitras->total() }} Mitra
        </span>
    </div>

    {{-- Tabel Master Mitra --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-black text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 min-w-[200px]">Identitas Mitra</th>
                        <th class="px-4 py-3.5">Posisi Default</th>
                        <th class="px-4 py-3.5">Kontak</th>
                        <th class="px-4 py-3.5">Wilayah</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($mitras as $idx => $m)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $mitras->firstItem() + $idx }}</td>
                        
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $m->foto_url }}" alt="{{ $m->nama }}" class="w-10 h-10 rounded-2xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                <div>
                                    <p class="font-extrabold text-slate-900 dark:text-white text-xs">{{ $m->nama }}</p>
                                    <span class="font-mono text-[10px] text-bps-blue dark:text-blue-400 font-bold block">{{ $m->sobat_id }}</span>
                                    @if($m->nik)
                                    <span class="text-[10px] text-slate-400 block font-mono">NIK: {{ $m->nik }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium text-[11px]">
                                {{ $m->posisi_default ?? 'PCL' }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-[11px]">
                            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $m->telepon ?? '-' }}</p>
                            <p class="text-slate-400">{{ $m->email ?? '-' }}</p>
                        </td>

                        <td class="px-4 py-4 text-[11px] text-slate-600 dark:text-slate-400">
                            {{ $m->kabupaten }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <form method="POST" action="{{ route('kinerja-mitra.master_mitra.toggle', $m->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition cursor-pointer {{ $m->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-300 hover:bg-slate-200' }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>

                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('kinerja-mitra.mitra.show', $m->id) }}" 
                                   class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-slate-800 hover:bg-bps-blue hover:text-white text-bps-blue dark:text-blue-400 flex items-center justify-center transition" title="Lihat Histori">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                </a>

                                <button type="button" 
                                        @click="bukaModalEdit({{ json_encode($m) }})"
                                        class="w-7 h-7 rounded-lg bg-bps-faint dark:bg-slate-800 hover:bg-bps-blue hover:text-white text-bps-blue dark:text-blue-400 flex items-center justify-center transition cursor-pointer" title="Edit">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </button>

                                <button type="button" 
                                        @click="modalHapus = true; hapusId = {{ $m->id }}; hapusNama = '{{ addslashes($m->nama) }}'"
                                        class="w-7 h-7 rounded-lg bg-red-50 dark:bg-red-950/30 hover:bg-red-500 hover:text-white text-red-500 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            Belum ada data mitra terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-855 flex items-center justify-between text-xs text-slate-500">
            <span>Menampilkan {{ $mitras->count() }} dari {{ $mitras->total() }} data</span>
            <div>
                {{ $mitras->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH MITRA --}}
    <div x-show="modalTambah" x-cloak style="display: none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalTambah = false" x-show="modalTambah" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm">Tambah Mitra Statistik Baru</h3>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="POST" action="{{ route('kinerja-mitra.master_mitra.store') }}" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 dark:text-slate-300">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Sobat-ID Mitra <span class="text-rose-500">*</span></label>
                            <input type="text" name="sobat_id" value="{{ old('sobat_id') }}" required placeholder="Contoh: 5310-M001"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Nama Lengkap Mitra <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">NIK (16 Digit)</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" maxlength="30"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-mono">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Posisi Default</label>
                            <input type="text" name="posisi_default" value="{{ old('posisi_default', 'Petugas Pencacah Lapangan (PCL)') }}" 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Nomor Telepon / WA</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08..."
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Provinsi <span class="text-rose-500">*</span></label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', 'Nusa Tenggara Timur') }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten', 'Kabupaten Manggarai') }}" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Alamat Lengkap Domisili</label>
                        <textarea name="alamat" rows="2" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">{{ old('alamat') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Email Mitra</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Pas Foto Mitra (Opsional)</label>
                        <input type="file" name="foto" accept="image/*"
                               class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 bg-slate-50 dark:bg-slate-800">
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" id="tambah_is_active" value="1" checked
                               class="rounded text-bps-blue focus:ring-bps-blue">
                        <label for="tambah_is_active" class="font-bold cursor-pointer">Status Mitra Aktif</label>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50/50 dark:bg-slate-800/40">
                    <button type="button" @click="modalTambah = false" 
                            class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-xl bg-bps-blue text-white font-bold hover:bg-bps-dark transition shadow-md shadow-bps-blue/20">
                        Simpan Data Mitra
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT MITRA --}}
    <div x-show="modalEdit" x-cloak style="display: none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalEdit = false" x-show="modalEdit" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm">Edit Data Mitra Statistik</h3>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form :action="'{{ url('kinerja-mitra/master-mitra') }}/' + editData.id + '/update'" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 dark:text-slate-300">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Sobat-ID Mitra <span class="text-rose-500">*</span></label>
                            <input type="text" name="sobat_id" x-model="editData.sobat_id" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Nama Lengkap Mitra <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" x-model="editData.nama" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">NIK (16 Digit)</label>
                            <input type="text" name="nik" x-model="editData.nik" maxlength="30"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue font-mono">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" x-model="editData.jenis_kelamin" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Posisi Default</label>
                            <input type="text" name="posisi_default" x-model="editData.posisi_default" 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Nomor Telepon / WA</label>
                            <input type="text" name="telepon" x-model="editData.telepon" 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Provinsi <span class="text-rose-500">*</span></label>
                            <input type="text" name="provinsi" x-model="editData.provinsi" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                            <input type="text" name="kabupaten" x-model="editData.kabupaten" required 
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Alamat Lengkap Domisili</label>
                        <textarea name="alamat" x-model="editData.alamat" rows="2" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Email Mitra</label>
                        <input type="email" name="email" x-model="editData.email" 
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-bps-blue">
                    </div>

                    <div>
                        <label class="block font-bold mb-1">Ubah Foto Mitra (Kosongkan bila tidak diganti)</label>
                        <input type="file" name="foto" accept="image/*"
                               class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 bg-slate-50 dark:bg-slate-800">
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editData.is_active"
                               class="rounded text-bps-blue focus:ring-bps-blue">
                        <label for="edit_is_active" class="font-bold cursor-pointer">Status Mitra Aktif</label>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50/50 dark:bg-slate-800/40">
                    <button type="button" @click="modalEdit = false" 
                            class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-xl bg-bps-blue text-white font-bold hover:bg-bps-dark transition shadow-md shadow-bps-blue/20">
                        Perbarui Data Mitra
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL IMPORT EXCEL --}}
    <div x-show="modalImport" x-cloak style="display: none;" 
         class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalImport = false" x-show="modalImport" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-lg overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                <h3 class="font-bold text-slate-900 dark:text-white text-sm">Import Mitra dari Berkas Excel</h3>
                <button @click="modalImport = false" class="text-slate-400 hover:text-rose-500"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-bps-blue text-white flex items-center justify-center font-bold text-xs shrink-0">1</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 mb-1">Unduh Template Berkas</p>
                        <p class="text-[11px] text-slate-500 mb-2">Unduh template Excel resmi BPS untuk pengisian data mitra massal.</p>
                        <a href="{{ route('kinerja-mitra.master_mitra.template') }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                            <i class="fa-solid fa-download"></i>
                            <span>Unduh Template</span>
                        </a>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-bps-blue text-white flex items-center justify-center font-bold text-xs shrink-0">2</div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800 dark:text-slate-200 mb-1">Unggah Berkas Excel</p>
                        <form method="POST" action="{{ route('kinerja-mitra.master_mitra.import') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="file" name="file_excel" accept=".xlsx,.xls" required 
                                   class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-bps-faint file:text-bps-blue border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 bg-white dark:bg-slate-900">
                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" @click="modalImport = false" class="px-4 py-2 bg-white dark:bg-slate-800 border rounded-xl font-bold">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-bps-blue text-white rounded-xl font-bold">Import Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS MITRA --}}
    <div x-show="modalHapus" x-cloak style="display: none;" 
         class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalHapus = false" x-show="modalHapus" x-transition.duration.200ms
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl mx-auto">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">Hapus Mitra Statistik?</h3>
                <p class="text-xs text-slate-500 mt-1" x-text="hapusNama"></p>
                <p class="text-[11px] text-rose-500 mt-2">Seluruh histori alokasi dan penilaian mitra ini akan ikut terhapus.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="modalHapus = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold">Batal</button>
                <form :action="'{{ url('kinerja-mitra/master-mitra') }}/' + hapusId" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold shadow-md">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
