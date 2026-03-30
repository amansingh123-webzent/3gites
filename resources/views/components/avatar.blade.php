@props(['photo' => null, 'name' => 'Member', 'size' => 'md', 'deceased' => false])

@php
[$sizeClass, $textClass] = match($size) {
    'sm'  => ['w-8 h-8', 'text-xs'],
    'lg'  => ['w-16 h-16', 'text-lg'],
    'xl'  => ['w-36 h-36', 'text-4xl'],
    default => ['w-10 h-10', 'text-sm'],
};
$initial = strtoupper(substr($name, 0, 1));
@endphp

@if($photo)
    <img
        src="{{ Storage::url($photo) }}"
        alt="{{ $name }}"
        {{ $attributes->merge(['class' => "rounded-full object-cover $sizeClass" . ($deceased ? ' grayscale' : '')]) }}
    >
@else
    <div {{ $attributes->merge(['class' => "rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 $sizeClass"]) }}>
        <span class="font-display font-bold text-purple-700 {{ $textClass }}">{{ $initial }}</span>
    </div>
@endif
