<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login dan apakah dia superadmin
        if (auth()->check() && auth()->user()->is_superadmin) {
            return $next($request);
        }

        // Jika tidak, abort 403 (Forbidden)
        abort(403, 'Anda tidak memiliki akses sebagai Superadmin.');
    }
}