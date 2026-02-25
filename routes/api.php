<?php

use App\Http\Controllers\Api\ParentCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Parent Code API (public, rate-limited)
|--------------------------------------------------------------------------
*/

Route::prefix('parent-code')->group(function () {
    Route::post('/validate', [ParentCodeController::class, 'validateCode'])
        ->middleware('throttle:10,1')
        ->name('api.parent-code.validate');

    Route::post('/signup', [ParentCodeController::class, 'signup'])
        ->middleware('throttle:5,1')
        ->name('api.parent-code.signup');
});
