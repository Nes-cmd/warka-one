<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as RulesPassword;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // 'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', RulesPassword::min(6)->letters()->numbers()->uncompromised(10)],
        ]);

        DB::beginTransaction();
        try {
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $authflowData = session('authflow');
            $candidate = $authflowData['authwith'] == 'email'?$authflowData['email']: $authflowData['country']->dial_code . $authflowData['phone'];

            
            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {
                session()->flash('authstatus',['type' => 'error','message'=> 'This email or phone is not verified']);
                return redirect('login');
            }
        
            $user = $authflowData['authwith'] == 'email'?User::where('email', $candidate)->first() : User::where('phone',$authflowData['phone'])->first();

            $user->password = Hash::make($request->password);
            $user->must_reset_password = false; // Clear must_reset_password flag when user resets password voluntarily
            $user->save();
            
            $verification->delete();
            session()->forget('authflow');
            session()->flash('authstatus', ['type'=> 'success','message'=> 'Password reseted successfully']);

            DB::commit();

            return redirect('login');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('authstatus', ['type'=> 'error','message'=> 'Password reseted failed']);
            return redirect('login');
        }
    }

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
            'password' => ['required', 'confirmed', RulesPassword::min(6)->letters()->numbers()->uncompromised(10)],
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

            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            
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
        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user) use ($request) {
        //         $user->forceFill([
        //             'password' => Hash::make($request->password),
        //             'remember_token' => Str::random(60),
        //         ])->save();

        //         event(new PasswordReset($user));
        //     }
        // );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        // return $status == Password::PASSWORD_RESET
        //             ? redirect()->route('login')->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }
}
