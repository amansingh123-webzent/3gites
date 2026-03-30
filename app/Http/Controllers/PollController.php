<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PollController extends Controller
{
    /**
     * GET /polls
     * List published polls split into open and closed tabs.
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'open'); // 'open' | 'closed'

        $open = Poll::where('is_published', true)
            ->where('is_closed', false)
            ->withCount('votes')
            ->latest()
            ->get();

        $closed = Poll::where('is_published', true)
            ->where('is_closed', true)
            ->withCount('votes')
            ->latest()
            ->get();

        // Which polls has the current user already voted in?
        $votedPollIds = PollVote::where('user_id', auth()->id())
            ->pluck('poll_id')
            ->toArray();

        return view('polls.index', compact('tab', 'open', 'closed', 'votedPollIds'));
    }

    /**
     * GET /polls/{poll}
     * Show a poll. If already voted (or closed), show results.
     * If not voted and open, show the vote form.
     */
    public function show(Poll $poll): View
    {
        Gate::authorize('view', $poll);

        $poll->load('options.votes');

        $totalVotes = $poll->votes()->count();

        // Has the current user already voted?
        $myVote = PollVote::where('poll_id', $poll->id)
            ->where('user_id', auth()->id())
            ->with('option')
            ->first();

        $hasVoted = (bool) $myVote;

        // Build results data for each option
        $results = $poll->options->map(function ($option) use ($totalVotes) {
            $count   = $option->votes->count();
            $percent = $totalVotes > 0
                ? round(($count / $totalVotes) * 100, 1)
                : 0;

            return [
                'id'      => $option->id,
                'text'    => $option->option_text,
                'count'   => $count,
                'percent' => $percent,
            ];
        })->sortByDesc('count')->values();

        return view('polls.show', compact(
            'poll',
            'totalVotes',
            'myVote',
            'hasVoted',
            'results',
        ));
    }

    /**
     * POST /polls/{poll}/vote
     * Cast a vote. Idempotent — second attempt is blocked gracefully.
     */
    public function vote(Request $request, Poll $poll): RedirectResponse
    {
        Gate::authorize('vote', $poll);

        // Double-check: has this member already voted?
        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyVoted) {
            return redirect()->route('polls.show', $poll)
                ->with('error', 'You have already voted in this poll.');
        }

        $validated = $request->validate([
            'poll_option_id' => [
                'required',
                'integer',
                // Must belong to this poll
                function ($attribute, $value, $fail) use ($poll) {
                    $exists = $poll->options()->where('id', $value)->exists();
                    if (! $exists) {
                        $fail('Invalid option selected.');
                    }
                },
            ],
        ]);

        PollVote::create([
            'poll_id'        => $poll->id,
            'poll_option_id' => $validated['poll_option_id'],
            'user_id'        => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($poll)
            ->log("Voted in poll: {$poll->question}");

        return redirect()->route('polls.show', $poll)
            ->with('success', 'Your vote has been recorded. Here are the results.');
    }
}
