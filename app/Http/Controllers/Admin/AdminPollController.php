<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminPollController extends Controller
{
    /**
     * GET /admin/polls
     */
    public function index(): View
    {
        $polls = Poll::withCount(['votes', 'options'])
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.polls.index', compact('polls'));
    }

    /**
     * GET /admin/polls/create
     */
    public function create(): View
    {
        return view('admin.polls.form', ['poll' => new Poll]);
    }

    /**
     * POST /admin/polls
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePoll($request);

        DB::transaction(function () use ($validated, $request) {
            $poll = Poll::create([
                'question'   => $validated['question'],
                'created_by' => auth()->id(),
                'is_published' => false,
                'is_closed'    => false,
            ]);

            $this->syncOptions($poll, $validated['options']);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($poll)
                ->log("Poll created: {$poll->question}");
        });

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll created. Publish it when ready.');
    }

    /**
     * GET /admin/polls/{poll}/edit
     * Only editable if no votes have been cast yet.
     */
    public function edit(Poll $poll): View
    {
        $poll->load('options');
        $hasVotes = $poll->votes()->exists();

        return view('admin.polls.form', compact('poll', 'hasVotes'));
    }

    /**
     * PATCH /admin/polls/{poll}
     */
    public function update(Request $request, Poll $poll): RedirectResponse
    {
        // Prevent editing a poll that already has votes
        if ($poll->votes()->exists()) {
            return back()->with('error', 'This poll already has votes and cannot be edited.');
        }

        $validated = $this->validatePoll($request);

        DB::transaction(function () use ($validated, $poll) {
            $poll->update(['question' => $validated['question']]);

            // Replace options entirely (safe since no votes exist)
            $this->syncOptions($poll, $validated['options']);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($poll)
                ->log("Poll updated: {$poll->question}");
        });

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll updated.');
    }

    /**
     * DELETE /admin/polls/{poll}
     * Hard-delete — removes all votes and options too (cascade).
     */
    public function destroy(Poll $poll): RedirectResponse
    {
        $question = $poll->question;
        $poll->delete(); // options + votes cascade via DB FK

        activity()
            ->causedBy(auth()->user())
            ->log("Poll deleted: {$question}");

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll deleted.');
    }

    /**
     * PATCH /admin/polls/{poll}/publish
     * Toggle published state. Cannot publish a poll with fewer than 2 options.
     */
    public function publish(Poll $poll): RedirectResponse
    {
        if (! $poll->is_published && $poll->options()->count() < 2) {
            return back()->with('error', 'A poll must have at least 2 options before publishing.');
        }

        $poll->update([
            'is_published' => ! $poll->is_published,
            // If unpublishing, also un-close it
            'is_closed' => $poll->is_published ? false : $poll->is_closed,
        ]);

        $state = $poll->is_published ? 'published' : 'moved back to draft';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($poll)
            ->log("Poll {$state}: {$poll->question}");

        return back()->with('success', "Poll {$state}.");
    }

    /**
     * PATCH /admin/polls/{poll}/close
     * Toggle closed state (stops further voting; results remain visible).
     */
    public function close(Poll $poll): RedirectResponse
    {
        if (! $poll->is_published) {
            return back()->with('error', 'Cannot close an unpublished poll.');
        }

        $poll->update(['is_closed' => ! $poll->is_closed]);

        $state = $poll->is_closed ? 'closed' : 're-opened';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($poll)
            ->log("Poll {$state}: {$poll->question}");

        return back()->with('success', "Poll {$state}.");
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function validatePoll(Request $request): array
    {
        return $request->validate([
            'question'    => ['required', 'string', 'max:500'],
            'options'     => ['required', 'array', 'min:2'],
            'options.*'   => ['required', 'string', 'max:200', 'distinct'],
        ], [
            'options.min'      => 'A poll must have at least 2 options.',
            'options.*.distinct' => 'Poll options must be unique.',
        ]);
    }

    private function syncOptions(Poll $poll, array $options): void
    {
        // Collect new option texts first
        $newOptions = [];
        foreach ($options as $text) {
            $text = trim($text);
            if ($text !== '') {
                $newOptions[] = $text;
            }
        }

        // Ensure we have at least 2 valid options
        if (count($newOptions) < 2) {
            throw new \Exception('Poll must have at least 2 valid options.');
        }

        // Delete all existing options
        $poll->options()->delete();
        
        // Create new options
        foreach ($newOptions as $text) {
            PollOption::create([
                'poll_id'     => $poll->id,
                'option_text' => $text,
            ]);
        }
    }
}
