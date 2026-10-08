@extends('layouts.app')

@section('title', 'Master Unit Kerja / Fungsi - e-Surat BPS')
@section('page_title', 'Master Unit Kerja & Fungsi BPS')

@section('content')
<div class="space-y-6" x-data="{
    createModal: false,
    editModal: false,
    editId: null,
    editKode: '',
    editNama: '',
    editKet: '',
    editStatus: true,
    openEdit(item) {
        this.editId = item.id;
        this.editKode = item.kode;
        this.editNama = item.nama;
        this.editKet = item.keterangan || '';
        this.editStatus = item.status;
        this.editModal = true;
    }
}">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-sitemap text-bps-blue"></i>
                <span>Master Unit Kerja / Fungsi (PRD 10.4)</span>
            </h2>
            <p class="text-xs text-slate-500">Kelola unit kerja dan fungsi kerja di lingkungan Badan Pusat Statistik</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Unit Kerja</span>
        </button>
    </div>

    <!-- Table -->
    <div class="glass-panel rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="p-3.5 w-12 text-center">No</th>
                        <th class="p-3.5 w-24">Kode</th>
                        <th class="p-3.5">Nama Unit Kerja / Fungsi</th>
                        <th class="p-3.5">Keterangan</th>
                        <th class="p-3.5 text-center">Jumlah Pegawai</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($unitKerjas as $idx => $uk)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="p-3.5 font-mono font-bold text-bps-blue">{{ $uk->kode }}</td>
                        <td class="p-3.5 font-bold text-slate-800">{{ $uk->nama }}</td>
                        <td class="p-3.5 text-slate-500">{{ $uk->keterangan ?? '-' }}</td>
                        <td class="p-3.5 text-center font-bold text-slate-700">{{ $uk->pegawais_count }} Staf</td>
                        <td class="p-3.5 text-center">
                            @if($uk->status)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" @click="openEdit({{ json_encode($uk) }})" 
                                        class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50" title="Edit">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                @if($uk->surats_count === 0)
                                <form method="POST" action="{{ route('surat.master.unit_kerja.destroy', $uk->id) }}" onsubmit="return confirm('Hapus unit kerja ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Belum ada unit kerja tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: Tambah -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.outside="createModal = false">
            <h3 class="font-bold text-sm text-slate-900">Tambah Unit Kerja / Fungsi Baru</h3>
            <form method="POST" action="{{ route('surat.master.unit_kerja.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Singkat <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" required placeholder="Contoh: SOSIAL, TU, PRODUKSI" 
                           class="w-full p-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Unit / Fungsi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Contoh: Fungsi Statistik Sosial" 
                           class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Deskripsi tugas dan fungsi..." 
                              class="w-full p-2.5 text-xs rounded-xl border border-slate-200"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-bps-blue hover:bg-bps-dark text-white font-bold text-xs rounded-xl shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.outside="editModal = false">
            <h3 class="font-bold text-sm text-slate-900">Edit Unit Kerja / Fungsi</h3>
            <form :action="'{{ url('surat/master/unit-kerja') }}/' + editId" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Singkat <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" x-model="editKode" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Unit / Fungsi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" x-model="editNama" required class="w-full p-2.5 text-xs rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" x-model="editKet" rows="2" class="w-full p-2.5 text-xs rounded-xl border border-slate-200"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="status" id="editStatus" x-model="editStatus" class="rounded border-slate-300 text-bps-blue">
                    <label for="editStatus" class="text-xs font-semibold text-slate-700">Status Aktif</label>
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
