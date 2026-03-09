<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('super-admin:reset-password {password : The new password} {--email=danny@empoweredcreative.co : Super admin email}', function () {
    $email = $this->option('email');
    $password = $this->argument('password');

    $user = User::where('email', $email)->first();
    if (!$user) {
        $this->error("No user found with email: {$email}");
        return 1;
    }

    if ($user->role !== User::ROLE_SUPER_ADMIN) {
        $this->warn("User {$email} is not a super admin (role: {$user->role}). Resetting password anyway.");
    }

    $user->update(['password' => Hash::make($password)]);
    $this->info("Password updated successfully for {$email}. You can log in with the new password.");
    return 0;
})->purpose('Reset the super admin password (e.g. when locked out on production)');
