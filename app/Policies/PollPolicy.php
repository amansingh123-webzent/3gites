<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;

class PollPolicy
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
     * Members can view published polls only.
     */
    public function view(User $user, Poll $poll): bool
    {
        return $poll->is_published;
    }

    /**
     * Members can vote only on published, non-closed polls.
     */
    public function vote(User $user, Poll $poll): bool
    {
        return $poll->is_published
            && ! $poll->is_closed
            && $user->hasPermissionTo('vote in polls');
    }
}
