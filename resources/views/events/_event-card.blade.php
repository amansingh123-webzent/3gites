@php
    $isPast      = $isPast ?? false;
    $rsvpStatus  = $myRsvps[$event->id] ?? null;
    $isAdmin     = auth()->check() && auth()->user()->hasRole('admin');
    $statusColor = match($rsvpStatus) {
        'attending'     => 'bg-emerald-100 text-emerald-700',
        'not_attending' => 'bg-red-100 text-red-600',
        'maybe'         => 'bg-amber-100 text-amber-700',
        default         => null,
    };
    $statusLabel = match($rsvpStatus) {
        'attending'     => '✓ Attending',
        'not_attending' => '✗ Not Going',
        'maybe'         => '? Maybe',
        default         => null,
    };
@endphp

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden {{ $isPast ? 'opacity-70' : '' }} hover:shadow-md transition-shadow duration-200">
    <div class="flex">

        {{-- Date badge --}}
        <div class="bg-navy-900 {{ $isPast ? 'opacity-60' : '' }} flex flex-col items-center justify-center px-5 py-4 min-w-[72px] flex-shrink-0">
            <span class="text-gold-400 text-xs font-bold uppercase tracking-widest leading-none">
                {{ $event->event_date->format('M') }}
            </span>
            <span class="text-white text-3xl font-bold font-playfair leading-none mt-1">
                {{ $event->event_date->format('d') }}
            </span>
            <span class="text-slate-400 text-xs mt-1">
                {{ $event->event_date->format('Y') }}
            </span>
        </div>

        {{-- Event details --}}
        <div class="flex-1 px-5 py-4">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('events.show', $event) }}" class="group">
                        <h3 class="font-playfair font-bold text-navy-900 text-lg group-hover:text-gold-600 transition-colors leading-snug">
                            {{ $event->title }}
                        </h3>
                    </a>

                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-400">
                        <span>
                            <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $event->event_date->format('g:i A') }}
                        </span>
                        @if ($event->location)
                            <span>·</span>
                            <span>
                                <svg class="w-3.5 h-3.5 inline -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $event->location }}
                            </span>
                        @endif
                        @if (! $event->is_published)
                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">Draft</span>
                        @endif
                    </div>

                    @if ($event->description)
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed line-clamp-2">
                            {{ Str::limit(strip_tags($event->description), 160) }}
                        </p>
                    @endif
                </div>

                {{-- RSVP status badge or action --}}
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if ($statusColor && ! $isPast)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    @endif

                    @if (! $isPast)
                        <a
                            href="{{ route('events.show', $event) }}"
                            class="text-xs text-gold-600 hover:text-gold-700 font-semibold hover:underline"
                        >
                            {{ auth()->check() ? 'RSVP →' : 'Details →' }}
                        </a>
                    @endif

                    {{-- Admin controls --}}
                    @if ($isAdmin)
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.events.edit', $event) }}" class="text-xs text-slate-400 hover:text-navy-700 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="{{ route('admin.events.rsvps', $event) }}" class="text-xs text-slate-400 hover:text-navy-700 transition-colors" title="View RSVPs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
