<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@3gites.org'],
            [
                'name'           => 'Site Administrator',
                'password'       => Hash::make('change-me-immediately!'),
                'member_status'  => 'active',
                'account_locked' => false,
            ]
        );

        $admin->assignRole('admin');

        // Create a blank profile for the admin
        Profile::firstOrCreate(['user_id' => $admin->id]);

        $this->command->info('✅ Admin user created: admin@3gites.org');
        $this->command->warn('⚠️  Change the admin password immediately after first login!');
    }
}
