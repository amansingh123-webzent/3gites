<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
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
     * Any active member can add a comment.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create comments');
    }

    /**
     * Author can delete their own comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
