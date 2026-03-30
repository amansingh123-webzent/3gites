@extends('layouts.app')
@section('title', 'Leadership')

@section('content')

<div class="bg-purple-900 py-12 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-white">Leadership Structure</h1>
        <p class="text-purple-200 text-sm mt-2">The team keeping the Class of 1975 connected</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="space-y-3 mb-10">
        @forelse ($roles as $index => $role)
        <div class="card px-6 py-5 flex items-center gap-5">
            <div class="w-10 h-10 rounded-full bg-purple-900 flex items-center justify-center flex-shrink-0">
                <span class="font-bold text-gold-400 text-sm font-display">{{ $index + 1 }}</span>
            </div>
            <div class="flex-1">
                <p class="font-display font-bold text-slate-800 text-lg leading-snug">{{ $role['name'] }}</p>
                <p class="text-slate-500 text-sm mt-0.5">{{ $role['role'] }}</p>
            </div>
            @if (! empty($role['since']))
            <div class="text-right flex-shrink-0">
                <p class="text-xs text-slate-400">Since</p>
                <p class="text-sm font-semibold text-slate-600">{{ $role['since'] }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-10 text-slate-400 card p-8">
            Leadership information is being updated.
        </div>
        @endforelse
    </div>

    @can('admin')
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm text-amber-800">
        <strong>Admin:</strong> To update the leadership list, edit
        <code class="bg-amber-100 px-1.5 py-0.5 rounded text-xs">config/leadership.php</code>
        on the server.
    </div>
    @endcan

    <div class="mt-8 text-center">
        <a href="{{ route('about') }}" class="text-sm text-slate-400 hover:text-purple-900 transition-colors">
            ← About Us
        </a>
    </div>
</div>

@endsection
