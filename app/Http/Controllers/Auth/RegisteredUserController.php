<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\VerificationCode;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $authflowData = session('authflow');
        if($authflowData == null ){
            return redirect('authflow/get-otp?for=register');
        }
        if($authflowData['authwith']?? null == null){
            return redirect('authflow/get-otp?for=register');
        }
       

        return view('auth.register', compact('authflowData'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $authflowData = session('authflow');
       
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(6)->letters()->numbers()->uncompromised(10)],
        ]);
       
        DB::beginTransaction();

        try {

            $candidate = $authflowData['authwith'] == 'email'?$authflowData['email']: $authflowData['country']->dial_code . $authflowData['phone'];

            
            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {
                throw new Exception('This email or phone is not verified');
            }
            
            $user = User::create([
                'name'              => $request->name,
                'email'             => $authflowData['email'],
                'phone'             => $authflowData['phone'],
                'country_id'        => $authflowData['country']->id,
                'password'          => Hash::make($request->password),
                'phone_verified_at' => $authflowData['authwith'] == 'phone'?now():null,
                'email_verified_at' => $authflowData['authwith'] == 'email'?now():null,
            ]);

            UserDetail::create([
                'user_id' => $user->id,
            ]);

            $verification->delete();

            event(new Registered($user));
            Auth::login($user);
            
            DB::commit();
            session()->forget('authflow');

            return redirect()->intended(RouteServiceProvider::HOME);

    } catch (Exception $e) {
        DB::rollBack();
        session()->flash('authstatus',['type' => 'error','message'=> $e->getMessage()]);
        return back();
    }
        
    }

    /**
     * Display the registration view for React/Inertia
     */
    public function createReact()
    {
        $authflowData = session('authflow');
        
        if ($authflowData == null) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }
        
        if (($authflowData['authwith'] ?? null) == null) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }

        // Match Blade version - just check if authflowData exists
        // Verification status is checked in storeReact() method
        return Inertia::render('Register', [
            'authflowData' => $authflowData,
        ]);
    }

    /**
     * Handle an incoming registration request for React/Inertia
     */
    public function storeReact(Request $request)
    {
        $authflowData = session('authflow');
       
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'register']);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(6)->letters()->numbers()->uncompromised(10)],
        ]);
       
        DB::beginTransaction();

        try {
            // Handle country - it might be an object, array, or need to be reloaded
            $country = $authflowData['country'] ?? null;
            $countryId = null;
            
            if (is_object($country)) {
                $countryId = $country->id ?? null;
            } elseif (is_array($country)) {
                $countryId = $country['id'] ?? null;
            }
            
            // If we don't have country ID, try to get it from country_id in session
            if (!$countryId && isset($authflowData['country_id'])) {
                $countryId = $authflowData['country_id'];
            }
            
            // If still no country ID, try to reload from database
            if (!$countryId) {
                // Try to get dial_code to find country
                $dialCode = null;
                if (is_object($country)) {
                    $dialCode = $country->dial_code ?? null;
                } elseif (is_array($country)) {
                    $dialCode = $country['dial_code'] ?? null;
                }
                
                if ($dialCode) {
                    $countryModel = \App\Models\Country::where('dial_code', $dialCode)->first();
                    $countryId = $countryModel ? $countryModel->id : null;
                }
            }
            
            if (!$countryId) {
                throw new Exception('Country information is missing. Please start registration again.');
            }
            
            // Build candidate for verification check
            $candidate = null;
            if ($authflowData['authwith'] == 'email') {
                $candidate = $authflowData['email'];
            } else {
                // Get dial code
                $dialCode = null;
                if ($countryId) {
                    $countryModel = \App\Models\Country::find($countryId);
                    $dialCode = $countryModel ? $countryModel->dial_code : null;
                }
                
                if (!$dialCode && $country) {
                    if (is_object($country)) {
                        $dialCode = $country->dial_code ?? null;
                    } elseif (is_array($country)) {
                        $dialCode = $country['dial_code'] ?? null;
                    }
                }
                
                if (!$dialCode) {
                    $dialCode = '+251'; // Default fallback
                }
                
                $candidate = $dialCode . $authflowData['phone'];
            }

            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            
            if (!$verification || $verification->status != 'verified') {
                throw new Exception('This email or phone is not verified. Please verify your code first.');
            }
            
            $user = User::create([
                'name'              => $request->name,
                'email'             => $authflowData['email'] ?? null,
                'phone'             => $authflowData['phone'] ?? null,
                'country_id'        => $countryId,
                'password'          => Hash::make($request->password),
                'phone_verified_at' => $authflowData['authwith'] == 'phone' ? now() : null,
                'email_verified_at' => $authflowData['authwith'] == 'email' ? now() : null,
            ]);

            UserDetail::create([
                'user_id' => $user->id,
            ]);

            $verification->delete();

            event(new Registered($user));
            Auth::login($user);
            
            DB::commit();
            session()->forget('authflow');

            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['general' => $e->getMessage()]);
        }
    }
}
