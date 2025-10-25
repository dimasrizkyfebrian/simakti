<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Jika ya, izinkan request untuk melanjutkan
            return $next($request);
        }

        // Jika tidak, hentikan request dan beri pesan error 403 (Forbidden)
        abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
    }
}
