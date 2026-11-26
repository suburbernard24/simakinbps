@extends('layouts.app')

@section('title', 'Tambah Pegawai Baru')
@section('page_title', 'Tambah Pegawai Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-slate-900">Formulir Tambah Pegawai</h2>
            <p class="text-xs text-slate-500">Daftarkan akun pegawai baru, atur role dan penugasan tim kerja</p>
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> Terdapat kesalahan pengisian:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.pegawai.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Tr.Stat." class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 199505102018011004" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username Login <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: budisantoso" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: budi@bps.go.id" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. Handphone / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Statistisi Ahli Pertama" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Golongan / Pangkat</label>
                    <input type="text" name="golongan" value="{{ old('golongan') }}" placeholder="Contoh: III/a" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password Awal <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter" class="w-full text-xs font-semibold px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-bps-blue transition focus:outline-none" title="Lihat / Sembunyikan Password">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Role Akses Sistem <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="pegawai" {{ old('role') === 'pegawai' ? 'selected' : '' }}>Anggota Tim</option>
                        <option value="ketua_tim" {{ old('role') === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                        <option value="kepala_bps" {{ old('role') === 'kepala_bps' ? 'selected' : '' }}>Kepala BPS</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tim Kerja BPS</label>
                    <select name="tim_id" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="">-- Tanpa Tim (Lintas / Pimpinan) --</option>
                        @foreach($teams as $t)
                            <option value="{{ $t->id }}" {{ old('tim_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tim }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col justify-center space-y-2 pt-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Akun</p>
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-emerald-700">Aktif</span>
                        <span class="text-[11px] text-slate-400 ml-1">— Pegawai baru otomatis aktif. Ubah via tombol aksi di halaman Kelola Pegawai</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pegawai.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-md shadow-bps-blue/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Pegawai
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
