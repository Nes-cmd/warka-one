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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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

            $authColumn = "{$authwith}_verified_at";
            
            if ($user->{$authColumn} == null) {
                return redirect()->route('must-verify-otp');
            }
            
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

        return redirect('/');
    }
}
