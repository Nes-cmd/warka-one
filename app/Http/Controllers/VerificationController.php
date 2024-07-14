<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

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

    public function mustVerify(){
        
        $user = auth()->user();
        $verifyData = [
            'authwith' => $user->email?'email':'phone',
            'email'    => $user->email,
            'otpIsFor' => 'must-verify',
            'phone'    => $user->phone,
            'country'  => Country::find($user->country_id),
        ];

        session()->put('authflow', $verifyData);

        return view('auth.verify-otp');
    }
}

