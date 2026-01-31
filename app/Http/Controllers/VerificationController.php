<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Passport\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class VerificationController extends Controller
{
    public function index(Request $request)  {
        $request->validate([
            'for' => 'required|in:register,reset-password'
        ]);
        $otpIsFor = $request->for;

        return view('auth.get-otp', compact('otpIsFor'));
    }

    public function verifyView()  {
        return view('auth.verify-otp');
    }

    public function mustVerify(Request $request){
        
        $user = auth()->user();

        $intendedFallback = $request->fallback;
        if($user->phone && $user->phone_verified_at && $request->verify == 'phone'){
            return $intendedFallback? redirect($intendedFallback. '?hash='.$user->id):back();
        }

        if($user->email && $user->email_verified_at && $request->verify == 'email'){
            return $intendedFallback? redirect($intendedFallback. '?hash='.$user->id):back();
        }
        
        $verifyData = [
            'authwith' => $request->verify,
            'email'    => $user->email,
            'otpIsFor' => 'must-verify',
            'phone'    => $user->phone,
            'country'  => Country::find($user->country_id),
            'fallback' => $intendedFallback
        ];

        session()->put('authflow', $verifyData);

        return view('auth.verify-otp');
    }

    /**
     * Display the must-verify OTP view for React/Inertia
     */
    public function mustVerifyReact(Request $request)
    {
        $user = auth()->user();

        $intendedFallback = $request->fallback;
        
        // Check if already verified
        if($user->phone && $user->phone_verified_at && $request->verify == 'phone'){
            return $intendedFallback ? redirect($intendedFallback . '?hash=' . $user->id) : back();
        }

        if($user->email && $user->email_verified_at && $request->verify == 'email'){
            return $intendedFallback ? redirect($intendedFallback . '?hash=' . $user->id) : back();
        }
        
        // Get country data
        $countryModel = Country::find($user->country_id);
        $country = $countryModel ? [
            'id' => $countryModel->id,
            'name' => $countryModel->name,
            'dial_code' => $countryModel->dial_code,
            'country_code' => $countryModel->country_code,
            'phone_length' => $countryModel->phone_length,
            'flag_url' => $countryModel->flag_url ? (str_starts_with($countryModel->flag_url, 'http') ? $countryModel->flag_url : asset($countryModel->flag_url)) : asset('flags/et.svg'),
        ] : null;
        
        $verifyData = [
            'authwith' => $request->verify,
            'email'    => $user->email,
            'otpIsFor' => 'must-verify',
            'phone'    => $user->phone,
            'country'  => $country,
            'country_id' => $user->country_id,
            'fallback' => $intendedFallback
        ];

        session()->put('authflow', $verifyData);
        
        // Auto-send OTP for must-verify
        try {
            $authwith = $request->verify;
            if ($authwith == 'phone' && $countryModel) {
                $fullPhone = $countryModel->dial_code . $user->phone;
                \App\Helpers\SendVerification::make()->via('sms')->receiver($fullPhone)->send();
            } else if ($authwith == 'email') {
                \App\Helpers\SendVerification::make()->via('mail')->receiver($user->email)->send();
            }
        } catch (\Exception $e) {
            Log::error("Failed to auto-send OTP for must-verify: " . $e->getMessage());
        }

        $resendin = 90; // Default countdown

        return Inertia::render('VerifyOtp', [
            'authwith' => $request->verify,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $country,
            'verificationFor' => 'must-verify',
            'resendin' => $resendin,
        ]);
    }

    /**
     * Show phone verification page for profile update
     */
    public function verifyPhone()
    {
        // Get verification data from session
        $verifyData = session('authflow');
        
        // If no verification data or not for phone verification, redirect to profile
        if (!$verifyData || $verifyData['otpIsFor'] !== 'phone-verify') {
            return redirect()->route('profile.update-profile');
        }
        
        return view('auth.verify-phone', [
            'phone' => $verifyData['phone']
        ]);
    }

    /**
     * Process phone verification
     */
    public function processVerifyPhone(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric', 'digits:6'],
        ]);
        
        $user = auth()->user();
        $verifyData = session('authflow');
        
        if (!$verifyData || $verifyData['otpIsFor'] !== 'phone-verify') {
            return redirect()->route('profile.update-profile');
        }
        
        // Find valid verification code
        $verificationCode = \App\Models\VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('for', 'phone-verify')
            ->where('expire_at', '>', now())
            ->first();
        
        if (!$verificationCode) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }
        
        // Mark phone as verified
        $user->phone_verified_at = now();
        $user->save();
        
        // Delete used verification codes
        \App\Models\VerificationCode::where('user_id', $user->id)
            ->where('for', 'phone-verify')
            ->delete();
        
        // Clear verification session data
        session()->forget('authflow');
        
        return redirect()->route('profile.update-profile')
            ->with('status', 'Your phone number has been verified successfully.');
    }

    /**
     * Resend phone verification code
     */
    public function resendPhoneVerification()
    {
        $user = auth()->user();
        $verifyData = session('authflow');
        
        if (!$verifyData || $verifyData['otpIsFor'] !== 'phone-verify') {
            return redirect()->route('profile.update-profile');
        }
        
        try {
            // Delete existing verification codes
            \App\Models\VerificationCode::where('user_id', $user->id)
                ->where('for', 'phone-verify')
                ->delete();
            
            // Generate new verification code
            $verificationCode = rand(100000, 999999);
            
            // Create a new verification code record
            \App\Models\VerificationCode::create([
                'user_id' => $user->id,
                'code' => $verificationCode,
                'for' => 'phone-verify',
                'expire_at' => now()->addMinutes(10),
            ]);
            
            // Send the verification code via SMS
            \App\Helpers\SmsSend::send($verifyData['phone'], "Your verification code is: $verificationCode");
            
            return back()->with('status', 'A new verification code has been sent to your phone.');
        } catch (\Exception $e) {
            Log::error("Failed to resend verification SMS: " . $e->getMessage());
            return back()->withErrors(['code' => 'Unable to send verification code. Please try again.']);
        }
    }

    /**
     * Initiate phone verification for an existing unverified phone
     */
    public function initiatePhoneVerification(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has an unverified phone
        if (!$user->phone || $user->phone_verified_at) {
            return redirect()->route('profile.update-profile')
                ->with('status', 'Your phone is already verified or not set.');
        }
        
        // Set up verification data in session
        $verifyData = [
            'authwith' => 'phone',
            'phone'    => $user->phone,
            'otpIsFor' => 'phone-verify',
            'country'  => Country::find($user->country_id),
        ];

        session()->put('authflow', $verifyData);
        
        // Generate and send verification code
        try {
            // Generate a random verification code
            $verificationCode = rand(100000, 999999);
            
            // Delete any existing verification codes
            \App\Models\VerificationCode::where('user_id', $user->id)
                ->where('for', 'phone-verify')
                ->delete();
            
            // Create a new verification code record
            \App\Models\VerificationCode::create([
                'user_id' => $user->id,
                'code' => $verificationCode,
                'for' => 'phone-verify',
                'expire_at' => now()->addMinutes(5),
            ]);
            
            // Send the verification code via SMS
            \App\Helpers\SmsSend::send($user->phone, "Your verification code is: $verificationCode");
            
            return redirect()->route('verify-phone')
                ->with('status', 'We sent a verification code to your phone.');
        } catch (\Exception $e) {
            Log::error("Failed to send verification SMS: " . $e->getMessage());
            return back()->withErrors(['phone' => 'Unable to send verification code. Please try again.']);
        }
    }

    /**
     * Display the get OTP view for React/Inertia
     */
    public function indexReact(Request $request)
    {
        $request->validate([
            'for' => 'required|in:register,reset-password'
        ]);
        $otpIsFor = $request->for;

        $intended = session()->get('url.intended');
        $clientId = null;
        if ($intended && strpos($intended, 'oauth/authorize') !== false) {
            $queryParams = [];
            parse_str(parse_url($intended, PHP_URL_QUERY), $queryParams);
            $clientId = $queryParams['client_id'] ?? null;
        }
        
        $client = null;
        $options = ['email', 'phone'];
        if($clientId && $client = Client::find($clientId)){
            $options = $client->use_auth_types ?? $options;
        }

        // Fetch countries from database and format for Inertia
        $countries = Country::all()->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'dial_code' => $country->dial_code,
                'country_code' => $country->country_code,
                'phone_length' => $country->phone_length,
                'flag_url' => $country->flag_url ? (str_starts_with($country->flag_url, 'http') ? $country->flag_url : asset($country->flag_url)) : asset('flags/et.svg'),
            ];
        });
        
        $selectedCountryModel = Country::first();
        $selectedCountry = $selectedCountryModel ? [
            'id' => $selectedCountryModel->id,
            'name' => $selectedCountryModel->name,
            'dial_code' => $selectedCountryModel->dial_code,
            'country_code' => $selectedCountryModel->country_code,
            'phone_length' => $selectedCountryModel->phone_length,
            'flag_url' => $selectedCountryModel->flag_url ? (str_starts_with($selectedCountryModel->flag_url, 'http') ? $selectedCountryModel->flag_url : asset($selectedCountryModel->flag_url)) : asset('flags/et.svg'),
        ] : null;

        return Inertia::render('GetOtp', [
            'otpIsFor' => $otpIsFor,
            'options' => $options,
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
        ]);
    }

    /**
     * Handle OTP request for React/Inertia
     */
    public function getOtpReact(Request $request)
    {
        // Get 'for' from query string or request body
        $otpIsFor = $request->input('for') ?? $request->query('for');
        
        if (!$otpIsFor) {
            return back()->withErrors(['for' => 'The purpose field is required.']);
        }
        
        if (!in_array($otpIsFor, ['register', 'reset-password'])) {
            return back()->withErrors(['for' => 'Invalid purpose.']);
        }
        
        $authwith = $request->authwith;

        if ($authwith == 'phone') {
            $request->validate([
                'phone' => ['required', 'min:9', 'max:9', $otpIsFor == 'register' ? 'unique:users,phone' : 'exists:users,phone'],
                'country_id' => 'required|exists:countries,id',
            ]);
            
            $country = Country::find($request->country_id);
            $phone = \trimPhone($request->phone);
            $fullPhone = $country->dial_code . $phone;
            
            $status = \App\Helpers\SendVerification::make()->via('sms')->receiver($fullPhone)->send();
        } else {
            $request->validate([
                'email' => ['required', 'email', $otpIsFor == 'register' ? 'unique:users,email' : 'exists:users,email']
            ]);
           
            $status = \App\Helpers\SendVerification::make()->via('mail')->receiver($request->email)->send();
        }
       
        if ($status) {
            $country = $authwith == 'phone' ? Country::find($request->country_id) : null;
            
            session()->put('authflow', [
                'authwith' => $authwith,
                'phone' => $authwith == 'phone' ? \trimPhone($request->phone) : null,
                'email' => $authwith == 'email' ? $request->email : null,
                'country' => $country,
                'country_id' => $country ? $country->id : null,
                'otpIsFor' => $otpIsFor,
            ]);

            return redirect()->route('v2.authflow.verify');
        }

        return back()->withErrors(['general' => 'Failed to send verification code. Please try again.']);
    }

    /**
     * Display the verify OTP view for React/Inertia
     */
    public function verifyViewReact()
    {
        $authflowData = session('authflow');
       
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }

        $resendin = 90; // Default countdown

        return Inertia::render('VerifyOtp', [
            'authwith' => $authflowData['authwith'],
            'email' => $authflowData['email'] ?? null,
            'phone' => $authflowData['phone'] ?? null,
            'country' => $authflowData['country'] ?? null,
            'verificationFor' => $authflowData['otpIsFor'] ?? 'register',
            'resendin' => $resendin,
        ]);
    }

    /**
     * Handle OTP verification for React/Inertia
     */
    public function verifyReact(Request $request)
    {
        $authflowData = session('authflow');
       
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }

        $request->validate([
            'verificationCode' => 'required|numeric|digits:6'
        ]);

        // Build candidate for verification check
        $candidate = null;
        if ($authflowData['authwith'] == 'email') {
            $candidate = $authflowData['email'];
        } else {
            // Handle country - it might be an object, array, or need to be reloaded
            $country = $authflowData['country'] ?? null;
            $dialCode = null;
            
            if (is_object($country)) {
                $dialCode = $country->dial_code ?? null;
            } elseif (is_array($country)) {
                $dialCode = $country['dial_code'] ?? null;
            }
            
            // If we don't have dial code, try to get it from country_id
            if (!$dialCode && isset($authflowData['country_id'])) {
                $countryModel = \App\Models\Country::find($authflowData['country_id']);
                $dialCode = $countryModel ? $countryModel->dial_code : null;
            }
            
            if (!$dialCode) {
                $dialCode = '+251'; // Default fallback
            }
            
            $candidate = $dialCode . ($authflowData['phone'] ?? '');
        }

        $verification = \App\Models\VerificationCode::where('candidate', $candidate)
            ->where('expire_at', '>=', now())
            ->latest()
            ->first();

        if ($verification) {
            if ($verification->verification_code == $request->verificationCode) {
                $verification->status = 'verified';
                $verification->save();

                $verificationFor = $authflowData['otpIsFor'] ?? 'register';

                if ($verificationFor == 'must-verify' && auth()->check()) {
                    $user = auth()->user();
                    $verifyColumn = "{$authflowData['authwith']}_verified_at";
                    $user->{$verifyColumn} = now();
                    $user->save();
                    $verification->delete();

                    $intendedFallback = $authflowData['fallback'] ?? null;
                    session()->forget('authflow');

                    if ($intendedFallback) {
                        return redirect($intendedFallback . '?hash=' . $user->id);
                    }

                    return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
                } else if ($verificationFor == 'reset-password') {
                    // Keep session for password reset
                    session()->put('authflow', $authflowData);
                    return redirect()->route('v2.password.reset');
                } else if ($verificationFor == 'register') {
                    // For register, keep session and redirect to register page
                    // Don't forget authflow session - it's needed for registration
                    // Ensure session is maintained - explicitly put it back and save
                    session()->put('authflow', $authflowData);
                    session()->save(); // Explicitly save session
                    return redirect()->route('v2.register');
                }
                
                // Default fallback to register
                session()->put('authflow', $authflowData);
                session()->save(); // Explicitly save session
                return redirect()->route('v2.register');
            }
            
            return back()->withErrors(['verificationCode' => 'Incorrect code!']);
        } else {
            return back()->withErrors(['verificationCode' => 'Code wasn\'t sent correctly or expired, please try again!']);
        }
    }

    /**
     * Handle OTP resend for React/Inertia
     */
    public function resendOtpReact(Request $request)
    {
        $authflowData = session('authflow');
       
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return response()->json(['success' => false, 'message' => 'No verification session found']);
        }

        $authwith = $request->authwith ?? $authflowData['authwith'];

        try {
            if ($authwith == 'phone') {
                // Handle country - it might be an object, array, or need to be reloaded
                $country = $authflowData['country'] ?? null;
                $dialCode = null;
                
                if (is_object($country)) {
                    $dialCode = $country->dial_code ?? null;
                } elseif (is_array($country)) {
                    $dialCode = $country['dial_code'] ?? null;
                }
                
                // If we still don't have dial code, try to get country from database
                if (!$dialCode) {
                    // Try to get country ID from the country object/array
                    $countryId = null;
                    if (is_object($country) && isset($country->id)) {
                        $countryId = $country->id;
                    } elseif (is_array($country) && isset($country['id'])) {
                        $countryId = $country['id'];
                    } elseif (isset($authflowData['country_id'])) {
                        $countryId = $authflowData['country_id'];
                    }
                    
                    if ($countryId) {
                        $countryModel = \App\Models\Country::find($countryId);
                        $dialCode = $countryModel ? $countryModel->dial_code : '+251';
                    } else {
                        $dialCode = '+251'; // Default fallback
                    }
                }
                
                $phone = $authflowData['phone'] ?? '';
                if (empty($phone)) {
                    return response()->json(['success' => false, 'message' => 'Phone number not found in session']);
                }
                
                $fullPhone = $dialCode . $phone;
                $result = \App\Helpers\SendVerification::make()->via('sms')->receiver($fullPhone)->send();
                
                // SendVerification returns VerificationCode model on success, false on failure
                if ($result === false) {
                    return response()->json(['success' => false, 'message' => 'Failed to send SMS. Please try again.']);
                }
            } else {
                $email = $authflowData['email'] ?? '';
                if (empty($email)) {
                    return response()->json(['success' => false, 'message' => 'Email not found in session']);
                }
                
                $result = \App\Helpers\SendVerification::make()->via('mail')->receiver($email)->send();
                
                // SendVerification returns VerificationCode model on success, false on failure
                if ($result === false) {
                    return response()->json(['success' => false, 'message' => 'Failed to send email. Please try again.']);
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("Failed to resend OTP: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to resend verification code: ' . $e->getMessage()]);
        }
    }
}

