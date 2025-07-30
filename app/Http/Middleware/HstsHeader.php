<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HstsHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add HSTS header for HTTPS requests (and local testing)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Add X-XSS-Protection header
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Add X-Content-Type-Options header
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Add X-Frame-Options header
        $response->headers->set('X-Frame-Options', 'DENY');

        // Add Referrer-Policy header
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
} 