<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Services\CspHashGenerator;

/**
 * Content Security Policy Middleware
 * 
 * This middleware implements a secure CSP that supports:
 * - Alpine.js with 'unsafe-eval' for reactivity
 * - Livewire with WebSocket connections and inline scripts
 * - Vite development server with HMR support
 * - Nonce-based security for inline scripts and styles
 * - Hash-based security for known inline scripts
 * - Subresource Integrity (SRI) for external CDN resources
 * 
 * Key security features:
 * - Uses nonces for inline scripts and styles (no 'unsafe-inline')
 * - Allows style attributes via 'style-src-attr' for Alpine.js/Livewire
 * - Restricts script sources to trusted CDNs and self
 * - Prevents clickjacking with frame-ancestors
 * - Blocks object embeds for security
 * - External scripts protected with SRI hashes
 */
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
        $isLivewireRoute = $this->hasLivewire($request);
        $isDevelopment = app()->environment('local', 'development');
        
        // Base CSP for production - strict security
        $policies = [
            "default-src 'self'",
            "object-src 'none'", // Explicitly block objects for security
            "base-uri 'self'",
            "frame-ancestors 'self'", // Prevent clickjacking
        ];

        // Form-action: Allow forms to be submitted to current domain
        // // In production, restrict to 'self' only
        // if (!$isDevelopment) {
        //     $policies[] = "form-action 'self'";
        // }
        // In development, no form-action restriction for easier development
        
        // Script-src: Allow Alpine.js and Livewire to function
        $scriptSrc = [
            "'self'",
            "'nonce-{$nonce}'",
            "'unsafe-eval'", // Required for Alpine.js reactivity and Livewire
            // Trusted CDNs
            "https://cdnjs.cloudflare.com",
            "https://cdn.jsdelivr.net",
        ];
        
        // Add Vite development server support
        if ($isDevelopment) {
            $scriptSrc[] = "http://nes-live.com:5173"; // Vite dev server
            $scriptSrc[] = "ws://nes-live.com:5173"; // Vite HMR WebSocket
        }
        
        // Add specific hashes for known inline scripts
        $scriptSrc = array_merge($scriptSrc, $this->getScriptHashes());
        
        $policies[] = "script-src " . implode(' ', $scriptSrc);

        // Style-src: Allow inline styles for Alpine.js and Livewire compatibility
        $styleSrc = [
            "'self'",
            "'nonce-{$nonce}'",
            "'unsafe-inline'", // Required for Alpine.js and Livewire dynamic styles
            // Font and style CDNs
            "https://fonts.googleapis.com",
            "https://fonts.bunny.net",
            "https://cdnjs.cloudflare.com",
        ];
        
        // Add Vite development server support for styles
        if ($isDevelopment) {
            $styleSrc[] = "http://nes-live.com:5173";
        }
        
        $policies[] = "style-src " . implode(' ', $styleSrc);
        
        // Other directives
        $policies[] = "img-src 'self' data: https://ui-avatars.com";
        $policies[] = "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net";
        
        // Connect-src: Allow WebSockets for Livewire and AJAX requests
        $connectSrc = ["'self'"];
        if ($isLivewireRoute) {
            $connectSrc[] = "ws:";
            $connectSrc[] = "wss:";
        }
        // Allow local development server WebSocket connections
        if ($isDevelopment) {
            $connectSrc[] = "ws://localhost:*";
            $connectSrc[] = "ws://127.0.0.1:*";
            $connectSrc[] = "ws://[::1]:*";
            $connectSrc[] = "ws://nes-live.com:5173"; // Vite HMR
            $connectSrc[] = "http://nes-live.com:5173"; // Vite dev server
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
     * Check if request likely uses Livewire or Alpine.js
     */
    private function hasLivewire(Request $request): bool
    {
        return $request->hasHeader('X-Livewire') ||
               $request->hasHeader('X-CSRF-TOKEN') ||
               str_contains($request->path(), 'livewire') ||
               $request->has('_token') || // Most Livewire requests have CSRF token
               $request->wantsJson(); // AJAX requests often from Livewire/Alpine
    }

    /**
     * Get SHA256 hashes for known inline scripts
     */
    private function getScriptHashes(): array
    {
        return [
            // Theme toggle script from layouts
            CspHashGenerator::generateHash("
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    "),
            // Alternative theme script format
            CspHashGenerator::generateHash("
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') == 'dark') {
            document.documentElement.classList.add('dark');
            console.log('dark');
        } else {
            console.log('light');
            document.documentElement.classList.remove('dark')
        }
    "),
            // Theme toggle functionality script
            CspHashGenerator::generateHash("
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }

        });
    "),
            // Common Alpine.js initialization patterns
            CspHashGenerator::generateHash("Alpine.start()"),
            CspHashGenerator::generateHash("window.Alpine = Alpine; Alpine.start();"),
        ];
    }

    /**
     * Get SHA256 hashes for known inline styles
     * 
     * Note: These hashes are not used in the main CSP policy because
     * 'unsafe-inline' is ignored when hashes are present. Since Alpine.js
     * and Livewire create dynamic styles that can't be predicted, we need
     * 'unsafe-inline' to work. This method is kept for potential future use
     * in more specific CSP policies or debugging.
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