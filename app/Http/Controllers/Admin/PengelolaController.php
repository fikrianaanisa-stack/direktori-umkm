<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class PengelolaController extends Controller
{
    /**
     * Menampilkan daftar pengelola admin.
     */
    public function index(): View
    {
        $pengelola = User::query()
            ->where('role', 'admin')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.pengelola.index', compact('pengelola'));
    }
}