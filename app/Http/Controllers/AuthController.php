<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Kalau sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Coba login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Cek user aktif
            if (! Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email'))
                    ->with('swal', [
                        'type' => 'error',
                        'title' => 'Akun Tidak Aktif',
                        'text' => 'Akun Anda tidak aktif. Hubungi administrator.',
                    ]);
            }

            // Update last login
            Auth::user()->update(['last_login_at' => now()]);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('swal', [
                    'type' => 'success',
                    'title' => 'Selamat Datang!',
                    'text' => 'Login berhasil. Halo, '.Auth::user()->name.'!',
                ]);
        }

        // Login gagal
        return back()
            ->withInput($request->only('email'))
            ->with('swal', [
                'type' => 'error',
                'title' => 'Login Gagal',
                'text' => 'Email atau password salah.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('swal', [
                'type' => 'success',
                'title' => 'Logout Berhasil',
                'text' => 'Anda telah keluar dari sistem.',
            ]);
    }
}
