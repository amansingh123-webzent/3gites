@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<x-page-header title="Change Password" subtitle="Update your account password" />

<div class="max-w-lg mx-auto px-4 py-10">
    <div class="card">
        <div class="px-8 py-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Current Password</label>
                    <input id="current_password" type="password" name="current_password" required
                        class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition {{ $errors->has('current_password') ? 'border-red-400' : 'border-slate-300' }}">
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full border rounded-xl px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1" aria-label="Toggle visibility">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Update Password</button>
                    <a href="{{ route('dashboard') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
