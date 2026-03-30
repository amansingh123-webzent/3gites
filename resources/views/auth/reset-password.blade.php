@extends('layouts.guest')
@section('title', 'Set New Password')

@section('content')
<div class="min-h-screen bg-purple-950 flex flex-col items-center justify-center px-4 py-12">

    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Clarendon College crest" class="object-contain mx-auto mb-4" style="height:72px;width:72px;">
        <h2 class="font-display text-2xl font-semibold text-white">3Gites-1975</h2>
        <p class="text-purple-400 text-sm mt-0.5">Class of 1975 &bull; Clarendon College</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="h-1 bg-gold-500"></div>
        <div class="px-8 py-8">
            <h1 class="font-display text-2xl font-semibold text-purple-950 text-center mb-1">Set New Password</h1>
            <p class="text-slate-500 text-sm text-center mb-7">Choose a strong password — at least 8 characters.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        readonly
                        class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-500"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full border rounded-xl px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600" tabindex="-1" aria-label="Toggle visibility">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition">
                </div>

                <button type="submit" class="w-full bg-purple-900 hover:bg-purple-800 text-white font-bold py-3 rounded-xl text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
