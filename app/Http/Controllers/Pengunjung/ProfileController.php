<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    // Menampilkan profil pengunjung
    public function index(): View
    {
        // Memastikan hanya akun pengunjung yang dapat mengakses profil
        abort_unless(auth()->user()->role === 'pengunjung', 403);

        return view('pengunjung.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    // Memperbarui nama pengunjung
    public function update(Request $request): RedirectResponse
    {
        // Memastikan hanya akun pengunjung yang dapat memperbarui profil
        abort_unless($request->user()->role === 'pengunjung', 403);

        // Validasi nama pengunjung
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Menyimpan perubahan nama
        $request->user()->update([
            'name' => $validated['name'],
        ]);

        // Kembali ke halaman profil dengan notifikasi
        return redirect()
            ->route('pengunjung.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}