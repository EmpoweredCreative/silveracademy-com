<?php

use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\NewsController;
use App\Http\Controllers\Marketing\EventController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PostController;
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

Route::prefix('programs')->name('programs.')->group(function () {
    Route::get('/ganeinu', [HomeController::class, 'ganeinu'])->name('ganeinu');
    Route::get('/kindergarten', [HomeController::class, 'kindergarten'])->name('kindergarten');
    Route::get('/lower-school', [HomeController::class, 'lowerSchool'])->name('lower-school');
    Route::get('/upper-school', [HomeController::class, 'upperSchool'])->name('upper-school');
    Route::get('/after-school', [HomeController::class, 'afterSchool'])->name('after-school');
    Route::get('/parent-circle', [HomeController::class, 'parentCircle'])->name('parent-circle');
});

// News & Events public routes
Route::get('/news-events', [NewsController::class, 'index'])->name('news-events');
Route::get('/news/{post:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/events/{post:slug}', [EventController::class, 'show'])->name('events.show');

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

    // Admin-only routes for managing posts
    Route::middleware('admin')->group(function () {
        Route::resource('posts', PostController::class)->except(['show']);
        Route::post('/posts/{post}/toggle-publish', [PostController::class, 'togglePublish'])
            ->name('posts.toggle-publish');
    });
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
