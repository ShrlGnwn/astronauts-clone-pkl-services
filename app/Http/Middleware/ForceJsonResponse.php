<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memaksa semua response route API berbentuk JSON.
 * Tanpa ini, validasi gagal (422) bisa balikin redirect HTML kalau
 * request tidak mengirim header `Accept: application/json`.
 * (Didaftarkan khusus untuk grup route api — lihat bootstrap/app.php)
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
