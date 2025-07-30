<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SlowlorisProtection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use Laravel's built-in rate limiting (much better than custom implementation)
        $key = 'slowloris-protection:' . $request->ip();
        
        // Rate limit: 100 requests per minute per IP
        if (RateLimiter::tooManyAttempts($key, 100)) {
            $seconds = RateLimiter::availableIn($key);
            return response('Too Many Requests - Potential Slowloris Attack Detected', 429)
                ->header('Retry-After', $seconds)
                ->header('X-RateLimit-Limit', 100)
                ->header('X-RateLimit-Remaining', 0);
        }
        
        // Record the attempt
        RateLimiter::hit($key, 60); // 60 seconds decay
        
        // Check for suspicious connection patterns
        if ($this->isSuspiciousRequest($request)) {
            // Also count suspicious requests more heavily
            RateLimiter::hit($key, 60, 5); // Count as 5 attempts
            return response('Suspicious Request Pattern Detected', 400);
        }
        
        $response = $next($request);
        
        // Add security headers to mitigate slow attacks
        $response->headers->set('Connection', 'close');
        $response->headers->set('Keep-Alive', 'timeout=5, max=100');
        
        // Add rate limit headers for transparency
        $remaining = RateLimiter::remaining($key, 100);
        $response->headers->set('X-RateLimit-Limit', 100);
        $response->headers->set('X-RateLimit-Remaining', max(0, $remaining));
        
        return $response;
    }
    
    /**
     * Check for suspicious request patterns typical of Slowloris
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        // Check for incomplete headers (common in Slowloris)
        $suspiciousPatterns = [
            // Very long request URIs
            strlen($request->getRequestUri()) > 2048,
            
            // Missing common headers that legitimate browsers send
            !$request->hasHeader('User-Agent') && !$request->hasHeader('Accept'),
            
            // Suspicious User-Agent patterns
            $request->hasHeader('User-Agent') && 
            preg_match('/^(slowloris|pyloris|torshammer|thc-ssl-dos)/i', $request->header('User-Agent')),
            
            // Very slow or malformed requests (checked via content length vs actual content)
            $request->hasHeader('Content-Length') && 
            (int)$request->header('Content-Length') > 0 && 
            strlen($request->getContent()) === 0,
        ];
        
        return in_array(true, $suspiciousPatterns, true);
    }
} 