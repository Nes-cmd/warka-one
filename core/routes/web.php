<?php

use App\Helpers\SmsSend;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;



Route::view('/', 'welcome');
Route::view('mail', 'emailtemp');
Route::view('about', 'about');
Route::view('services', 'services');
Route::view('contact', 'contact');
// Route::view('profileSettings', 'profile.update-profile');

Route::redirect('dashboard', 'account')->name('dashboard');
Route::redirect('profile', 'account')->name('profile.edit');
Route::redirect('profile', 'profileSettings')->name('profile.update-profile');


Route::middleware('auth')->group(function () {
    Route::get('account', [ProfileController::class, 'index'])->name('profile.edit');
    Route::get('profileSettings', [ProfileController::class, 'edit'])->name('profile.update-profile');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->get('authflow/must-verify', [VerificationController::class, 'mustVerify'])->name('must-verify-otp');


Route::get('logout-pms', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect($request->callback);
});

Route::get('logout-wallet', function (Request $request) {
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

// Route::get('sms-test', function () {
//     $op = SmsSend::sendSMS("251940678725", "Selam there, Your confirmation code is 4236");

//     dd($op);
// });