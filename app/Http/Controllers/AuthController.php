<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan form login sederhana.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('categories.index');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek login dan pastikan akun is_active = true
        if (Auth::attempt(array_merge($credentials, ['is_active' => true]))) {
            $request->session()->regenerate();

            return redirect()->intended(route('categories.index'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '! (Role: ' . Auth::user()->role . ')');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah, atau akun Anda dinonaktifkan.',
        ])->onlyInput('email');
    }

    /**
     * Logout user dari sistem.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
