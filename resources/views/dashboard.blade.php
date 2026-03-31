@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Greeting header --}}
<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="font-display text-3xl font-semibold text-white">Welcome back, {{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}</h1>
        <p class="text-purple-200 text-sm mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>
</div>

{{-- Birthday banner --}}
@if(isset($birthdaysToday) && $birthdaysToday->count())
<div class="bg-amber-50 border-b border-amber-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3 flex-wrap text-amber-800 text-sm">
        <span class="text-2xl">🎂</span>
        <span class="font-semibold">Birthday today:</span>
        @foreach($birthdaysToday as $member)
            <a href="{{ route('members.show', $member) }}" class="font-bold hover:underline">{{ $member->name }}</a>@if(!$loop->last),@endif
        @endforeach
        <span>— send them a message on the board!</span>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column (2/3) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Upcoming events --}}
            <div class="card">
                <div class="bg-purple-900 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h2 class="font-display text-xl font-semibold text-white">My Upcoming Events</h2>
                    <a href="{{ route('events.index') }}" class="text-purple-300 hover:text-gold-400 text-sm transition-colors">View all →</a>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($nextEvent ? [$nextEvent] : [] as $event)
                    <div class="flex items-start gap-4 px-6 py-4">
                        <div class="w-14 bg-purple-900 rounded-xl text-center py-2 flex flex-col items-center flex-shrink-0">
                            <span class="text-gold-400 text-xs font-semibold uppercase leading-none">{{ $event->event_date->format('M') }}</span>
                            <span class="text-white font-bold font-display text-2xl leading-tight">{{ $event->event_date->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('events.show', $event) }}" class="font-semibold text-slate-800 hover:text-purple-900 transition-colors">{{ $event->title }}</a>
                            @if($event->location)<p class="text-sm text-slate-500 mt-0.5">📍 {{ $event->location }}</p>@endif
                            <p class="text-xs text-slate-400 mt-0.5">{{ $event->event_date->format('g:i A') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-slate-400 text-sm">No upcoming events. <a href="{{ route('events.index') }}" class="text-gold-600 hover:underline">Browse events →</a></p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent board activity --}}
            <div class="card">
                <div class="bg-purple-900 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h2 class="font-display text-xl font-semibold text-white">Recent Board Activity</h2>
                    <a href="{{ route('posts.index') }}" class="text-purple-300 hover:text-gold-400 text-sm transition-colors">View all →</a>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($recentPosts ?? [] as $post)
                    <div class="flex items-start gap-3 px-6 py-4">
                        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-display font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($post->author->name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('posts.show', $post) }}" class="font-semibold text-slate-800 hover:text-purple-900 text-sm transition-colors line-clamp-1">{{ $post->title }}</a>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $post->author->name ?? 'Member' }} &bull; {{ $post->created_at->diffForHumans() }}
                                @if($post->comments_count) &bull; 💬 {{ $post->comments_count }} @endif
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-slate-400 text-sm">No posts yet. <a href="{{ route('posts.create') }}" class="text-gold-600 hover:underline">Start a conversation →</a></p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right column (1/3) --}}
        <div class="space-y-6">

            {{-- Birthdays this month --}}
            <div class="card">
                <div class="bg-purple-900 text-white px-6 py-4 rounded-t-2xl">
                    <h2 class="font-display text-xl font-semibold text-white">Birthdays This Month 🎂</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($birthdays ?? [] as $birthday)
                    <div class="flex items-center gap-3 px-6 py-3">
                        <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center text-gold-600 font-bold font-display text-sm flex-shrink-0">
                            {{ $birthday->birth_day }}
                        </div>
                        <a href="{{ route('members.show', $birthday->user) }}" class="font-semibold text-slate-700 hover:text-purple-900 text-sm transition-colors">{{ $birthday->user->name }}</a>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">No birthdays this month.</div>
                    @endforelse
                </div>
            </div>

            {{-- Profile completion hint --}}
            @auth
            @if(!auth()->user()->member?->photo_1975 || !auth()->user()->member?->bio_about)
            <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5">
                <h3 class="font-semibold text-purple-900 text-sm mb-2">Complete Your Profile</h3>
                <p class="text-slate-600 text-xs mb-3 leading-relaxed">Add your photo and bio so classmates can reconnect with you.</p>
                @if(auth()->user()->member)
                <a href="{{ route('profile.edit', auth()->user()->member) }}" class="bg-gold-500 hover:bg-gold-400 text-purple-950 font-bold px-4 py-2 rounded-lg text-xs transition-colors inline-flex items-center gap-1">
                    Update Profile →
                </a>
                @endif
            </div>
            @endif
            @endauth

            {{-- Quick links --}}
            <div class="card p-5">
                <h3 class="font-semibold text-slate-700 text-sm mb-3 uppercase tracking-wide text-xs">Quick Links</h3>
                <div class="space-y-2">
                    <a href="{{ route('members.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-purple-900 text-sm transition-colors py-1">👥 Class Directory</a>
                    <a href="{{ route('gallery.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-purple-900 text-sm transition-colors py-1">🖼️ Photo Gallery</a>
                    <a href="{{ route('polls.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-purple-900 text-sm transition-colors py-1">📊 Class Polls</a>
                    <a href="https://www.zeffy.com/en-US/donation-form/donate-to-3gites--1975" target="_blank" class="flex items-center gap-2 text-slate-600 hover:text-purple-900 text-sm transition-colors py-1">💛 Donate to Reunion</a>
                    <a href="{{ route('store.index') }}" class="flex items-center gap-2 text-slate-600 hover:text-purple-900 text-sm transition-colors py-1">🛍️ Class Store</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
