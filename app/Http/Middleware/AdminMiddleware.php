<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Hanya admin yang boleh mengakses halaman administrasi
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
