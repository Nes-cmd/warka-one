<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SendVerification;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Inertia\Inertia;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        $authwith = 'phone';
        $countries = Country::all();
        $selectedCountry = Country::first();
        return view('auth.forgot-password', compact('authwith', 'countries', 'selectedCountry'));
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $authwith = $request->authwith;
        $country = null;
        if ($authwith == 'phone') {
            $request->validate([
                'phone' => 'required|exists:users,phone'
            ]);

            $country = Country::find($request->country_id);

            $fullPhone = $country->dial_code . $request->phone;

            $status = SendVerification::make()->via('sms')->receiver($fullPhone)->send();
        }
        if($authwith == 'email') {
            $request->validate([
                'email' => 'required|exists:users,email'
            ]);
            $status = SendVerification::make()->via('mail')->receiver($request->email)->send();
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

         session()->put('authflow', [
            'authwith' => $authwith, 
            'phone'    => $request->phone, 
            'email'    => $request->email,
            'country'  => $country,
        ]);
       
        return redirect()->route('verify-otp');

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset link request view for React/Inertia
     */
    public function createReact()
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
     * Handle an incoming password reset link request for React/Inertia
     */
    public function storeReact(Request $request)
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
