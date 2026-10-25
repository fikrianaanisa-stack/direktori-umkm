<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm(Request $request)
    {
        // Simpan tujuan pengunjung sebelum login
        $redirect = $request->query('redirect');

        if (
            is_string($redirect) &&
            str_starts_with($redirect, url('/'))
        ) {
            $request->session()->put('url.intended', $redirect);
        }

        return view('auth.login');
    }

    // Memproses login pengguna
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Admin diarahkan ke dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Pengunjung kembali ke halaman yang dituju sebelum login
            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])
            ->onlyInput('email');
    }

    // Memproses logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
