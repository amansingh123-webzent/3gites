@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')

<x-page-header title="Contact Us" subtitle="Get in touch with the reunion committee" />

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Contact Form --}}
        <div class="lg:col-span-3">
            <div class="card overflow-hidden">
                <x-card-header title="Send a Message" />
                <div class="px-7 py-7">
                    @if(session('success'))
                    <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 text-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="cName">Your Name</label>
                            <input type="text" id="cName" name="name" value="{{ old('name') }}" required maxlength="100"
                                class="w-full border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                                placeholder="Full name">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="cEmail">Email Address</label>
                            <input type="email" id="cEmail" name="email"
                                value="{{ old('email', auth()->user()?->email) }}" required maxlength="255"
                                class="w-full border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                                placeholder="your@email.com">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="cSubject">Subject</label>
                            <select id="cSubject" name="subject"
                                class="w-full border {{ $errors->has('subject') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gold-500" required>
                                <option value="">Select a topic…</option>
                                @foreach ([
                                    "I'm a classmate — help me connect",
                                    "I know a classmate you're searching for",
                                    "Reunion event enquiry",
                                    "Technical issue with the portal",
                                    "Other",
                                ] as $opt)
                                <option value="{{ $opt }}" {{ old('subject') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @error('subject')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="cMessage">Message</label>
                            <textarea id="cMessage" name="message" rows="5" required minlength="10" maxlength="3000"
                                class="w-full border {{ $errors->has('message') ? 'border-red-400' : 'border-slate-300' }} rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition resize-none"
                                placeholder="Tell us how we can help…">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full btn-primary justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send Message
                        </button>

                        <p class="text-xs text-slate-400 text-center">Limited to 5 messages per hour per visitor.</p>
                    </form>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="card p-6">
                <h3 class="font-display text-lg font-semibold text-slate-800 mb-5">Reunion Committee</h3>
                <div class="space-y-4">
                    @foreach ([
                        ['email', 'reunion@3gites.org', 'Email'],
                        ['phone', '+1 (876) 555-1975', 'Phone (WhatsApp)'],
                    ] as [$type, $value, $label])
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                            @if ($type === 'email')
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @else
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">{{ $label }}</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $value }}</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">School Address</p>
                            <p class="text-sm font-semibold text-slate-700">Clarendon College<br>May Pen, Clarendon<br>Jamaica, W.I.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 mt-5 pt-4">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        If you are a member of our class or know someone we're searching for, please don't hesitate to
                        reach out. Every connection matters to us.
                    </p>
                </div>
            </div>

            <a href="{{ route('members.index') }}" class="block card p-4 text-center hover:shadow-md transition-shadow">
                <p class="text-sm font-semibold text-purple-900">View Class Directory →</p>
                <p class="text-xs text-slate-400 mt-0.5">Find classmates you've lost touch with</p>
            </a>
        </div>

    </div>
</div>

@endsection
