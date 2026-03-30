@extends('layouts.app')
@section('title', 'Manage Events')

@section('content')

<x-page-header title="Manage Events" :back="route('admin.dashboard')" backLabel="Admin">
    <x-slot name="actions">
        <a href="{{ route('admin.events.create') }}" class="btn-gold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Event
        </a>
    </x-slot>
</x-page-header>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card divide-y divide-slate-50 overflow-hidden">
        @forelse ($events as $event)
        @php $date = $event->start_at ?? $event->event_date; @endphp
        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="bg-purple-900 rounded-xl px-3 py-2 text-center min-w-[52px] flex-shrink-0 {{ $date?->isPast() ? 'opacity-40' : '' }}">
                <div class="text-gold-400 text-[10px] font-bold uppercase">{{ $date?->format('M') }}</div>
                <div class="text-white text-xl font-bold leading-none">{{ $date?->format('d') }}</div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-slate-800 text-sm truncate">{{ $event->title }}</span>
                    @if (! ($event->is_published ?? true))
                    <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full uppercase">Draft</span>
                    @endif
                    @if ($date?->isPast())
                    <span class="text-[10px] text-slate-400">(past)</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $date?->format('F j, Y · g:i A') }}
                    @if ($event->location) · {{ $event->location }} @endif
                </p>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.events.rsvps', $event) }}"
                   class="text-xs text-slate-400 hover:text-purple-700 transition-colors px-2 py-1 rounded border border-slate-200 hover:border-purple-300">
                    RSVPs
                </a>
                <a href="{{ route('admin.events.edit', $event) }}"
                   class="text-xs text-slate-400 hover:text-purple-700 transition-colors px-2 py-1 rounded border border-slate-200 hover:border-purple-300">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs px-2 py-1 rounded border transition-colors
                        {{ ($event->is_published ?? true)
                            ? 'border-emerald-200 text-emerald-600 hover:border-emerald-400'
                            : 'border-amber-200 text-amber-600 hover:border-amber-400' }}">
                        {{ ($event->is_published ?? true) ? 'Published' : 'Draft' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this event?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs px-2 py-1 rounded border border-red-200 text-red-600 hover:border-red-400 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-slate-400">
            <p>No events yet. <a href="{{ route('admin.events.create') }}" class="text-gold-600 hover:underline">Create the first one →</a></p>
        </div>
        @endforelse
    </div>

    @if ($events->hasPages())
    <div class="mt-6">{{ $events->links() }}</div>
    @endif

</div>

@endsection
