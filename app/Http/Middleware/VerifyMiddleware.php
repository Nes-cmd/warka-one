<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyMiddleware
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
            $authwith = $user->phone != null?'phone':'email';
            $authColumn = "{$authwith}_verified_at";
            if($user->{$authColumn} == null) {
                return redirect()->route('must-verify-otp');
            }
        }
        return $next($request);
    }
}
