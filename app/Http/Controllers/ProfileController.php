<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    /**
     * Show user profile page
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update user profile (name, jabatan, golongan, email)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'golongan'=> 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'    => $request->name,
            'jabatan' => $request->jabatan,
            'golongan'=> $request->golongan,
            'email'   => $request->email,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Upload profile photo
     */
    public function uploadFoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Delete old photo
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('foto_profil', 'public');
        $user->update(['foto' => $path]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Delete profile photo
     */
    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->update(['foto' => null]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }
}
