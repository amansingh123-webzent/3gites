@extends('layouts.guest')
@section('title', 'Sign In')

@section('content')
<div class="min-h-screen bg-purple-950 flex flex-col items-center justify-center px-4 py-12">

    {{-- Logo + wordmark above card --}}
    <div class="text-center mb-8">
       
        <h2 class="font-display text-2xl font-semibold text-white leading-tight">3Gites-1975</h2>
        <p class="text-purple-400 text-sm mt-0.5">Class of 1975 &bull; Clarendon College</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        {{-- Gold stripe --}}
        <div class="h-1 bg-gold-500"></div>

        <div class="px-8 py-8">
            <h1 class="font-display text-2xl font-semibold text-purple-950 text-center mb-1">Welcome Back</h1>
            <p class="text-slate-500 text-sm text-center mb-7">Sign in to your member account</p>

            @if(session('status'))
                <div class="mb-5 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="your.name@example.com"
                        class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition bg-white {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password with show/hide --}}
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-gold-600 hover:text-gold-700 transition-colors">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}"
                        >
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors" tabindex="-1" aria-label="Toggle password visibility">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-gold-500 focus:ring-gold-500">
                    <label for="remember" class="text-sm text-slate-600">Keep me signed in</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full bg-purple-900 hover:bg-purple-800 text-white font-bold py-3 rounded-xl text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2">
                    Sign In to Member Portal
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-400 leading-relaxed">
                This is a private community for the<br>Clarendon College Class of 1975.
            </p>
        </div>
    </div>
</div>
@endsection
