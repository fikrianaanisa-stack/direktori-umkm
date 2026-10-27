<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    // Menampilkan halaman ubah password
    public function edit(): View
    {
        abort_unless(auth()->user()->role === 'pengunjung', 403);

        return view('pengunjung.password.edit');
    }

    // Menyimpan password baru
    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'pengunjung', 403);

        // Validasi password
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // Pesan validasi dalam bahasa Indonesia
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        // Memperbarui password pengguna
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()
            ->route('pengunjung.password.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }
}