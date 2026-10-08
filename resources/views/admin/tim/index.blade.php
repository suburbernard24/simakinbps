@extends('layouts.app')

@section('title', 'Kelola Tim BPS')
@section('page_title', 'Kelola Tim Kerja BPS')

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false, modalEdit: false, modalHapus: false, editData: {}, hapusData: {} }">

    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Manajemen Tim Kerja BPS Manggarai</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola divisi, nama tim kerja, serta penetapan Ketua Tim</p>
        </div>
        <button @click="modalTambah = true" class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Tim Baru</span>
        </button>
    </div>

    <!-- Grid Tim Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($teams as $tim)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-700 text-[11px] font-extrabold uppercase">
                            {{ $tim->singkatan ?? 'Tim' }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <button @click="editData = {{ json_encode($tim) }}; modalEdit = true" class="text-slate-400 hover:text-bps-blue p-1.5 rounded-lg hover:bg-blue-50 transition" title="Edit Tim">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button @click="hapusData = { id: {{ $tim->id }}, nama: '{{ addslashes($tim->nama_tim) }}', jumlah: {{ $tim->pegawais_count }} }; modalHapus = true"
                                    class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition" title="Hapus Tim">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <h3 class="text-base font-black text-slate-900 mt-3">{{ $tim->nama_tim }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $tim->keterangan ?? 'Tidak ada keterangan khusus.' }}</p>

                    <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Ketua Tim</p>
                        <div class="flex items-center gap-2.5">
                            @if($tim->ketua)
                                <img src="{{ $tim->ketua->avatar_url }}" alt="{{ $tim->ketua->name }}" class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-200">
                                <div>
                                    <p class="text-xs font-bold text-slate-900 leading-tight">{{ $tim->ketua->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $tim->ketua->nip ?? '-' }}</p>
                                </div>
                            @else
                                <span class="text-xs text-rose-500 font-semibold italic flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum ditunjuk Ketua Tim
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Anggota Tim:</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 font-bold text-slate-800">{{ $tim->pegawais_count }} Pegawai</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Tambah Tim -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalTambah = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Tambah Tim Kerja Baru</h3>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.tim.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tim Kerja <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_tim" required placeholder="Contoh: Tim Statistik Sosial" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Singkatan / Kode Tim</label>
                    <input type="text" name="singkatan" placeholder="Contoh: Sosial" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Ketua Tim</label>
                    <select name="ketua_id" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="">-- Tentukan Nanti --</option>
                        @foreach($ketuaCandidates as $kc)
                            <option value="{{ $kc->id }}">{{ $kc->name }} ({{ $kc->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi / Ruang Lingkup</label>
                    <textarea name="keterangan" rows="2" placeholder="Uraian tanggung jawab survei/kegiatan tim..." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm">Simpan Tim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Tim -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalEdit = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Perbarui Tim Kerja</h3>
                <button @click="modalEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" :action="'/admin/tim/' + editData.id + '/update'" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Tim Kerja <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_tim" x-model="editData.nama_tim" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Singkatan / Kode Tim</label>
                    <input type="text" name="singkatan" x-model="editData.singkatan" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Ketua Tim</label>
                    <select name="ketua_id" x-model="editData.ketua_id" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="">-- Kosongkan / Tanpa Ketua --</option>
                        @foreach($ketuaCandidates as $kc)
                            <option value="{{ $kc->id }}">{{ $kc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi / Ruang Lingkup</label>
                    <textarea name="keterangan" x-model="editData.keterangan" rows="2" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Tim -->
    <div x-show="modalHapus" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalHapus = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-rose-100 flex items-center justify-center">
                    <i class="fa-solid fa-trash-can text-2xl text-rose-500"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Hapus Tim Kerja?</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Anda akan menghapus tim <strong x-text="'«' + hapusData.nama + '»'"></strong>.
                        <span x-show="hapusData.jumlah > 0" class="block mt-2 text-rose-600 font-semibold">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Tim ini masih memiliki <strong x-text="hapusData.jumlah"></strong> pegawai. Pindahkan pegawai terlebih dahulu.
                        </span>
                        <span x-show="hapusData.jumlah == 0" class="block mt-2">
                            Tindakan ini tidak dapat dibatalkan.
                        </span>
                    </p>
                </div>
                <div class="flex items-center gap-3 w-full">
                    <button type="button" @click="modalHapus = false" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <form :action="'/admin/tim/' + hapusData.id" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 transition">
                            Ya, Hapus Tim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
