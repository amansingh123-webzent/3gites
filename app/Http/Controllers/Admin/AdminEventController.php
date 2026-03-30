<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    /**
     * GET /admin/events
     * Admin listing of all events (published + unpublished).
     */
    public function index(): View
    {
        $events = Event::orderByDesc('event_date')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    /**
     * GET /admin/events/create
     */
    public function create(): View
    {
        return view('admin.events.form', ['event' => new Event]);
    }

    /**
     * POST /admin/events
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEvent($request);

        $event = Event::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event)
            ->log("Event created: {$event->title}");

        return redirect()->route('events.show', $event)
            ->with('success', "Event \"{$event->title}\" created.");
    }

    /**
     * GET /admin/events/{event}/edit
     */
    public function edit(Event $event): View
    {
        return view('admin.events.form', compact('event'));
    }

    /**
     * PATCH /admin/events/{event}
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $this->validateEvent($request);

        $event->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event)
            ->log("Event updated: {$event->title}");

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated.');
    }

    /**
     * DELETE /admin/events/{event}
     */
    public function destroy(Event $event): RedirectResponse
    {
        $title = $event->title;
        $event->delete();

        activity()
            ->causedBy(auth()->user())
            ->log("Event deleted: {$title}");

        return redirect()->route('admin.events.index')
            ->with('success', "Event \"{$title}\" deleted.");
    }

    /**
     * PATCH /admin/events/{event}/publish
     * Toggle published / draft state.
     */
    public function togglePublish(Event $event): RedirectResponse
    {
        $event->update(['is_published' => ! $event->is_published]);

        $state = $event->is_published ? 'published' : 'unpublished (draft)';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($event)
            ->log("Event {$state}: {$event->title}");

        return back()->with('success', "Event marked as {$state}.");
    }

    /**
     * GET /admin/events/{event}/rsvps
     * Full RSVP breakdown for one event.
     */
    public function rsvps(Event $event): View
    {
        $rsvps = Rsvp::where('event_id', $event->id)
            ->with('user.profile')
            ->orderBy('status')
            ->orderBy('created_at')
            ->get()
            ->groupBy('status');

        $counts = [
            'attending'     => ($rsvps['attending']     ?? collect())->count(),
            'not_attending' => ($rsvps['not_attending'] ?? collect())->count(),
            'maybe'         => ($rsvps['maybe']         ?? collect())->count(),
        ];

        return view('admin.events.rsvps', compact('event', 'rsvps', 'counts'));
    }

    // ── Shared validation ────────────────────────────────────────────────────

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'event_date'   => ['required', 'date', 'after_or_equal:today'],
            'location'     => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
    }
}
