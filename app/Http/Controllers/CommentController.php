<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * POST /board/{post}/comments
     * Add a comment to a post.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('create', Comment::class);

        // Prevent commenting on soft-deleted posts
        if ($post->trashed()) {
            return back()->with('error', 'You cannot comment on a removed post.');
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'body'    => $validated['body'],
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($comment)
            ->log('Comment added to post: ' . $post->title);

        // Redirect back to the post thread, anchored to the new comment
        return redirect()
            ->route('posts.show', $post)
            ->withFragment('comment-' . $comment->id)
            ->with('success', 'Comment added.');
    }

    /**
     * DELETE /board/{post}/comments/{comment}
     * Soft-delete a comment. Author or admin only.
     */
    public function destroy(Post $post, Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($comment)
            ->log('Comment deleted from post: ' . $post->title);

        return back()->with('success', 'Comment removed.');
    }
}
