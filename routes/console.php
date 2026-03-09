<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('super-admin:reset-password {password : The new password} {--email=danny@empoweredcreative.co : Super admin email} {--set-super-admin : Also set the user role to super_admin}', function () {
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

    // Use plain password so the User model's 'hashed' cast hashes it once (avoids double-hashing)
    $updates = ['password' => $password];
    if ($this->option('set-super-admin')) {
        $updates['role'] = User::ROLE_SUPER_ADMIN;
        $this->info("Setting role to super_admin for {$email}.");
    }

    $user->update($updates);
    $this->info("Password updated successfully for {$email}. You can log in with the new password.");
    $this->comment('If you were locked out after too many attempts, run: php artisan cache:clear');
    return 0;
})->purpose('Reset the super admin password (e.g. when locked out on production)');
