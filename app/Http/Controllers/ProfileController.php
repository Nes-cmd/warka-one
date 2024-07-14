<?php

namespace App\Http\Controllers;

use App\Enum\GenderEnum;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(): View
    {
        $user = User::with('userDetail.address')->where('id', auth()->id())->first();
        // dd( $user);
        return view('profile.index', [
            'user' => $user,
        ]);
    }
    public function edit(): View
    {
        $user = User::with('userDetail')->where('id', auth()->id())->first();
        
        $genders = GenderEnum::cases();


        return view('profile.update-profile', [
            'user' => $user,
            'genders' => $genders,
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $userDetail = UserDetail::where('user_id', $user->id)->first();

       
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('phone')) {
            $user->pone = trimPhone($request->phone);
            $user->phone_verified_at = null;
        }

        $user->save();

        $userDetail->gender = $request->gender;
        $userDetail->birth_date = $request->birth_date;
        $userDetail->save();
     
        return back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
