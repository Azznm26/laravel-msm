<div class="min-h-screen px-4 sm:px-6 py-8" style="background: var(--background); font-family: 'Inter', sans-serif; color: var(--ink);">

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

        /* ---------- Dark mode overrides (applied when an ancestor, usually <html>, has class="dark") ---------- */
        .dark {
            --primary: #3b82f6;
            --primary-hover: #60a5fa;
            --primary-tint: rgba(59, 130, 246, 0.12);

            --surface: #1e293b;
            --background: #0f172a;
            --border: #334155;

            --ink: #f1f5f9;
            --ink-soft: #94a3b8;

            --danger: #f87171;
            --danger-hover: #fca5a5;
            --danger-tint: rgba(248, 113, 113, 0.12);

            --success: #34d399;
            --success-tint: rgba(52, 211, 153, 0.12);
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark .md-panel {
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -2px rgba(0, 0, 0, 0.15);
        }

        .md-input {
            background: var(--background);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            color: var(--ink);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark .md-input {
            border: 1px solid var(--border);
            color-scheme: dark;
        }

        .md-input:focus {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .dark .md-input:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
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

        .dark .md-btn-primary:hover {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .md-btn-neutral {
            background: #f1f5f9;
            color: var(--ink-soft);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .dark .md-btn-neutral {
            background: #334155;
        }

        .md-btn-neutral:hover {
            background: #e2e8f0;
            color: var(--ink);
        }

        .dark .md-btn-neutral:hover {
            background: #475569;
        }

        .md-tab {
            border-radius: 999px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

        {{-- Flash Messages --}}
        @if (session()->has('success'))
        <div class="px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-400">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 text-red-800 dark:text-red-400">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-500 dark:text-red-400"></i>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/40">
                        <i data-lucide="sliders-horizontal" class="w-6 h-6"></i>
                    </div>
                    {{ __('gap-analysis.title') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 ml-1 font-medium">
                    {{ __('gap-analysis.subtitle') }}
                </p>
            </div>
        </div>

        {{-- Tabs (Material Filter Chips) --}}
        <div class="md-panel p-3 flex flex-wrap gap-2 items-center">
            @foreach([
            'aspects' => ['label' => __('gap-analysis.tab_aspects'), 'icon' => 'layers'],
            'standards' => ['label' => __('gap-analysis.tab_standards'), 'icon' => 'target'],
            'scores' => ['label' => __('gap-analysis.tab_scores'), 'icon' => 'clipboard-check'],
            ] as $key => $tab)
            <button
                type="button"
                wire:click="switchTab('{{ $key }}')"
                @class([ 'md-tab flex items-center gap-2 px-5 py-2.5 text-sm font-bold shadow-sm' , 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/40'=> $activeTab === $key,
                'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 border-gray-100 dark:border-gray-800 shadow-none' => $activeTab !== $key,
                ])
                >
                <i data-lucide="{{ $tab['icon'] }}" class="w-4.5 h-4.5"></i> {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ================================================================= --}}
        {{-- TAB 1: ASPEK & KRITERIA --}}
        {{-- ================================================================= --}}
        @if($activeTab === 'aspects')

        <div class="flex justify-end mb-2">
            <button
                type="button"
                wire:click="newAspectForm"
                class="flex items-center gap-2 px-5 py-2.5 md-btn-primary text-sm font-bold">
                <i data-lucide="plus" class="w-4.5 h-4.5"></i> {{ __('gap-analysis.add_aspect') }}
            </button>
        </div>

        {{-- Form Aspek --}}
        @if($showAspectForm)
        <div class="md-panel p-6 sm:p-8 mb-6 border-blue-200 dark:border-blue-800/40 ring-4 ring-blue-50 dark:ring-blue-900/20 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500"></div>
            <h3 class="font-extrabold text-lg text-gray-900 dark:text-gray-100 mb-5 flex items-center gap-2">
                <i data-lucide="{{ $editingAspectId ? 'edit-3' : 'plus-square' }}" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                {{ $editingAspectId ? __('gap-analysis.edit_aspect') : __('gap-analysis.new_aspect') }}
            </h3>
            <form wire:submit.prevent="saveAspect" class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('gap-analysis.aspect_name_label') }}</label>
                    <input type="text" wire:model="aspectName" placeholder="{{ __('gap-analysis.aspect_name_placeholder') }}" class="w-full px-4 py-3 md-input text-sm font-medium">
                    @error('aspectName') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('gap-analysis.core_factor_label') }}</label>
                    <input type="number" step="0.01" wire:model="aspectCfWeight" class="w-full px-4 py-3 md-input text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('gap-analysis.secondary_factor_label') }}</label>
                    <input type="number" step="0.01" wire:model="aspectSfWeight" class="w-full px-4 py-3 md-input text-sm font-medium">
                    @error('aspectSfWeight') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-4 flex gap-3 justify-end mt-2 border-t border-gray-100 dark:border-gray-800 pt-5">
                    <button type="button" wire:click="cancelAspectForm" class="px-6 py-2.5 md-btn-neutral text-sm font-bold">{{ __('gap-analysis.cancel') }}</button>
                    <button type="submit" class="px-6 py-2.5 md-btn-primary text-sm font-bold flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> {{ __('gap-analysis.save') }}
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- List Aspek + Kriteria --}}
        <div class="space-y-6">
            @forelse($this->aspects as $aspect)
            <div class="md-panel overflow-hidden">
                <div class="px-6 py-5 bg-slate-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-lg text-gray-900 dark:text-gray-100 mb-1">{{ $aspect->name }}</h4>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-[10px] font-extrabold uppercase tracking-wider">{{ __('gap-analysis.cf_percent', ['value' => (int) $aspect->cf_weight]) }}</span>
                            <span class="px-2 py-0.5 bg-slate-200 dark:bg-gray-700 text-slate-600 dark:text-gray-300 rounded text-[10px] font-extrabold uppercase tracking-wider">{{ __('gap-analysis.sf_percent', ['value' => (int) $aspect->sf_weight]) }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="editAspect({{ $aspect->id }})" class="p-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:border-amber-200 dark:hover:border-amber-800 transition-colors shadow-sm" title="{{ __('gap-analysis.edit_aspect_title') }}"><i data-lucide="pencil" class="w-4.5 h-4.5"></i></button>
                        <button wire:click="deleteAspect({{ $aspect->id }})" wire:confirm="{{ __('gap-analysis.confirm_delete_aspect', ['name' => $aspect->name]) }}" class="p-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800 transition-colors shadow-sm" title="{{ __('gap-analysis.delete_aspect_title') }}"><i data-lucide="trash-2" class="w-4.5 h-4.5"></i></button>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($aspect->criteria as $criteria)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                        <div class="flex items-center gap-4">
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm border {{ $criteria->factor_type === 'core' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-800/40' : 'bg-slate-50 dark:bg-gray-800 text-slate-500 dark:text-gray-400 border-slate-200 dark:border-gray-700' }}">
                                {{ $criteria->factor_type === 'core' ? __('gap-analysis.factor_core') : __('gap-analysis.factor_secondary') }}
                            </span>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $criteria->name }}</span>
                        </div>
                        <div class="flex gap-1.5 opacity-60 group-hover:opacity-100 transition-opacity">
                            <button wire:click="editCriteria({{ $criteria->id }})" class="p-2 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button wire:click="deleteCriteria({{ $criteria->id }})" wire:confirm="{{ __('gap-analysis.confirm_delete_criteria') }}" class="p-2 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                    @endforeach

                    {{-- Form tambah/edit kriteria untuk aspek ini --}}
                    @if($addingCriteriaForAspectId === $aspect->id)
                    <form wire:submit.prevent="saveCriteria" class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10 border-t border-blue-100 dark:border-blue-800/30 flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('gap-analysis.criteria_name_label') }}</label>
                            <input type="text" wire:model="criteriaName" placeholder="{{ __('gap-analysis.criteria_name_placeholder') }}" class="w-full px-3 py-2.5 md-input text-sm font-medium">
                            @error('criteriaName') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('gap-analysis.factor_label') }}</label>
                            <select wire:model="criteriaFactorType" class="px-3 py-2.5 md-input text-sm font-medium w-40">
                                <option value="core">{{ __('gap-analysis.option_core_factor') }}</option>
                                <option value="secondary">{{ __('gap-analysis.option_secondary_factor') }}</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" wire:click="cancelCriteriaForm" class="px-4 py-2.5 md-btn-neutral text-sm font-bold">{{ __('gap-analysis.cancel') }}</button>
                            <button type="submit" class="px-4 py-2.5 md-btn-primary text-sm font-bold flex items-center gap-1.5"><i data-lucide="save" class="w-4 h-4"></i> {{ __('gap-analysis.save') }}</button>
                        </div>
                    </form>
                    @else
                    <button
                        type="button"
                        wire:click="newCriteriaForm({{ $aspect->id }})"
                        class="w-full px-6 py-3.5 text-xs font-extrabold text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors flex items-center justify-center gap-2 bg-white dark:bg-gray-900">
                        <i data-lucide="plus" class="w-4 h-4"></i> {{ __('gap-analysis.add_criteria') }}
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="md-panel p-16 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="layers" class="w-8 h-8 text-slate-300 dark:text-gray-600"></i>
                </div>
                <h3 class="text-lg font-extrabold text-gray-800 dark:text-gray-100">{{ __('gap-analysis.no_aspects_title') }}</h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1.5 mb-5">{{ __('gap-analysis.no_aspects_subtitle') }}</p>
                <button type="button" wire:click="newAspectForm" class="px-5 py-2.5 md-btn-primary text-sm font-bold flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('gap-analysis.add_aspect') }}
                </button>
            </div>
            @endforelse
        </div>

        {{-- ================================================================= --}}
        {{-- TAB 2: STANDAR PER LEVEL --}}
        {{-- ================================================================= --}}
        @elseif($activeTab === 'standards')

        <div class="md-panel p-5 flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('gap-analysis.select_career_path_label') }}</label>
                <select wire:model.live="standardsCareerPathId" class="w-full px-4 py-3 md-input text-sm font-medium">
                    <option value="">{{ __('gap-analysis.select_career_path_placeholder') }}</option>
                    @foreach($this->careerPathsList as $cp)
                    <option value="{{ $cp->id }}">{{ $cp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">{{ __('gap-analysis.select_level_label') }}</label>
                <select wire:model.live="standardsLevelId" class="w-full px-4 py-3 md-input text-sm font-medium disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600" @disabled(!$standardsCareerPathId)>
                    <option value="">{{ __('gap-analysis.select_level_placeholder') }}</option>
                    @foreach($this->levelsForStandards as $lvl)
                    <option value="{{ $lvl->id }}">{{ __('gap-analysis.level_option', ['level' => $lvl->level, 'name' => $lvl->level_name]) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($standardsLevelId)
        <div class="md-panel overflow-hidden">
            <div class="p-5 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800/30 flex items-center gap-3">
                <i data-lucide="target" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                <div>
                    <h3 class="font-extrabold text-blue-900 dark:text-blue-300">{{ __('gap-analysis.standards_settings_title') }}</h3>
                    <p class="text-[11px] font-medium text-blue-700 dark:text-blue-400">{{ __('gap-analysis.standards_settings_subtitle') }}</p>
                </div>
            </div>

            @foreach($this->aspects as $aspect)
            <div class="px-6 py-3.5 bg-slate-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-800">
                <span class="text-xs font-extrabold text-slate-700 dark:text-gray-300 uppercase tracking-wider">{{ $aspect->name }}</span>
            </div>
            @foreach($aspect->criteria as $criteria)
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border {{ $criteria->factor_type === 'core' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-800/40' : 'bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 border-slate-200 dark:border-gray-700' }}">
                        {{ $criteria->factor_type === 'core' ? __('gap-analysis.factor_core') : __('gap-analysis.factor_secondary') }}
                    </span>
                    <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $criteria->name }}</span>
                </div>
                <select wire:model="standardValues.{{ $criteria->id }}" class="w-24 px-3 py-2 md-input text-sm font-bold text-center">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                </select>
            </div>
            @endforeach
            @endforeach

            <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-800 flex justify-end">
                <button
                    type="button"
                    wire:click="saveStandards"
                    class="px-6 py-3 md-btn-primary text-sm font-bold flex items-center gap-2 w-full sm:w-auto justify-center" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveStandards">{{ __('gap-analysis.save_standards') }}</span>
                    <span wire:loading wire:target="saveStandards" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('gap-analysis.saving') }}
                    </span>
                </button>
            </div>
        </div>
        @else
        <div class="md-panel p-12 text-center flex flex-col items-center justify-center border-dashed">
            <div class="w-16 h-16 bg-slate-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="mouse-pointer-click" class="w-8 h-8 text-slate-300 dark:text-gray-600"></i>
            </div>
            <p class="text-base font-bold text-gray-700 dark:text-gray-200">{{ __('gap-analysis.select_prompt_title') }}</p>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ __('gap-analysis.select_prompt_subtitle') }}</p>
        </div>
        @endif

        {{-- ================================================================= --}}
        {{-- TAB 3: PENILAIAN KOMPETENSI USER --}}
        {{-- ================================================================= --}}
        @elseif($activeTab === 'scores')

        @if(!$selectedUserId)
        {{-- Cari user --}}
        <div class="md-panel p-6 sm:p-8 max-w-2xl mx-auto">
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-gray-100 mb-2 text-center">{{ __('gap-analysis.employee_assessment_title') }}</h3>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6 text-center">{{ __('gap-analysis.employee_assessment_subtitle') }}</p>

            <div class="relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="userSearch"
                    placeholder="{{ __('gap-analysis.search_employee_placeholder') }}"
                    class="w-full pl-12 pr-4 py-3.5 md-input text-sm font-medium shadow-sm">
            </div>

            @if($this->userSearchResults->isNotEmpty())
            <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($this->userSearchResults as $u)
                <button
                    type="button"
                    wire:click="selectUserForScoring({{ $u->id }})"
                    class="w-full px-5 py-4 text-left hover:bg-blue-50 dark:hover:bg-blue-900/10 focus:bg-blue-50 dark:focus:bg-blue-900/10 transition-colors flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $u->name }}</span>
                            <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">{{ $u->jabatan?->nama_jabatan ?? '-' }}</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4.5 h-4.5 text-gray-300 dark:text-gray-600 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors"></i>
                </button>
                @endforeach
            </div>
            @endif
        </div>
        @else

        {{-- Form penilaian user terpilih --}}
        <div class="md-panel p-5 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 text-white shadow-md border-transparent">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center font-extrabold text-xl border border-white/30 shrink-0">
                    {{ strtoupper(substr($this->selectedUser->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-blue-100 uppercase tracking-wider mb-0.5">{{ __('gap-analysis.assessing_label') }}</p>
                    <p class="text-lg font-extrabold">{{ $this->selectedUser->name }}</p>
                </div>
            </div>
            <button type="button" wire:click="clearSelectedUser" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/30 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 w-full sm:w-auto justify-center">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> {{ __('gap-analysis.change_employee') }}
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
            <div class="xl:col-span-2 md-panel overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-slate-50 dark:bg-gray-800/60 flex items-center justify-between">
                    <h3 class="font-extrabold text-gray-900 dark:text-gray-100 flex items-center gap-2"><i data-lucide="clipboard-edit" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('gap-analysis.assessment_form_title') }}</h3>
                </div>

                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                    <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('gap-analysis.assessment_date_label') }}</label>
                    <input type="date" wire:model="scoreAssessedAt" class="px-4 py-2.5 md-input text-sm font-medium w-full sm:w-1/2">
                </div>

                @foreach($this->aspects as $aspect)
                <div class="px-6 py-3.5 bg-blue-50/40 dark:bg-blue-900/10 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-extrabold text-blue-800 dark:text-blue-400 uppercase tracking-wider">{{ $aspect->name }}</span>
                </div>
                @foreach($aspect->criteria as $criteria)
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50 dark:border-gray-800/60 hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider border {{ $criteria->factor_type === 'core' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-800/40' : 'bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 border-slate-200 dark:border-gray-700' }}">
                            {{ $criteria->factor_type === 'core' ? __('gap-analysis.factor_core') : __('gap-analysis.factor_secondary') }}
                        </span>
                        <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $criteria->name }}</span>
                    </div>
                    <select wire:model="scoreValues.{{ $criteria->id }}" class="w-24 px-3 py-2 md-input text-sm font-bold text-center">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
                @endforeach
                @endforeach

                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                    <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('gap-analysis.notes_label') }}</label>
                    <textarea wire:model="scoreNotes" rows="3" class="w-full px-4 py-3 md-input text-sm font-medium" placeholder="{{ __('gap-analysis.notes_placeholder') }}"></textarea>
                </div>

                <div class="px-6 py-5 bg-slate-50 dark:bg-gray-800/60 flex justify-end border-t border-gray-200 dark:border-gray-800">
                    <button
                        type="button"
                        wire:click="saveScores"
                        class="px-6 py-3 md-btn-primary text-sm font-bold flex items-center gap-2 w-full sm:w-auto justify-center" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveScores">{{ __('gap-analysis.save_assessment') }}</span>
                        <span wire:loading wire:target="saveScores" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('gap-analysis.saving') }}
                        </span>
                    </button>
                </div>
            </div>

            <div class="xl:col-span-1">
                {{-- Riwayat penilaian user ini --}}
                <div class="md-panel overflow-hidden sticky top-8">
                    <div class="px-6 py-5 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5 text-gray-400 dark:text-gray-500"></i>
                        <h3 class="font-extrabold text-gray-900 dark:text-gray-100">{{ __('gap-analysis.recent_history_title') }}</h3>
                    </div>
                    @if($this->scoreHistory->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                            <thead class="bg-slate-50 dark:bg-gray-800/60">
                                <tr>
                                    <th class="px-5 py-3 text-left text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('gap-analysis.criteria_column') }}</th>
                                    <th class="px-5 py-3 text-center text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('gap-analysis.value_column') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/60 bg-white dark:bg-gray-900">
                                @foreach($this->scoreHistory as $h)
                                <tr class="hover:bg-slate-50 dark:hover:bg-gray-800/40 transition-colors">
                                    <td class="px-5 py-3">
                                        <p class="font-bold text-gray-800 dark:text-gray-100 text-xs">{{ $h->criteria->name }}</p>
                                        <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-0.5">{{ $h->assessed_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-center font-black text-blue-600 dark:text-blue-400 text-sm">
                                        {{ $h->actual_value }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-8 text-center text-gray-400 dark:text-gray-500 flex flex-col items-center">
                        <i data-lucide="file-x" class="w-10 h-10 mb-2 opacity-50"></i>
                        <p class="text-sm font-medium">{{ __('gap-analysis.no_history') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endif

    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>