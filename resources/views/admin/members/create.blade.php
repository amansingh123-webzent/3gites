@extends('layouts.app')
@section('title', 'Create Member')

@section('content')

<x-page-header title="Create Member" subtitle="Add a new classmate to the directory"
    :back="route('admin.dashboard')" backLabel="Admin" />

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
    <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 text-sm">
        Account created for <strong>{{ session('name') }}</strong>. A welcome email has been sent.
    </div>
    @endif

    <div class="card overflow-hidden">
        <x-card-header title="New Member Details" />

        <form method="POST" action="{{ route('admin.members.store') }}" class="px-7 py-7 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 transition"
                        placeholder="Enter full name">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 transition"
                        placeholder="member@example.com">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 transition"
                        placeholder="+1 (876) 000-0000">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="member_status">Member Status</label>
                    <select id="member_status" name="member_status" required
                        class="w-full border {{ $errors->has('member_status') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Status</option>
                        <option value="active"    {{ old('member_status') == 'active'    ? 'selected' : '' }}>Active Member</option>
                        <option value="searching" {{ old('member_status') == 'searching' ? 'selected' : '' }}>Searching (Not yet found)</option>
                        <option value="deceased"  {{ old('member_status') == 'deceased'  ? 'selected' : '' }}>In Memoriam (Deceased)</option>
                    </select>
                    @error('member_status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-slate-700 mb-2">Birthday</p>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Month</label>
                        <select name="birth_month" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">—</option>
                            @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ old('birth_month') == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Day</label>
                        <select name="birth_day" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">—</option>
                            @foreach(range(1, 31) as $day)
                            <option value="{{ $day }}" {{ old('birth_day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Year <span class="text-slate-400">(private)</span></label>
                        <input type="number" name="birth_year" value="{{ old('birth_year') }}"
                            min="1950" max="1965" placeholder="1957"
                            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-100 rounded-xl px-5 py-4 text-sm text-amber-800 space-y-1">
                <p class="font-semibold">Status Guide</p>
                <ul class="list-disc pl-4 mt-1 space-y-1 text-xs">
                    <li><strong>Active:</strong> Member has been found and will receive a login account and welcome email.</li>
                    <li><strong>Searching:</strong> We haven't found this person yet. No account created — they appear in the directory with an amber badge.</li>
                    <li><strong>In Memoriam:</strong> Classmate has passed away. A tribute page will be created automatically.</li>
                </ul>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary">Create Member</button>
                <a href="{{ route('admin.dashboard') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
