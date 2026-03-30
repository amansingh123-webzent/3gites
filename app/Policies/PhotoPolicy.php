<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    /**
     * Admin can do anything.
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    /**
     * Member can only delete their own photos.
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $user->id === $photo->user_id;
    }
}
