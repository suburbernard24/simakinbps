@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', '👤 Profil Saya')

@section('content')
<div class="space-y-6">


    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-sm font-bold mb-1">Terdapat kesalahan:</p>
                @foreach($errors->all() as $error)
                    <p class="text-xs font-medium">• {{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Hero Profile Card --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-bps-blue via-bps-dark to-indigo-900 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-56 h-56 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-6 -bottom-10 w-40 h-40 bg-bps-light/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            {{-- Avatar + Upload & Hapus --}}
            <div class="flex flex-col items-center gap-2.5 shrink-0">
                <div class="relative group">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white/30 shadow-2xl">
                    <label for="foto-upload-btn"
                           title="Klik untuk memilih foto baru"
                           class="absolute inset-0 rounded-2xl bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center">
                        <i class="fa-solid fa-camera text-white text-xl"></i>
                    </label>
                    <form id="foto-form" method="POST" action="{{ route('profile.foto') }}" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="file" id="foto-upload-btn" name="foto" accept="image/*"
                                onchange="document.getElementById('foto-form').submit()" class="hidden">
                    </form>
                </div>

                {{-- Action buttons: Ganti & Hapus Foto --}}
                <div class="flex items-center gap-1.5">
                    <label for="foto-upload-btn"
                           class="px-2.5 py-1 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition cursor-pointer flex items-center gap-1.5 border border-white/20 shadow-xs">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                        <span>Ganti</span>
                    </label>

                    @if($user->foto)
                    <form method="POST" action="{{ route('profile.foto.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-2.5 py-1 rounded-xl bg-rose-500/80 hover:bg-rose-600 text-white text-xs font-bold transition cursor-pointer flex items-center gap-1.5 border border-rose-400/40 shadow-xs">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-bold uppercase tracking-wider mb-2 border border-white/20">
                    <i class="fa-solid fa-id-badge text-amber-300"></i>
                    <span>{{ $user->role_display }}</span>
                </div>
                <h2 class="text-2xl font-black tracking-tight">{{ $user->name }}</h2>
                <p class="text-bps-pale text-sm mt-0.5">{{ $user->jabatan ?? 'Pegawai BPS Manggarai' }}</p>
                <p class="text-white/60 text-xs mt-1 font-mono">NIP: {{ $user->nip ?? '-' }}</p>
                @if($user->tim)
                    <span class="inline-block mt-2 px-3 py-1 rounded-xl bg-white/15 text-white/90 text-xs font-semibold border border-white/20">
                        <i class="fa-solid fa-users mr-1 text-amber-300"></i>{{ $user->tim->nama_tim }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Kartu Informasi Profil --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-bps-blue"></i> Edit Informasi Profil
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, jabatan, golongan, dan email Anda.</p>
        </div>

        <div class="p-6 space-y-6">
            {{-- Pengaturan Cepat Foto Profil --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-slate-200">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Foto Profil Saat Ini</p>
                        <p class="text-[11px] text-slate-500">Format yang didukung: JPG, PNG, atau WEBP (maks. 2MB)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <label for="foto-upload-btn-2" class="px-3.5 py-2 rounded-xl bg-bps-blue hover:bg-bps-light text-white text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-xs">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                        <span>Upload Foto Baru</span>
                    </label>
                    <input type="file" id="foto-upload-btn-2" accept="image/*" class="hidden"
                           onchange="document.getElementById('foto-upload-btn').files = this.files; document.getElementById('foto-form').submit();">
                    @if($user->foto)
                    <form method="POST" action="{{ route('profile.foto.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-600 text-xs font-bold transition cursor-pointer flex items-center gap-1.5 border border-rose-200 shadow-xs">
                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                            <span>Hapus Foto</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                           class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Golongan / Pangkat</label>
                    <input type="text" name="golongan" value="{{ old('golongan', $user->golongan) }}"
                           class="w-full text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-bps-blue outline-none">
                </div>
            </div>

            {{-- Read-only info --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-2">
                <div>
                    <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-0.5">Username</p>
                    <p class="text-sm font-bold text-slate-800 font-mono">{{ $user->username }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-0.5">NIP</p>
                    <p class="text-sm font-bold text-slate-800 font-mono">{{ $user->nip ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mb-0.5">Role Sistem</p>
                    <p class="text-sm font-bold text-bps-blue">{{ $user->role_display }}</p>
                </div>
            </div>

            {{-- Catatan Keamanan Password --}}
            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-900 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </div>
                <p class="text-xs leading-relaxed">
                    Demi keamanan akun dan integritas sistem, penggantian atau reset kata sandi dilakukan secara terpusat. Silakan hubungi <strong>Administrator Sistem</strong> jika Anda memerlukan perubahan password.
                </p>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-bps-blue hover:bg-bps-dark text-white text-xs font-bold transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
        </div>
    </div>

</div>
@endsection
