<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user (website developer)
        User::updateOrCreate(
            ['email' => 'admin@silveracademypa.org'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@silveracademypa.org',
                'password' => Hash::make('ChangeThisPassword123!'),
                'role' => User::ROLE_SUPER_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super admin user created/updated:');
        $this->command->info('Email: admin@silveracademypa.org');
        $this->command->info('Password: ChangeThisPassword123!');
        $this->command->warn('⚠️  Please change this password immediately after first login!');
    }
}

