<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Admin bypasses all checks.
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    /**
     * Any active member can create a post.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create posts');
    }

    /**
     * Author can delete their own post.
     * Admin handled by before().
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Only admin can pin/unpin.
     * (Admin already handled by before(), but being explicit.)
     */
    public function pin(User $user): bool
    {
        return false; // Non-admins never reach this — before() handles admin
    }
}
