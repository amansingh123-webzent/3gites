@props(['title', 'subtitle' => null, 'actions' => null])
<div class="bg-purple-900 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
    <div>
        <h3 class="font-display text-xl font-semibold text-white leading-tight">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-purple-200 text-sm mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div>{{ $actions }}</div>
    @endif
</div>
