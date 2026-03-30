@props(['event', 'userRsvp' => null, 'counts' => []])

@auth
<div
    class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6"
    x-data="{
        status: '{{ $userRsvp ?? '' }}',
        loading: false,
        async setRsvp(newStatus) {
            this.loading = true;
            try {
                const res = await fetch('/events/{{ $event->id }}/rsvp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                if (res.ok) this.status = newStatus;
            } finally {
                this.loading = false;
            }
        },
        async removeRsvp() {
            this.loading = true;
            try {
                await fetch('/events/{{ $event->id }}/rsvp', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                this.status = '';
            } finally {
                this.loading = false;
            }
        }
    }"
>
    <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide">Your RSVP</h3>

    <div class="space-y-2">
        <button
            type="button"
            role="button"
            :aria-pressed="status === 'attending'"
            @click="setRsvp('attending')"
            :disabled="loading"
            :class="status === 'attending' ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 text-slate-600 hover:border-emerald-300 hover:text-emerald-700'"
            class="w-full border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-gold-500"
        >
            <svg class="w-4 h-4" :class="status === 'attending' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            Attending
        </button>

        <button
            type="button"
            role="button"
            :aria-pressed="status === 'maybe'"
            @click="setRsvp('maybe')"
            :disabled="loading"
            :class="status === 'maybe' ? 'bg-amber-500 text-white border-amber-500' : 'border-slate-200 text-slate-600 hover:border-amber-300 hover:text-amber-700'"
            class="w-full border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-gold-500"
        >
            <svg class="w-4 h-4" :class="status === 'maybe' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            Maybe
        </button>

        <button
            type="button"
            role="button"
            :aria-pressed="status === 'not_going'"
            @click="setRsvp('not_going')"
            :disabled="loading"
            :class="status === 'not_going' ? 'bg-red-500 text-white border-red-500' : 'border-slate-200 text-slate-600 hover:border-red-300 hover:text-red-600'"
            class="w-full border-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-gold-500"
        >
            <svg class="w-4 h-4" :class="status === 'not_going' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            Can't Make It
        </button>
    </div>

    {{-- Counts --}}
    <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
        <div>
            <div class="text-xl font-bold font-display text-emerald-700">{{ $counts['attending'] ?? 0 }}</div>
            <div class="text-xs text-slate-400">Attending</div>
        </div>
        <div>
            <div class="text-xl font-bold font-display text-amber-600">{{ $counts['maybe'] ?? 0 }}</div>
            <div class="text-xs text-slate-400">Maybe</div>
        </div>
        <div>
            <div class="text-xl font-bold font-display text-red-500">{{ $counts['not_going'] ?? 0 }}</div>
            <div class="text-xs text-slate-400">Not Going</div>
        </div>
    </div>

    <div x-show="status" x-cloak class="mt-3 text-center">
        <button
            @click="removeRsvp()"
            class="text-slate-400 hover:text-red-500 text-xs transition-colors focus:outline-none"
        >Remove RSVP</button>
    </div>
</div>
@else
<div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 text-center">
    <p class="text-slate-500 text-sm mb-3">Sign in to RSVP for this event.</p>
    <a href="{{ route('login') }}" class="bg-purple-900 hover:bg-purple-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors inline-block">Sign In</a>
</div>
@endauth
