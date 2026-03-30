<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * GET /board
     * Paginated post listing. Pinned posts always float to the top.
     */
    public function index(): View
    {
        // Pinned posts — always shown, not paginated
        $pinned = Post::with(['user', 'comments'])
            ->where('is_pinned', true)
            ->latest()
            ->get();

        // Regular (non-pinned) posts — paginated, 15 per page
        $posts = Post::with(['user', 'comments'])
            ->where('is_pinned', false)
            ->latest()
            ->paginate(15);

        return view('board.index', compact('pinned', 'posts'));
    }

    /**
     * GET /board/create
     */
    public function create(): View
    {
        Gate::authorize('create', Post::class);

        return view('board.create');
    }

    /**
     * POST /board
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:200'],
            'body'  => ['required', 'string', 'min:10', 'max:10000'],
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title'   => $validated['title'],
            'body'    => $validated['body'],
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($post)
            ->log('Post created: ' . $post->title);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Your post has been published.');
    }

    /**
     * GET /board/{post}
     * Show the full post with all comments.
     */
    public function show(Post $post): View
    {
        // Load comments with their authors; eager-load for performance
        $post->load([
            'user',
            'comments' => fn ($q) => $q->with('user')->oldest(),
        ]);

        return view('board.show', compact('post'));
    }

    /**
     * DELETE /board/{post}
     * Soft-delete a post. Author can delete own post; admin can delete any.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete(); // SoftDelete — record stays in DB

        activity()
            ->causedBy(auth()->user())
            ->performedOn($post)
            ->log('Post soft-deleted: ' . $post->title);

        return redirect()->route('posts.index')
            ->with('success', 'Post removed.');
    }

    /**
     * PATCH /board/{post}/pin   [admin only]
     * Toggle the pinned state of a post.
     */
    public function togglePin(Post $post): RedirectResponse
    {
        Gate::authorize('pin', Post::class);

        $post->update(['is_pinned' => ! $post->is_pinned]);

        $action = $post->is_pinned ? 'pinned' : 'unpinned';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($post)
            ->log("Post {$action}: " . $post->title);

        return back()->with('success', 'Post ' . $action . '.');
    }
}
