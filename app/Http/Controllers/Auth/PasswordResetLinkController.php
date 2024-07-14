<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SendVerification;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

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
}
