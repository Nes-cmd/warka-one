<?php

use App\Helpers\SmsSend;
use App\Http\Controllers\AssumptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AssumptionController::class)->group(function(){
    Route::get('detail', 'fix_all_user_has_detail');
});
Route::redirect('et', '/');
Route::view('/', 'welcome');
Route::view('mail', 'emailtemp');
Route::view('about', 'about');
Route::view('services', 'services');
Route::view('contact', 'contact');

Route::middleware('auth')->group(function () {
    Route::get('account', [ProfileController::class, 'index'])->name('account');
    Route::get('profile-setting', [ProfileController::class, 'edit'])->name('profile.update-profile');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->get('authflow/must-verify', [VerificationController::class, 'mustVerify'])->name('must-verify-otp');


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

Route::get('tt', function () {
    $op = SmsSend::send("251940678725", "Selam there, Your confirmation code is 4236");

    dd($op->json());
});