@props(['name', 'label', 'options' => [], 'placeholder' => 'Select...', 'required' => false, 'selected' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 mb-1.5">
        {{ $label }}
        @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
    </label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition bg-white text-slate-700 ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-400' : 'border-slate-300')]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ (old($name, $selected) == $value) ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
