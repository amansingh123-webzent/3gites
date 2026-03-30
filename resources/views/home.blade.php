@extends('layouts.app')
@section('title', 'Welcome — 3Gites-1975')

@section('content')

{{-- Hero --}}
<div class="relative bg-purple-950 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-950 via-purple-900 to-purple-800 opacity-90"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <div class="w-20 h-20 bg-gold-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
            <span class="font-display font-bold text-purple-950 text-3xl">3G</span>
        </div>
        <h1 class="font-display text-5xl sm:text-6xl font-bold text-white mb-4 leading-tight">
            3Gites-1975
        </h1>
        <p class="text-gold-400 text-xl tracking-wide mb-6">
            Clarendon College · Class of 1975 · Reunion Portal
        </p>
        <p class="text-purple-200 text-lg mb-10 leading-relaxed max-w-2xl mx-auto">
            A private community for the {{ ($stats['active'] ?? 0) + ($stats['searching'] ?? 0) + ($stats['deceased'] ?? 0) }} members
            of the Class of 1975.<br>
            Reconnect, reminisce, and celebrate a lifetime of friendship.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('members.index') }}" class="btn-gold">
                View Class Directory
            </a>
            @guest
            <a href="{{ route('login') }}" class="border border-white/30 hover:border-white text-white font-semibold px-8 py-3 rounded-xl text-sm transition-colors">
                Member Login
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="border border-white/30 hover:border-white text-white font-semibold px-8 py-3 rounded-xl text-sm transition-colors">
                My Dashboard
            </a>
            @endguest
        </div>
    </div>
</div>

{{-- Next event banner --}}
@if ($nextEvent)
<div class="bg-gold-500 py-4">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-purple-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="font-semibold text-purple-900 text-sm">
                Next Event: <strong>{{ $nextEvent->title }}</strong>
                · {{ ($nextEvent->start_at ?? $nextEvent->event_date)?->format('F j, Y') }}
                @if ($nextEvent->location) · {{ $nextEvent->location }} @endif
            </span>
        </div>
        <a href="{{ route('events.show', $nextEvent) }}" class="text-purple-900 underline text-sm font-medium hover:no-underline">
            View details →
        </a>
    </div>
</div>
@endif

{{-- Stats --}}
<div class="bg-cream-dark py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div class="card py-8">
                <div class="text-4xl font-bold text-purple-900 font-display">{{ $stats['active'] ?? 0 }}</div>
                <div class="text-slate-500 text-sm mt-2 font-semibold">Active Members</div>
                <div class="text-xs text-slate-400 mt-1">Connected & sharing</div>
            </div>
            <div class="card py-8">
                <div class="text-4xl font-bold text-amber-500 font-display">{{ $stats['searching'] ?? 0 }}</div>
                <div class="text-slate-500 text-sm mt-2 font-semibold">Being Searched For</div>
                <div class="text-xs text-slate-400 mt-1">Help us find them</div>
            </div>
            <div class="card py-8">
                <div class="text-4xl font-bold text-slate-400 font-display">{{ $stats['deceased'] ?? 0 }}</div>
                <div class="text-slate-500 text-sm mt-2 font-semibold">In Loving Memory</div>
                <div class="text-xs text-slate-400 mt-1">Forever in our hearts</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick links --}}
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <h2 class="font-display text-3xl font-bold text-slate-800 text-center mb-10">Explore the Portal</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ([
            ['title' => 'Members', 'desc' => 'The class directory — photos, bios, and stories', 'route' => 'members.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['title' => 'Events', 'desc' => 'Upcoming reunions, gatherings, and milestones', 'route' => 'events.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['title' => 'Gallery', 'desc' => 'Photos from then and now', 'route' => 'gallery.index', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['title' => 'Message Board', 'desc' => 'Stay in touch between reunions', 'route' => 'posts.index', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['title' => 'Tributes', 'desc' => 'Remembering those who have passed', 'route' => 'tributes.index', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['title' => 'About Us', 'desc' => 'Our story, our purpose, our legacy', 'route' => 'about', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $item)
        <a href="{{ route($item['route']) }}"
           class="card card-hover p-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 mb-1">{{ $item['title'] }}</h3>
                <p class="text-xs text-slate-500">{{ $item['desc'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Motto footer --}}
<div class="bg-purple-950 py-10 text-center">
    <p class="font-display text-gold-400 text-lg italic">"Perstare et Praestare"</p>
    <p class="text-purple-400 text-xs mt-1 tracking-widest uppercase">To Persevere and Excel · Clarendon College 1975</p>
</div>

@endsection
