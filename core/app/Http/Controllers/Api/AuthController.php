<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SmsSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\UserNotification;
use Exception;
use Illuminate\Http\Request;
// use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if ($request->from != 'ker-wallet') {
            return response([
                'status' => 'fail',
                'message' => 'Please dont try to use this api directly from your app. It is designed for other purpose!',
            ], 423);
        }

        $authwith = $request->authwith;

        $phone = trimPhone($request->phone);

        $request->validate(['password' => ['required', 'string']]);
        if ($authwith == 'phone') {
            $request->validate(['phone' => 'required']);
            $user = User::where('phone', $phone)->first();
        } else {
            $request->validate(['email' => 'required']);
            $user = User::where('email', $request->email)->first();
        }
        if ($user) {
            if($user->email_verified_at == null && $user->phone_verified_at == null){
                return response([
                    'status' => 'fail',
                    'message' => 'Neither the phone or the email is not verified. please try to verify one or both of them first on the sso server'
                ], 401);
            }


            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken('MySecret');

                return response()->json(['token' => $token, 'user' => $user]);
            }
        }
        return response(['status' => 'fail', 'message' => 'These credientials didn\'t match our records'], 401);
    }


    public function register(RegisterRequest $request)
    {
       
        DB::beginTransaction();

        try {
            $phone = trimPhone($request->phone);
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
                'country_id' => $request->country_id,
                'password' => Hash::make($request->password),
            ];

            $country = Country::find($request->country_id);
            $candidate = $request->authwith == 'email' ? $request->email : $country->dial_code . $phone;

            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();

            if ($verification && $verification->status == 'verified') {
                $userData['phone_verified_at'] = $request->authwith == 'phone' ? now() : null;
                $userData['email_verified_at'] = $request->authwith == 'email' ? now() : null;
                $verification->delete();
            }

            // if($verification == null){
            //     throw new Exception("Please verify your {$request->authwith} first");
            // }
            // if ($verification->status != 'verified') {
            //     throw new Exception("This {$request->authwith} was not verified");
            // }

            $user = User::create($userData);

            $tokens = $user->createToken('API-TOKEN');
           

            if($request->inform){
                $this->informAccountCreation($user, $request->password);
            } 
            
            DB::commit();

            return response([
                'status' => 'success',
                'message' => 'You have registered on sso server successfully!, Now you can use these credentials to access in one of our applications',
                'user' => $user,
                'tokens' => $tokens,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 423);
        }
    }

    public function informAccountCreation(User $user, $password){
        $message = __("Your account has been created successfully. You can access all services of kertech with this account. Access your account with this phone number and password is $password
            
            Thanks for choosing Ker Technology.
            ");

        if($user->phone){
            $country = Country::find($user->country_id);
            $receiver = $country->dial_code . $user->phone;
            
            SmsSend::send($receiver, $message);
        }
        else if($user->email){
            Notification::send($user, new UserNotification($message));
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        DB::beginTransaction();
        try {
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $phone = trimPhone($request->phone);

            $country = Country::find($request->country_id);
            $candidate = $request->authwith == 'email' ? $request->email : $country->dial_code . $phone;


            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {
                throw new Exception("This {$request->authwith} was not verified");
            }

            $user = $request->authwith == 'email' ? User::where('email', $candidate)->first() : User::where('phone', $phone)->first();

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

    // public function changePassword(Request $request){
    //     $user = User::find($request->id);
    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     return response([
    //         'status' => 'success',
    //         'massage' => 'You have successfully changed your password, and you have loged out'
    //     ]);
    // }
}
