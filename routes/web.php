<?php

use App\Helpers\SmsSend;
use App\Http\Controllers\AssumptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthClientController;
use App\Http\Controllers\ContactController;


Route::controller(AssumptionController::class)->group(function(){
    Route::get('detail', 'fix_all_user_has_detail');
});

Route::redirect('et', '/');
Route::view('/', 'welcome');
Route::view('privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('mail', 'emailtemp');
Route::view('about', 'about');
Route::view('services', 'services');
Route::view('contact', 'contact');
Route::view('documentation', 'documentation')->name('documentation');

Route::middleware(['auth', 'verified-auth'])->group(function () {
    Route::get('account', [ProfileController::class, 'index'])->name('account');
    Route::get('profile-setting', [ProfileController::class, 'edit'])->name('profile.update-profile');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('profile/logout-session', [ProfileController::class, 'logoutSession'])->name('profile.logout-session');
    Route::post('profile/revoke-token', [ProfileController::class, 'revokeToken'])->name('profile.revoke-token');
    
    // Phone verification routes
    Route::get('/verify-phone', [VerificationController::class, 'verifyPhone'])->name('verify-phone');
    Route::post('/verify-phone', [VerificationController::class, 'processVerifyPhone'])->name('verify-phone.verify');
    Route::post('/resend-phone-verification', [VerificationController::class, 'resendPhoneVerification'])->name('verify-phone.resend');
    Route::post('/initiate-phone-verification', [VerificationController::class, 'initiatePhoneVerification'])->name('initiate-phone-verification');
});

Route::middleware(['auth', 'throttle:10,5'])->get('authflow/must-verify', [VerificationController::class, 'mustVerify'])->name('must-verify-otp');


Route::get('logout-sso-client', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect($request->callback);
});


require __DIR__ . '/auth.php';


/*
curl -XPOST -H 'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpZGVudGlmaWVyIjoidkV0MjVTVWg2OG9MNWNBdFE1dWxHNlpicHJ3RzMxd1QiLCJleHAiOjE4NjEyMDA0OTEsImlhdCI6MTcwMzM0NzY5MSwianRpIjoiNzI1OWQzNzgtZGYwZS00NzIwLWJiOTctY2YxMzM4Njc3YTAwIn0.w58houRyXLAt8xaO6QbgUwnG3nHdVMTiKUHJQcH9AAk' \
    -H "Content-type: application/json" \
    -d '{"from":"e80ad9d8-adf3-463f-80f4-7c4b39f7f164","sender":"9786","to":"0940678725","message":"Selaaam","callback":"http://example.com"}' \
    'https://api.afromessage.com/api/send'
*/

Route::get('test-sms', function () {
    $op = SmsSend::send("251940678725", "Selam there, Your confirmation code is 4236");

    dd($op->json());
});

// OAuth Clients Management Routes
Route::middleware(['auth', 'verified-auth'])->prefix('oauth')->group(function () {
    Route::get('clients', [OAuthClientController::class, 'index'])->name('clients.index');
    Route::get('clients/create', [OAuthClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [OAuthClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}/edit', [OAuthClientController::class, 'edit'])->name('clients.edit');
    Route::post('clients/{client}', [OAuthClientController::class, 'update'])->name('clients.update');
    Route::delete('clients/{client}', [OAuthClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('clients/{client}/regenerate-secret', [OAuthClientController::class, 'regenerateSecret'])->name('clients.regenerate-secret');
});

// Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::group(['prefix' => 'your-package', 'middleware' => ['web']], function () {
    // Your package routes here
});
