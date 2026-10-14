@extends('layouts.guest')

@section('title', 'Masuk ke SIMAKINBPS')

@section('content')
<div class="login-page" x-data="{ showPass: false, showForgotModal: false }" @keydown.escape.window="showForgotModal = false">

    <!-- LEFT PANEL -->

    <div class="login-left">

        {{-- Tombol Navigasi Kembali ke Home Page (Atas) --}}
        <div style="padding: 24px 36px 0; display: flex; align-items: center; justify-content: flex-start;">
            <a href="{{ url('/') }}" id="btn-back-home" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #1e3a8a; text-decoration: none; padding: 8px 18px; border-radius: 12px; background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: all 0.22s ease;" onmouseover="this.style.background='#ffffff'; this.style.color='#0D47A1'; this.style.boxShadow='0 4px 14px rgba(21,101,192,0.18)'; this.style.transform='translateX(-3px)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.85)'; this.style.color='#1e3a8a'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.06)'; this.style.transform='none'">
                <i class="fa-solid fa-arrow-left" style="font-size: 11px;"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <div class="login-form-area">
            <div class="login-title-block" style="text-align: center; margin-bottom: 24px;">
                <a href="{{ url('/') }}" style="display: inline-block; margin-bottom: 16px;">
                    <img src="{{ asset('images/simakin.png') }}" alt="Logo SIMAKINBPS" class="logo-simakin" style="height: 70px; width: auto; object-fit: contain;">
                </a>
                <h1 class="login-page-title">Sistem Informasi Manajemen Kinerja</h1>
                <p class="login-page-sub">BPS Kabupaten Manggarai</p>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="alert-body">
                    <p class="alert-title">Autentikasi Gagal</p>
                    @foreach ($errors->all() as $error)
                        <p class="alert-msg">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if (session('info'))
            <div class="alert alert-info" role="alert">
                <div class="alert-icon"><i class="fa-solid fa-circle-info"></i></div>
                <div class="alert-body">
                    <p class="alert-msg">{{ session('info') }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="login-form">
                @csrf

                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrap">
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <input
                            :type="showPass ? 'text' : 'password'"
                            id="password"
                            name="password"
                            required
                            class="form-input pr-icon {{ $errors->has('password') ? 'is-error' : '' }}"
                        >
                        <button
                            type="button"
                            @click="showPass = !showPass"
                            class="toggle-pass"
                        >
                            <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="form-label-row form-remember">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" class="checkbox-input" id="remember-me">
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text">Ingat saya</span>
                    </label>
                    <button 
                        type="button" 
                        @click="showForgotModal = true" 
                        class="forgot-link" 
                        style="background: none; border: none; padding: 0; font-family: inherit; font-size: 12px; cursor: pointer;"
                    >
                        Lupa password?
                    </button>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="login-submit-btn">
                    <span class="btn-login-shimmer"></span>
                    <span>Masuk</span>
                </button>
            </form>

            {{-- Tombol / Tautan Tambahan Kembali ke Home Page (Bawah) --}}
            <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(0, 0, 0, 0.08);">
                <a href="{{ url('/') }}" id="link-back-home-bottom" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #1565C0; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.textDecoration='underline'; this.style.color='#0D47A1'" onmouseout="this.style.textDecoration='none'; this.style.color='#1565C0'">
                    <i class="fa-solid fa-house" style="font-size: 12px;"></i>
                    <span>Kembali ke Home Page (Portal Publik BPS)</span>
                </a>
            </div>
        </div><!-- /login-form-area -->

    </div><!-- /login-left -->


    <!-- RIGHT PANEL -->
    <div class="login-right">
        <div class="right-header">
            <div class="right-header-logo">
                <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS Kabupaten Manggarai">
            </div>
            <div class="right-header-text">
                <span class="right-header-name">BPS KABUPATEN</span>
                <span class="right-header-loc">MANGGARAI</span>
            </div>
        </div>

        <div class="right-building">
            <img src="{{ asset('images/logo_kantor.png') }}" alt="Gedung BPS Kabupaten Manggarai">
        </div>
    </div><!-- /login-right -->

    <!-- MODAL POP UP LUPA PASSWORD -->
    <div 
        x-show="showForgotModal" 
        x-cloak
        style="position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; padding: 20px;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="showForgotModal = false"
    >
        <div 
            x-show="showForgotModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
            style="background: #ffffff; border-radius: 24px; max-width: 440px; width: 100%; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35); border: 1px solid rgba(226, 232, 240, 0.9); overflow: hidden; position: relative; padding: 28px 24px;"
            @click.stop
        >
            <!-- Close Button -->
            <button 
                type="button" 
                @click="showForgotModal = false"
                style="position: absolute; top: 16px; right: 16px; width: 32px; height: 32px; border-radius: 10px; border: none; background: #F1F5F9; color: #64748B; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
                onmouseover="this.style.background='#E2E8F0'; this.style.color='#0F172A'"
                onmouseout="this.style.background='#F1F5F9'; this.style.color='#64748B'"
                title="Tutup"
            >
                <i class="fa-solid fa-xmark" style="font-size: 14px;"></i>
            </button>

            <!-- Icon Header -->
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); color: #1565C0; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; box-shadow: 0 6px 16px rgba(21, 101, 192, 0.15); border: 1px solid rgba(191, 219, 254, 0.6); margin-bottom: 14px;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin-bottom: 6px; letter-spacing: -0.01em;">
                    Lupa Password Akun?
                </h3>
                <p style="font-size: 13px; color: #64748B; line-height: 1.5; margin: 0 auto; max-width: 320px;">
                    Demi keamanan data dan integritas sistem, pemulihan kata sandi dilakukan secara terpusat oleh administrator.
                </p>
            </div>

            <!-- Notice Box -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 16px; margin-bottom: 22px;">
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <div style="width: 26px; height: 26px; border-radius: 8px; background: #E0E7FF; color: #4338CA; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; margin-top: 1px;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <p style="font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                            Silakan Hubungi Administrator
                        </p>
                        <p style="font-size: 12px; color: #475569; line-height: 1.5; margin: 0;">
                            Silakan hubungi <strong>Admin SIMAKINBPS / Tim IPDS BPS Kabupaten Manggarai</strong> untuk melakukan reset kata sandi akun Anda.
                        </p>
                    </div>
                </div>

                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed #CBD5E1; font-size: 11.5px; color: #64748B;">
                    <p style="margin-bottom: 4px; font-weight: 600; color: #334155;">Informasi yang perlu disampaikan ke Admin:</p>
                    <ul style="padding-left: 16px; margin: 0; line-height: 1.6;">
                        <li>Nama Lengkap Pegawai</li>
                        <li>NIP / Username akun Anda</li>
                    </ul>
                </div>
            </div>

            <!-- Action Button -->
            <button 
                type="button" 
                @click="showForgotModal = false"
                style="width: 100%; padding: 12px 20px; background: var(--bps-blue, #1565C0); color: #ffffff; border: none; border-radius: 14px; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 14px rgba(21, 101, 192, 0.3); display: flex; align-items: center; justify-content: center; gap: 8px;"
                onmouseover="this.style.background='var(--bps-blue-dark, #0D47A1)'"
                onmouseout="this.style.background='var(--bps-blue, #1565C0)'"
            >
                <i class="fa-solid fa-check" style="font-size: 12px;"></i>
                <span>Mengerti, Hubungi Admin</span>
            </button>
        </div>
    </div>

</div>
@endsection
