<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For OAuth authorization requests, always use React login (v2.login)
        // External applications should use the React login page
        if ($request->is('oauth/authorize') || $request->fullUrlIs('*oauth/authorize*')) {
            return $request->expectsJson() ? null : route('v2.login');
        }
        
        // For other requests, check if they're coming from React routes
        // React routes are at root level (not /v1 or /admin)
        $path = $request->path();
        if (!str_starts_with($path, 'v1/') && !str_starts_with($path, 'admin/')) {
            return $request->expectsJson() ? null : route('v2.login');
        }
        
        // Default to v1 login for Blade routes
        return $request->expectsJson() ? null : route('v2.login');
    }
}
