@extends('layouts.app')
@section('title', 'RSVPs — ' . $event->title)

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <nav class="text-sm text-slate-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-700 transition-colors">Admin</a>
        <span class="mx-2">›</span>
        <a href="{{ route('admin.events.index') }}" class="hover:text-purple-700 transition-colors">Events</a>
        <span class="mx-2">›</span>
        <a href="{{ route('events.show', $event) }}" class="hover:text-purple-700 transition-colors">{{ Str::limit($event->title, 40) }}</a>
        <span class="mx-2">›</span>
        <span class="text-slate-800">RSVPs</span>
    </nav>

    {{-- Event summary --}}
    <div class="bg-purple-900 rounded-2xl px-7 py-6 mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold text-white">{{ $event->title }}</h1>
            <p class="text-purple-200 text-sm mt-1">
                @php $date = $event->start_at ?? $event->event_date; @endphp
                {{ $date?->format('l, F j, Y \a\t g:i A') }}
                @if ($event->location) · {{ $event->location }} @endif
            </p>
        </div>
        <div class="flex gap-3">
            @foreach (['attending' => ['emerald', '✓ Attending'], 'maybe' => ['amber', '? Maybe'], 'not_attending' => ['slate', '✗ Not Going']] as $status => [$color, $label])
            <div class="text-center bg-white/10 border border-white/20 rounded-xl px-4 py-2">
                <div class="text-xl font-bold text-white">{{ $counts[$status] ?? 0 }}</div>
                <div class="text-xs text-purple-300 mt-0.5">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- RSVP groups --}}
    @foreach ([
        'attending'     => ['✓ Attending',    'bg-emerald-100 text-emerald-700'],
        'maybe'         => ['? Maybe',         'bg-amber-100 text-amber-700'],
        'not_attending' => ['✗ Not Attending', 'bg-slate-100 text-slate-500'],
    ] as $status => [$label, $badge])
    @php $group = $rsvps[$status] ?? collect(); @endphp
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $badge }}">{{ $label }}</span>
            <span class="text-xs text-slate-400">{{ $group->count() }} {{ Str::plural('member', $group->count()) }}</span>
        </div>

        @if ($group->isEmpty())
        <p class="text-sm text-slate-400 pl-4 italic">No responses in this category.</p>
        @else
        <div class="card divide-y divide-slate-50 overflow-hidden">
            @foreach ($group as $rsvp)
            @php $photo = $rsvp->user->photo_recent ?? $rsvp->user->profile?->recent_photo ?? null; @endphp
            <div class="flex items-center gap-4 px-5 py-3 hover:bg-slate-50 transition-colors">
                <div class="w-9 h-9 rounded-full bg-purple-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                    @if ($photo)
                    <img src="{{ Storage::url($photo) }}" class="w-9 h-9 object-cover" alt="{{ $rsvp->user->name }}">
                    @else
                    <span class="text-xs font-bold text-purple-700 font-display">{{ strtoupper(substr($rsvp->user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <a href="{{ route('members.show', $rsvp->user) }}" class="font-semibold text-sm text-slate-800 hover:text-purple-900 transition-colors">
                        {{ $rsvp->user->name }}
                    </a>
                    <p class="text-xs text-slate-400">Responded {{ $rsvp->updated_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach

    <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-sm text-slate-500 mt-4">
        💡 <strong>Tip:</strong> To export this list, use your browser's print function (Ctrl+P / ⌘+P) and save as PDF.
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.events.index') }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">
            ← Back to Events
        </a>
    </div>

</div>

@endsection
