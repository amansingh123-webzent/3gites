@props(['name', 'label', 'rows' => 4, 'maxlength' => null, 'placeholder' => '', 'required' => false])

<div x-data="{ count: {{ strlen(old($name, '')) }} }">
    <div class="flex items-center justify-between mb-1.5">
        <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
            {{ $label }}
            @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
        @if($maxlength)
            <span
                class="text-xs transition-colors"
                :class="count >= {{ $maxlength }} * 0.9 ? 'text-red-500 font-semibold' : 'text-slate-400'"
            ><span x-text="count"></span>/{{ $maxlength }}</span>
        @endif
    </div>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        @input="count = $el.value.length"
        {{ $attributes->merge(['class' => 'w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition bg-white text-slate-700 placeholder:text-slate-400 resize-none ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300')]) }}
    >{{ old($name) }}</textarea>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
