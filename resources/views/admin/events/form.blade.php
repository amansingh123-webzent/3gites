@extends('layouts.app')
@section('title', $event->exists ? 'Edit Event' : 'New Event')

@section('content')

<x-page-header :title="$event->exists ? 'Edit Event' : 'Create Event'"
    :back="route('admin.events.index')" backLabel="All Events" />

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="card overflow-hidden">
        <x-card-header :title="$event->exists ? 'Edit Event Details' : 'New Event'"
            :subtitle="$event->exists ? $event->title : 'Fill in the details below'" />

        <form method="POST"
              action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}"
              class="px-7 py-7 space-y-6">
            @csrf
            @if ($event->exists) @method('PATCH') @endif

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Event Title <span class="text-red-500">*</span>
                </label>
                <input id="title" type="text" name="title" value="{{ old('title', $event->title) }}"
                    required maxlength="255"
                    class="w-full border {{ $errors->has('title') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                    placeholder="50th Reunion Dinner">
                @error('title')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="event_date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Date & Time <span class="text-red-500">*</span>
                    </label>
                    <input id="event_date" type="datetime-local" name="event_date"
                        value="{{ old('event_date', $event->exists ? $event->event_date?->format('Y-m-d\TH:i') : '') }}"
                        required
                        class="w-full border {{ $errors->has('event_date') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                    @error('event_date')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="location" class="block text-sm font-semibold text-slate-700 mb-1.5">Location</label>
                    <input id="location" type="text" name="location" value="{{ old('location', $event->location) }}"
                        maxlength="500"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                        placeholder="Le Grand Hôtel, Kingston">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea id="description" name="description" rows="6" maxlength="5000"
                    class="w-full border {{ $errors->has('description') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-y"
                    placeholder="Tell members what to expect: dress code, agenda, parking, what to bring…">{{ old('description', $event->description) }}</textarea>
                @error('description')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <input id="is_published" type="checkbox" name="is_published" value="1"
                    {{ old('is_published', $event->is_published ?? false) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-gold-500 focus:ring-gold-500">
                <div>
                    <label for="is_published" class="block text-sm font-semibold text-slate-700 cursor-pointer">Publish this event</label>
                    <p class="text-xs text-slate-400 mt-0.5">Unpublished events are only visible to admins.</p>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    {{ $event->exists ? 'Save Changes' : 'Create Event' }}
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn-ghost">Cancel</a>

                @if ($event->exists)
                <button type="button" 
                        class="ml-auto text-sm text-red-500 hover:text-red-700 transition-colors"
                        onclick="if(confirm('Delete this event? All RSVPs will also be deleted.')) { 
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('admin.events.destroy', $event) }}';
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            form.appendChild(csrf);
                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        }">
                    Delete Event
                </button>
                @endif
            </div>

        </form>
    </div>
</div>

@endsection
