<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SmsSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePassword;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\PhoneRequest;
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
            if ($user->email_verified_at == null && $user->phone_verified_at == null) {
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
            $email = $request->email;
            $user = null;
            if($phone && $phone != "") $user = User::where('phone', $phone)->first();
            if($email && $email != "") $user = User::where('email', $email)->first();
           
            if ($user) throw new Exception('User already has account');
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone??null,
                'country_id' => $request->country_id??1,
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


            if ($request->inform && $request->inform != "") {
                $this->informAccountCreation($user, $request->password, $request->authwith);
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

    public function informAccountCreation(User $user, $password, $authwith)
    {
        $message = "Your account has been created successfully. You can access all services of kertech with this one account. Access your account with this $authwith and password is $password 
            Please don't share your password with anyone else.
            
            Thanks for choosing Ker Labs.
            ";

        if ($user->phone) {
            $country = Country::find($user->country_id);
            $receiver = $country->dial_code . $user->phone;

            SmsSend::send($receiver, $message);
        } else if ($user->email) {
            Notification::send($user, new UserNotification($message));
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $authwith = isPhoneOrEmail($request->phoneOrEmail);

        if ($authwith == 'invalid') {
            return response([
                'status' => 'fail',
                'message' => 'The input neither recognized as phhone nor as email'
            ], 423);
        }

        DB::beginTransaction();

        try {
            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $phone = trimPhone($request->phoneOrEmail);

            $country = Country::find($request->country_id ? $request->country_id : 1);
            $candidate = $authwith == 'email' ? $request->phoneOrEmail : $country->dial_code . $phone;

            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();
            if ($verification->status != 'verified') {
                throw new Exception("This {$authwith} was not verified");
            }

            $user = $authwith == 'email' ? User::where('email', $candidate)->first() : User::where('phone', $phone)->first();

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
    public function updatePdassword(ChangePassword $request)
    {
        if ($request->new_password !== $request->password_confirmation) {
            return response([
                "message" => "The password confirmation field confirmation does not match.",
                "errors" => [
                    "password_confirmation" => [
                        "The password confirmation field confirmation does not match."
                    ]
                ]
            ], 422);
        }

        $user = User::find($request->user_id);

        if ($user == null) {
            return response([
                'status' => 'fail',
                'message' => 'we didn\'t found the user on our sso server. please contact a support'
            ], 423);
        }
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response([
                'status' => 'success',
                'massage' => 'You have successfully changed your password, and you have loged out'
            ]);
        } else {
            return response([
                'status' => 'fail',
                'message' => 'Your old paddword is incorrect'
            ], 423);
        }
    }

    public function addPhoneNumber(PhoneRequest $request)
    {
        $user = User::find($request->user_id);

        if ($user && isPhoneOrEmail($request->phone == 'phone')) {

            $country = Country::find($request->country_id);
            $candidate = $country->dial_code . trimPhone($request->phone);
            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();

            if ($this->isVerified($verification, $request->verification_code)) {
                $user->phone = trimPhone($request->phone);
                $user->phone_verified_at = now();
                $user->save();

                $verification->delete();

                return response([
                    'status' => 'success',
                    'message' => 'phone updated to current user'
                ]);
            }
            return response([
                'status' => 'fail',
                'message' => 'Please verify first or send verification code along with phone number'
            ], 423);
        }
        return response([
            'status' => 'fail',
            'message' => 'Invalid input'
        ], 423);
    }

    public function addEmail(EmailRequest $request)
    {
        $user = User::find($request->user_id);

        if ($user && isPhoneOrEmail($request->email == 'email')) {
            $candidate = $request->email;
            $verification = VerificationCode::where('candidate', $candidate)->latest()->first();

            if ($this->isVerified($verification, $request->verification_code)) {
                $user->email = $request->email;
                $user->email_verified_at = now();
                $user->save();

                $verification->delete();

                return response([
                    'status' => 'success',
                    'message' => 'email updated to current user'
                ]);
            }
            return response([
                'status' => 'fail',
                'message' => 'Please verify first or send verification code along with the email'
            ], 423);
        }
        return response([
            'status' => 'fail',
            'message' => 'Invalid input'
        ], 423);
    }

    public function isVerified($verification, $code = null)
    {
        if ($verification?->status == 'verified') {
            return true;
        }
        if ($code != null && $verification?->verification_code == $code) {
            return true;
        }
        return false;
    }
}
