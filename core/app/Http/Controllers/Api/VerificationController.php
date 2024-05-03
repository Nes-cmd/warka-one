<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SendVerification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GetVerificationRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\Country;
use App\Models\VerificationCode;

class VerificationController extends Controller
{
    public function getVerificationCode(GetVerificationRequest $request)
    {
        $authwith = isPhoneOrEmail($request->phoneOrEmail);

        if ($authwith == 'invalid') {
            return response(['status' => 'fail', 'message' => 'The input neither recognized as phhone nor as email'], 423);
        }
        if($authwith == 'phone'){
            $request->merge([
                'phoneOrEmail' => trimPhone($request->phoneOrEmail)
            ]);
        }
        
        if ($request->otpIsFor == 'reset-password') {
            $request->validate([
                'phoneOrEmail' => 'exists:users,' . $authwith,
            ]);
        } elseif($request->otpIsFor == 'registration' || $request->otpIsFor == 'add-auth') {
            $request->validate([
                'phoneOrEmail' => 'unique:users,' . $authwith
            ]);
        }

        if ($authwith == 'phone') {
            $phone = $request->phoneOrEmail;
            $country = Country::find($request->country_id ? $request->country_id : 1);
            $receiver = $country->dial_code . trimPhone($phone);

            $via = "sms";
        } else {
            $receiver = $request->phoneOrEmail;
            $via = 'mail';
        }


        SendVerification::make()->via($via)->receiver($receiver)->send();
        return response([
            'status' => 'success',
            'message' => "Verification code sent, please use the otp sent to your {$authwith} to complete the rest of the process!"
        ]);
    }

    public function verifyCode(VerifyRequest $request)
    {
        $authwith = isPhoneOrEmail($request->phoneOrEmail);

        if ($authwith == 'invalid') {
            return response([
                'status' => 'fail', 
                'message' => 'The input neither recognized as phhone nor as email'
            ], 423);
        }

        $country = Country::find($request->country_id?$request->country_id:1);
        $candidate = $authwith == 'email' ? $request->phoneOrEmail : $country->dial_code . trimPhone($request->phoneOrEmail);
        $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
        if($verification) {
            if ($verification->verification_code == $request->verificationCode) {
                $verification->status = 'verified';
                $verification->save();

                return response([
                    'status' => 'success',
                    "message" => "Your {$authwith} verified successfully",
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
            ], 401);
        }
    }
}
