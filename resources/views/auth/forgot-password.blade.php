@extends('layouts.guest')
@section('title', 'Reset Password')

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
            <h1 class="font-display text-2xl font-semibold text-purple-950 text-center mb-1">Reset Your Password</h1>
            <p class="text-slate-500 text-sm text-center mb-7">Enter your email and we'll send you a reset link.</p>

            @if(session('status'))
                <div class="mb-5 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="your.name@example.com"
                        class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-400 text-purple-950 font-bold py-3 rounded-xl text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gold-600 hover:text-gold-700 transition-colors">← Back to Sign In</a>
            </div>
        </div>
    </div>
</div>
@endsection
