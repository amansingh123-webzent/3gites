<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Admin can do anything.
     * This runs before all other checks.
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null; // fall through to specific methods
    }

    /**
     * Any authenticated user can view any profile.
     * (Guests are handled at the route level — profile pages are public.)
     */
    public function view(?User $user, User $member): bool
    {
        return true;
    }

    /**
     * Only the member themselves can edit their own profile.
     * Admin is handled by before().
     * Deceased members cannot edit (no login), but guard here too.
     */
    public function update(User $user, User $member): bool
    {
        return $user->id === $member->id
            && $user->member_status !== 'deceased';
    }

    /**
     * Teen photo: member themselves or admin (admin handled by before()).
     */
    public function uploadTeenPhoto(User $user, User $member): bool
    {
        return $user->id === $member->id
            && $user->member_status !== 'deceased';
    }

    /**
     * Recent photo: member themselves or admin.
     */
    public function uploadRecentPhoto(User $user, User $member): bool
    {
        return $user->id === $member->id
            && $user->member_status !== 'deceased';
    }

    /**
     * Delete a photo: owner or admin.
     */
    public function deletePhoto(User $user, User $member): bool
    {
        return $user->id === $member->id;
    }
}
