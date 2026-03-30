@props(['hover' => false])
<div {{ $attributes->merge(['class' => 'bg-white border border-slate-100 rounded-2xl shadow-sm' . ($hover ? ' hover:shadow-md hover:-translate-y-0.5 transition-all duration-200' : '')]) }}>
    {{ $slot }}
</div>
