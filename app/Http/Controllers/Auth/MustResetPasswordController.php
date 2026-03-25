<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\View\View;
use Inertia\Inertia;

class MustResetPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(): View
    {
        return view('auth.must-reset-password');
    }

    /**
     * Display the password reset view for React/Inertia.
     */
    public function createReact(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('v2.login');
        }

        return Inertia::render('MustResetPassword', [
            'status' => session('status'),
            'success' => session('success'),
            'error' => session('error'),
        ]);
    }

    /**
     * Handle an incoming password reset request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required', 
                'confirmed', 
                RulesPassword::min(6)->letters()->numbers()->uncompromised(10),
                function ($attribute, $value, $fail) use ($user) {
                    // Check if new password is different from current password
                    if (Hash::check($value, $user->password)) {
                        $fail('The new password must be different from your current password.');
                    }
                },
            ],
        ]);

        // Update password and set must_reset_password to false
        $user->password = Hash::make($request->password);
        $user->must_reset_password = false;
        $user->save();

        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('status', 'password-reset-success');
    }

    /**
     * Handle an incoming password reset request for React/Inertia.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeReact(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('v2.login');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                RulesPassword::min(6)->letters()->numbers()->uncompromised(10),
                function ($attribute, $value, $fail) use ($user) {
                    if (Hash::check($value, $user->password)) {
                        $fail('The new password must be different from your current password.');
                    }
                },
            ],
        ]);

        $user->password = Hash::make($request->password);
        $user->must_reset_password = false;
        $user->save();

        return redirect()->intended(route('v2.account'))
            ->with('status', 'password-reset-success');
    }
}
