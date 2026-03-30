@extends('layouts.app')
@section('title', $event->title)

@section('content')

{{-- Page header --}}
<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-purple-300 hover:text-white text-sm mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All Events
        </a>
        <h1 class="font-display text-3xl font-semibold text-white mb-3">{{ $event->title }}</h1>
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-purple-200 text-sm">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $event->start_at?->format('l, F j, Y') ?? $event->event_date?->format('l, F j, Y') }}
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $event->start_at?->format('g:i A') ?? $event->event_date?->format('g:i A') }}
            </span>
            @if($event->location)
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $event->location }}
            </span>
            @endif
        </div>
        @can('admin')
        <div class="flex items-center gap-2 mt-4">
            <a href="{{ route('admin.events.edit', $event) }}" class="text-xs border border-purple-700 text-purple-200 hover:border-gold-400 hover:text-gold-400 px-3 py-1.5 rounded-lg transition-colors">Edit</a>
            <a href="{{ route('admin.events.rsvps', $event) }}" class="text-xs border border-purple-700 text-purple-200 hover:border-gold-400 hover:text-gold-400 px-3 py-1.5 rounded-lg transition-colors">View RSVPs</a>
        </div>
        @endcan
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left: Description + Attendees --}}
        <div class="lg:col-span-2 space-y-6">

            @if($event->description)
            <div class="card p-6">
                <h2 class="font-display text-xl font-semibold text-slate-800 mb-4">About This Event</h2>
                <div class="text-slate-600 leading-relaxed prose max-w-none">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
            @endif

            {{-- Who's coming --}}
            @if(isset($attendees) && $attendees->count())
            <div class="card p-6">
                <h2 class="font-display text-xl font-semibold text-slate-800 mb-4">
                    Who's Coming
                    <span class="text-base font-normal text-slate-400 ml-1">({{ $rsvpCounts['attending'] ?? $attendees->count() }})</span>
                </h2>
                <div class="flex flex-wrap gap-3">
                    @foreach($attendees as $rsvp)
                    @php $attendeePerson = $rsvp->user ?? $rsvp->member ?? null; @endphp
                    @if($attendeePerson)
                    <a href="{{ route('members.show', $attendeePerson) }}" class="flex items-center gap-2 group" title="{{ $attendeePerson->name }}">
                        <div class="w-10 h-10 rounded-full bg-purple-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                            @php $aPhoto = $attendeePerson->photo_recent ?? $attendeePerson->profile?->recent_photo ?? null; @endphp
                            @if($aPhoto)
                            <img src="{{ Storage::url($aPhoto) }}" class="w-full h-full object-cover" alt="{{ $attendeePerson->name }}">
                            @else
                            <span class="text-sm font-bold text-purple-700 font-display">{{ strtoupper(substr($attendeePerson->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="text-sm text-slate-700 group-hover:text-purple-900 transition-colors">{{ $attendeePerson->name }}</span>
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right: RSVP widget --}}
        <div class="lg:col-span-1">
            <div class="sticky top-20">
                <x-rsvp-widget
                    :event="$event"
                    :userRsvp="$myRsvp?->status ?? null"
                    :counts="$rsvpCounts ?? ['attending' => 0, 'maybe' => 0, 'not_going' => 0]"
                />
                <div class="mt-3 text-center">
                    <a href="{{ route('events.index') }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">← Back to Events</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
