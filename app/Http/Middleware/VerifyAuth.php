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
            if($user->phone_verified_at == null){
                $verify = 'phone';
            }
            else if($user->email_verified_at == null){
                $verify = 'email';
            }
            else{
                return $next($request);
            }

            $verifyData = [
                'authwith' => $verify,
                'email'    => $user->email,
                'otpIsFor' => 'must-verify',
                'phone'    => $user->phone,
                'country'  => Country::find($user->country_id),
            ];

            session()->put('authflow', $verifyData);

            return $request->expectsJson()?null : redirect()->route('must-verify-otp', ['verify' => $verify]);
        }

        return $request->expectsJson()?null: redirect()->route('login');
    }
}
