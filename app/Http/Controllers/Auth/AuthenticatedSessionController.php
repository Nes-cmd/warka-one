<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Passport\Client;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    protected $maxAttempts = 5; 
    protected $decayMinutes = 30;
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $intended = session()->get('url.intended');
        $clientId = null;
        if ($intended && strpos($intended, 'oauth/authorize') !== false) {
            $queryParams = [];
            parse_str(parse_url($intended, PHP_URL_QUERY), $queryParams);
            $clientId = $queryParams['client_id'] ?? null;
        }
        
        $client = null;
        $options = ['email', 'phone'];
        $authMethod = 'password'; // Default to password only options are password || otp
        $registrationEnabled = true;
        
        if($clientId && $client = Client::find($clientId)){
            $options = $client->use_auth_types ?? $options;
            $authMethod = $client->pass_type ?  $client->pass_type : $authMethod;
            $registrationEnabled = $client->registration_enabled;
        }

        $authwith = count($options) == 1 ? $options[0] : 'email';
        
        $countries = Country::all();
        $selectedCountry = Country::first();
        
        return view('auth.login', compact(
            'authwith', 
            'authMethod',
            'countries', 
            'selectedCountry', 
            'options', 
            'registrationEnabled'
        ));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
      
        $authwith = $request->authwith;
        $authMethod = $request->auth_method ?? 'password';
        
        // Validation based on auth method
        if ($authMethod == 'password') {
            $request->validate(['password' => ['required', 'string', 'max:255']]);
        } else if ($authMethod == 'otp') {
            $request->validate(['otp' => ['required', 'string', 'min:4', 'max:8']]);
        }
        
        // Validation based on auth type (email/phone)
        if ($authwith == 'phone') {
            $request->validate(['phone' => 'required', 'max:25']);
            $phone = trimPhone($request->phone);
            $user = User::where('phone', $phone)->first();
            $identifier = $phone;
        } else {
            $request->validate(['email' => 'required', 'max:60']);
            $user = User::where('email', $request->email)->first();
            $identifier = $request->email;
        }
        
        // If user not found, return generic error
        if (!$user) {
            if ($authMethod == 'password') {
                throw ValidationException::withMessages([$authwith => 'These credentials didn\'t match our records']);
            } else {
                throw ValidationException::withMessages(['otp' => 'Invalid verification code']);
            }
        }
        $this->logoutFromOtherSessions($user);
        // Authentication logic based on method
        $authenticated = false;
        
        if ($authMethod == 'password' && Hash::check($request->password, $user->password)) {
            $authenticated = true;
        } else if ($authMethod == 'otp') {
            // For OTP, use the  existing verification code system
            $candidate = '';
            
            if ($authwith == 'email') {
                $candidate = $user->email;
            } else {
                // Get the country for the dial code
                $country = Country::find($user->country_id);
                if ($country) {
                    $candidate = $country->dial_code . $user->phone;
                } else {
                    $candidate = $user->phone; // Fallback if country not found
                }
            }
            
            $verification = \App\Models\VerificationCode::where('candidate', $candidate)
                ->where('verification_code', $request->otp)
                ->latest()
                ->first();
            
            if ($verification) {
                // Update status to verified
                $verification->status = 'verified';
                $verification->save();
                $authenticated = true;
            } else {
                Log::debug("No verification found matching the criteria");
            }
        }
        
        if ($authenticated) {
            Auth::login($user);
            $request->session()->regenerate();

            // $authColumn = "{$authwith}_verified_at";
            
            // if ($user->{$authColumn} == null) {
            //     return redirect()->route('must-verify-otp');
            // }
            
            // Check if user must reset password
            if ($user->must_reset_password) {
                return redirect()->route('password.must-reset');
            }
            
            // Check if coming from React login (routes are now at root level, not /v2)
            // React routes: /login, /register, /account, /clients, etc.
            // v1 Blade routes: /v1/login, /v1/account, etc.
            $intended = session()->get('url.intended');
            $referer = $request->headers->get('referer');
            
            // Check if login came from React route (root level, not /v1)
            $isReactRoute = false;
            if ($referer) {
                $refererPath = parse_url($referer, PHP_URL_PATH);
                // React routes are at root level and don't start with /v1
                $isReactRoute = $refererPath && 
                    !str_starts_with($refererPath, '/v1') && 
                    !str_starts_with($refererPath, '/admin') &&
                    (str_contains($refererPath, '/login') || str_contains($refererPath, '/register'));
            }
            
            // Also check intended URL
            if ($intended && !str_starts_with($intended, '/v1') && !str_starts_with($intended, '/admin')) {
                $isReactRoute = true;
            }
            
            if ($isReactRoute) {
                session()->forget('url.intended');
                return redirect(route('v2.account'));
            }
            
            // Default: redirect to v1 account (Blade) or intended URL
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Authentication failed
        if ($authMethod == 'password') {
            throw ValidationException::withMessages([$authwith => 'These credentials didn\'t match our records']);
        } else {
            throw ValidationException::withMessages(['otp' => 'The provided verification code is invalid or has expired']);
        }
    }

    /**
     * Handle requests for login OTPs
     */
    public function requestLoginOTP(Request $request)
    {
        $authWith = $request->auth_with;
        
        if ($authWith == 'email') {
            $request->validate(['email' => 'required|email']);
            $identifier = $request->email;
            $user = User::where('email', $identifier)->first();
        } else {
            $request->validate(['phone' => 'required']);
            $phone = trimPhone($request->phone);
            $identifier = $phone;
            $user = User::where('phone', $phone)->first();
        }
        
        // Rate limiting to prevent abuse
        $key = 'otp-request:' . $identifier;
        if (RateLimiter::tooManyAttempts($key, 3)) { // 3 attempts per minute
            return response()->json([
                'success' => false,
                'message' => 'Too many OTP requests. Please try again later.'
            ], 429);
        }
        
        RateLimiter::hit($key, 60); // 1 minute cooldown
        
        // Send OTP using your existing verification system
        if ($user) {
            Log::debug("Sending OTP to user: " . $user->id);
            
            try {
                if ($authWith == 'email') {
                    \App\Helpers\SendVerification::make()
                        ->via('mail')
                        ->receiver($identifier)
                        ->send();
                    
                    Log::debug("OTP sent via email to: " . $identifier);
                } else {
                    // For phone, get the country dial code first
                    $country = Country::find($user->country_id);
                    $fullPhone = $country->dial_code . $phone;
                    
                    \App\Helpers\SendVerification::make()
                        ->via('sms')
                        ->receiver($fullPhone)
                        ->send();
                    
                    Log::debug("OTP sent via SMS to: " . $fullPhone);
                }
                
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Error sending OTP: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.'
                ]);
            }
        } else {
            Log::debug("User not found for " . $authWith . ": " . $identifier);
            // Don't reveal user existence, pretend we sent an OTP
            return response()->json(['success' => true]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Check if the request came from a React route
        // React logout route is at root level (/logout), Blade logout is at /v1/logout
        $currentPath = $request->path();
        $referer = $request->headers->get('referer');
        
        // If logout was called from root level (/logout), it's a React route
        $isReactRoute = $currentPath === 'logout';
        
        // Also check referer as fallback
        if (!$isReactRoute && $referer) {
            $refererPath = parse_url($referer, PHP_URL_PATH);
            // React routes are at root level and don't start with /v1 or /admin
            if ($refererPath && 
                !str_starts_with($refererPath, '/v1') && 
                !str_starts_with($refererPath, '/admin') &&
                (str_contains($refererPath, '/account') ||
                 str_contains($refererPath, '/clients') ||
                 str_contains($refererPath, '/profile-setting'))) {
                $isReactRoute = true;
            }
        }
        
        if ($isReactRoute || $request->expectsJson()) {
            return redirect()->route('v2.login');
        }
        
        // Default: redirect to home for Blade routes
        return redirect('/');
    }

    public function logoutFromOtherSessions($user)
    {
        if ($user && !config('session.allow_concurrent_login')) {
            $userId = $user->id;
            // Invalidate all other sessions except current
            DB::table('sessions')
                ->where('user_id', $userId)
                // ->where('id', '!=', $currentSessionId)
                ->delete();

            return true;
        }
        
        return false;
    }

    /**
     * Display the login view for React/Inertia
     */
    public function createReact(Request $request)
    {
        $intended = session()->get('url.intended');
        $clientId = null;
        if ($intended && strpos($intended, 'oauth/authorize') !== false) {
            $queryParams = [];
            parse_str(parse_url($intended, PHP_URL_QUERY), $queryParams);
            $clientId = $queryParams['client_id'] ?? null;
        }
        
        $client = null;
        $options = ['email', 'phone'];
        $authMethod = 'password'; // Default to password only options are password || otp
        $registrationEnabled = true;
        
        if($clientId && $client = Client::find($clientId)){
            $options = $client->use_auth_types ?? $options;
            $authMethod = $client->pass_type ?  $client->pass_type : $authMethod;
            $registrationEnabled = $client->registration_enabled;
        }

        $authwith = count($options) == 1 ? $options[0] : 'email';
        
        $countries = Country::all();
        $selectedCountry = Country::first();
        
        return Inertia::render('Login', [
            'authwith' => $authwith,
            'authMethod' => $authMethod,
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'options' => $options,
            'registrationEnabled' => $registrationEnabled,
            'success' => Session::get('success'),
            'error' => Session::get('error'),
        ]);
    }

    /**
     * Handle an incoming authentication request for React/Inertia
     */
    public function storeReact(Request $request)
    {
        $authwith = $request->authwith;
        $authMethod = $request->auth_method ?? 'password';
        
        // Validation based on auth method
        if ($authMethod == 'password') {
            $request->validate(['password' => ['required', 'string', 'max:255']]);
        } else if ($authMethod == 'otp') {
            $request->validate(['otp' => ['required', 'string', 'min:4', 'max:8']]);
        }
        
        // Validation based on auth type (email/phone)
        if ($authwith == 'phone') {
            $request->validate(['phone' => 'required', 'max:25']);
            $phone = trimPhone($request->phone);
            $user = User::where('phone', $phone)->first();
            $identifier = $phone;
        } else {
            $request->validate(['email' => 'required', 'max:60']);
            $user = User::where('email', $request->email)->first();
            $identifier = $request->email;
        }
        
        // If user not found, return generic error
        if (!$user) {
            if ($authMethod == 'password') {
                return back()->withErrors([$authwith => 'These credentials didn\'t match our records']);
            } else {
                return back()->withErrors(['otp' => 'Invalid verification code']);
            }
        }
        
        $this->logoutFromOtherSessions($user);
        
        // Authentication logic based on method
        $authenticated = false;
        
        if ($authMethod == 'password' && Hash::check($request->password, $user->password)) {
            $authenticated = true;
        } else if ($authMethod == 'otp') {
            // For OTP, use the existing verification code system
            $candidate = '';
            
            if ($authwith == 'email') {
                $candidate = $user->email;
            } else {
                // Get the country for the dial code
                $country = Country::find($user->country_id);
                if ($country) {
                    $candidate = $country->dial_code . $user->phone;
                } else {
                    $candidate = $user->phone; // Fallback if country not found
                }
            }
            
            $verification = \App\Models\VerificationCode::where('candidate', $candidate)
                ->where('verification_code', $request->otp)
                ->latest()
                ->first();
            
            if ($verification) {
                // Update status to verified
                $verification->status = 'verified';
                $verification->save();
                $authenticated = true;
            } else {
                Log::debug("No verification found matching the criteria");
            }
        }
        
        if ($authenticated) {
            Auth::login($user);
            $request->session()->regenerate();

            // Check if user must reset password
            if ($user->must_reset_password) {
                return redirect()->route('password.must-reset');
            }
            
            // Check if coming from React login (routes are now at root level, not /v2)
            // React routes: /login, /register, /account, /clients, etc.
            // v1 Blade routes: /v1/login, /v1/account, etc.
            $intended = session()->get('url.intended');
            $referer = $request->headers->get('referer');
            
            // If there's an intended URL (e.g., OAuth authorization), redirect there
            // This is important for OAuth flows - after login, redirect back to authorization
            if ($intended) {
                // Check if it's an OAuth authorization request
                if (strpos($intended, 'oauth/authorize') !== false) {
                    session()->forget('url.intended');
                    return Inertia::location($intended);
                }
                
                // Check if intended URL is a React route (not /v1 or /admin)
                if (!str_starts_with($intended, '/v1') && !str_starts_with($intended, '/admin')) {
                    session()->forget('url.intended');
                    return Inertia::location($intended);
                }
            }
            
            // Check if login came from React route (root level, not /v1)
            $isReactRoute = false;
            if ($referer) {
                $refererPath = parse_url($referer, PHP_URL_PATH);
                // React routes are at root level and don't start with /v1
                $isReactRoute = $refererPath && 
                    !str_starts_with($refererPath, '/v1') && 
                    !str_starts_with($refererPath, '/admin') &&
                    (str_contains($refererPath, '/login') || str_contains($refererPath, '/register'));
            }
            
            if ($isReactRoute) {
                return redirect(route('v2.account'));
            }
            
            // Default: redirect to v1 account (Blade) or intended URL
            return Inertia::location(route('v2.account'));
        }

        // Authentication failed
        if ($authMethod == 'password') {
            return back()->withErrors([$authwith => 'These credentials didn\'t match our records']);
        } else {
            return back()->withErrors(['otp' => 'The provided verification code is invalid or has expired']);
        }
    }
}
