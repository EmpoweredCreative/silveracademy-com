<?php

use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Portal\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Marketing Routes
|--------------------------------------------------------------------------
|
| These routes are for the public marketing website.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/admissions', [HomeController::class, 'admissions'])->name('admissions');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Portal Routes
|--------------------------------------------------------------------------
|
| These routes are for the authenticated parent portal.
|
*/

Route::middleware(['auth', 'verified'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes will be added by Laravel Breeze/Fortify when auth is set up.
|
*/

require __DIR__.'/auth.php';
