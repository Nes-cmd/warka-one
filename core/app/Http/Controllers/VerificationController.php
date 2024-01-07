<?php

namespace App\Http\Controllers;

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
}

