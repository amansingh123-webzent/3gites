<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use App\Models\Event;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
          $nextEvent = Event::upcoming()->first();
        // Birthdays this month (not the current user's own)
        $birthdaysThisMonth = Birthday::thisMonth()
            ->with('user')
            ->whereHas('user', fn ($q) => $q->where('member_status', 'active'))
            ->where('user_id', '!=', $user->id)
            ->get();

        // Today's birthday specifically
        $birthdaysToday = $birthdaysThisMonth->filter(fn ($b) => $b->isToday());

        // Recent posts (last 5)
        $recentPosts = Post::with('user')
            ->withCount('comments')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'birthdays' => $birthdaysThisMonth,
            'birthdaysToday' => $birthdaysToday,
            'nextEvent' => $nextEvent,
            'recentPosts' => $recentPosts
        ]);
    }
}
