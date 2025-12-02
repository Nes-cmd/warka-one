<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustResetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_reset_password) {
            // Allow access to the password reset routes and logout
            if (!$request->routeIs('password.must-reset') && 
                !$request->routeIs('password.must-reset.store') && 
                !$request->routeIs('logout')) {
                return redirect()->route('password.must-reset');
            }
        }

        return $next($request);
    }
}
