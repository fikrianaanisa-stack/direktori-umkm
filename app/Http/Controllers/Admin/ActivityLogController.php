<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Pencarian nama Admin atau deskripsi aktivitas
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('nama_pengguna', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter nama Admin
        if ($request->filled('admin')) {
            $query->where('nama_pengguna', $request->input('admin'));
        }

        // Filter modul
        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        // Filter aktivitas
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter tanggal
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->input('tanggal_awal'));
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->input('tanggal_akhir'));
        }

        // Pengurutan
        switch ($request->input('sort', 'terbaru')) {
            case 'terlama':
                $query->orderBy('created_at')->orderBy('id');
                break;

            case 'nama_asc':
                $query->orderBy('nama_pengguna')->orderByDesc('id');
                break;

            case 'nama_desc':
                $query->orderByDesc('nama_pengguna')->orderByDesc('id');
                break;

            default:
                $query->orderByDesc('created_at')->orderByDesc('id');
                break;
        }

        // Pagination 10 data per halaman
        $logs = $query->paginate(10)->withQueryString();

        // Daftar nama Admin untuk dropdown filter
        $admins = ActivityLog::query()
            ->whereNotNull('nama_pengguna')
            ->distinct()
            ->orderBy('nama_pengguna')
            ->pluck('nama_pengguna');

        return view('admin.activity-logs.index', compact('logs', 'admins'));
    }
}