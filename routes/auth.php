<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\MustResetPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('reset-password', );
    
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // Best case trotle:10 tries lock for 5 minutes
    Route::middleware('throttle:10,5')->group(function () {
        Route::get('authflow/get-otp', [VerificationController::class, 'index'])->name('get-otp');
        Route::get('authflow/verify', [VerificationController::class, 'verifyView'])->name('verify-otp');
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/', [NewPasswordController::class, 'create'])->name('password.reset');
    });

    Route::middleware('throttle:10,5')->post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['auth', 'must-reset-password', 'verified-auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

});

Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Must reset password routes (accessible to authenticated users who need to reset)
Route::middleware('auth')->group(function () {
    Route::get('must-reset-password', [MustResetPasswordController::class, 'create'])->name('password.must-reset');
    Route::post('must-reset-password', [MustResetPasswordController::class, 'store'])->name('password.must-reset.store');
});

// Add this new route for requesting OTPs for login
Route::post('/request-login-otp', [AuthenticatedSessionController::class, 'requestLoginOTP'])
    ->middleware('guest')
    ->name('request-login-otp');
