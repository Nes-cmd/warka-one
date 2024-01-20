<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('countries', function(){
    return CountryResource::collection(App\Models\Country::all());
});

Route::post('get-verification-code', [AuthController::class, 'getVerificationCode']);
Route::post('verify-code', [AuthController::class, 'verifyCode']);
Route::post('create-user', [AuthController::class, 'register']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::post('ext-login', [AuthController::class, 'login']);

Route::middleware('auth:api')->get('/user/get', function (Request $request) {
    return $request->user();
});