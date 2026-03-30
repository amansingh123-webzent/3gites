<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GuestbookController extends Controller
{
    /**
     * GET /comments
     * Readable by anyone — general community comments board.
     */
    public function index(): View
    {
        $comments = Comment::whereNull('post_id')
            ->with('user.profile')
            ->latest()
            ->paginate(20);

        return view('pages.guestbook', compact('comments'));
    }

    /**
     * POST /comments
     * Auth required — members only.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => null, // null = guestbook entry (not tied to a post)
            'body'    => $validated['body'],
        ]);

        return back()->with('success', 'Your message has been added.');
    }

    /**
     * DELETE /comments/{comment}
     * Author or admin only.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment removed.');
    }
}
