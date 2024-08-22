<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Country;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $maxAttempts = 5; // Number of failed attempts allowed
    protected $decayMinutes = 30; // Time until they can try again
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $authwith = 'email';
        $countries = Country::all();
        $selectedCountry = Country::first();
        return view('auth.login', compact('authwith', 'countries', 'selectedCountry'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {

        $authwith = $request->authwith;
        $request->validate(['password' => ['required', 'string']]);
        if($authwith == 'phone'){
            $request->validate(['phone' => 'required']);
            $phone  = trimPhone($request->phone);
            $user = User::where('phone', $phone)->first();
        }
        else{
            $request->validate(['email' => 'required']);
            $user = User::where('email', $request->email)->first();
        }
        
        if($user && Hash::check($request->password, $user->password)){
            Auth::login($user);
            $request->session()->regenerate();

            $authColumn = "{$authwith}_verified_at";
                
            
            if($user->{$authColumn} == null){
                return redirect()->route('must-verify-otp');
            }
          
            
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        throw ValidationException::withMessages([$authwith => 'These credientials didn\'t match our records']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
