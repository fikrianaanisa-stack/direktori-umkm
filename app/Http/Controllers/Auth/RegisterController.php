<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Menampilkan formulir registrasi pengunjung
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Memproses pendaftaran akun pengunjung
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Role selalu ditetapkan oleh sistem, bukan pengunjung
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'pengunjung',
        ]);

        // Mengirim notifikasi verifikasi email
        event(new Registered($user));

        // Login otomatis setelah registrasi
        Auth::login($user);

        $request->session()->regenerate();

       return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Silakan periksa email Anda untuk melakukan verifikasi akun.');
    }
}
