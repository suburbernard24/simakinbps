@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page_title', 'Pengumuman Backend')

@section('content')
<div class="space-y-6" x-data="pengumumanApp()" x-cloak>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-5 rounded-2xl shadow-glass border border-slate-200/60">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-bps-faint text-bps-blue flex items-center justify-center">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800">Manajemen Pengumuman</h2>
                <p class="text-[11px] text-slate-500">Buat dan kelola pengumuman yang tampil di seluruh halaman backend.</p>
            </div>
        </div>
        <button @click="openCreate()"
                class="px-5 py-2.5 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-xl shadow-md shadow-bps-blue/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Pengumuman
        </button>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-semibold">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-semibold">
        <i class="fa-solid fa-circle-xmark text-rose-500"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-glass border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] uppercase font-extrabold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-4 py-4 w-10 text-center">No</th>
                        <th class="px-4 py-4 min-w-[200px]">Judul & Isi</th>
                        <th class="px-4 py-4">Tipe</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Periode</th>
                        <th class="px-4 py-4">Dibuat</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengumumans as $index => $p)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-4 text-center font-medium">{{ $pengumumans->firstItem() + $index }}</td>
                        <td class="px-4 py-4 max-w-xs">
                            <p class="font-bold text-slate-800 text-xs">{{ $p->judul }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-2">{{ $p->isi }}</p>
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $tipeClass = match($p->tipe) {
                                'info'      => 'bg-blue-50 text-blue-700 border-blue-200',
                                'sukses'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'peringatan'=> 'bg-amber-50 text-amber-700 border-amber-200',
                                'bahaya'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                default     => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                            $tipeIcon = match($p->tipe) {
                                'info'      => 'fa-circle-info',
                                'sukses'    => 'fa-circle-check',
                                'peringatan'=> 'fa-triangle-exclamation',
                                'bahaya'    => 'fa-circle-xmark',
                                default     => 'fa-circle-info',
                            };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-[10px] font-bold {{ $tipeClass }}">
                                <i class="fa-solid {{ $tipeIcon }} text-[9px]"></i>
                                {{ ucfirst($p->tipe) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($p->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-bold">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-[10px] text-slate-500">
                            @if($p->tanggal_mulai || $p->tanggal_selesai)
                                <span>{{ $p->tanggal_mulai ? $p->tanggal_mulai->format('d M Y') : '—' }}</span>
                                <span class="text-slate-300 mx-1">→</span>
                                <span>{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('d M Y') : '—' }}</span>
                            @else
                                <span class="text-slate-400 italic">Tidak terbatas</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-[10px] text-slate-500">
                            <p>{{ $p->creator->name ?? '-' }}</p>
                            <p class="text-slate-400">{{ $p->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Toggle --}}
                                <form method="POST" action="{{ route('admin.pengumuman.toggle', $p->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors
                                                   {{ $p->is_active ? 'bg-amber-50 hover:bg-amber-500 hover:text-white text-amber-500' : 'bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-600' }}">
                                        <i class="fa-solid {{ $p->is_active ? 'fa-pause' : 'fa-play' }} text-[10px]"></i>
                                    </button>
                                </form>
                                {{-- Edit --}}
                                <button @click="openEdit({{ $p->id }}, {{ json_encode($p->judul) }}, {{ json_encode($p->isi) }}, '{{ $p->tipe }}', {{ $p->is_active ? 'true' : 'false' }}, '{{ $p->tanggal_mulai ? $p->tanggal_mulai->format('Y-m-d') : '' }}', '{{ $p->tanggal_selesai ? $p->tanggal_selesai->format('Y-m-d') : '' }}')"
                                        class="w-7 h-7 rounded-lg bg-bps-faint hover:bg-bps-blue hover:text-white text-bps-blue flex items-center justify-center transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                </button>
                                {{-- Hapus --}}
                                <button @click="confirmDelete({{ $p->id }}, {{ json_encode($p->judul) }})"
                                        class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-500 flex items-center justify-center transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <i class="fa-regular fa-bell-slash text-3xl"></i>
                                <p class="text-sm font-semibold">Belum ada pengumuman.</p>
                                <p class="text-xs">Klik "Tambah Pengumuman" untuk membuat pengumuman baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengumumans->hasPages())
        <div class="p-4 border-t border-slate-200/80 bg-slate-50">
            {{ $pengumumans->links() }}
        </div>
        @endif
    </div>

    {{-- ==================== MODAL FORM (Create / Edit) ==================== --}}
    <div x-show="modalOpen" style="display:none;"
         class="fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="modalOpen = false" x-show="modalOpen" x-transition.duration.200ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm" x-text="isEdit ? 'Edit Pengumuman' : 'Tambah Pengumuman'"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :method="'POST'" :action="formAction" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto space-y-4 text-sm">

                    @if($errors->any())
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-xs">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold mb-1.5">Judul Pengumuman <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" x-model="form.judul" required maxlength="255"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1.5">Isi Pengumuman <span class="text-rose-500">*</span></label>
                        <textarea name="isi" x-model="form.isi" required rows="4"
                                  placeholder="Tuliskan isi pengumuman di sini..."
                                  class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue focus:ring-2 focus:ring-bps-blue/10 text-sm resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1.5">Tipe <span class="text-rose-500">*</span></label>
                            <select name="tipe" x-model="form.tipe" required
                                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue text-sm">
                                <option value="info">ℹ️ Info</option>
                                <option value="sukses">✅ Sukses</option>
                                <option value="peringatan">⚠️ Peringatan</option>
                                <option value="bahaya">🚨 Bahaya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1.5">Status</label>
                            <label class="flex items-center gap-2 cursor-pointer mt-3">
                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                                       class="w-4 h-4 accent-bps-blue rounded">
                                <span class="text-xs font-semibold text-slate-700">Aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" x-model="form.tanggal_mulai"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue text-sm">
                            <p class="text-[10px] text-slate-400 mt-0.5">Kosongkan = langsung aktif</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" x-model="form.tanggal_selesai"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl outline-none focus:border-bps-blue text-sm">
                            <p class="text-[10px] text-slate-400 mt-0.5">Kosongkan = tidak ada batas</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                    <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit"
                            class="px-5 py-2 bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah Pengumuman'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL: KONFIRMASI HAPUS ==================== --}}
    <div x-show="deleteModal" style="display:none;"
         class="fixed inset-0 z-[70] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="deleteModal = false" x-show="deleteModal" x-transition.duration.200ms
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
                </div>
                <h3 class="font-extrabold text-slate-800 text-base mb-1">Hapus Pengumuman?</h3>
                <p class="text-xs text-slate-500">Pengumuman <strong x-text="'&quot;' + deleteTitle + '&quot;'"></strong> akan dihapus secara permanen.</p>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-center">
                <button @click="deleteModal = false"
                        class="px-5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <form :action="deleteAction" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-md transition-all">
                        <i class="fa-solid fa-trash mr-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function pengumumanApp() {
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    const oldIsEdit = {{ session('_old_input.pengumuman_id') ? 'true' : 'false' }};

    return {
        modalOpen: hasErrors,
        isEdit: false,
        deleteModal: false,
        deleteTitle: '',
        deleteAction: '',
        form: {
            judul: '',
            isi: '',
            tipe: 'info',
            is_active: true,
            tanggal_mulai: '',
            tanggal_selesai: '',
        },
        formAction: '{{ route('admin.pengumuman.store') }}',

        openCreate() {
            this.isEdit = false;
            this.form = { judul: '', isi: '', tipe: 'info', is_active: true, tanggal_mulai: '', tanggal_selesai: '' };
            this.formAction = '{{ route('admin.pengumuman.store') }}';
            this.modalOpen = true;
        },

        openEdit(id, judul, isi, tipe, isActive, mulai, selesai) {
            this.isEdit = true;
            this.form = {
                judul: judul,
                isi: isi,
                tipe: tipe,
                is_active: isActive,
                tanggal_mulai: mulai,
                tanggal_selesai: selesai,
            };
            this.formAction = '/admin/pengumuman/' + id + '/update';
            this.modalOpen = true;
        },

        confirmDelete(id, title) {
            this.deleteTitle = title;
            this.deleteAction = '/admin/pengumuman/' + id;
            this.deleteModal = true;
        },
    };
}
</script>
@endpush
