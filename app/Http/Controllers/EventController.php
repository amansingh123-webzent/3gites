<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * GET /events
     * Public listing: upcoming + past events, plus calendar data.
     */
    public function index(Request $request): View
    {
        $now = now();

        // Calendar navigation
        $year = (int) $request->query('year', $now->year);
        $month = (int) $request->query('month', $now->month);
        
        // Clamp to sane values
        $year = max(2020, min(2100, $year));
        $month = max(1, min(12, $month));
        
        $calendarDate = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth = $calendarDate->copy()->subMonth();
        $nextMonth = $calendarDate->copy()->addMonth();
        
        // Generate navigation URLs
        $prevMonthUrl = route('events.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]);
        $nextMonthUrl = route('events.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]);
        
        // Calendar data for current month
        $calendarEvents = Event::where('is_published', true)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get();
            
        $eventDays = $calendarEvents->pluck('event_date')->map->day->toArray();
        $calendarMonth = $calendarDate->format('F Y');
        $calendarDaysInMonth = $calendarDate->daysInMonth;
        $calendarStartDay = $calendarDate->dayOfWeek; // 0 = Sunday, 6 = Saturday

        // Upcoming — published, future or today
        $upcoming = Event::where('is_published', true)
            ->where('event_date', '>=', $now->startOfDay()->copy())
            ->orderBy('event_date')
            ->get();

        // Past — published, before today, newest first
        $past = Event::where('is_published', true)
            ->where('event_date', '<', $now->startOfDay()->copy())
            ->orderByDesc('event_date')
            ->limit(12) // last 12 past events
            ->get();

        // Calendar: map event dates for the Alpine calendar component
        // Format: ['YYYY-MM-DD' => [event_id, title, ...], ...]
        // Fetch events for current month only (AJAX will handle other months)
        $calendarEventsJson = $calendarEvents
            ->groupBy(fn ($e) => $e->event_date->format('Y-m-d'))
            ->map(fn ($group) => $group->map(fn ($e) => [
                'id'    => $e->id,
                'title' => $e->title,
                'url'   => route('events.show', $e),
            ])->values())
            ->toJson();

        // Current user's RSVPs (for showing status on listing)
        $myRsvps = auth()->check()
            ? Rsvp::where('user_id', auth()->id())
                ->pluck('status', 'event_id')
                ->toArray()
            : [];

        return view('events.index', compact(
            'upcoming',
            'past',
            'calendarEvents',
            'calendarEventsJson',
            'myRsvps',
            'prevMonthUrl',
            'nextMonthUrl',
            'calendarMonth',
            'calendarDaysInMonth',
            'calendarStartDay',
            'eventDays',
        ));
    }

    /**
     * GET /events/{event}
     * Public event detail page with RSVP widget.
     */
    public function show(Event $event): View
    {
        // Guests see published events; admins see all
        if (! $event->is_published && ! (auth()->check() && auth()->user()->hasRole('admin'))) {
            abort(404);
        }

        // RSVP counts
        $rsvpCounts = [
            'attending'     => $event->rsvps()->where('status', 'attending')->count(),
            'not_attending' => $event->rsvps()->where('status', 'not_attending')->count(),
            'maybe'         => $event->rsvps()->where('status', 'maybe')->count(),
        ];

        // Current user's RSVP status
        $myRsvp = auth()->check()
            ? $event->rsvps()->where('user_id', auth()->id())->first()
            : null;

        // Attendee list (for members — shows who's coming)
        $attendees = $event->rsvps()
            ->with('user.profile')
            ->where('status', 'attending')
            ->get();

        return view('events.show', compact('event', 'rsvpCounts', 'myRsvp', 'attendees'));
    }

    /**
     * GET /events/calendar-data
     * AJAX endpoint for calendar component
     */
    public function calendarData(Request $request)
    {
        $year  = (int) $request->query('year',  now()->year);
        $month = (int) $request->query('month', now()->month);

        // Clamp to sane values
        $year  = max(2020, min(2100, $year));
        $month = max(1, min(12, $month));

        $events = Event::where('is_published', true)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get()
            ->groupBy(fn ($e) => $e->event_date->format('Y-m-d'))
            ->map(fn ($group) => $group->map(fn ($e) => [
                'id'    => $e->id,
                'title' => $e->title,
                'url'   => route('events.show', $e),
            ])->values());

        return response()->json($events);
    }
}
