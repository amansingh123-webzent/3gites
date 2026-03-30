@extends('layouts.app')
@section('title', 'About Us')

@section('content')

<div class="bg-purple-900 py-12 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-gold-400 text-xs tracking-[0.3em] uppercase mb-3">Our Story · Our Bond · Our Legacy</p>
        <h1 class="font-display text-4xl font-bold text-white">About 3Gites-1975</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="prose prose-slate max-w-none space-y-6">

        <p class="text-slate-600 leading-relaxed text-lg">
            In 1975, fifty young men and women walked through the gates of Clarendon College for the last time
            as students. We left with our certificates, our memories, and bonds that would endure for a lifetime.
            This portal is our digital home — a place to stay connected across the decades and the distances.
        </p>

        <div class="card p-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800 mb-3">Our Purpose</h2>
            <p class="text-slate-600 leading-relaxed">
                3Gites-1975 exists to keep the spirit of our class alive. Through this portal, we locate classmates
                we've lost touch with, share photos and stories from our school days and the years since, organise
                reunion events, and honour those who have passed on.
            </p>
            <p class="text-slate-600 leading-relaxed mt-3">
                The name "3Gites" comes from the Clarendon College tradition — a nod to our roots and our shared
                identity. This is not just a website; it is a living archive of who we were, who we became, and
                the friendships that held through it all.
            </p>
        </div>

        <div class="card p-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800 mb-3">Fifty Years Later</h2>
            <p class="text-slate-600 leading-relaxed">
                As we celebrate our 50th anniversary as graduates, we are proud to have found {{ $stats['active'] ?? 29 }}
                of our {{ ($stats['active'] ?? 29) + ($stats['searching'] ?? 21) }} classmates. The search continues for
                those we haven't located yet — and through this portal, we hope that one day every last classmate will
                be reunited, even if only digitally.
            </p>
            <blockquote class="mt-4 border-l-4 border-gold-400 pl-5 italic text-slate-600 font-display text-lg">
                "Perstare et Praestare — To Persevere and Excel. This motto guided us through school and through life.
                It remains our guiding light."
            </blockquote>
        </div>

        <div class="card p-6">
            <h2 class="font-display text-2xl font-semibold text-slate-800 mb-3">How It Started</h2>
            <p class="text-slate-600 leading-relaxed">
                The idea for a class reunion portal came from a small group of dedicated classmates who had been
                organising informal gatherings since the late 1990s. What started as a phone tree and mailing list
                grew into this full digital platform — a place where every member of the class could reconnect
                regardless of where life had taken them.
            </p>
        </div>

        {{-- School info card --}}
        <div class="card p-6 bg-cream">
            <h2 class="font-display text-2xl font-semibold text-slate-800 mb-4">About Clarendon College</h2>
            <dl class="space-y-3">
                @foreach ([
                    ['Founded', '1876'],
                    ['Location', 'May Pen, Clarendon, Jamaica'],
                    ['Motto', 'Perstare et Praestare (To Persevere and Excel)'],
                    ['Type', 'Co-educational secondary school'],
                    ['Our Class', 'Graduating Class of 1975 — 50 students'],
                ] as [$term, $def])
                <div class="flex gap-4 text-sm">
                    <dt class="font-semibold text-slate-600 w-28 flex-shrink-0">{{ $term }}</dt>
                    <dd class="text-slate-700">{{ $def }}</dd>
                </div>
                @endforeach
            </dl>
        </div>

        <div class="card p-6 text-center">
            <h2 class="font-display text-2xl font-semibold text-slate-800 mb-3">Join Us</h2>
            <p class="text-slate-600 leading-relaxed mb-5">
                If you are a member of the Clarendon College Class of 1975 and haven't yet connected with us,
                we'd love to hear from you.
            </p>
            <a href="{{ route('contact.index') }}" class="btn-primary">
                Get in Touch
            </a>
        </div>

    </div>
</div>

@endsection
