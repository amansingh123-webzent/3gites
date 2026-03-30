@props(['option', 'index', 'name' => 'option_id'])

<label
    :class="selected === {{ $option->id }} ? 'border-gold-500 bg-gold-50' : 'border-slate-200 hover:border-slate-300 bg-white'"
    class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-colors"
    @click="selected = {{ $option->id }}"
>
    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-sm flex-shrink-0">
        {{ chr(65 + $index) }}
    </div>
    <span class="text-slate-700 font-medium flex-1">{{ $option->text }}</span>
    <div
        class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center"
        :class="selected === {{ $option->id }} ? 'border-gold-500 bg-gold-500' : 'border-slate-300'"
    >
        <div x-show="selected === {{ $option->id }}" class="w-2 h-2 bg-white rounded-full"></div>
    </div>
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $option->id }}"
        class="sr-only"
        :checked="selected === {{ $option->id }}"
    >
</label>
