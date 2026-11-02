<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidatorOnly
{
    /**
     * Batasi akses hanya untuk role: validator atau super_admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        if (!in_array($role, ['validator', 'super_admin'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya tersedia untuk Validator.');
        }

        return $next($request);
    }
}
