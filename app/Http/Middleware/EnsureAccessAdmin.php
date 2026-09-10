<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware akses dashboard: hanya user dengan kolom `access = 'admin'`.
 * Dipasang lewat alias 'admin' (lihat bootstrap/app.php).
 */
class EnsureAccessAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->access !== 'admin') {
            abort(403, 'Akses dashboard hanya untuk Admin.');
        }

        return $next($request);
    }
}
