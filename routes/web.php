<?php

use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\NewsController;
use App\Http\Controllers\Marketing\EventController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PostController;
use App\Http\Controllers\Portal\CalendarController;
use App\Http\Controllers\Portal\LunchMenuController;
use App\Http\Controllers\Portal\Admin\ClassroomController;
use App\Http\Controllers\Portal\Admin\StudentImportController;
use App\Http\Controllers\Portal\Admin\StaffController;
use App\Http\Controllers\Portal\Admin\ParentController;
use App\Http\Controllers\Portal\Admin\LunchMenuImportController;
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
Route::get('/why-silver-academy', [HomeController::class, 'whySilverAcademy'])->name('why-silver-academy');
Route::get('/our-community', [HomeController::class, 'ourCommunity'])->name('our-community');
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

Route::prefix('get-involved')->name('get-involved.')->group(function () {
    Route::get('/', [HomeController::class, 'getInvolved'])->name('index');
    Route::get('/donate', [HomeController::class, 'donate'])->name('donate');
    Route::get('/eitc-corporate', [HomeController::class, 'eitcCorporate'])->name('eitc-corporate');
    Route::get('/eitc-individual', [HomeController::class, 'eitcIndividual'])->name('eitc-individual');
    Route::get('/life-and-legacy', [HomeController::class, 'lifeAndLegacy'])->name('life-and-legacy');
    Route::get('/fundraisers', [HomeController::class, 'fundraisers'])->name('fundraisers');
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

    // Calendar (accessible to all authenticated users)
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Lunch Menus (view accessible to all, CRUD requires admin)
    Route::get('/lunch', [LunchMenuController::class, 'index'])->name('lunch.index');
    Route::get('/lunch/api/month', [LunchMenuController::class, 'getMenusForMonth'])->name('lunch.api.month');
    Route::middleware('admin')->group(function () {
        Route::get('/lunch/import', [LunchMenuImportController::class, 'showImport'])->name('lunch.import');
        Route::post('/lunch/import', [LunchMenuImportController::class, 'import'])->name('lunch.import.process');
        Route::get('/lunch/template', [LunchMenuImportController::class, 'downloadTemplate'])->name('lunch.template');
        Route::get('/lunch/export', [LunchMenuImportController::class, 'export'])->name('lunch.export');
        Route::get('/lunch/create', [LunchMenuController::class, 'create'])->name('lunch.create');
        Route::post('/lunch', [LunchMenuController::class, 'store'])->name('lunch.store');
        Route::get('/lunch/{lunch}/edit', [LunchMenuController::class, 'edit'])->name('lunch.edit');
        Route::put('/lunch/{lunch}', [LunchMenuController::class, 'update'])->name('lunch.update');
        Route::delete('/lunch/{lunch}', [LunchMenuController::class, 'destroy'])->name('lunch.destroy');
    });

    // Admin-only routes for managing posts
    Route::middleware('admin')->group(function () {
        Route::resource('posts', PostController::class)->except(['show']);
        Route::post('/posts/{post}/toggle-publish', [PostController::class, 'togglePublish'])
            ->name('posts.toggle-publish');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Classroom & Student Management)
|--------------------------------------------------------------------------
|
| These routes are for admin management of classrooms, students, and teachers.
|
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('portal/admin')->name('admin.')->group(function () {
    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('/staff/{staff}/toggle-role', [StaffController::class, 'toggleRole'])->name('staff.toggle-role');

    // Parent Management
    Route::get('/parents', [ParentController::class, 'index'])->name('parents.index');
    Route::get('/parents/{parent}/edit', [ParentController::class, 'edit'])->name('parents.edit');
    Route::put('/parents/{parent}', [ParentController::class, 'update'])->name('parents.update');
    Route::delete('/parents/{parent}', [ParentController::class, 'destroy'])->name('parents.destroy');
    Route::post('/parents/{parent}/resend-verification', [ParentController::class, 'resendVerification'])->name('parents.resend-verification');
    Route::post('/parents/{parent}/verify-email', [ParentController::class, 'verifyEmail'])->name('parents.verify-email');

    // Import/Export (must be before {classroom} parameter routes)
    Route::get('/classrooms/import', [StudentImportController::class, 'showImport'])->name('classrooms.import');
    Route::post('/classrooms/import', [StudentImportController::class, 'import'])->name('classrooms.import.process');
    Route::get('/classrooms/template', [StudentImportController::class, 'downloadTemplate'])->name('classrooms.template');
    Route::get('/classrooms/export', [StudentImportController::class, 'export'])->name('classrooms.export');

    // Classroom Management
    Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classrooms/grade/{grade}', [ClassroomController::class, 'gradeDetail'])->name('classrooms.grade.show');
    Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
    Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');
    Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
    Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');

    // Student Assignment
    Route::post('/classrooms/{classroom}/assign-students', [ClassroomController::class, 'assignStudents'])
        ->name('classrooms.assign-students');
    Route::post('/classrooms/{classroom}/remove-students', [ClassroomController::class, 'removeStudents'])
        ->name('classrooms.remove-students');
    Route::post('/classrooms/{classroom}/assign-all', [ClassroomController::class, 'assignAllStudents'])
        ->name('classrooms.assign-all');
    Route::post('/classrooms/move-students', [ClassroomController::class, 'moveStudents'])
        ->name('classrooms.move-students');
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
