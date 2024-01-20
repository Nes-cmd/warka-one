<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SendVerivication;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GetVerificationRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\VerificationCode;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        if ($request->from != 'ker-wallet') {
            return response([
                'status' => 'fail',
                'message' => 'Please dont try to use this api directly from your app.',
            ], 423);
        }

        $authwith = $request->authwith;
        $request->validate(['password' => ['required', 'string']]);
        if ($authwith == 'phone') {
            $request->validate(['phone' => 'required']);
            $user = User::where('phone', $request->phone)->first();
        } else {
            $request->validate(['email' => 'required']);
            $user = User::where('email', $request->email)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('MySecret');

            return response()->json(['token' => $token, 'user' => $user]);
        }
        return response(['status' => 'fail', 'message' => 'These credientials didn\'t match our records'], 302);
    }

    public function getVerificationCode(GetVerificationRequest $request)
    {
        if ($request->authwith == 'phone') {
            $country = Country::find($request->country_id);
            $receiver = $country->dial_code . $request->phone;

            $via = "sms";
        } else {
            $receiver = $request->email;
            $via = 'mail';
        }

        SendVerivication::make()->via($via)->receiver($receiver)->send();
        return response([
            'status' => 'success',
            'message' => "Verification code sent, please use the otp sent to your {$request->authwith} to complete the rest of the process!"
        ]);
    }
    public function verifyCode(VerifyRequest $request)
    {

        $country = Country::find($request->country_id);
        $candidate = $request->authwith == 'email' ? $request->email : $country->dial_code . $request->phone;


        $verification = VerificationCode::where('candidate', $candidate)->latest()->first();


        if ($verification) {
            if ($verification->verification_code === $request->verificationCode) {
                $verification->status = 'verified';
                $verification->save();

                return response([
                    'status' => 'success',
                    "message" => "{$request->authwith} verified successfully",
                ]);
            }
            return response([
                'message' => 'Incorrect code!',
                'status' => 'error'
            ], 401);

        } else {
            return response([
                'message' => 'Code wasn\'t sent correctly or expierd, please try again!',
                'status' => 'error'
            ], 423);
        }
    }
    public function register(RegisterRequest $request)
    {

        DB::beginTransaction();

        try {
            $country = Country::find($request->country_id);
            $candidate = $request->authwith == 'email' ? $request->email : $country->dial_code . $request->phone;

            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {

                throw new Exception("This {$request->authwith} was not verified");
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => $request->country_id,
                'password' => Hash::make($request->password),
                'phone_verified_at' => $request->authwith == 'phone' ? now() : null,
                'email_verified_at' => $request->authwith == 'email' ? now() : null,
            ]);

            $verification->delete();

            DB::commit();

            return response([
                'status' => 'success',
                'message' => 'You have registered on sso server successfully!, Now you can use these credentials to access in one of our applications',
                'user' => $user,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request){
        
        DB::beginTransaction();
        try {
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            
            $country = Country::find($request->country_id);
            $candidate = $request->authwith == 'email'?$request->email: $country->dial_code . $request->phone;

            
            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {
                throw new Exception("This {$request->authwith} was not verified");
            }
        
            $user = $request->authwith == 'email'?User::where('email', $candidate)->first() : User::where('phone',$request->phone)->first();

            $user->password = Hash::make($request->password);
            $user->save();
            
            $verification->delete();
            

            DB::commit();

            return response([
                'status' => 'success',
                'message' => 'Password reseted successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            
            return response([
                'status' => "fail",
                "message" => "Password reseted failed. {$e->getMessage()}"
            ], 423);
        }
    }
}
