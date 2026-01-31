<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()){
            $user = Auth::user();

            $verify = null;
            if($user->phone && $user->phone_verified_at == null){
                $verify = 'phone';
            }
            else if($user->email && $user->email_verified_at == null){
                $verify = 'email';
            }
            else{
                return $next($request);
            }

            return $request->expectsJson()?null : redirect()->route('must-verify-otp', ['verify' => $verify]);
        }

        // For OAuth authorization requests, use React login (v2.login)
        if ($request->is('oauth/authorize') || $request->fullUrlIs('*oauth/authorize*')) {
            return $request->expectsJson() ? null : redirect()->route('v2.login');
        }
        
        // Check if coming from React routes (not /v1 or /admin)
        $path = $request->path();
        if (!str_starts_with($path, 'v1/') && !str_starts_with($path, 'admin/')) {
            return $request->expectsJson() ? null : redirect()->route('v2.login');
        }

        return $request->expectsJson() ? null : redirect()->route('v1.login');
    }
}
