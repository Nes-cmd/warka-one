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
        if ($request->authwith == 'phone') {
            $country = Country::find($request->country_id);
            $receiver = $country->dial_code . trimPhone($request->phone);

            $via = "sms";
        } else {
            $receiver = $request->email;
            $via = 'mail';
        }

        SendVerification::make()->via($via)->receiver($receiver)->send();
        return response([
            'status' => 'success',
            'message' => "Verification code sent, please use the otp sent to your {$request->authwith} to complete the rest of the process!"
        ]);
    }
    
    public function verifyCode(VerifyRequest $request)
    {
        $country = Country::find($request->country_id);
        $candidate = $request->authwith == 'email' ? $request->email : $country->dial_code . trimPhone($request->phone);
        $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
        if ($verification) {
            if ($verification->verification_code == $request->verificationCode) {
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
            ], 401);
        }
    }
}
