@extends('layouts.app')
@section('title', 'Support 3Gites-1975')

@section('content')

<div class="bg-purple-900 py-12 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-14 h-14 bg-gold-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h1 class="font-display text-4xl font-bold text-white mb-3">Support Our Class</h1>
        <p class="text-purple-200 text-lg leading-relaxed">
            Your generous contribution helps fund reunions, scholarships, and community events
            for the Class of 1975.
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center">
        <a href="https://www.zeffy.com/en-US/donation-form/donate-to-3gites--1975" target="_blank" 
           class="bg-gold-500 hover:bg-gold-400 text-purple-900 font-bold px-16 py-4 rounded-xl text-lg transition-colors inline-flex items-center gap-3">
            Donate to Reunion
        </a>
        <p class="text-slate-500 text-sm mt-4">
            Click the button above to be redirected to our secure donation platform
        </p>
    </div>
</div>

@endsection
