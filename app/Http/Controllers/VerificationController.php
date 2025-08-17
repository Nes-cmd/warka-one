<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}

