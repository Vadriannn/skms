<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    /**
     * Batasi akses rute hanya untuk pengguna dengan role super_admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Modul ini hanya dapat diakses oleh Administrator Utama (Super Admin).');
        }

        return $next($request);
    }
}
