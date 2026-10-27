<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PengunjungController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data pengunjung terdaftar
        $query = User::where('role', 'pengunjung');

        // Pencarian berdasarkan nama atau email
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter status verifikasi email
        if ($request->input('status') === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->input('status') === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        // Mengurutkan berdasarkan tanggal registrasi terbaru
        $pengunjung = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // Menghitung seluruh pengunjung terdaftar
        $totalPengunjung = User::where('role', 'pengunjung')->count();

        return view('admin.pengunjung.index', compact(
            'pengunjung',
            'totalPengunjung'
        ));
    }
}