<?php

use App\Helpers\SmsSend;
use App\Http\Controllers\AssumptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmsCallbackController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthClientController;
use App\Http\Controllers\ContactController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Inertia\Inertia;

// Old Blade Routes - v1 prefix (route names remain unchanged)
Route::prefix('v1')->group(function () {
    Route::controller(AssumptionController::class)->group(function(){
        Route::get('detail', 'fix_all_user_has_detail');
    });

    Route::get('test', function(){
        throw new HttpException(429, 'Too many OTP requests. Try again later.');
    })->name('error.429');

    Route::redirect('et', '/v1');
    Route::view('privacy-policy', 'privacy-policy')->name('privacy-policy');
    Route::view('mail', 'emailtemp');
    Route::view('about', 'about');
    Route::view('services', 'services');
    Route::view('contact', 'contact');
    Route::view('documentation', 'documentation')->name('documentation');
});

// React Pages Routes (previously v2 routes, now at root for SSO compatibility)
// Note: Route names still use v2. prefix for consistency
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('v2.welcome');

Route::get('/services', function () {
    return Inertia::render('Services');
})->name('v2.services');

Route::get('/documentation', function () {
    return Inertia::render('Documentation');
})->name('v2.documentation');

Route::get('/contact', function () {
    // Generate simple math CAPTCHA
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    
    session(['captcha_num1' => $num1, 'captcha_num2' => $num2]);
    
    return Inertia::render('Contact', [
        'captcha_num1' => $num1,
        'captcha_num2' => $num2,
    ]);
})->name('v2.contact');

Route::post('/contact/submit', [ContactController::class, 'submitReact'])->name('v2.contact.submit');

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
})->name('v2.privacy-policy');

// React Authentication Routes - grouped with guest middleware and throttling (matching routes/auth.php)
// Note: These routes are at root level (not /v2) for SSO compatibility with external apps
// Route names use v2. prefix for consistency, but URLs are at root level
Route::middleware('guest')->group(function () {
    // React Register Routes (no throttle on register routes in auth.php)
    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'createReact'])->name('v2.register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'storeReact'])->name('v2.register.store');
    
    // React Auth Routes with throttle:10,1 (10 attempts per 1 minutes) - matching auth.php
    // Route::middleware('throttle:10,1')->group(function () {
        // React Authflow Routes
        Route::prefix('authflow')->group(function () {
            Route::get('/get-otp', [\App\Http\Controllers\VerificationController::class, 'indexReact'])->name('v2.authflow.get-otp');
            Route::post('/get-otp', [\App\Http\Controllers\VerificationController::class, 'getOtpReact'])->name('v2.authflow.get-otp.store');
            Route::get('/verify', [\App\Http\Controllers\VerificationController::class, 'verifyViewReact'])->name('v2.authflow.verify');
            Route::post('/verify', [\App\Http\Controllers\VerificationController::class, 'verifyReact'])->name('v2.authflow.verify.store');
            Route::post('/resend-otp', [\App\Http\Controllers\VerificationController::class, 'resendOtpReact'])->name('v2.authflow.resend-otp');
            // Route::middleware('auth')->get('/must-verify', [\App\Http\Controllers\VerificationController::class, 'mustVerifyReact'])->name('v2.must-verify-otp');
        // }); 
        
        // React Login Routes (at root level for SSO compatibility, v2. route name for consistency)
        Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'createReact'])->name('v2.login');
        Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'storeReact'])->name('v2.login.store');
        
        // React Password Reset Request Routes
        Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'createReact'])->name('v2.password.request');
        Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'storeReact'])->name('v2.password.email');
        Route::get('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'createReact'])->name('v2.password.reset');
    });
    
    // React Password Reset Store Route (separate throttle group like in auth.php)
    Route::middleware('throttle:10,5')->post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'storeReact'])->name('v2.password.store');
});

// React Request Login OTP Route (matching auth.php)
Route::middleware('guest')->post('/request-login-otp', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'requestLoginOTP'])
    ->name('v2.request-login-otp');

// React Logout Route (at root level for SSO compatibility)
Route::middleware('auth')->post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::middleware(['auth', 'must-reset-password', 'verified-auth'])->group(function () {
    // Old Blade Account Routes - v1 prefix (route names remain unchanged)
    Route::prefix('v1')->group(function () {
        Route::get('account', [ProfileController::class, 'index'])->name('account');
        Route::get('profile-setting', [ProfileController::class, 'edit'])->name('profile.update-profile');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('profile/logout-session', [ProfileController::class, 'logoutSession'])->name('profile.logout-session');
        Route::post('profile/revoke-token', [ProfileController::class, 'revokeToken'])->name('profile.revoke-token');
        
        // Phone verification routes
        Route::get('verify-phone', [VerificationController::class, 'verifyPhone'])->name('verify-phone');
        Route::post('verify-phone', [VerificationController::class, 'processVerifyPhone'])->name('verify-phone.verify');
        Route::post('resend-phone-verification', [VerificationController::class, 'resendPhoneVerification'])->name('verify-phone.resend');
        Route::post('initiate-phone-verification', [VerificationController::class, 'initiatePhoneVerification'])->name('initiate-phone-verification');
    });
    
    // React Account Routes (previously v2 routes, now at root for SSO compatibility)
    // Note: Route names still use v2. prefix for consistency
    Route::get('/account', [ProfileController::class, 'indexReact'])->name('v2.account');
    Route::get('/profile-setting', [ProfileController::class, 'editReact'])->name('v2.profile.setting');
    Route::patch('/profile', [ProfileController::class, 'updateReact'])->name('v2.profile.update');
    
    // React OAuth Clients Routes (previously v2 routes, now at root for SSO compatibility)
    Route::get('/clients', [OAuthClientController::class, 'indexReact'])->name('v2.clients.index');
    Route::get('/clients/create', [OAuthClientController::class, 'createReact'])->name('v2.clients.create');
    Route::post('/clients', [OAuthClientController::class, 'storeReact'])->name('v2.clients.store');
    Route::get('/clients/{client}/edit', [OAuthClientController::class, 'editReact'])->name('v2.clients.edit');
    Route::post('/clients/{client}', [OAuthClientController::class, 'updateReact'])->name('v2.clients.update');
    Route::delete('/clients/{client}', [OAuthClientController::class, 'destroyReact'])->name('v2.clients.destroy');
    Route::post('/clients/{client}/regenerate-secret', [OAuthClientController::class, 'regenerateSecretReact'])->name('v2.clients.regenerate-secret');
});

Route::prefix('authflow')->group(function () {
    Route::middleware(['auth', 'throttle:10,1'])->get('must-verify', [VerificationController::class, 'mustVerifyReact'])->name('v2.must-verify-otp');
});

// Old Blade Routes - v1 prefix (route names remain unchanged)
Route::prefix('v1')->group(function () {
    Route::middleware(['auth', 'throttle:10,5'])->get('authflow/must-verify', [VerificationController::class, 'mustVerify'])->name('must-verify-otp');
});

// SSO Logout Route (at root level for SSO compatibility)
// This route is used by external applications to log out users from SSO
Route::get('logout-sso-client', function (Request $request) {
    // Get callback URL from query parameter
    $callback = $request->query('callback');
    
    // Log out the user properly
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Validate and redirect to callback if provided and valid
    if ($callback) {
        // Basic validation: ensure it's a valid URL
        if (filter_var($callback, FILTER_VALIDATE_URL)) {
            // Additional security: check if callback is from allowed domains
            // For SSO, we typically allow redirects to registered OAuth client redirect URLs
            // For now, we'll allow any valid URL (you can add domain whitelist if needed)
            $parsedCallback = parse_url($callback);
            if ($parsedCallback && isset($parsedCallback['scheme']) && in_array($parsedCallback['scheme'], ['http', 'https'])) {
                return redirect($callback);
            }
        }
    }
    
    // Fallback: redirect to login if no callback or invalid callback
    return redirect()->route('v2.login');
});


require __DIR__ . '/auth.php';


// Old Blade Routes - v1 prefix (route names remain unchanged)
Route::prefix('v1')->group(function () {
    Route::get('test-sms', function () {
        $op = SmsSend::send("251940678725", "Selam there, Your confirmation code is 4236");
    
        dd($op->json());
    });
    
    // OAuth Clients Management Routes
    Route::middleware(['auth', 'must-reset-password', 'verified-auth'])->prefix('oauth')->group(function () {
        Route::get('clients', [OAuthClientController::class, 'index'])->name('clients.index');
        Route::get('clients/create', [OAuthClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [OAuthClientController::class, 'store'])->name('clients.store');
        Route::get('clients/{client}/edit', [OAuthClientController::class, 'edit'])->name('clients.edit');
        Route::post('clients/{client}', [OAuthClientController::class, 'update'])->name('clients.update');
        Route::delete('clients/{client}', [OAuthClientController::class, 'destroy'])->name('clients.destroy');
        Route::get('clients/{client}/regenerate-secret', [OAuthClientController::class, 'regenerateSecret'])->name('clients.regenerate-secret');
    });
    
    // Contact Routes
    Route::get('contact', [ContactController::class, 'index'])->name('contact');
    Route::post('contact', [ContactController::class, 'submit'])->name('contact.submit');
});

Route::group(['prefix' => 'your-package', 'middleware' => ['web']], function () {
    // Your package routes here
});
