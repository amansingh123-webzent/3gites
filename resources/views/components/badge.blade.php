@props(['type' => 'active', 'text' => null])

@php
$label = $text ?? match($type) {
    'active'    => 'Active',
    'searching' => 'Searching',
    'memoriam'  => 'In Memoriam',
    'locked'    => 'Locked',
    'pending'   => 'Pending',
    'paid'      => 'Paid',
    'failed'    => 'Failed',
    'refunded'  => 'Refunded',
    'draft'     => 'Draft',
    'published' => 'Published',
    'closed'    => 'Closed',
    'open'      => 'Open',
    'attending' => 'Attending',
    'maybe'     => 'Maybe',
    'not_going' => 'Not Going',
    default     => ucfirst(str_replace('_', ' ', $type)),
};

$classes = 'text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide ' . match($type) {
    'active', 'paid', 'published', 'attending' => 'bg-emerald-100 text-emerald-700',
    'searching', 'maybe', 'pending', 'draft', 'open' => 'bg-amber-100 text-amber-700',
    'memoriam', 'closed', 'refunded' => 'bg-slate-200 text-slate-500',
    'locked', 'failed', 'not_going' => 'bg-red-100 text-red-600',
    default => 'bg-slate-100 text-slate-600',
};
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $label }}</span>
