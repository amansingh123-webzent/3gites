@props(['icon' => '📭', 'title', 'description' => null, 'actionText' => null, 'actionUrl' => null])

<div class="py-16 text-center">
    <div class="text-5xl mb-4">{{ $icon }}</div>
    <h3 class="font-semibold text-slate-700 text-lg mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-slate-400 text-sm max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-2 bg-purple-900 hover:bg-purple-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors">
            {{ $actionText }}
        </a>
    @endif
</div>
