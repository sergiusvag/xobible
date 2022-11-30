<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// redirects
Route::get('/register', static function () {
    return redirect('/register/'. app()->getLocale());
});
Route::get('/login', static function () {
    return redirect('/login/'. app()->getLocale());
});
Route::get('/forgot-password', static function () {
    return redirect('/forgot-password/'. app()->getLocale());
});
Route::get('/reset-password', static function () {
    return redirect('/reset-password/'. app()->getLocale());
});
Route::get('/verify-email', static function () {
    return redirect('/verify-email/'. app()->getLocale());
});
Route::get('/email/verification-notification', static function () {
    return redirect('/email/verification-notification/'. app()->getLocale());
});
Route::get('/confirm-password', static function () {
    return redirect('/confirm-password/'. app()->getLocale());
});
Route::get('/logout', static function () {
    return redirect('/logout/'. app()->getLocale());
});

Route::middleware(['guest', 'locale'])->group(function () {
    Route::get('register/{locale?}', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register/{locale?}', [RegisteredUserController::class, 'store']);

    Route::get('login/{locale?}', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login/{locale?}', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password/{locale?}', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password/{locale?}', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}/{locale?}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password/{locale?}', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware(['auth', 'locale'])->group(function () {
    Route::get('verify-email/{locale?}', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}/{locale?}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification/{locale?}', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password/{locale?}', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password/{locale?}', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout/{locale?}', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
