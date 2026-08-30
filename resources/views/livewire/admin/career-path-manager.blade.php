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

        .md-btn-danger-solid {
            background: var(--danger);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .md-btn-danger-solid:hover {
            background: var(--danger-hover);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            transform: translateY(-1px);
        }

        .md-icon-btn {
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .md-icon-btn:hover {
            transform: scale(1.05);
        }

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

    {{-- ========================================== --}}
    {{-- MODE: LIST --}}
    {{-- ========================================== --}}
    @if($viewMode === 'list')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                {{ __('admin_career_path.title_list') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium ml-1">{{ __('admin_career_path.subtitle_list') }}</p>
        </div>
        <button wire:click="create" class="flex items-center gap-2 px-5 py-3 md-btn-primary font-bold text-sm shadow-md">
            <i data-lucide="plus" class="w-4.5 h-4.5"></i> {{ __('admin_career_path.btn_create_path') }}
        </button>
    </div>

    {{-- Kartu Panduan Singkat --}}
    <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl shadow-sm">
        <h2 class="text-sm font-extrabold text-blue-900 dark:text-blue-300 flex items-center gap-2 mb-4">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_career_path.guide_title') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs font-medium text-blue-800 dark:text-blue-300">
            <div class="flex gap-3.5 items-start">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-200 dark:bg-blue-800/60 text-blue-700 dark:text-blue-400 font-extrabold flex items-center justify-center border border-blue-300 dark:border-blue-700/50 shadow-sm">1</span>
                <p class="leading-relaxed">{{ __('admin_career_path.guide_step_1') }}</p>
            </div>
            <div class="flex gap-3.5 items-start">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-200 dark:bg-blue-800/60 text-blue-700 dark:text-blue-400 font-extrabold flex items-center justify-center border border-blue-300 dark:border-blue-700/50 shadow-sm">2</span>
                <p class="leading-relaxed">{{ __('admin_career_path.guide_step_2') }}</p>
            </div>
            <div class="flex gap-3.5 items-start">
                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-200 dark:bg-blue-800/60 text-blue-700 dark:text-blue-400 font-extrabold flex items-center justify-center border border-blue-300 dark:border-blue-700/50 shadow-sm">3</span>
                <p class="leading-relaxed">{{ __('admin_career_path.guide_step_3') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($careerPaths as $cp)
        <div class="relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1.5 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-300 group overflow-hidden">

            <!-- Hover Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            <!-- Top Accent Line -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>

            <div class="relative z-10 pt-1">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors duration-300">{{ $cp->name }}</h3>
                    <div class="flex items-center gap-2">
                        <button wire:click="show({{ $cp->id }})" class="p-2 md-icon-btn bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50" title="{{ __('admin_career_path.tooltip_view_level') }}"><i data-lucide="eye" class="w-4.5 h-4.5"></i></button>
                        <button wire:click="edit({{ $cp->id }})" class="p-2 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50" title="{{ __('admin_career_path.tooltip_edit_info') }}"><i data-lucide="edit" class="w-4.5 h-4.5"></i></button>
                        <button wire:click="confirmDeletePath({{ $cp->id }})" class="p-2 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50" title="{{ __('admin_career_path.tooltip_delete') }}"><i data-lucide="trash" class="w-4.5 h-4.5"></i></button>
                    </div>
                </div>

                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-5 line-clamp-2 h-10">{{ $cp->description ?? __('admin_career_path.no_desc') }}</p>

                <div class="flex items-center gap-4 text-xs font-bold text-gray-500 dark:text-gray-400 mb-5">
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-700"><i data-lucide="trending-up" class="w-4 h-4 text-emerald-500 dark:text-emerald-400"></i> {{ $cp->levels_count ?? $cp->levels()->count() }} {{ __('admin_career_path.level_count') }}</span>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-700"><i data-lucide="users" class="w-4 h-4 text-blue-500 dark:text-blue-400"></i> {{ $cp->users_count ?? $cp->users()->count() }} {{ __('admin_career_path.employee_count') }}</span>
                </div>

                <div class="flex flex-wrap gap-2 pt-5 border-t border-gray-100 dark:border-slate-700">
                    @foreach($cp->departments->take(3) as $dept)
                    <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-md text-[10px] font-extrabold uppercase tracking-wider">{{ $dept->nama_department }}</span>
                    @endforeach
                    @if($cp->departments->count() > 3)
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 rounded-md text-[10px] font-extrabold uppercase tracking-wider">+{{ $cp->departments->count() - 3 }} {{ __('admin_career_path.others') }}</span>
                    @endif
                </div>

                <button wire:click="show({{ $cp->id }})" class="mt-5 w-full text-center px-4 py-2.5 text-xs font-bold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-800/50 rounded-xl transition-colors flex items-center justify-center gap-2">
                    {{ __('admin_career_path.btn_manage_level') }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl shadow-sm">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900/50 rounded-full flex items-center justify-center mb-4 mx-auto border border-slate-100 dark:border-slate-700/50">
                <i data-lucide="route" class="w-10 h-10 text-slate-400 dark:text-slate-500"></i>
            </div>
            <p class="text-gray-700 dark:text-gray-300 font-extrabold text-lg mb-4">{{ __('admin_career_path.empty_path_title') }}</p>
            <button wire:click="create" class="inline-flex items-center gap-2 px-5 py-3 md-btn-primary font-bold text-sm shadow-md">
                <i data-lucide="plus" class="w-4.5 h-4.5"></i> {{ __('admin_career_path.btn_create_first') }}
            </button>
        </div>
        @endforelse
    </div>

    {{-- ========================================== --}}
    {{-- MODE: FORM (CREATE/EDIT) --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'form')
    <div class="mb-8">
        <button wire:click="backToList" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_career_path.btn_back') }}
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="{{ $careerPathId ? 'edit-3' : 'map' }}" class="w-6 h-6"></i>
            </div>
            {{ $careerPathId ? __('admin_career_path.title_edit') : __('admin_career_path.title_create') }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium ml-1">{{ __('admin_career_path.subtitle_form') }}</p>
    </div>

    <form wire:submit.prevent="store" class="md-panel overflow-hidden max-w-4xl">
        <div class="p-6 md:p-8 space-y-6">

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('admin_career_path.lbl_path_name') }}</label>
                    <input type="text" wire:model="name" placeholder="{{ __('admin_career_path.ph_path_name') }}" class="w-full px-4 py-3 md-input text-sm font-medium" required>
                    @error('name') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('admin_career_path.lbl_desc') }}</label>
                    <textarea wire:model="description" rows="2" placeholder="{{ __('admin_career_path.ph_desc') }}" class="w-full px-4 py-3 md-input text-sm font-medium"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-slate-700">
                <div>
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-xs font-extrabold flex items-center justify-center shadow-sm">1</span>
                        <label class="text-xs font-extrabold text-gray-800 dark:text-white uppercase tracking-wider">{{ __('admin_career_path.lbl_dept') }}</label>
                    </div>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-3 ml-8.5">{{ __('admin_career_path.desc_dept') }}</p>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl max-h-56 overflow-y-auto space-y-1.5 scrollbar-thin ml-8.5 shadow-inner">
                        @foreach($allDepartments as $dept)
                        <label class="flex items-center gap-3 p-2.5 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-slate-600 hover:shadow-sm has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:border-blue-200 dark:has-[:checked]:border-blue-800/50">
                            <input type="checkbox" wire:model.live="selectedDepartments" value="{{ $dept->id }}" class="md-checkbox">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $dept->nama_department }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('selectedDepartments') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block ml-8.5">{{ $message }}</span> @enderror
                </div>

                <div>
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 text-xs font-extrabold flex items-center justify-center shadow-sm">2</span>
                        <label class="text-xs font-extrabold text-gray-800 dark:text-white uppercase tracking-wider">{{ __('admin_career_path.lbl_pos') }}</label>
                    </div>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-3 ml-8.5">{{ __('admin_career_path.desc_pos') }}</p>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl max-h-56 overflow-y-auto space-y-1.5 scrollbar-thin ml-8.5 shadow-inner">
                        @forelse($availableJabatans as $jab)
                        <label class="flex items-center gap-3 p-2.5 hover:bg-white dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-slate-600 hover:shadow-sm has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:border-blue-200 dark:has-[:checked]:border-blue-800/50">
                            <input type="checkbox" wire:model="selectedJabatans" value="{{ $jab->id }}" class="md-checkbox">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $jab->nama_jabatan }}</span>
                        </label>
                        @empty
                        <div class="flex flex-col items-center justify-center py-4">
                            <i data-lucide="info" class="w-6 h-6 text-gray-300 dark:text-gray-600 mb-2"></i>
                            <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 text-center px-4">{{ __('admin_career_path.empty_pos') }}</p>
                        </div>
                        @endforelse
                    </div>
                    @error('selectedJabatans') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block ml-8.5">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="p-6 md:p-8 border-t border-gray-100 dark:border-slate-700/80 bg-white dark:bg-slate-800 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-2 rounded-lg flex items-center gap-2 border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="lightbulb" class="w-4 h-4"></i> {{ __('admin_career_path.info_after_save') }}
            </p>
            <button type="submit" class="px-6 py-3 text-sm font-bold md-btn-primary flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">{{ __('admin_career_path.btn_save_continue') }} <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i></span>
                <span wire:loading wire:target="store" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('admin_career_path.saving') }}
                </span>
            </button>
        </div>
    </form>

    {{-- ========================================== --}}
    {{-- MODE: DETAIL (KELOLA LEVEL & USER) --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'detail')
    <div class="mb-8 flex justify-between items-start">
        <div>
            <button wire:click="backToList" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_career_path.btn_back_list') }}
            </button>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                {{ $activeCareerPath->name }}
            </h1>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 ml-1">{{ $activeCareerPath->description }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
        <div class="xl:col-span-2 space-y-6">
            <div class="md-panel p-6 sm:p-8">
                <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-1.5 flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_career_path.title_levels') }}
                </h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">{{ __('admin_career_path.desc_levels') }}</p>

                <div class="space-y-4 mb-8">
                    @forelse($activeCareerPath->levels as $lvl)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-md transition-all duration-300 gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-black text-lg border-2 border-blue-100 dark:border-blue-800 shadow-sm shrink-0">
                                L{{ $lvl->level }}
                            </div>
                            <div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base mb-1">{{ $lvl->level_name }}</h4>
                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold">
                                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-md border border-slate-200 dark:border-slate-600">{{ __('admin_career_path.lbl_jabatan') }}: {{ $lvl->jabatan->nama_jabatan ?? 'N/A' }}</span>
                                    <span class="px-2 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md border border-amber-100 dark:border-amber-800/50"><i data-lucide="star" class="w-3 h-3 inline align-middle mr-0.5"></i> {{ __('admin_career_path.lbl_target') }}: {{ number_format($lvl->required_exp) }} EXP</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 self-end sm:self-auto">
                            <button wire:click="editLevel({{ $lvl->id }})" class="p-2.5 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 border border-transparent hover:border-amber-200 dark:hover:border-amber-800/50" title="{{ __('admin_career_path.tooltip_edit') }}"><i data-lucide="edit" class="w-4.5 h-4.5"></i></button>
                            <button wire:click="confirmDeleteLevel({{ $lvl->id }})" class="p-2.5 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 border border-transparent hover:border-red-200 dark:hover:border-red-800/50" title="{{ __('admin_career_path.tooltip_delete') }}"><i data-lucide="trash" class="w-4.5 h-4.5"></i></button>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-xl flex items-start gap-3 shadow-sm">
                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300">{!! __('admin_career_path.empty_levels') !!}</p>
                    </div>
                    @endforelse
                </div>

                @if(count($levelJabatanOptions) === 0)
                <div class="p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl text-sm font-medium text-red-700 dark:text-red-400 flex items-start gap-3 shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <p>{{ __('admin_career_path.warning_no_pos') }}</p>
                </div>
                @else
                <div class="p-6 sm:p-8 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-inner">
                    <h4 class="text-base font-extrabold text-gray-900 dark:text-white mb-1.5 flex items-center gap-2">
                        <i data-lucide="{{ $levelId ? 'edit-3' : 'plus-square' }}" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        {{ $levelId ? __('admin_career_path.title_edit_level') : __('admin_career_path.title_add_level') }}
                    </h4>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-5">{{ __('admin_career_path.desc_level_form') }}</p>

                    <form wire:submit.prevent="storeLevel" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('admin_career_path.lbl_level_order') }}</label>
                            <input type="number" wire:model="levelOrder" min="1" class="w-full px-4 py-2.5 md-input text-sm font-bold bg-white dark:bg-slate-900" required>
                            @error('levelOrder') <span class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('admin_career_path.lbl_level_name') }}</label>
                            <input type="text" wire:model="levelName" placeholder="{{ __('admin_career_path.ph_level_name') }}" class="w-full px-4 py-2.5 md-input text-sm font-bold bg-white dark:bg-slate-900" required>
                            @error('levelName') <span class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('admin_career_path.lbl_exp_target') }}</label>
                            <div class="relative">
                                <i data-lucide="star" class="w-4 h-4 text-amber-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                                <input type="number" wire:model="expRequired" min="0" class="w-full pl-9 pr-4 py-2.5 md-input text-sm font-bold bg-white dark:bg-slate-900" required>
                            </div>
                            <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-1.5">{{ __('admin_career_path.desc_exp_target') }}</p>
                            @error('expRequired') <span class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('admin_career_path.lbl_level_pos') }}</label>
                            <select wire:model="jabatanIdLevel" class="w-full px-4 py-2.5 md-input text-sm font-bold bg-white dark:bg-slate-900" required>
                                <option value="">{{ __('admin_career_path.opt_select_pos') }}</option>
                                @foreach($levelJabatanOptions as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-1.5">{{ __('admin_career_path.desc_level_pos') }}</p>
                            @error('jabatanIdLevel') <span class="text-[10px] font-bold text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-slate-700/80">
                            @if($levelId)
                            <button type="button" wire:click="cancelLevelEdit" class="px-5 py-2.5 text-xs font-bold md-btn-neutral">{{ __('admin_career_path.btn_cancel_edit') }}</button>
                            @endif
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold md-btn-primary flex items-center gap-1.5 shadow-md">
                                <i data-lucide="{{ $levelId ? 'save' : 'plus' }}" class="w-4 h-4"></i>
                                {{ $levelId ? __('admin_career_path.btn_save_changes') : __('admin_career_path.btn_add_level') }}
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <div class="xl:col-span-1">
            <div class="md-panel p-6 sticky top-8">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_career_path.title_participants') }}
                    </h3>
                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold text-xs rounded-lg border border-blue-100 dark:border-blue-800/50 shadow-sm">{{ $activeCareerPath->users->count() }}</span>
                </div>
                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-5">{{ __('admin_career_path.desc_participants') }}</p>

                <div class="space-y-3 max-h-[500px] overflow-y-auto scrollbar-thin pr-2">
                    @forelse($activeCareerPath->users as $u)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 rounded-xl hover:bg-white dark:hover:bg-slate-800 hover:border-blue-200 dark:hover:border-blue-700 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200 dark:border-blue-800/50">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">{{ $u->name }}</p>
                                <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400">{{ $u->jabatan->nama_jabatan ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-block px-2 py-0.5 bg-blue-600 dark:bg-blue-500 text-white rounded text-[10px] font-extrabold mb-1 shadow-sm">Lvl {{ $u->pivot->current_level }}</span>
                            <span class="block text-[10px] font-bold text-amber-500 dark:text-amber-400"><i data-lucide="star" class="w-2.5 h-2.5 inline align-baseline"></i> {{ number_format($u->pivot->total_exp) }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl">
                        <i data-lucide="users" class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('admin_career_path.empty_participants') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL: KONFIRMASI HAPUS (Career Path & Level) --}}
    {{-- ========================================== --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm" x-data x-on:keydown.escape.window="$wire.cancelDelete()">

        {{-- Overlay --}}
        <div class="absolute inset-0" wire:click="cancelDelete"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl w-full max-w-md overflow-hidden shadow-xl"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="p-6 border-b border-red-50 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/20 text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 dark:bg-red-900/50 mb-4 shadow-sm border border-red-200 dark:border-red-800/50">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">
                    {{ $deleteType === 'level' ? __('admin_career_path.modal_del_level_title') : __('admin_career_path.modal_del_path_title') }}
                </h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2">
                    {{ __('admin_career_path.modal_del_confirm') }} <span class="font-bold text-gray-800 dark:text-gray-200">"{{ $deleteTargetName }}"</span>.
                    @if($deleteType === 'level')
                    {{ __('admin_career_path.modal_del_level_warning') }}
                    @else
                    {{ __('admin_career_path.modal_del_path_warning') }}
                    @endif
                    {{ __('admin_career_path.modal_del_irreversible') }}
                </p>
            </div>

            @if($deleteType === 'path' && $deleteTargetUserCount > 0)
            <div class="px-6 py-4 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-800/50 flex items-start gap-3">
                <i data-lucide="users" class="w-5 h-5 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5"></i>
                <p class="text-xs font-bold text-amber-800 dark:text-amber-400 leading-relaxed">
                    {{ __('admin_career_path.modal_attention') }} <strong>{{ $deleteTargetUserCount }} {{ __('admin_career_path.modal_users_affected') }}</strong>
                </p>
            </div>
            @endif

            <div class="p-6 bg-white dark:bg-slate-800 flex flex-col sm:flex-row-reverse gap-3">
                <button
                    wire:click="executeDelete"
                    wire:loading.attr="disabled"
                    wire:target="executeDelete"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold md-btn-danger-solid flex items-center justify-center gap-2 disabled:opacity-60 flex-1 shadow-md">
                    <i data-lucide="trash" class="w-4 h-4" wire:loading.remove wire:target="executeDelete"></i>
                    <span wire:loading.remove wire:target="executeDelete">{{ __('admin_career_path.btn_delete_permanent') }}</span>
                    <span wire:loading wire:target="executeDelete" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('admin_career_path.deleting') }}
                    </span>
                </button>
                <button
                    wire:click="cancelDelete"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold md-btn-neutral flex-1">
                    {{ __('admin_career_path.btn_cancel') }}
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