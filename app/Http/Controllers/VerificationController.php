<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Passport\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class VerificationController extends Controller
{
    /**
     * Display the must-verify OTP view.
     */
    public function mustVerifyCapture(Request $request)
    {
        $request->validate([
            'verify' => 'required|in:phone,email',
            'fallback' => 'nullable|string',
        ]);

        session()->put('authflow', [
            'fallback' => $request->fallback,
            'verify' => $request->verify,
            'authwith' => $request->verify,
        ]);

        if (! auth()->check()) {
            return redirect()->route('v2.login');
        }

        return redirect()->route('v2.must-verify-otp', ['verify' => $request->verify]);
    }

    public function mustVerify(Request $request)
    {
        $user = auth()->user();

        $intendedFallback = session('authflow')['fallback'] ?? $request->fallback;
        $verify = session('authflow')['verify'] ?? $request->verify;

        if ($user->phone && $user->phone_verified_at && $verify == 'phone') {
            return $this->redirectAfterMustVerify($user, $intendedFallback);
        }

        if ($user->email && $user->email_verified_at && $verify == 'email') {
            return $this->redirectAfterMustVerify($user, $intendedFallback);
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
            'authwith' => $verify,
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
            $authwith = $verify;
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
            'authwith' => $verify,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => $country,
            'verificationFor' => 'must-verify',
            'resendin' => $resendin,
        ]);
    }

    /**
     * Display the get OTP view.
     */
    public function index(Request $request)
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

        $countries = Country::all()->map->toFrontendArray();
        $selectedCountryModel = Country::first();
        $selectedCountry = $selectedCountryModel?->toFrontendArray();

        return Inertia::render('GetOtp', [
            'otpIsFor' => $otpIsFor,
            'options' => $options,
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
        ]);
    }

    /**
     * Handle OTP request.
     */
    public function getOtp(Request $request)
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
     * Display the verify OTP view.
     */
    public function verifyView()
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
     * Handle OTP verification.
     */
    public function verify(Request $request)
    {
        $authflowData = session('authflow');
       
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }

        $verificationFor = $authflowData['otpIsFor'] ?? 'register';

        if ($verificationFor === 'must-verify' && auth()->check()) {
            $user = auth()->user();
            $authwith = $authflowData['authwith'];
            $verifyColumn = "{$authwith}_verified_at";

            if ($user->{$verifyColumn} != null) {
                return $this->redirectAfterMustVerify($user, $authflowData['fallback'] ?? null);
            }
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

        $verification = \App\Models\VerificationCode::latestActiveForCandidate($candidate);

        if ($verification) {
            if ($verification->verification_code == $request->verificationCode) {
                $verification->status = 'verified';
                $verification->save();

                if ($verificationFor == 'must-verify' && auth()->check()) {
                    $user = auth()->user();
                    $verifyColumn = "{$authflowData['authwith']}_verified_at";
                    $user->{$verifyColumn} = now();
                    $user->save();
                    $verification->delete();

                    return $this->redirectAfterMustVerify($user, $authflowData['fallback'] ?? null);
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
     * Handle OTP resend.
     */
    public function resendOtp(Request $request)
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

    /**
     * Redirect after must-verify OTP. Inertia POST cannot follow redirect()->away() —
     * external / full-URL targets need Inertia::location() for a real browser navigation.
     */
    private function redirectAfterMustVerify($user, ?string $returnTo)
    {
        session()->forget('authflow');
        session()->forget('url.intended');

        if (!$returnTo) {
            return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
        }

        if (str_contains($returnTo, 'oauth/authorize')) {
            return Inertia::location($returnTo);
        }

        $separator = str_contains($returnTo, '?') ? '&' : '?';
        $target = $returnTo . $separator . 'hash=' . $user->id;

        if (str_starts_with($returnTo, 'http://') || str_starts_with($returnTo, 'https://')) {
            return Inertia::location($target);
        }

        return redirect($target);
    }
}

