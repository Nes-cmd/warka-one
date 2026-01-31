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
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        // Get current user with details
        $user = User::with('userDetail', 'country')->where('id', auth()->id())->first();

        // Create at least one session entry for the current session
        $currentSession = [
            'id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_active' => now()->toDateTimeString(),
        ];

        // Parse user agent (temporary solution without Agent package)
        $userAgent = $currentSession['user_agent'];

        // Simple user agent parsing
        $browser = 'Unknown Browser';
        $platform = 'Unknown Platform';

        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        }

        if (strpos($userAgent, 'Windows') !== false) {
            $platform = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $platform = 'Mac';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $platform = 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $platform = 'iPhone';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            $platform = 'iPad';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $platform = 'Android';
        }

        $sessions = collect([$currentSession])->map(function ($session) use ($request, $browser, $platform) {
            return [
                'id' => $session['id'],
                'ip_address' => $session['ip_address'],
                'is_current_device' => $session['id'] === $request->session()->getId(),
                'browser' => $browser,
                'platform' => $platform,
                'last_active' => $session['last_active'],
                'location' => 'Unknown', // You could integrate with a geolocation API here
                'user_agent' => $session['user_agent'],
            ];
        });

        // Store in session for view
        $request->session()->put('sessions', $sessions);

        // Get unique authorized applications
        $uniqueTokens = $request->user()->tokens()
            ->with('client')
            ->get()
            ->groupBy('client_id')
            ->map(function ($clientTokens) {
                // Return the most recently used token for each client
                return $clientTokens->sortByDesc('last_used_at')->first();
            })
            ->values();

        // Paginate the collection manually
        $perPage = 5; // Number of apps per page
        $currentPage = $request->input('page', 1);
        $pagedTokens = new \Illuminate\Pagination\LengthAwarePaginator(
            $uniqueTokens->forPage($currentPage, $perPage),
            $uniqueTokens->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('profile.index', [
            'user' => $user,
            'authorizedApps' => $pagedTokens,
        ]);
    }

    /**
     * Display the user's profile form for React/Inertia.
     */
    public function indexReact(Request $request)
    {
        // Get current user with details
        $user = User::with([
            'userDetail.address.subcity',
            'userDetail.address.city',
            'userDetail.address.country',
            'country'
        ])->where('id', auth()->id())->first();

        // Create at least one session entry for the current session
        $currentSession = [
            'id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_active' => now()->toDateTimeString(),
        ];

        // Parse user agent (temporary solution without Agent package)
        $userAgent = $currentSession['user_agent'];

        // Simple user agent parsing
        $browser = 'Unknown Browser';
        $platform = 'Unknown Platform';

        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        }

        if (strpos($userAgent, 'Windows') !== false) {
            $platform = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $platform = 'Mac';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $platform = 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $platform = 'iPhone';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            $platform = 'iPad';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $platform = 'Android';
        }

        $sessions = collect([$currentSession])->map(function ($session) use ($request, $browser, $platform) {
            return [
                'id' => $session['id'],
                'ip_address' => $session['ip_address'],
                'is_current_device' => $session['id'] === $request->session()->getId(),
                'browser' => $browser,
                'platform' => $platform,
                'last_active' => $session['last_active'],
                'location' => 'Unknown', // You could integrate with a geolocation API here
                'user_agent' => $session['user_agent'],
            ];
        });

        // Get unique authorized applications
        $uniqueTokens = $request->user()->tokens()
            ->with('client')
            ->get()
            ->groupBy('client_id')
            ->map(function ($clientTokens) {
                // Return the most recently used token for each client
                return $clientTokens->sortByDesc('last_used_at')->first();
            })
            ->values();

        // Format user data for Inertia
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
            'phone_verified_at' => $user->phone_verified_at?->toDateTimeString(),
            'created_at' => $user->created_at?->toDateTimeString(),
            'profile_photo_path' => $user->profile_photo_path,
            'country' => $user->country ? [
                'id' => $user->country->id,
                'name' => $user->country->name,
                'dial_code' => $user->country->dial_code,
            ] : null,
            'user_detail' => $user->userDetail ? [
                'gender' => $user->userDetail->gender?->value ?? $user->userDetail->gender,
                'birth_date' => $user->userDetail->birth_date?->toDateString(),
                'address' => $user->userDetail->address ? $user->userDetail->address->effective_address : null,
                'city' => $user->userDetail->city,
                'country' => $user->userDetail->country ? [
                    'id' => $user->userDetail->country->id,
                    'name' => $user->userDetail->country->name,
                ] : null,
            ] : null,
        ];

        // Format authorized apps
        $authorizedApps = $uniqueTokens->map(function ($token) {
            return [
                'id' => $token->id,
                'last_used_at' => $token->last_used_at?->toDateTimeString(),
                'created_at' => $token->created_at->toDateTimeString(),
                'client' => $token->client ? [
                    'id' => $token->client->id,
                    'name' => $token->client->name,
                    'redirect' => $token->client->redirect,
                ] : null,
            ];
        })->toArray();

        return Inertia::render('Account', [
            'user' => $userData,
            'authorizedApps' => $authorizedApps,
            'sessions' => $sessions->toArray(),
        ]);
    }

    /**
     * Display the user's profile edit form for React/Inertia.
     */
    public function editReact(Request $request)
    {
        $user = User::with('userDetail')->where('id', auth()->id())->first();
        $genders = GenderEnum::cases();
        
        $countries = Country::all()->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'dial_code' => $country->dial_code,
                'country_code' => $country->country_code,
            ];
        });

        // Format user data for Inertia
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country_id' => $user->country_id,
            'user_detail' => $user->userDetail ? [
                'gender' => $user->userDetail->gender?->value,
                'birth_date' => $user->userDetail->birth_date?->format('Y-m-d'),
            ] : null,
        ];

        return Inertia::render('EditProfile', [
            'user' => $userData,
            'genders' => array_map(fn($gender) => $gender->value, $genders),
            'countries' => $countries->toArray(),
        ]);
    }

    /**
     * Update the user's profile information for React/Inertia.
     */
    public function updateReact(Request $request)
    {
        $user = $request->user();
        $userDetail = UserDetail::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!empty($value) && (strlen($value) < 9 || strlen($value) > 16)) {
                    $fail('The phone number must be between 9 and 16 characters.');
                }
            }],
            'country_id' => [
                'nullable',
                'required_with:phone',
                'exists:countries,id'
            ],
            'gender' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
        ], [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 3 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'country_id.required_with' => 'Please select a country when providing a phone number.',
            'country_id.exists' => 'The selected country is invalid.',
        ]);

        // Handle phone - only update if provided and not empty
        $phoneValue = isset($validated['phone']) && !empty(trim($validated['phone'])) 
            ? trimPhone($validated['phone']) 
            : $user->phone;

        // Handle country_id - only update if provided, or clear if phone is being cleared
        $countryIdValue = null;
        if (!empty($phoneValue)) {
            // If phone is provided, country_id should be set
            $countryIdValue = isset($validated['country_id']) && !empty($validated['country_id'])
                ? $validated['country_id']
                : $user->country_id;
        } else {
            // If phone is being cleared, country_id can be cleared too
            $countryIdValue = isset($validated['country_id']) && !empty($validated['country_id'])
                ? $validated['country_id']
                : null;
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phoneValue,
            'country_id' => $countryIdValue,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        if (!$userDetail) {
            $userDetail = new UserDetail(['user_id' => $user->id]);
        }

        try {
            $user->save();

            if (isset($validated['gender']) && !empty($validated['gender'])) {
                $userDetail->gender = $validated['gender'];
            }
            if (isset($validated['birth_date']) && !empty($validated['birth_date'])) {
                $userDetail->birth_date = $validated['birth_date'];
            }
            $userDetail->save();

            return redirect()->route('v2.account')->with('status', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('flash_error', 'Unable to update profile. Please try again.');
        }
    }

    public function edit(): View
    {
        $user = User::with('userDetail')->where('id', auth()->id())->first();
        $genders = GenderEnum::cases();

        $countries = Country::all();


        return view('profile.update-profile', [
            'user' => $user,
            'genders' => $genders,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $userDetail = UserDetail::where('user_id', $user->id)->first();

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ? trimPhone($request->phone) : null,
            'country_id' => $request->country_id,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        if (!$userDetail) {
            $userDetail = new UserDetail(['user_id' => $user->id]);
        }

        try {
            $user->save();

            $userDetail->gender = $request->gender;
            $userDetail->birth_date = $request->birth_date;
            $userDetail->save();
        } catch (\Exception $e) {
            return back()->with('flash_error', 'Unable to update profile. Please try again.');
        }

        return back()->with('status', 'Profile updated successfully.');
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

    /**
     * Log out from a specific session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutSession(Request $request)
    {
        if ($request->session_id) {
            // Logic to invalidate the specific session
            // This would require a DB implementation for sessions

            return back()->with('status', 'Session has been revoked successfully.');
        }

        return back()->with('error', 'Unable to revoke session.');
    }

    /**
     * Revoke a specific OAuth token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeToken(Request $request)
    {
        $token = $request->user()->tokens()->find($request->token_id);

        if ($token) {
            $token->delete();
            return back()->with('status', 'Application access has been revoked.');
        }

        return back()->with('error', 'Unable to revoke application access.');
    }

    /**
     * Show the phone verification form.
     */
    public function showVerifyPhone(Request $request): View
    {
        // Check if there's a verification in progress
        if (!session('verification_phone')) {
            return redirect()->route('profile.update-profile');
        }

        return view('auth.verify-phone', [
            'phone' => session('verification_phone'),
        ]);
    }

    /**
     * Verify the phone verification code.
     */
    public function verifyPhone(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = $request->user();

        // Find the latest unexpired OTP for this user
        $otp = $user->otps()
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
        }

        // Mark phone as verified
        $user->phone_verified_at = now();
        $user->save();

        // Delete used OTPs for this user
        $user->otps()->delete();

        // Clear verification session data
        session()->forget(['verification_phone', 'verification_for']);

        return redirect()->route('profile.update-profile')
            ->with('status', 'Your phone number has been verified successfully.');
    }

    /**
     * Resend the verification code.
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Delete existing OTPs
        $user->otps()->delete();

        // Generate a new verification code
        $verificationCode = rand(100000, 999999);

        // Store the new code
        $otp = new \App\Models\Otp([
            'code' => $verificationCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        $user->otps()->save($otp);

        // Send the new verification code
        try {
            \App\Helpers\SmsSend::send($user->phone, "Your verification code is: $verificationCode");
            return back()->with('status', 'A new verification code has been sent to your phone.');
        } catch (\Exception $e) {
            \Log::error("Failed to resend verification SMS: " . $e->getMessage());
            return back()->withErrors(['phone' => 'Unable to send verification code. Please try again.']);
        }
    }
}
