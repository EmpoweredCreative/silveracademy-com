<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication for the parent portal.
| Note: Registration is for PARENTS ONLY. Staff are imported via admin.
|
*/

Route::middleware('guest')->group(function () {
    // Parent Registration (staff are imported via admin)
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // Parent signup with code (first-time: email + parent code)
    Route::get('parent/signup', [\App\Http\Controllers\Auth\ParentSignupController::class, 'create'])
        ->name('parent.signup');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Pending approval page (accessible without auth)
Route::get('pending-approval', [RegisteredUserController::class, 'pendingApproval'])
    ->name('pending-approval');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
