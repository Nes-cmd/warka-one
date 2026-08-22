<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as RulesPassword;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view for React/Inertia
     */
    public function createReact(Request $request)
    {
        $authflowData = session('authflow');
        
        if (!$authflowData || !isset($authflowData['authwith'])) {
            return redirect()->route('v2.authflow.get-otp', ['for' => 'reset-password']);
        }

        return Inertia::render('ResetPassword', [
            'authflowData' => $authflowData,
        ]);
    }

    /**
     * Handle an incoming new password request for React/Inertia
     */
    public function storeReact(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 
                RulesPassword::min(8)
                    ->max(18) // maximum 18 chars
                    ->letters() // must contain at least one letter
                    ->numbers() // must contain at least one number
                    ->mixedCase() // must contain at least one uppercase and one lowercase letter
                    ->symbols() // must contain at least one symbol
                    ->uncompromised(), // check against common/breached passwords
            ],
        ]);

        DB::beginTransaction();
        try {
            $authflowData = session('authflow');
            
            if (!$authflowData || !isset($authflowData['authwith'])) {
                return redirect()->route('v2.authflow.get-otp', ['for' => 'reset-password']);
            }
            
            $candidate = $authflowData['authwith'] == 'email'
                ? $authflowData['email']
                : $authflowData['country']->dial_code . $authflowData['phone'];

            $verification = VerificationCode::latestVerifiedForCandidate($candidate);
            
            if (!$verification || $verification->status != 'verified') {
                throw new \Exception('This email or phone is not verified');
            }
        
            $user = $authflowData['authwith'] == 'email'
                ? User::where('email', $candidate)->first()
                : User::where('phone', $authflowData['phone'])->first();

            if (!$user) {
                throw new \Exception('User not found');
            }

            $user->password = Hash::make($request->password);
            $user->must_reset_password = false; // Clear must_reset_password flag when user resets password voluntarily
            $user->save();
            
            $verification->delete();
            session()->forget('authflow');
            
            DB::commit();

            return redirect()->route('v2.login')->with('success', 'Password reset successfully. Please login with your new password.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['general' => $e->getMessage()]);
        }
    }
}
