@extends('layouts.app')
@section('title', 'Thank You!')
@section('content')

<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center">

        {{-- Checkmark animation --}}
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="font-playfair text-4xl font-bold text-navy-900 mb-3">
            Thank You!
        </h1>
        <p class="text-slate-600 text-lg leading-relaxed mb-6">
            Your generous donation means the world to the Class of 1975.
        </p>

        @if ($donation)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-5 mb-8 text-left space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Amount</span>
                    <span class="font-bold text-navy-900">${{ number_format($donation->amount, 2) }}</span>
                </div>
                @if ($donation->message)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Your message</span>
                        <span class="text-slate-700 italic text-right max-w-[60%]">"{{ $donation->message }}"</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Reference</span>
                    <span class="font-mono text-xs text-slate-400">{{ $donation->stripe_payment_id }}</span>
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="bg-navy-900 hover:bg-navy-800 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                Back to Home
            </a>
            <a href="{{ route('donate.index') }}" class="border border-slate-200 hover:border-slate-300 text-slate-600 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                Donate Again
            </a>
        </div>

    </div>
</div>

@endsection
