<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        
        if(auth()->check()){
            $user = auth()->user();
            if($user->email_verified_at != null || $user->phone_verified_at != null){
                return $next($request);
            }

            return $request->expectsJson()?null : redirect()->route('must-verify-otp');
        }

        return $request->expectsJson()?null: redirect()->route('login');
    }
}
