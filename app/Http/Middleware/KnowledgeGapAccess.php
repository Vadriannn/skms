<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KnowledgeGapAccess
{
    /**
     * Batasi akses rute Knowledge Gap Analytics hanya untuk Super Admin dan Data Entry.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, ['super_admin', 'data_entry'])) {
            abort(403, 'Akses ditolak. Modul ini hanya dapat diakses oleh Administrator Utama (Super Admin) dan Penyusun Informasi (Data Entry).');
        }

        return $next($request);
    }
}
