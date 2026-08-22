<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\MustResetPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Old Blade Auth Routes - v1 prefix with v1. route name prefix
// Disabled: v1 is fully retired in favor of the React/Inertia routes below.
// Kept commented (not deleted) until the v1 Blade views/controllers are removed.
// Route::prefix('v1')->group(function () {
//     Route::middleware(['auth', 'must-reset-password', 'verified-auth'])->group(function () {
//         Route::get('verify-email', EmailVerificationPromptController::class)->name('v1.verification.notice');
//
//         Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('v1.verification.verify');
//
//         Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('v1.verification.send');
//
//         Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('v1.password.confirm');
//
//         Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
//
//         Route::put('password', [PasswordController::class, 'update'])->name('v1.password.update');
//
//     });
//
//     Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('v1.logout');
//
//     // Must reset password routes (accessible to authenticated users who need to reset)
//     Route::middleware('auth')->group(function () {
//         Route::get('must-reset-password', [MustResetPasswordController::class, 'create'])->name('v1.password.must-reset');
//         Route::post('must-reset-password', [MustResetPasswordController::class, 'store'])->name('v1.password.must-reset.store');
//     });
// });
