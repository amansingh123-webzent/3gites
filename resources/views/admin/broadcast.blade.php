@extends('layouts.app')
@section('title', 'Broadcast Email')

@section('content')

<x-page-header title="Broadcast Email" subtitle="Send an email to all {{ $activeCount ?? 29 }} active members"
    :back="route('admin.dashboard')" backLabel="Admin" />

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex items-start gap-3 mb-6">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-sm font-semibold text-amber-800">
            This will send an email to {{ $activeCount ?? 29 }} active members. Review carefully before sending.
        </p>
    </div>

    <div class="card overflow-hidden">
        <x-card-header title="Compose Broadcast">
            <x-slot name="actions">
                <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </x-slot>
        </x-card-header>

        <form method="POST" action="{{ route('admin.broadcast.send') }}" class="px-7 py-7 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="subject">Subject Line</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                    class="w-full border {{ $errors->has('subject') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                    placeholder="e.g., Important Update: Reunion Dinner Details">
                @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="body">Email Body</label>
                <textarea id="body" name="body" rows="12" required
                    class="w-full border {{ $errors->has('body') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 transition resize-y"
                    placeholder="Write your message here. This will be sent as plain text to all active members.">{{ old('body') }}</textarea>
                <p class="mt-1.5 text-xs text-slate-400">
                    This email will be sent from noreply@3gites.org with your name as the sender.
                </p>
                @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" id="confirmSend" name="confirm" value="1" required
                        class="w-5 h-5 rounded border-red-300 text-red-500 focus:ring-red-500 mt-0.5 flex-shrink-0">
                    <span class="text-sm text-red-700 font-medium">
                        I understand this email will be sent to all {{ $activeCount ?? 29 }} active members and cannot be
                        unsent. I have reviewed the content carefully.
                    </span>
                </label>
                @error('confirm')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="w-full btn-primary justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Send Broadcast
            </button>
        </form>
    </div>

</div>

@endsection
