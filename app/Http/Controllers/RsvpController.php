<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    /**
     * POST /events/{event}/rsvp
     * Create or update RSVP. One row per user per event — upsert pattern.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        if (! $event->is_published) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:attending,not_attending,maybe'],
        ]);

        Rsvp::updateOrCreate(
            [
                'user_id'  => auth()->id(),
                'event_id' => $event->id,
            ],
            [
                'status' => $validated['status'],
            ]
        );

        $label = match ($validated['status']) {
            'attending'     => "You're going! See you there.",
            'not_attending' => "RSVP recorded. We'll miss you!",
            'maybe'         => "Noted as Maybe. Update anytime.",
        };

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event)
            ->log("RSVP: {$validated['status']} for event: {$event->title}");

        return back()->with('success', $label);
    }

    /**
     * DELETE /events/{event}/rsvp
     * Remove the member's RSVP from this event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        Rsvp::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event)
            ->log("RSVP removed for event: {$event->title}");

        return back()->with('success', 'Your RSVP has been removed.');
    }
}
