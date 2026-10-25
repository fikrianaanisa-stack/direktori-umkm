<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Menampilkan halaman Profil Saya
    public function edit(Request $request)
    {
        return view('admin.profile.edit', [
            'admin' => $request->user(),
        ]);
    }

    // Memperbarui profil admin
    public function update(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],
        ]);

        $admin->update($validated);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // Menampilkan halaman Ubah Password
    public function editPassword()
    {
        return view('admin.profile.password');
    }

    // Memproses perubahan password admin
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);

        $admin = $request->user();

        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return redirect()
            ->route('admin.password.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }
}