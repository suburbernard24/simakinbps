@extends('layouts.app')

@section('title', 'Kelola User Publik')
@section('page_title', 'Kelola Pengguna Publik')

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false }">

    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-black text-slate-900">Frontend User Publik</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun publik, mitra, atau stakeholder eksternal yang dapat mengakses informasi publik SIMAKINBPS</p>
        </div>
        <button @click="modalTambah = true" class="px-4 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition flex items-center gap-2 shadow-sm shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah User Publik</span>
        </button>
    </div>

    <!-- User Publik Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Nama User</th>
                        <th class="py-3.5 px-4">Username</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $u->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-700">{{ $u->username }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $u->email ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.pegawai.toggle', $u->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-xl {{ $u->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} text-xs font-bold transition">
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.user_publik.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user publik \'{{ $u->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" title="Hapus User">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada user publik terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User Publik -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="modalTambah = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Tambah User Publik</h3>
                <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.user_publik.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap / Instansi <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Pengamat Statistik BPS" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Login <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required placeholder="Contoh: publik_manggarai" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" placeholder="Contoh: publik@gmail.com" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter" class="w-full text-xs font-semibold px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-bps-blue transition focus:outline-none" title="Lihat / Sembunyikan Password">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-bps-blue text-white text-xs font-bold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
