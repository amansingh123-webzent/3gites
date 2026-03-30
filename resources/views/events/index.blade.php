@extends('layouts.app')
@section('title', 'Events')

@section('content')

<x-page-header title="Events & Calendar" subtitle="Reunions, gatherings, and class milestones">
    @can('admin')
    <x-slot name="actions">
        <a href="{{ route('admin.events.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Event
        </a>
    </x-slot>
    @endcan
</x-page-header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col lg:flex-row gap-8">

    {{-- Left: Event list --}}
    <div class="flex-1 min-w-0">
        <h2 class="font-display text-2xl font-semibold text-slate-800 mb-5">Upcoming Events</h2>

        @forelse($upcoming ?? [] as $event)
        <div class="card card-hover mb-4">
            <div class="flex items-start gap-4 p-5">
                {{-- Date badge --}}
                <div class="w-14 bg-purple-900 rounded-xl text-center py-2 flex flex-col items-center flex-shrink-0">
                    <span class="text-gold-400 text-xs font-semibold uppercase leading-none">{{ $event->start_at?->format('M') ?? $event->event_date?->format('M') }}</span>
                    <span class="text-white font-bold font-display text-2xl leading-tight">{{ $event->start_at?->format('d') ?? $event->event_date?->format('d') }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-display text-xl font-semibold text-slate-800">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-purple-900 transition-colors">{{ $event->title }}</a>
                        </h3>
                        @auth
                        @if(isset($myRsvps[$event->id]) || $event->userRsvp)
                        @php $rsvpStatus = $myRsvps[$event->id]?->status ?? $event->userRsvp?->status ?? null; @endphp
                        @if($rsvpStatus)
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0 {{ $rsvpStatus === 'attending' ? 'bg-emerald-100 text-emerald-700' : ($rsvpStatus === 'maybe' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                            ✓ {{ ucfirst($rsvpStatus === 'not_attending' ? 'Not Going' : $rsvpStatus) }}
                        </span>
                        @endif
                        @endif
                        @endauth
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-sm text-slate-500">
                        <span>📅 {{ $event->start_at?->format('l, F j, Y') ?? $event->event_date?->format('l, F j, Y') }}</span>
                        @if($event->start_at ?? $event->event_date)
                        <span>🕐 {{ $event->start_at?->format('g:i A') ?? $event->event_date?->format('g:i A') }}</span>
                        @endif
                        @if($event->location)
                        <span>📍 {{ $event->location }}</span>
                        @endif
                    </div>
                    @if($event->description)
                    <p class="text-slate-600 text-sm mt-2 line-clamp-2">{{ Str::limit(strip_tags($event->description), 180) }}</p>
                    @endif
                    <a href="{{ route('events.show', $event) }}" class="inline-block mt-3 text-purple-700 hover:text-purple-900 text-sm font-semibold transition-colors">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center">
            <div class="text-5xl mb-3">📅</div>
            <p class="text-slate-500 text-lg">No upcoming events scheduled.</p>
            @can('admin')
            <a href="{{ route('admin.events.create') }}" class="mt-4 inline-block text-gold-600 hover:underline text-sm">Schedule one →</a>
            @endcan
        </div>
        @endforelse

        {{-- Past events (collapsible) --}}
        @if(isset($past) && $past->count())
        <div x-data="{ open: false }" class="mt-6">
            <button @click="open = !open" class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-semibold mb-4 transition-colors">
                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span x-text="open ? 'Hide Past Events' : 'Show Past Events ({{ $past->count() }})'"></span>
            </button>
            <div x-show="open" x-collapse class="space-y-3">
                @foreach($past as $event)
                <div class="card opacity-70">
                    <div class="flex items-start gap-4 p-4">
                        <div class="w-12 bg-slate-200 rounded-xl text-center py-1.5 flex-shrink-0">
                            <div class="text-slate-500 text-xs uppercase">{{ $event->start_at?->format('M') ?? $event->event_date?->format('M') }}</div>
                            <div class="text-slate-600 text-xl font-bold font-display">{{ $event->start_at?->format('d') ?? $event->event_date?->format('d') }}</div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-700">
                                <a href="{{ route('events.show', $event) }}" class="hover:text-purple-900 transition-colors">{{ $event->title }}</a>
                            </h4>
                            @if($event->location)<p class="text-slate-400 text-xs mt-0.5">{{ $event->location }}</p>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Calendar widget (sticky) --}}
    <div class="lg:w-80 flex-shrink-0">
        <div class="sticky top-20">
            <h3 class="font-display text-xl font-semibold text-slate-800 mb-4">Calendar</h3>
            <x-calendar-widget
                :monthLabel="$calendarMonth ?? now()->format('F Y')"
                :eventDays="$eventDays ?? []"
                :startDay="$calendarStartDay ?? 0"
                :daysInMonth="$calendarDaysInMonth ?? now()->daysInMonth"
                :prevUrl="$prevMonthUrl ?? null"
                :nextUrl="$nextMonthUrl ?? null"
            />

            {{-- Events for current month --}}
            @if(isset($calendarEvents) && $calendarEvents->count())
            <div class="mt-6">
                <h4 class="font-display text-lg font-semibold text-slate-800 mb-3">Events in {{ $calendarMonth }}</h4>
                <ul class="space-y-3">
                    @foreach($calendarEvents->sortBy('event_date') as $event)
                    <li class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 text-purple-700 font-bold text-sm">
                            {{ $event->event_date->format('d') }}
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="text-slate-700 hover:text-purple-900 text-sm font-semibold transition-colors">
                            {{ $event->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="mt-6 p-4 bg-slate-50 rounded-lg text-center text-slate-500 text-sm">
                No events scheduled for {{ $calendarMonth }}.
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calendar data for Alpine.js
    window.calendarEvents = {!! $calendarEventsJson !!};
</script>
@endpush

@endsection
