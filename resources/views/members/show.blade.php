@extends('layouts.app')
@section('title', $member->name ?? $user->name)

@section('content')

@php $person = $member ?? $user; $status = $person->status ?? $person->member_status ?? 'active'; @endphp

{{-- Page header --}}
<div class="bg-purple-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('members.index') }}" class="inline-flex items-center gap-1 text-purple-300 hover:text-white text-sm mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Class Directory
        </a>
        <div class="flex items-center gap-3 flex-wrap">
            <h1 class="font-display text-3xl font-semibold text-white">{{ $person->name }}</h1>
            @if($status === 'active')
                <span class="badge-active">Active</span>
            @elseif($status === 'searching')
                <span class="badge-searching">Searching</span>
            @elseif(in_array($status, ['memoriam','deceased']))
                <span class="badge-memoriam">In Memoriam</span>
            @endif
        </div>
    </div>
</div>

{{-- Searching banner --}}
@if($status === 'searching')
<div class="bg-amber-50 border-b border-amber-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <div>
            <p class="text-amber-800 font-semibold">We Are Looking For <strong>{{ $person->name }}</strong></p>
            <p class="text-amber-700 text-sm">If you know how to reach this classmate, please <a href="{{ route('contact.index') }}" class="underline hover:text-amber-900">contact us</a>.</p>
        </div>
    </div>
</div>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Photos row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Circa 1975 Photo --}}
                <div class="card overflow-hidden" x-data>
                    <div class="bg-slate-50 border-b border-slate-100 px-5 py-3 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">CIRCA 1975</h3>
                        @can('update', $person)
                        <button
                            @click="$refs.teenUpload.classList.toggle('hidden')"
                            class="text-xs text-gold-600 hover:text-gold-700 font-medium transition-colors"
                        >Change Photo</button>
                        @endcan
                    </div>
                    <div class="aspect-[4/5] bg-slate-100 relative">
                        @php $photo1975 = $person->photo_1975 ?? $person->profile?->teen_photo; @endphp
                        @if($photo1975)
                        <img src="{{ Storage::url($photo1975) }}" alt="{{ $person->name }} circa 1975" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-slate-400">No photo yet</p>
                        </div>
                        @endif
                    </div>
                    @can('update', $person)
                    <div x-ref="teenUpload" class="hidden bg-slate-800 px-5 py-4">
                        <form method="POST" action="{{ route('profile.photo.teen', $person) }}" enctype="multipart/form-data">
                            @csrf
                            <x-upload-zone name="teen_photo" />
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="btn-gold text-xs px-4 py-2 rounded-lg">Upload</button>
                            </div>
                        </form>
                    </div>
                    @endcan
                </div>

                {{-- Recent Photo --}}
                <div class="card overflow-hidden" x-data>
                    <div class="bg-slate-50 border-b border-slate-100 px-5 py-3 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Recent Photo</h3>
                        @can('update', $person)
                        <button
                            @click="$refs.recentUpload.classList.toggle('hidden')"
                            class="text-xs text-gold-600 hover:text-gold-700 font-medium transition-colors"
                        >Change Photo</button>
                        @endcan
                    </div>
                    <div class="aspect-[4/5] bg-slate-100 relative">
                        @php $photoRecent = $person->photo_recent ?? $person->profile?->recent_photo; @endphp
                        @if($photoRecent)
                        <img src="{{ Storage::url($photoRecent) }}" alt="{{ $person->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-slate-400">No photo yet</p>
                        </div>
                        @endif
                    </div>
                    @can('update', $person)
                    <div x-ref="recentUpload" class="hidden bg-slate-800 px-5 py-4">
                        <form method="POST" action="{{ route('profile.photo.recent', $person) }}" enctype="multipart/form-data">
                            @csrf
                            <x-upload-zone name="recent_photo" />
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="btn-gold text-xs px-4 py-2 rounded-lg">Upload</button>
                            </div>
                        </form>
                    </div>
                    @endcan
                </div>
            </div>

            {{-- Bio sections --}}
            @foreach(['bio_about' => ['About', '👤'], 'bio_career' => ['Career & Work', '💼'], 'bio_family' => ['Family', '👨‍👩‍👧'], 'bio_retirement' => ['Retirement & Hobbies', '🌴']] as $field => [$label, $icon])
            @php
                $content = $person->$field
                    ?? $person->profile?->bio
                    ?? null;
                // Map legacy field names
                if ($field === 'bio_career') $content = $person->bio_career ?? $person->profile?->career ?? null;
                if ($field === 'bio_family') $content = $person->bio_family ?? $person->profile?->family_info ?? null;
                if ($field === 'bio_retirement') $content = $person->bio_retirement ?? $person->profile?->retirement_info ?? null;
            @endphp
            <div class="card">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-lg">{{ $icon }}</span>
                    <h2 class="font-display text-xl font-semibold text-slate-800">{{ $label }}</h2>
                </div>
                <div class="px-6 py-5">
                    @if($content)
                    <p class="text-slate-600 leading-relaxed">{!! nl2br(e($content)) !!}</p>
                    @else
                    @can('update', $person)
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center">
                        <p class="text-slate-400 text-sm mb-2">No {{ strtolower($label) }} added yet.</p>
                        <a href="{{ route('profile.edit', $person) }}" class="text-purple-700 hover:text-purple-900 text-sm font-semibold transition-colors">Add your story →</a>
                    </div>
                    @else
                    <p class="text-slate-400 text-sm italic">Not yet added.</p>
                    @endcan
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            @can('update', $person)
            <a href="{{ route('profile.edit', $person) }}" class="btn-primary w-full justify-center block text-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
            @endcan

            <div class="card p-6">
                <h3 class="font-semibold text-slate-700 text-xs uppercase tracking-wide mb-4">Member Details</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Class</dt>
                        <dd class="text-slate-700 font-medium">Clarendon College 1975</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Status</dt>
                        <dd><x-badge :type="$status" /></dd>
                    </div>
                    @if($person->birthday || $person->birthday_month)
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Birthday</dt>
                        <dd class="text-slate-700 font-medium">
                            @if($person->birthday instanceof \Carbon\Carbon)
                                {{ $person->birthday->format('F j') }}
                            @else
                                @php $m = $person->birthday_month ?? $person->birth_month; $d = $person->birthday_day ?? $person->birth_day; @endphp
                                @if($m && $d) {{ \Carbon\Carbon::create(null, $m, $d)->format('F j') }} @endif
                            @endif
                        </dd>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500">Last Updated</dt>
                        <dd class="text-slate-400">{{ $person->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <a href="{{ route('members.index') }}" class="block text-center text-sm text-slate-500 hover:text-purple-900 transition-colors">
                ← Back to Directory
            </a>
        </div>
    </div>
</div>

@endsection
