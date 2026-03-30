@extends('layouts.app')
@section('title', $poll->exists ? 'Edit Poll' : 'New Poll')

@section('content')

<x-page-header :title="$poll->exists ? 'Edit Poll' : 'New Poll'"
    :back="route('admin.polls.index')" backLabel="Polls" />

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if ($poll->exists && ($hasVotes ?? false))
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 flex gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-semibold text-amber-800 text-sm">Votes have been cast</p>
            <p class="text-amber-700 text-xs mt-0.5">This poll already has votes. The question and options cannot be changed.</p>
        </div>
    </div>
    @endif

    <div class="card overflow-hidden" x-data="pollForm">

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('pollForm', () => ({
                    options: @json($poll->exists ? $poll->options->pluck('option_text')->toArray() : array('', '')),

                    addOption() {
                        this.options.push('');
                        this.$nextTick(() => {
                            const inputs = this.$el.querySelectorAll('input[name^="options"]');
                            inputs[inputs.length - 1]?.focus();
                        });
                    },

                    removeOption(index) {
                        if (this.options.length > 2) this.options.splice(index, 1);
                    }
                }))
            })
        </script>

        <x-card-header
            :title="$poll->exists ? 'Edit Poll' : 'Create New Poll'"
            :subtitle="$poll->exists ? 'Update the question or options.' : 'Add a question and at least 2 options. Publish when ready.'" />

        <form method="POST"
              action="{{ $poll->exists ? route('admin.polls.update', $poll) : route('admin.polls.store') }}"
              class="px-7 py-7 space-y-6">
            @csrf
            @if ($poll->exists) @method('PATCH') @endif

            {{-- Question --}}
            <div>
                <label for="question" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Poll Question <span class="text-red-500">*</span>
                </label>
                <input id="question" type="text" name="question"
                    value="{{ old('question', $poll->question) }}"
                    required maxlength="500"
                    {{ ($hasVotes ?? false) ? 'readonly' : '' }}
                    class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition
                           {{ ($hasVotes ?? false) ? 'bg-slate-50 text-slate-500 cursor-not-allowed border-slate-200' : 'border-slate-300' }}
                           @error('question') border-red-400 @enderror"
                    placeholder="What is your question?">
                @error('question')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Options --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">
                        Answer Options <span class="text-red-500">*</span>
                        <span class="font-normal text-slate-400 ml-1">(minimum 2)</span>
                    </label>
                    @if (!($hasVotes ?? false))
                    <span class="text-xs text-slate-400" x-text="`${options.length} option${options.length !== 1 ? 's' : ''}`"></span>
                    @endif
                </div>

                @error('options')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror
                @error('options.*')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror

                <div class="space-y-2">
                    <template x-for="(option, index) in options" :key="index">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-purple-700"
                                      x-text="String.fromCharCode(65 + index)"></span>
                            </div>

                            <input type="text"
                                :name="`options[${index}]`"
                                x-model="options[index]"
                                required maxlength="200"
                                :disabled="{{ ($hasVotes ?? false) ? 'true' : 'false' }}"
                                class="flex-1 border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition
                                       {{ ($hasVotes ?? false) ? 'bg-slate-50 text-slate-500 cursor-not-allowed' : '' }}"
                                :placeholder="`Option ${String.fromCharCode(65 + index)}…`">

                            @if (!($hasVotes ?? false))
                            <button type="button" @click="removeOption(index)"
                                x-show="options.length > 2"
                                class="w-7 h-7 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </template>
                </div>

                @if (!($hasVotes ?? false))
                <button type="button" @click="addOption()" x-show="options.length < 10"
                    class="mt-3 flex items-center gap-2 text-sm text-gold-600 hover:text-gold-700 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add another option
                </button>
                @endif
            </div>

            {{-- Info box --}}
            <div class="bg-slate-50 rounded-xl border border-slate-100 px-4 py-3 text-xs text-slate-500 space-y-1">
                <p>📋 <strong>Draft:</strong> Only admins can see this poll. Publish it when the options are finalised.</p>
                <p>🔒 <strong>Locked:</strong> Once members start voting, the question and options cannot be changed.</p>
                <p>✅ <strong>Closed:</strong> Closing stops new votes but keeps results visible to all members.</p>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-4 pt-2 border-t border-slate-100">
                <button type="submit"
                    {{ ($hasVotes ?? false) ? 'disabled' : '' }}
                    class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ $poll->exists ? 'Save Changes' : 'Create Poll' }}
                </button>
                <a href="{{ route('admin.polls.index') }}" class="btn-ghost">Cancel</a>
            </div>

            </form>
    </div>

    @if ($poll->exists)
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}"
              onsubmit="return confirm('Delete this poll and all its votes permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-500 hover:text-red-700 transition-colors">
                Delete Poll
            </button>
        </form>
    </div>
    @endif
</div>

@endsection
