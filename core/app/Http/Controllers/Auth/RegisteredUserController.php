<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $authflowData = session('authflow');
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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
}
