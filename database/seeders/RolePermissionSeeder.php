<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Define all permissions ────────────────────────────────────────────
        $permissions = [
            // Members
            'view members',
            'edit own profile',
            'edit any profile',
            'manage members',         // create / lock / unlock / change status

            // Posts & Comments
            'create posts',
            'delete own posts',
            'delete any post',
            'pin posts',
            'create comments',
            'delete own comments',
            'delete any comment',

            // Photos
            'upload photos',
            'delete own photos',
            'delete any photo',
            'upload admin gallery',

            // Events & RSVPs
            'view events',
            'create events',
            'edit events',
            'rsvp events',

            // Polls
            'view polls',
            'vote in polls',
            'create polls',
            'manage polls',           // publish, close, delete

            // Store
            'view store',
            'purchase products',
            'manage store',           // products, orders

            // Donations
            'donate',
            'view donations',         // admin only

            // Tributes
            'view tributes',
            'manage tributes',        // admin only

            // Chat
            'use chat',

            // Admin panel
            'access admin panel',
            'send broadcast email',
            'view activity log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ── Define roles & assign permissions ─────────────────────────────────

        // ADMIN — everything
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // ACTIVE MEMBER — community participant
        $memberRole = Role::firstOrCreate(['name' => 'active_member', 'guard_name' => 'web']);
        $memberRole->syncPermissions([
            'view members',
            'edit own profile',
            'create posts',
            'delete own posts',
            'create comments',
            'delete own comments',
            'upload photos',
            'delete own photos',
            'view events',
            'rsvp events',
            'view polls',
            'vote in polls',
            'view store',
            'purchase products',
            'donate',
            'view tributes',
            'use chat',
        ]);

        // No explicit role for guests (unauthenticated) — handled via middleware/gates
        // Public pages (profiles, gallery, tributes) are open without auth

        $this->command->info('✅ Roles and permissions seeded successfully.');
    }
}
