<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;



Route::view('/', 'welcome');
Route::view('mail', 'emailtemp');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('test-afro', function () {

    $response = Http::withHeaders([
        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJpZGVudGlmaWVyIjoidkV0MjVTVWg2OG9MNWNBdFE1dWxHNlpicHJ3RzMxd1QiLCJleHAiOjE4NjEyMDA0OTEsImlhdCI6MTcwMzM0NzY5MSwianRpIjoiNzI1OWQzNzgtZGYwZS00NzIwLWJiOTctY2YxMzM4Njc3YTAwIn0.w58houRyXLAt8xaO6QbgUwnG3nHdVMTiKUHJQcH9AAk',
        'Content-type'  => 'application/json',
    ])->post('https://api.afromessage.com/api/send', [
        'from'     => 'e80ad9d8-adf3-463f-80f4-7c4b39f7f164', //
        'sender'   => '9786', // sender short code 
        'to'       => '0940678725', 
        'message'  => 'This message is a test message',
        'callback' => 'http://example.com'
    ]);

    dd($response->json());
});

Route::get('logout-pms', function(Request $request){
    
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