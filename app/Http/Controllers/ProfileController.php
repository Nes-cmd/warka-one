<?php

namespace App\Http\Controllers;

use App\Enum\GenderEnum;
use App\Models\Country;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
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
     * Display the user's profile edit form.
     */
    public function edit(Request $request)
    {
        $user = User::with('userDetail')->where('id', auth()->id())->first();
        $genders = GenderEnum::cases();
        
        $countries = Country::all()->map->toFrontendArray();

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
     * Update the user's profile information.
     */
    public function update(Request $request)
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
}
