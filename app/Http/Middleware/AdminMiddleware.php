<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Hanya admin dan super_admin yang boleh mengakses
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'], true)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
