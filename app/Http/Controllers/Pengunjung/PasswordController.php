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