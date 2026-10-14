<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsPolicyMiddleware
{
    /**
     * Handle an incoming request.
     * Sets Permissions-Policy header to explicitly allow camera access.
     * Without this, some hosting environments (Nginx/Hostinger) may block
     * getUserMedia() with NotAllowedError even when the user has granted permission.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Permissions-Policy', 'camera=*, microphone=(), geolocation=()');

        return $response;
    }
}
