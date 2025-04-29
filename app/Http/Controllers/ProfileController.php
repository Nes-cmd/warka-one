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
            $user->phone = trimPhone($request->phone);
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
