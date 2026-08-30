<div class="min-h-screen bg-[#f8fafc] dark:bg-gray-950 px-4 sm:px-6 py-8 transition-colors">

    <div class="mb-8">
        <a href="{{ route('admin.task.index') }}" wire:navigate class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-3 w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> {{ __('question.back_to_task_management') }}
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <i data-lucide="list-checks" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            {{ __('question.manage_questions_title', ['title' => $task->judul]) }}
        </h1>
        <div class="mt-3 flex gap-3">
            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-bold">{{ trans_choice('question.questions_count', $questions->count(), ['count' => $questions->count()]) }}</span>
            <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-bold">{{ __('question.total_score', ['score' => $task->total_skor]) }}</span>
        </div>
    </div>

    @if (session()->has('success'))
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 rounded-xl flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span class="font-medium text-sm">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODE: LIST SOAL --}}
    {{-- ========================================== --}}
    @if($viewMode === 'list')
    <div class="flex justify-end mb-6">
        <button wire:click="create" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-900/20 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> {{ __('question.add_question') }}
        </button>
    </div>

    <div class="space-y-4">
        @forelse($questions as $index => $q)
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm rounded-2xl p-6 transition-all hover:border-blue-200 dark:hover:border-blue-800">
            <div class="flex justify-between items-start gap-4">
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-start gap-2">
                        <span class="text-blue-600 dark:text-blue-400">{{ $index + 1 }}.</span> {{ $q->pertanyaan }}
                    </h3>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <div class="p-2 rounded-lg {{ strtolower($q->jawaban_benar) === 'a' ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-semibold' : 'bg-gray-50 dark:bg-gray-800/60 border border-transparent' }}">A. {{ $q->pilihan_a }}</div>
                        <div class="p-2 rounded-lg {{ strtolower($q->jawaban_benar) === 'b' ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-semibold' : 'bg-gray-50 dark:bg-gray-800/60 border border-transparent' }}">B. {{ $q->pilihan_b }}</div>
                        <div class="p-2 rounded-lg {{ strtolower($q->jawaban_benar) === 'c' ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-semibold' : 'bg-gray-50 dark:bg-gray-800/60 border border-transparent' }}">C. {{ $q->pilihan_c }}</div>
                        <div class="p-2 rounded-lg {{ strtolower($q->jawaban_benar) === 'd' ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 font-semibold' : 'bg-gray-50 dark:bg-gray-800/60 border border-transparent' }}">D. {{ $q->pilihan_d }}</div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-3 shrink-0">
                    <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-bold rounded text-xs border border-blue-200 dark:border-blue-800">{{ __('question.points', ['score' => $q->skor]) }}</span>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $q->id }})" class="p-1.5 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-md transition-colors"><i data-lucide="edit" class="w-4 h-4"></i></button>
                        <button wire:click="confirmDeleteQuestion({{ $q->id }})" class="p-1.5 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-md transition-colors"><i data-lucide="trash" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 dark:text-gray-700 mx-auto mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">{{ __('question.no_questions') }}</p>
        </div>
        @endforelse
    </div>

    {{-- ========================================== --}}
    {{-- MODE: CREATE MULTIPLE SOAL (WIZARD) --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'create')

    <!-- ========================================== -->
    <!-- PANEL GENERATE AI -->
    <!-- ========================================== -->
    <div class="mb-6 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800/50 shadow-sm rounded-2xl p-6">
        <h3 class="text-base font-bold text-blue-800 dark:text-blue-400 mb-4 flex items-center">
            <i data-lucide="sparkles" class="w-5 h-5 mr-2"></i>
            {{ __('Generate Soal Otomatis dengan AI') }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Input Jabatan -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Target Jabatan</label>
                <input type="text" wire:model="aiJabatan" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: IT Support">
                @error('aiJabatan') <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Input Kompetensi -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Fokus Kompetensi</label>
                <input type="text" wire:model="aiKompetensi" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Hardware & Jaringan">
                @error('aiKompetensi') <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Input Jumlah Soal -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Jumlah Soal (Max 10)</label>
                <input type="number" wire:model="aiJumlahSoal" min="1" max="10" class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                @error('aiJumlahSoal') <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Tombol Generate -->
        <button
            type="button"
            wire:click="generateWithAI"
            wire:loading.attr="disabled"
            class="px-4 py-2.5 text-sm font-semibold text-white bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-xl shadow-lg flex items-center transition-colors">
            <span wire:loading.remove wire:target="generateWithAI" class="flex items-center">
                <i data-lucide="bot" class="w-4 h-4 mr-2"></i> Generate via AI
            </span>
            <span wire:loading wire:target="generateWithAI" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                AI Sedang Menyusun Soal...
            </span>
        </button>
    </div>
    <!-- ========================================== -->

    <div class="mb-6 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm rounded-2xl p-4">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ __('question.question_progress', ['current' => $activeIndex + 1, 'total' => count($newQuestions)]) }}
            </span>
            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">
                {{ __('question.complete_count', [
                    'complete' => collect($newQuestions)->filter(fn($q) => filled($q['text']) && filled($q['option_a']) && filled($q['option_b']) && filled($q['option_c']) && filled($q['option_d']))->count(),
                    'total' => count($newQuestions),
                ]) }}
            </span>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($newQuestions as $stepIndex => $stepQ)
            @php
            $isComplete = filled($stepQ['text']) && filled($stepQ['option_a']) && filled($stepQ['option_b']) && filled($stepQ['option_c']) && filled($stepQ['option_d']);
            @endphp
            <button type="button"
                wire:click="goToQuestion({{ $stepIndex }})"
                class="w-9 h-9 rounded-lg text-xs font-bold flex items-center justify-center transition-all border-2
                        {{ $stepIndex === $activeIndex
                            ? 'bg-blue-600 dark:bg-blue-500 border-blue-600 dark:border-blue-500 text-white shadow-md'
                            : ($isComplete
                                ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400'
                                : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400 dark:text-gray-500') }}">
                {{ $stepIndex + 1 }}
            </button>
            @endforeach
            <button type="button" wire:click="addQuestionField"
                class="w-9 h-9 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700 text-gray-400 dark:text-gray-500 hover:border-blue-600 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-400 flex items-center justify-center transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <form wire:submit.prevent="storeQuestions" class="space-y-6">

        @php $index = $activeIndex; $q = $newQuestions[$activeIndex] ?? null; @endphp

        @if($q)
        <div class="bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-800/50 shadow-sm rounded-2xl p-6 relative" wire:key="question-card-{{ $index }}">
            @if(count($newQuestions) > 1)
            <button type="button" wire:click="confirmRemoveField({{ $index }})"
                class="absolute top-4 right-4 text-rose-500 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 bg-rose-50 dark:bg-rose-900/20 p-1.5 rounded-lg">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.question_label') }}</label>
                    <textarea wire:model="newQuestions.{{ $index }}.text" rows="2"
                        placeholder="{{ __('question.question_placeholder') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    @error('newQuestions.'.$index.'.text') <span class="text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">
                        {{ __('question.answer_options') }} <span class="text-gray-400 dark:text-gray-500 font-normal">{{ __('question.answer_options_hint') }}</span>
                    </label>
                    <div class="space-y-2">
                        @foreach(['a', 'b', 'c', 'd'] as $opt)
                        <div class="flex items-center gap-3 p-1 rounded-xl transition-colors
                            {{ strtoupper($opt) === $q['correct_option'] ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800' : '' }}">
                            <label class="flex items-center justify-center w-9 h-9 shrink-0 rounded-lg cursor-pointer font-bold text-sm
    {{ strtoupper($opt) === $q['correct_option'] ? 'bg-emerald-500 dark:bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400' }}">
                                <input type="radio"
                                    name="correct_option_{{ $index }}"
                                    wire:model.live="newQuestions.{{ $index }}.correct_option"
                                    value="{{ strtoupper($opt) }}"
                                    class="sr-only">
                                {{ strtoupper($opt) }}
                            </label>
                            <input type="text"
                                wire:model="newQuestions.{{ $index }}.option_{{ $opt }}"
                                placeholder="{{ __('question.option_placeholder', ['option' => strtoupper($opt)]) }}"
                                class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 shrink-0">{{ __('question.score_weight') }}</label>
                    <input type="number" wire:model="newQuestions.{{ $index }}.score"
                        class="w-24 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" min="1" required>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('question.score_weight_hint') }}</span>
                </div>
            </div>
        </div>
        @endif

        @error('newQuestions.*.*')
        <span class="text-sm text-rose-500 dark:text-rose-400 font-semibold block">{{ $message }}</span>
        @enderror

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sticky bottom-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-lg rounded-2xl p-4">
            <div class="flex gap-2">
                <button type="button" wire:click="prevQuestion" @if($activeIndex===0) disabled @endif
                    class="px-4 py-2.5 text-sm font-semibold rounded-xl border transition-colors
                        {{ $activeIndex === 0 ? 'text-gray-300 dark:text-gray-600 border-gray-100 dark:border-gray-800 cursor-not-allowed' : 'text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    <i data-lucide="chevron-left" class="w-4 h-4 inline"></i> {{ __('question.previous') }}
                </button>
                @if($activeIndex < count($newQuestions) - 1)
                    <button type="button" wire:click="nextQuestion"
                    class="px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                    {{ __('question.next') }} <i data-lucide="chevron-right" class="w-4 h-4 inline"></i>
                    </button>
                    @else
                    <button type="button" wire:click="addQuestionField"
                        class="px-4 py-2.5 text-sm font-bold rounded-xl border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                        <i data-lucide="plus" class="w-4 h-4 inline"></i> {{ __('question.new_question') }}
                    </button>
                    @endif
            </div>

            <div class="flex gap-3">
                @if($task->questions()->count() > 0)
                <button type="button" wire:click="backToList" class="px-6 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">{{ __('question.cancel') }}</button>
                @endif
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl shadow-lg shadow-blue-500/20 dark:shadow-blue-900/20 flex items-center gap-2" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="storeQuestions">{{ __('question.save_all_questions', ['count' => count($newQuestions)]) }}</span>
                    <span wire:loading wire:target="storeQuestions">{{ __('question.saving') }}</span>
                </button>
            </div>
        </div>
    </form>

    {{-- ========================================== --}}
    {{-- MODE: EDIT SINGLE SOAL --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'edit')
    <form wire:submit.prevent="updateQuestion" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm rounded-2xl p-6 md:p-8 space-y-6 max-w-3xl">
        <div>
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.question_label') }}</label>
            <textarea wire:model="pertanyaan" rows="3" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.option_label', ['option' => 'A']) }}</label>
                <input type="text" wire:model="pilihan_a" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.option_label', ['option' => 'B']) }}</label>
                <input type="text" wire:model="pilihan_b" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.option_label', ['option' => 'C']) }}</label>
                <input type="text" wire:model="pilihan_c" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.option_label', ['option' => 'D']) }}</label>
                <input type="text" wire:model="pilihan_d" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.correct_answer') }}</label>
                <select wire:model="jawaban_benar" class="w-full px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 font-bold rounded-lg text-sm">
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('question.score_weight') }}</label>
                <input type="number" wire:model="skor" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" min="1" required>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
            <button type="button" wire:click="backToList" class="px-6 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700">{{ __('question.cancel') }}</button>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-xl">{{ __('question.save_changes') }}</button>
        </div>
    </form>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL KONFIRMASI HAPUS --}}
    {{-- ========================================== --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="delete-modal">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="cancelDelete"></div>

        <!-- Modal Card -->
        <div class="relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-11 h-11 rounded-full bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                    <i data-lucide="trash-2" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">
                        {{ __('question.confirm_delete_title', [], null) ?? 'Hapus Soal?' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($deleteType === 'field')
                        Soal ini akan dihapus dari daftar wizard. Tindakan ini tidak bisa dibatalkan.
                        @else
                        Soal ini akan dihapus permanen dari database. Tindakan ini tidak bisa dibatalkan.
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" wire:click="cancelDelete"
                    class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="button" wire:click="executeDelete" wire:loading.attr="disabled"
                    class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 dark:bg-rose-600 dark:hover:bg-rose-500 rounded-xl shadow-lg shadow-rose-500/20 transition-colors">
                    <span wire:loading.remove wire:target="executeDelete">Ya, Hapus</span>
                    <span wire:loading wire:target="executeDelete">Menghapus...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>