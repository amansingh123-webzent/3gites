<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,  // Must run first — roles must exist before assigning
            AdminUserSeeder::class,
            MemberSeeder::class,
            EventSeeder::class,
            RsvpSeeder::class,
        ]);
    }
}
