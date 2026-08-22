<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SendVerification;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
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
        if($clientId && $client = \App\Models\Passport\Client::find($clientId)){
            $options = $client->use_auth_types ?? $options;
        }

        $countries = Country::all();
        $selectedCountry = Country::first();

        return Inertia::render('ForgotPassword', [
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'options' => $options,
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request)
    {
        $authwith = $request->authwith;
        $country = null;
        
        if ($authwith == 'phone') {
            $request->validate([
                'phone' => 'required|exists:users,phone',
                'country_id' => 'required|exists:countries,id',
            ]);

            $country = Country::find($request->country_id);
            $phone = \trimPhone($request->phone);
            $fullPhone = $country->dial_code . $phone;

            $status = SendVerification::make()->via('sms')->receiver($fullPhone)->send();
        } else {
            $request->validate([
                'email' => 'required|exists:users,email'
            ]);
            $status = SendVerification::make()->via('mail')->receiver($request->email)->send();
        }

        if ($status) {
            session()->put('authflow', [
                'authwith' => $authwith, 
                'phone' => $authwith == 'phone' ? \trimPhone($request->phone) : null, 
                'email' => $authwith == 'email' ? $request->email : null,
                'country' => $country,
                'country_id' => $country ? $country->id : null,
                'otpIsFor' => 'reset-password',
            ]);
       
            return redirect()->route('v2.authflow.verify');
        }

        return back()->withErrors(['general' => 'Failed to send verification code. Please try again.']);
    }
}
