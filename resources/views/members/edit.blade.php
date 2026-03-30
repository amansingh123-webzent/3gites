@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')

@php $person = $user; @endphp

<x-page-header
    title="Edit Profile"
    :subtitle="$person->name . ' · Class of 1975'"
    :back="route('members.show', $person)"
    backLabel="Back to Profile"
/>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <form method="POST" action="{{ route('profile.update', $person) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Section 1: Personal Information --}}
        <div class="card overflow-hidden">
            <x-card-header title="Personal Information" />
            <div class="px-8 py-6 space-y-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $person->name) }}" required
                        class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone <span class="font-normal text-slate-400">(private)</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $person->phone) }}" placeholder="+1 555 000 0000"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        @can('admin')
                        <select id="status" name="status" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 bg-white">
                            @foreach(['active' => 'Active', 'searching' => 'Searching', 'memoriam' => 'In Memoriam', 'locked' => 'Locked'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $person->status ?? $person->member_status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" value="{{ ucfirst($person->status ?? $person->member_status ?? 'Active') }}" readonly class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-500">
                        @endcan
                    </div>
                </div>

                {{-- Birthday --}}
                <div>
                    <p class="text-sm font-semibold text-slate-700 mb-2">Birthday <span class="font-normal text-slate-400">(for reminders — year kept private)</span></p>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Month</label>
                            <select name="birth_month" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                                <option value="">—</option>
                                @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" {{ (old('birth_month', $person->birthday_month ?? $person->birth_month ?? $person->birthday?->birth_month) == $m) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Day</label>
                            <select name="birth_day" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                                <option value="">—</option>
                                @foreach(range(1,31) as $d)
                                <option value="{{ $d }}" {{ (old('birth_day', $person->birthday_day ?? $person->birth_day ?? $person->birthday?->birth_day) == $d) ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Year <span class="text-slate-400">(private)</span></label>
                            <input type="number" name="birth_year"
                                value="{{ old('birth_year', $person->birthday_year ?? $person->birth_year ?? $person->birthday?->birth_year) }}"
                                min="1920" max="1980" placeholder="1957"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Your Story --}}
        <div class="card overflow-hidden">
            <x-card-header title="Your Story" subtitle="Share your journey with classmates" />
            <div class="px-8 py-6 space-y-5">
                @php
                    $fields = [
                        'bio' => ['About Me', 'Tell your classmates what you\'ve been up to since 1975…'],
                        'career' => ['Career & Work Life', 'What did your career look like? Where did you work?'],
                        'family_info' => ['Family', 'Spouse, children, grandchildren…'],
                        'retirement_info' => ['Retirement & Hobbies', 'Are you retired? What do you enjoy now?'],
                    ];
                @endphp
                @foreach($fields as $field => [$label, $placeholder])
                <div x-data="{ count: {{ strlen(old($field, $person->profile?->$field ?? '')) }} }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="{{ $field }}" class="block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        <span class="text-xs text-slate-400" :class="count > 450 ? 'text-amber-500' : ''"><span x-text="count"></span>/500</span>
                    </div>
                    <textarea id="{{ $field }}" name="{{ $field }}" rows="4" maxlength="500"
                        @input="count = $el.value.length"
                        placeholder="{{ $placeholder }}"
                        class="w-full border {{ $errors->has($field) ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-none"
                    >{{ old($field, $person->profile?->$field ?? '') }}</textarea>
                    @error($field)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Photos --}}
        <div class="card overflow-hidden">
            <x-card-header title="Your Photos" subtitle="JPG or PNG, max 5MB each" />
            <div class="px-8 py-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 mb-2">Circa 1975</p>
                        @if($person->profile?->teen_photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($person->profile->teen_photo) }}" class="w-full max-h-40 object-cover rounded-xl mb-3" alt="Current 1975 photo">
                        @endif
                        <x-upload-zone name="teen_photo" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700 mb-2">Recent Photo</p>
                        @if($person->profile?->recent_photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($person->profile->recent_photo) }}" class="w-full max-h-40 object-cover rounded-xl mb-3" alt="Current recent photo">
                        @endif
                        <x-upload-zone name="recent_photo" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Changes
            </button>
            <a href="{{ route('members.show', $person) }}" class="btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
