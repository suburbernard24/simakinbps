<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan form login bertema IKN Glassmorphism.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi user dengan username & password.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali di SIMAKINBPS, ' . Auth::user()->name . '!');
        }

        // Cek apakah username ada tapi tidak aktif
        $user = \App\Models\User::where('username', $credentials['username'])->first();
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'username' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Administrator BPS Manggarai.',
            ]);
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Logout pengguna dan hancurkan sesi.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
