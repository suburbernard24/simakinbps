@extends('layouts.app')

@section('title', 'Edit Pegawai')
@section('page_title', 'Edit Data Pegawai')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-slate-900">Perbarui Data Pegawai: {{ $pegawai->name }}</h2>
            <p class="text-xs text-slate-500">Edit informasi dasar, role, jabatan, tim kerja, atau reset password</p>
        </div>
        <div class="flex items-center gap-2">
            @if($pegawai->id !== auth()->id())
                <form method="POST" action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pegawai \'{{ $pegawai->name }}\' secara permanen? Akun ini tidak akan dapat login kembali.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition flex items-center gap-1.5 border border-rose-200">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Hapus User</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.pegawai.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
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
        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai->id) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username Login <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $pegawai->username) }}" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Golongan / Pangkat</label>
                    <input type="text" name="golongan" value="{{ old('golongan', $pegawai->golongan) }}" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>

                <div x-data="{ showPass: false }">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ganti Password (Kosongkan jika tidak diubah)</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" placeholder="Biarkan kosong jika tidak ganti" class="w-full text-xs font-semibold px-3.5 py-2.5 pr-10 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-bps-blue transition focus:outline-none" title="Lihat / Sembunyikan Password">
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Role Akses Sistem <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="pegawai" {{ old('role', $pegawai->role) === 'pegawai' || old('role', $pegawai->role) === 'anggota_tim' ? 'selected' : '' }}>Anggota Tim</option>
                        <option value="ketua_tim" {{ old('role', $pegawai->role) === 'ketua_tim' ? 'selected' : '' }}>Ketua Tim</option>
                        <option value="kepala_bps" {{ old('role', $pegawai->role) === 'kepala_bps' ? 'selected' : '' }}>Kepala BPS</option>
                        <option value="admin" {{ old('role', $pegawai->role) === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tim Kerja BPS</label>
                    <select name="tim_id" class="w-full text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                        <option value="">-- Tanpa Tim (Lintas / Pimpinan) --</option>
                        @foreach($teams as $t)
                            <option value="{{ $t->id }}" {{ old('tim_id', $pegawai->tim_id) == $t->id ? 'selected' : '' }}>{{ $t->nama_tim }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col justify-center space-y-3 pt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_ketua_tim" value="1" {{ old('is_ketua_tim', $pegawai->is_ketua_tim) ? 'checked' : '' }} class="rounded border-slate-300 text-bps-blue focus:ring-bps-blue">
                        <span class="text-xs font-bold text-slate-800">Tugaskan sebagai Ketua Tim</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pegawai->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-slate-800">Status Akun Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pegawai.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition shadow-md shadow-bps-blue/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
