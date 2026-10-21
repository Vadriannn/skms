<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Menampilkan form pengaturan akun pengguna (username, email, password).
     */
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Memperbarui profil akun (nama, email, password baru jika diisi).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ], [
            'name.required'      => 'Nama / Username wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email tersebut sudah digunakan oleh akun lain.',
            'password.min'       => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        ActivityLog::log(
            "User {$user->name} memperbarui informasi akun/profil",
            'user',
            $user->id
        );

        return back()->with('success', 'Pengaturan akun dan profil berhasil diperbarui.');
    }
}
