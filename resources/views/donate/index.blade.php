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
        @if ($totalRaised > 0)
        <div class="mt-6 inline-block bg-white/10 border border-white/20 rounded-2xl px-8 py-3">
            <p class="text-purple-200 text-sm">Total raised by classmates</p>
            <p class="font-display text-3xl font-bold text-gold-400 mt-1">${{ number_format($totalRaised, 2) }}</p>
        </div>
        @endif
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">

        {{-- Donation Form --}}
        <div class="md:col-span-3">

            @guest
            <div class="card px-7 py-8 text-center">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <h2 class="font-display text-xl font-semibold text-slate-800 mb-2">Members Only</h2>
                <p class="text-slate-500 text-sm mb-5">Please sign in to make a donation.</p>
                <a href="{{ route('login') }}" class="btn-primary">Sign In to Donate</a>
            </div>
            @else
            <div class="card overflow-hidden"
                x-data="{
                    amount: '',
                    message: '',
                    step: 'form',
                    error: '',
                    clientSecret: '',
                    donationId: null,
                    stripe: null,
                    elements: null,
                    cardElement: null,

                    async initStripe() {
                        this.stripe = Stripe('{{ config('cashier.key') }}');
                    },

                    async createIntent() {
                        const minAmount = {{ config('donation.min_amount', 5) }};
                        if (! this.amount || parseFloat(this.amount) < minAmount) {
                            this.error = `Minimum donation is $${minAmount.toFixed(2)}.`;
                            return;
                        }
                        this.error = '';
                        this.step = 'card';

                        const res = await fetch('{{ route('donate.intent') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ amount: this.amount, message: this.message }),
                        });

                        const data = await res.json();
                        if (! res.ok) {
                            this.error = data.message ?? 'Something went wrong. Please try again.';
                            this.step = 'form';
                            return;
                        }

                        this.clientSecret = data.client_secret;
                        this.donationId   = data.donation_id;
                        await this.$nextTick();
                        this.mountCard();
                    },

                    mountCard() {
                        this.elements   = this.stripe.elements();
                        this.cardElement = this.elements.create('card', {
                            style: {
                                base: {
                                    fontFamily: '\'Plus Jakarta Sans\', system-ui, sans-serif',
                                    fontSize: '16px',
                                    color: '#1e293b',
                                    '::placeholder': { color: '#94a3b8' },
                                },
                                invalid: { color: '#dc2626' },
                            },
                            hidePostalCode: true,
                        });
                        this.cardElement.mount('#card-element');
                    },

                    async confirmPayment() {
                        this.step  = 'processing';
                        this.error = '';
                        const { error, paymentIntent } = await this.stripe.confirmCardPayment(
                            this.clientSecret,
                            { payment_method: { card: this.cardElement } }
                        );
                        if (error) {
                            this.error = error.message;
                            this.step  = 'card';
                            return;
                        }
                        const form = document.getElementById('confirm-form');
                        document.getElementById('pi-id').value  = paymentIntent.id;
                        document.getElementById('don-id').value = this.donationId;
                        form.submit();
                    }
                }"
                x-init="initStripe()">

                <x-card-header title="Make a Donation" subtitle="Secure payment powered by Stripe" />

                {{-- Step 1: Amount & message --}}
                <div x-show="step === 'form'" class="px-7 py-7 space-y-5">
                    <div>
                        <p class="text-sm font-semibold text-slate-700 mb-2">Select Amount</p>
                        <div class="grid grid-cols-4 gap-2 mb-3">
                            @foreach ([10, 25, 50, 100] as $preset)
                            <button type="button" @click="amount = '{{ $preset }}'"
                                class="py-2.5 rounded-xl border text-sm font-bold transition-all"
                                :class="amount === '{{ $preset }}' ? 'bg-purple-900 border-purple-900 text-white' : 'border-slate-200 text-slate-600 hover:border-purple-400 hover:text-purple-700'">
                                ${{ $preset }}
                            </button>
                            @endforeach
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">$</span>
                            <input type="number" x-model="amount"
                                min="{{ config('donation.min_amount', 5) }}" max="10000" step="1"
                                class="w-full border border-slate-300 rounded-xl pl-8 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition"
                                placeholder="Custom amount (min ${{ number_format(config('donation.min_amount', 5), 2) }})">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Message <span class="font-normal text-slate-400">(optional)</span>
                        </label>
                        <textarea x-model="message" rows="3" maxlength="500"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 resize-none"
                            placeholder="Leave a note with your donation…"></textarea>
                    </div>

                    <p x-show="error" x-text="error" class="text-sm text-red-600"></p>

                    <button @click="createIntent()"
                        :disabled="!amount || parseFloat(amount) < 5"
                        class="w-full bg-gold-500 hover:bg-gold-400 disabled:opacity-50 disabled:cursor-not-allowed text-purple-900 font-bold py-3.5 rounded-xl text-sm transition-colors">
                        Continue to Payment →
                    </button>

                    <p class="text-center text-xs text-slate-400 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Secured by Stripe. We never store card details.
                    </p>
                </div>

                {{-- Step 2: Card entry --}}
                <div x-show="step === 'card'" class="px-7 py-7 space-y-5">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-slate-700">Card Details</p>
                        <span class="font-bold text-slate-800" x-text="'$' + parseFloat(amount).toFixed(2)"></span>
                    </div>
                    <div id="card-element"
                         class="border border-slate-300 rounded-xl px-4 py-3.5 bg-white focus-within:ring-2 focus-within:ring-gold-500 focus-within:border-gold-500 transition"></div>
                    <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
                    <button @click="confirmPayment()"
                        class="w-full btn-primary justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Donate $<span x-text="parseFloat(amount).toFixed(2)"></span>
                    </button>
                    <button @click="step = 'form'" class="w-full text-sm text-slate-400 hover:text-slate-600 transition-colors">
                        ← Change amount
                    </button>
                </div>

                {{-- Step 3: Processing --}}
                <div x-show="step === 'processing'" class="px-7 py-16 text-center">
                    <svg class="animate-spin w-8 h-8 text-gold-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <p class="text-slate-500 text-sm">Processing your donation…</p>
                </div>

                {{-- Hidden confirm form --}}
                <form id="confirm-form" method="POST" action="{{ route('donate.confirm') }}" class="hidden">
                    @csrf
                    <input id="pi-id"  type="hidden" name="payment_intent_id">
                    <input id="don-id" type="hidden" name="donation_id">
                </form>

            </div>
            @endguest
        </div>

        {{-- Sidebar --}}
        <div class="md:col-span-2 space-y-4">
            <div class="card px-5 py-5">
                <h3 class="font-display text-lg font-semibold text-slate-800 mb-4">Recent Donors</h3>
                @forelse ($recentDonations as $donation)
                <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-gold-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-gold-700 font-display">
                            {{ strtoupper(substr($donation->user->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $donation->user->name ?? 'Anonymous' }}</p>
                        @if ($donation->message)
                        <p class="text-xs text-slate-400 truncate italic">"{{ $donation->message }}"</p>
                        @endif
                    </div>
                    <span class="text-sm font-bold text-gold-600 flex-shrink-0">${{ number_format($donation->amount, 0) }}</span>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">Be the first to donate!</p>
                @endforelse
            </div>

            <div class="card px-5 py-5">
                <h3 class="font-display text-lg font-semibold text-slate-800 mb-3">How Funds Are Used</h3>
                <ul class="space-y-2 text-sm text-slate-600">
                    @foreach ([
                        ['🎉', 'Annual reunion event costs'],
                        ['📸', 'Class photo archive & digitisation'],
                        ['🎓', 'Classmate scholarships & memorials'],
                        ['🌐', 'Website hosting & maintenance'],
                    ] as [$icon, $text])
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">{{ $icon }}</span>
                        <span>{{ $text }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
@endpush

@endsection
