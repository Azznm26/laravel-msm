<div class="min-h-screen px-4 sm:px-6 py-8 bg-[#f8fafc] dark:bg-slate-900 font-sans text-slate-900 dark:text-slate-100" style="font-family: 'Inter', sans-serif;">

    <style>
        /* ---------- Material Design 3 Colors & Components ---------- */
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-tint: #eff6ff;

            --surface: #ffffff;
            --background: #f8fafc;
            --border: #e2e8f0;

            --ink: #1e293b;
            --ink-soft: #64748b;

            --danger: #ef4444;
            --danger-hover: #dc2626;
            --danger-tint: #fef2f2;

            --success: #10b981;
            --success-tint: #ecfdf5;
        }

        /* Dark Mode Variables */
        :is(.dark) {
            --surface: #1e293b;
            /* slate-800 */
            --background: #0f172a;
            /* slate-900 */
            --border: #334155;
            /* slate-700 */

            --ink: #f8fafc;
            /* slate-50 */
            --ink-soft: #94a3b8;
            /* slate-400 */

            --primary-tint: rgba(37, 99, 235, 0.15);
            --danger-tint: rgba(239, 68, 68, 0.15);
            --success-tint: rgba(16, 185, 129, 0.15);
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input {
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--ink);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input:focus {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .md-btn-primary {
            background: var(--primary);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-btn-primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .md-btn-primary:active {
            transform: translateY(0);
        }

        .md-btn-neutral {
            background: var(--background);
            color: var(--ink-soft);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .md-btn-neutral:hover {
            background: var(--border);
            color: var(--ink);
        }
    </style>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('error'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 text-red-500"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif
    @if (session()->has('success'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if (session()->has('failed'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="x-circle" class="w-5 h-5 flex-shrink-0 text-red-500"></i>
        <span class="font-bold">{{ session('failed') }}</span>
    </div>
    @endif
    @if (session()->has('info'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-800 dark:text-blue-400">
        <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-blue-500"></i>
        <span class="font-bold">{{ session('info') }}</span>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- INDEX: DAFTAR TASK --}}
    {{-- ======================================================== --}}
    @if($viewMode === 'index')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                </div>
                {{ __('user_tasks.task_list') }}
            </h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 ml-1">
                {{ __('user_tasks.task_list_subtitle') }}
            </p>
        </div>
        <select wire:model.live="filter_type" class="px-4 py-2.5 md-input text-sm font-bold shadow-sm w-full md:w-auto">
            <option value="">{{ __('user_tasks.all_task_types') }}</option>
            <option value="pilihan_ganda">{{ __('user_tasks.mcq_quiz') }}</option>
            <option value="survey">{{ __('user_tasks.survey') }}</option>
            <option value="upload_file">{{ __('user_tasks.upload_report') }}</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $t)
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col hover:-translate-y-1.5 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 group">

            <!-- Top Accent Line -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>

            <div class="p-6 sm:p-7 flex-1">
                <div class="flex justify-between items-start mb-5">
                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 shadow-sm group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 group-hover:text-blue-700 dark:group-hover:text-blue-400 group-hover:border-blue-100 dark:group-hover:border-blue-800/50 transition-colors">
                        {{ str_replace('_', ' ', $t->jenis_task) }}
                    </span>

                    {{-- Status Badge --}}
                    @if($t->status_color === 'completed')
                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 rounded-md text-[11px] font-extrabold border border-emerald-100 dark:border-emerald-800/50"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ __('user_tasks.completed') }}</span>
                    @elseif($t->status_color === 'pending_review')
                    <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-md text-[11px] font-extrabold border border-blue-100 dark:border-blue-800/50"><i data-lucide="clock" class="w-3.5 h-3.5"></i> {{ __('user_tasks.evaluation') }}</span>
                    @elseif($t->status_color === 'failed')
                    <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30 px-2.5 py-1 rounded-md text-[11px] font-extrabold border border-rose-100 dark:border-rose-800/50"><i data-lucide="x-circle" class="w-3.5 h-3.5"></i> {{ __('user_tasks.failed') }}</span>
                    @elseif($t->status_color === 'overdue')
                    <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30 px-2.5 py-1 rounded-md text-[11px] font-extrabold border border-rose-100 dark:border-rose-800/50"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ __('user_tasks.overdue') }}</span>
                    @else
                    <span class="flex items-center gap-1 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 rounded-md text-[11px] font-extrabold border border-amber-100 dark:border-amber-800/50"><i data-lucide="unlock" class="w-3.5 h-3.5"></i> {{ __('user_tasks.available') }}</span>
                    @endif
                </div>

                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg leading-snug group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $t->judul }}</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2.5 line-clamp-2 leading-relaxed">{{ $t->deskripsi }}</p>

                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between text-sm">
                    <div class="font-black text-amber-500 dark:text-amber-400 flex items-center gap-1"><i data-lucide="star" class="w-4 h-4 fill-current"></i>+{{ $t->exp_reward }} EXP</div>
                    <div class="text-gray-500 dark:text-gray-400 font-bold text-xs"><i data-lucide="calendar" class="w-3.5 h-3.5 inline mr-1 align-text-bottom"></i> {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('d M') : __('user_tasks.no_limit') }}</div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700/50">
                <button wire:click="showTask({{ $t->id }})" class="w-full py-3 rounded-xl font-bold transition-all duration-200 text-sm flex items-center justify-center gap-2 {{ $t->status_color === 'completed' || $t->status_color === 'pending_review' ? 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 shadow-sm' : 'md-btn-primary' }}">
                    {{ $t->status_color === 'completed' || $t->status_color === 'pending_review' ? __('user_tasks.view_results') : __('user_tasks.do_now') }}
                    @if($t->status_color !== 'completed' && $t->status_color !== 'pending_review')
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    @endif
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full md-panel p-16 text-center flex flex-col items-center justify-center border-dashed border-slate-300 dark:border-slate-700">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700 shadow-sm">
                <i data-lucide="coffee" class="w-10 h-10 text-slate-400 dark:text-slate-500"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('user_tasks.all_done') }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 max-w-sm">{{ __('user_tasks.no_new_tasks') }}</p>
        </div>
        @endforelse
    </div>

    {{-- ======================================================== --}}
    {{-- SHOW: KERJAKAN TASK --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'show')
    <div class="max-w-4xl mx-auto">
        <button wire:click="backToIndex" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-6">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('user_tasks.back_to_list') }}
        </button>

        <div class="md-panel overflow-hidden mb-6 border-blue-200 dark:border-slate-700 shadow-lg relative">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-800"></div>

            <div class="p-6 md:p-10 border-b border-gray-100 dark:border-slate-700/80 bg-white dark:bg-slate-800">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-tight">{{ $task->judul }}</h1>
                <p class="text-sm sm:text-base font-medium text-gray-600 dark:text-gray-300 mt-3 leading-relaxed">{{ $task->deskripsi }}</p>
                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm font-bold">
                    <span class="px-3 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 rounded-lg shadow-sm flex items-center gap-1.5">
                        <i data-lucide="star" class="w-4 h-4 text-amber-500 dark:text-amber-400 fill-current"></i> {{ __('user_tasks.reward') }} +{{ $task->exp_reward }} EXP
                    </span>
                    <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg shadow-sm flex items-center gap-1.5 uppercase tracking-wider text-[10px]">
                        <i data-lucide="layout-list" class="w-4 h-4 text-blue-500 dark:text-blue-400"></i> {{ str_replace('_', ' ', $task->jenis_task) }}
                    </span>
                </div>
            </div>

            <form wire:submit.prevent="submitTask" class="p-6 md:p-10 bg-slate-50/50 dark:bg-slate-900/50 space-y-8">

                {{-- MCQ --}}
                @if($task->jenis_task === 'pilihan_ganda')
                <div class="space-y-8">
                    @foreach($questions as $index => $q)
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm">
                        <p class="font-extrabold text-gray-900 dark:text-white mb-5 text-base sm:text-lg">{{ $index + 1 }}. {{ $q->pertanyaan }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach(['A', 'B', 'C', 'D'] as $opt)
                            @if($q->{"pilihan_".strtolower($opt)})
                            <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:shadow-sm {{ isset($answers[$q->id]) && $answers[$q->id] === $opt ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-500 dark:border-blue-500 shadow-md ring-1 ring-blue-500' : 'border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-slate-50 dark:hover:bg-slate-600' }}">
                                <input type="radio" wire:model="answers.{{ $q->id }}" value="{{ $opt }}" class="w-5 h-5 text-blue-600 border-gray-300 dark:border-slate-500 dark:bg-slate-800 focus:ring-blue-600 focus:ring-offset-0 transition-colors" required>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-200 flex-1 leading-relaxed"><span class="mr-2 text-gray-400">{{ $opt }}.</span> {{ $q->{"pilihan_".strtolower($opt)} }}</span>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Survey --}}
                @elseif($task->jenis_task === 'survey')
                <div class="space-y-6">
                    @foreach($surveyQuestions as $index => $sq)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 p-6 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                        <p class="font-bold text-gray-800 dark:text-white flex-1 text-base">{{ $index + 1 }}. {{ $sq->pertanyaan }}</p>
                        <div class="flex gap-3 shrink-0">
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 rounded-xl border-2 transition-all duration-200 {{ isset($answers[$sq->id]) && $answers[$sq->id] === '1' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 ring-1 ring-emerald-500' : 'border-gray-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-emerald-500 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200' }}">
                                <input type="radio" wire:model="answers.{{ $sq->id }}" value="1" class="w-4.5 h-4.5 text-emerald-600 border-gray-300 dark:border-slate-500 dark:bg-slate-800 focus:ring-emerald-600 focus:ring-offset-0" required>
                                <span class="font-extrabold text-sm">{{ __('user_tasks.yes') }}</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 rounded-xl border-2 transition-all duration-200 {{ isset($answers[$sq->id]) && $answers[$sq->id] === '0' ? 'border-rose-500 bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 ring-1 ring-rose-500' : 'border-gray-200 dark:border-slate-600 hover:border-rose-300 dark:hover:border-rose-500 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200' }}">
                                <input type="radio" wire:model="answers.{{ $sq->id }}" value="0" class="w-4.5 h-4.5 text-rose-600 border-gray-300 dark:border-slate-500 dark:bg-slate-800 focus:ring-rose-600 focus:ring-offset-0" required>
                                <span class="font-extrabold text-sm">{{ __('user_tasks.no') }}</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Upload File --}}
                @elseif($task->jenis_task === 'upload_file')
                <div class="p-8 border-2 border-dashed border-blue-300 dark:border-blue-700/50 rounded-3xl bg-blue-50/50 dark:bg-blue-900/10 text-center hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-300">
                    <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-blue-100 dark:border-slate-700">
                        <i data-lucide="file-up" class="w-8 h-8 text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <h4 class="font-extrabold text-gray-900 dark:text-white mb-2 text-lg">{{ __('user_tasks.upload_document') }}</h4>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">{{ __('user_tasks.supported_formats') }}</p>

                    <div class="relative max-w-md mx-auto">
                        <input type="file" wire:model="file" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-extrabold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer shadow-sm transition-all" required>
                    </div>
                    @error('file') <span class="text-sm text-rose-600 dark:text-rose-400 font-bold mt-3 block">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="file" class="text-xs text-blue-600 dark:text-blue-400 font-extrabold mt-3 flex items-center justify-center gap-2">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> {{ __('user_tasks.uploading_file') }}
                    </div>
                </div>
                @endif

                <div class="pt-8 border-t border-gray-200 dark:border-slate-700 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 md-btn-primary text-base font-bold shadow-lg w-full sm:w-auto flex items-center justify-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitTask">{{ __('user_tasks.submit_answer') }} <i data-lucide="send" class="w-4.5 h-4.5 inline ml-1"></i></span>
                        <span wire:loading wire:target="submitTask" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('user_tasks.processing') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- RESULT: HASIL TASK --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'result')
    <div class="max-w-2xl mx-auto text-center mt-12 md-panel p-10 shadow-xl border-t-8 {{ $hasPassed ? 'border-t-emerald-500 dark:border-t-emerald-500' : 'border-t-blue-500 dark:border-t-blue-500' }}">
        @if($task->jenis_task === 'upload_file')
        @if($hasPassed)
        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="check-circle" class="w-12 h-12"></i></div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('user_tasks.report_accepted') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-3 font-medium text-base">
            {{ __('user_tasks.report_validated') }} <span class="font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded border border-amber-100 dark:border-amber-800/50">+{{ $task->exp_reward }} EXP</span> {{ __('user_tasks.added_to_profile') }}
        </p>
        @else
        <div class="w-24 h-24 bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="clock" class="w-12 h-12"></i></div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('user_tasks.waiting_review') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-3 font-medium text-base">{{ __('user_tasks.report_uploaded') }}</p>
        @endif
        @else
        @if($hasPassed)
        <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="award" class="w-12 h-12"></i></div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('user_tasks.evaluation_completed') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-3 font-medium text-base">
            {{ __('user_tasks.excellent_score') }} <strong class="text-emerald-600 dark:text-emerald-400 text-lg">{{ $percentage }}%</strong>. <span class="font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded border border-amber-100 dark:border-amber-800/50">+{{ $task->exp_reward }} EXP</span> {{ __('user_tasks.added_to_profile') }}
        </p>
        @else
        <div class="w-24 h-24 bg-rose-100 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800/50 text-rose-600 dark:text-rose-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm"><i data-lucide="x-circle" class="w-12 h-12"></i></div>
        <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('user_tasks.not_passed') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-3 font-medium text-base">{{ __('user_tasks.your_score') }} <strong class="text-rose-600 dark:text-rose-400 text-lg">{{ $percentage }}%</strong> {{ __('user_tasks.kkm_message') }}</p>
        @endif
        @endif

        <div class="mt-10">
            <button wire:click="backToIndex" class="px-8 py-3.5 md-btn-neutral text-gray-700 dark:text-gray-300 font-bold text-sm shadow-sm inline-flex items-center gap-2 border border-gray-200 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-700">
                <i data-lucide="arrow-left" class="w-4.5 h-4.5"></i> {{ __('user_tasks.back_to_list') }}
            </button>
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