<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan Halaman Login
    public function showLoginForm()
    {
        return view('login');
    }

    // Memproses Login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek ke Database (Tabel Users)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Jika sukses, alihkan ke dashboard
            return redirect()->intended('dashboard');
        }

        // 3. Jika gagal, kembalikan ke login dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
