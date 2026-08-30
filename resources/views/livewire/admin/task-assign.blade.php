<div class="min-h-screen px-4 sm:px-6 py-8 bg-[#f8fafc] dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors duration-300" style="font-family: 'Inter', sans-serif;">

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

            --warning: #f59e0b;
            --warning-tint: #fffbeb;
        }

        /* Dark Mode Variables */
        :is(.dark) {
            --surface: #1e293b; /* slate-800 */
            --background: #0f172a; /* slate-900 */
            --border: #334155; /* slate-700 */
            
            --ink: #f8fafc; /* slate-50 */
            --ink-soft: #94a3b8; /* slate-400 */
            
            --primary-tint: rgba(37, 99, 235, 0.15);
            --danger-tint: rgba(239, 68, 68, 0.15);
            --success-tint: rgba(16, 185, 129, 0.15);
            --warning-tint: rgba(245, 158, 11, 0.15);
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--ink);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input:focus {
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

        /* Checkbox Custom Styling for Material */
        .md-checkbox {
            width: 1.125rem;
            height: 1.125rem;
            color: var(--primary);
            background-color: var(--surface);
            border: 1.5px solid var(--ink-soft);
            border-radius: 4px;
            transition: all 0.2s;
        }

        .md-checkbox:focus {
            --tw-ring-color: rgba(37, 99, 235, 0.25);
            --tw-ring-offset-width: 0px;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: var(--ink-soft);
        }
    </style>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 text-red-500 dark:text-red-400"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-6">
        <a href="{{ route('admin.task.index') }}" wire:navigate class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4 w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_task_assign.back_to_task_management') }}
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            {{ __('admin_task_assign.assign_employee') }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium ml-1">{{ __('admin_task_assign.assign_instruction') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- KOLOM KIRI: INFO TASK --}}
        <div class="lg:col-span-1">
            <div class="md-panel p-6 sm:p-8 sticky top-8">
                <h3 class="text-[11px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ __('admin_task_assign.task_info') }}
                </h3>

                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white mb-3">{{ $task->judul }}</h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-6 line-clamp-4 leading-relaxed">{{ $task->deskripsi }}</p>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 rounded-xl">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ __('admin_task_assign.task_type') }}</span>
                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 shadow-sm">
                            @if($task->jenis_task === 'pilihan_ganda') {{ __('admin_task_assign.quiz') }}
                            @elseif($task->jenis_task === 'survey') {{ __('admin_task_assign.survey') }}
                            @else {{ __('admin_task_assign.upload') }}
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 rounded-xl">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ __('admin_task_assign.reward') }}</span>
                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/50 shadow-sm">
                            +{{ $task->exp_reward }} EXP
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 rounded-xl">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ __('admin_task_assign.target_position') }}</span>
                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-white dark:bg-slate-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-600 shadow-sm">
                            {{ $task->jabatan->nama_jabatan ?? __('admin_task_assign.all') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl flex items-start gap-3">
                    <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs font-medium text-blue-800 dark:text-blue-300 leading-relaxed">{{ __('admin_task_assign.info_active_employee') }}</p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM ASSIGN --}}
        <div class="lg:col-span-2">
            <form wire:submit.prevent="assign" class="md-panel overflow-hidden flex flex-col h-full">

                <div class="p-6 sm:p-8 border-b border-gray-100 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_task_assign.employee_list') }}
                        </h3>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1.5">{{ __('admin_task_assign.select_employee') }}</p>
                    </div>

                    <div class="w-full sm:w-auto bg-white dark:bg-slate-900 p-3 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm">
                        <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('admin_task_assign.custom_deadline') }}</label>
                        <input type="datetime-local" wire:model="deadline" class="w-full sm:w-auto px-3 py-2 md-input text-xs font-medium">
                        <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-1.5">{{ __('admin_task_assign.ignore_deadline') }}</p>
                        @error('deadline') <span class="text-[10px] font-bold text-red-500 dark:text-red-400 block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-6 sm:p-8 flex-1 bg-white dark:bg-slate-800" x-data="{ 
                    selectAll: false,
                    allIds: {{ $users->pluck('id')->toJson() }},
                    toggleAll() {
                        this.selectAll = !this.selectAll;
                        if(this.selectAll) {
                            $wire.set('user_ids', this.allIds);
                        } else {
                            $wire.set('user_ids', []);
                        }
                    }
                }">

                    @error('user_ids')
                    <div class="mb-5 p-3.5 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm font-bold rounded-xl border border-red-200 dark:border-red-800/50 flex items-start gap-2.5 shadow-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror

                    @if($users->count() > 0)
                    <div class="mb-5 flex items-center">
                        <button type="button" @click="toggleAll()" class="text-sm font-extrabold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-2.5 transition-colors">
                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors" :class="selectAll ? 'bg-blue-600 border-blue-600 dark:bg-blue-500 dark:border-blue-500' : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-white" x-show="selectAll"></i>
                            </div>
                            <span x-text="selectAll ? '{{ __('admin_task_assign.unselect_all') }}' : '{{ __('admin_task_assign.select_all') }}'"></span>
                        </button>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 max-h-[420px] overflow-y-auto pr-2 scrollbar-thin">
                        @forelse($users as $user)
                        <label class="flex items-center p-3.5 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-all duration-200 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:border-blue-300 dark:has-[:checked]:border-blue-700 has-[:checked]:shadow-sm group">
                            <input type="checkbox" wire:model="user_ids" value="{{ $user->id }}" class="md-checkbox focus:ring-0">
                            <div class="ml-3.5 flex flex-col">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100 group-has-[:checked]:text-blue-900 dark:group-has-[:checked]:text-blue-400">{{ $user->name }}</span>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 group-has-[:checked]:text-blue-600 dark:group-has-[:checked]:text-blue-300">{{ $user->email }}</span>
                            </div>
                        </label>
                        @empty
                        <div class="col-span-full py-12 text-center flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="users" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('admin_task_assign.no_employee') }}</p>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1.5 max-w-sm">{{ __('admin_task_assign.make_sure_active', ['jabatan' => $task->jabatan->nama_jabatan ?? '-']) }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 sm:p-8 border-t border-gray-100 dark:border-slate-700/80 bg-white dark:bg-slate-800 flex justify-end">
                    <button type="submit" class="px-6 py-3 text-sm font-bold md-btn-primary flex items-center gap-2 w-full sm:w-auto justify-center" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="assign">{{ __('admin_task_assign.assign_now') }}</span>
                        <span wire:loading wire:target="assign" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('admin_task_assign.processing') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>