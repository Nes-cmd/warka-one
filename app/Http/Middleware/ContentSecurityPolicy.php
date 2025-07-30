<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Services\CspHashGenerator;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a unique nonce for this request
        $nonce = base64_encode(Str::random(16));
        
        // Store nonce in request for use in views
        $request->attributes->set('csp_nonce', $nonce);
        
        $response = $next($request);

        // Build CSP appropriate for environment
        $csp = $this->buildContentSecurityPolicy($nonce, $request);
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }

    /**
     * Build Content Security Policy with nonce-based security
     */
    private function buildContentSecurityPolicy(string $nonce, Request $request): string
    {
        $isDevelopment = app()->environment('local');

        // Use very permissive CSP for development to avoid breaking frontend
        info("isDevelopment: $isDevelopment");
        if ($isDevelopment) {
            return implode('; ', [
                "default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: http: https:",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' http: https: data: blob:",
                "style-src 'self' 'unsafe-inline' http: https: data:",
                "img-src 'self' data: blob: http: https:",
                "font-src 'self' data: http: https:",
                "connect-src 'self' ws: wss: http: https:",
                "media-src 'self' data: blob: http: https:",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
                "frame-src 'self'"
            ]);
        }

        // Production CSP with framework-aware security
       
        $isLivewireRoute = $this->hasLivewire($request);
        
        // Base CSP for production - strict security
        $policies = [
            "default-src 'self'",
            "object-src 'none'", // Explicitly block objects for security
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'", // Prevent clickjacking
        ];

        
        // For other routes, use strict nonce/hash-based security
        $scriptSrc = [
            "'self'",
            "'nonce-{$nonce}'",
            // Only allow specific trusted CDNs
            "https://cdnjs.cloudflare.com",
            "https://cdn.jsdelivr.net",
        ];
        
        // Add specific hashes for known inline scripts (only for non-framework routes)
        $scriptSrc = array_merge($scriptSrc, $this->getScriptHashes());
        

        $policies[] = "script-src " . implode(' ', $scriptSrc);

        
        // For other routes, use strict nonce/hash-based security
        $styleSrc = [
            "'self'",
            "'nonce-{$nonce}'",
            // Font and style CDNs
            "https://fonts.googleapis.com",
            "https://fonts.bunny.net",
            "https://cdnjs.cloudflare.com",
        ];
        
        // Add hashes for known inline styles (only for non-framework routes)
        $styleSrc = array_merge($styleSrc, $this->getStyleHashes());
        

        $policies[] = "style-src " . implode(' ', $styleSrc);

        // Other directives
        $policies[] = "img-src 'self' data: https://ui-avatars.com";
        $policies[] = "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net";
        
        // Connect-src: Allow WebSockets for Livewire and local dev server
        $connectSrc = ["'self'"];
        if ($isLivewireRoute) {
            $connectSrc[] = "ws:";
            $connectSrc[] = "wss:";
        }

        $policies[] = "connect-src " . implode(' ', $connectSrc);
        
        // Additional security directives
        $policies[] = "frame-src 'self'";
        $policies[] = "media-src 'self'";
        $policies[] = "worker-src 'self'"; // Service workers
        $policies[] = "manifest-src 'self'"; // Web app manifests

        return implode('; ', $policies);
    }

    /**
     * Check if request likely uses Livewire
     */
    private function hasLivewire(Request $request): bool
    {
        return $request->hasHeader('X-Livewire') ||
               str_contains($request->path(), 'livewire') ||
               $request->has('_token'); // Most Livewire requests have CSRF token
    }

    /**
     * Get SHA256 hashes for known inline scripts
     */
    private function getScriptHashes(): array
    {
        return [
            // Add hashes for specific inline scripts you control
            // Example: "'sha256-hash-of-your-inline-script'"
            // You can generate these hashes for your specific inline scripts
        ];
    }

    /**
     * Get SHA256 hashes for known inline styles
     */
    private function getStyleHashes(): array
    {
        return CspHashGenerator::getCommonStyleHashes();
    }
}

/**
 * Helper function to get CSP nonce in views
 */
function csp_nonce(): string
{
    return request()->attributes->get('csp_nonce', '');
} 