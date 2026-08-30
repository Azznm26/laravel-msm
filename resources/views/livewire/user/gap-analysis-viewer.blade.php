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
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-panel-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            border-color: transparent;
        }
    </style>

    <div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                        <i data-lucide="target" class="w-6 h-6"></i>
                    </div>
                    {{ __('user_gap.title') }}
                </h1>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 ml-1">
                    {{ __('user_gap.subtitle') }}
                </p>
            </div>
        </div>

        @if(!$this->activeCareerPath)

        {{-- Belum punya career path aktif --}}
        <div class="md-panel p-12 sm:p-16 text-center border-dashed border-slate-200 dark:border-slate-700">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                <i data-lucide="compass" class="w-10 h-10 text-slate-400 dark:text-slate-500"></i>
            </div>
            <p class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('user_gap.no_active_path') }}</p>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2 max-w-md mx-auto">{{ __('user_gap.no_active_path_desc') }}</p>
        </div>

        @elseif(!$this->targetLevel)

        {{-- Data level tidak ditemukan --}}
        <div class="md-panel p-12 sm:p-16 text-center border-dashed border-amber-200 dark:border-amber-800/50 bg-amber-50/30 dark:bg-amber-900/10">
            <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                <i data-lucide="alert-triangle" class="w-10 h-10 text-amber-500 dark:text-amber-400"></i>
            </div>
            <p class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('user_gap.level_not_found') }}</p>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2 max-w-md mx-auto">{{ __('user_gap.level_not_found_desc') }}</p>
        </div>

        @else

        {{-- Toggle: level sekarang vs level berikutnya --}}
        <div class="md-panel p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-800">
            <div class="text-sm text-gray-600 dark:text-gray-300 font-medium flex flex-wrap items-center gap-2">
                <span class="font-extrabold text-gray-800 dark:text-gray-200">{{ __('user_gap.current_level') }}</span>
                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-xs font-bold">{{ $this->currentLevel?->level_name ?? '-' }}</span>

                @if($this->nextLevel)
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-300 dark:text-gray-600 mx-1"></i>
                <span class="font-extrabold text-gray-800 dark:text-gray-200">{{ __('user_gap.next_target') }}</span>
                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-bold">{{ $this->nextLevel->level_name }}</span>
                @else
                <span class="ml-2 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50 rounded-lg text-xs font-bold shadow-sm">{{ __('user_gap.highest_level_reached') }}</span>
                @endif
            </div>

            <div class="flex gap-2.5 bg-slate-50 dark:bg-slate-900/50 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700">
                <button
                    type="button"
                    wire:click="$set('compareMode', 'current')"
                    @class([ 'px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm' , 'bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-700/50 ring-1 ring-blue-500'=> $compareMode === 'current',
                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent shadow-none' => $compareMode !== 'current',
                    ])>
                    {{ __('user_gap.current_level_standard') }}
                </button>
                <button
                    type="button"
                    wire:click="$set('compareMode', 'next')"
                    @disabled(!$this->nextLevel)
                    @class([
                    'px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none',
                    'bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-700/50 ring-1 ring-blue-500' => $compareMode === 'next',
                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border border-transparent shadow-none' => $compareMode !== 'next',
                    ])>
                    {{ __('user_gap.promotion_target') }}
                </button>
            </div>
        </div>

        @if(!$this->gapResult)

        {{-- Standar belum diatur --}}
        <div class="md-panel p-12 sm:p-16 text-center border-dashed border-slate-200 dark:border-slate-700">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                <i data-lucide="file-x" class="w-10 h-10 text-slate-400 dark:text-slate-500"></i>
            </div>
            <p class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('user_gap.standard_not_set') }}</p>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mt-2 max-w-md mx-auto">
                {{ __('user_gap.standard_not_set_desc') }} "{{ $this->targetLevel->level_name }}".
            </p>
        </div>

        @else

        {{-- Skor Akhir --}}
        <div class="md-panel p-6 sm:p-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-5 transform translate-x-4 -translate-y-4 pointer-events-none">
                <i data-lucide="gauge" class="w-48 h-48 text-blue-600 dark:text-blue-400"></i>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('user_gap.suitability_score') }} {{ $this->gapResult['level']->level_name }}</p>
                    <p class="text-4xl sm:text-5xl font-black text-blue-600 dark:text-blue-400 tracking-tight">
                        {{ $this->gapResult['final_percentage'] }}%
                        <span class="text-base font-bold text-gray-400 dark:text-gray-500 ml-1">({{ $this->gapResult['final_score'] }} / 5.0)</span>
                    </p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 rounded-2xl text-blue-600 dark:text-blue-400 shadow-sm shrink-0 hidden sm:block">
                    <i data-lucide="gauge" class="w-8 h-8"></i>
                </div>
            </div>
            <div class="relative z-10 w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3.5 mt-2 overflow-hidden shadow-inner border border-slate-200 dark:border-slate-600">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-400 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $this->gapResult['final_percentage'] }}%"></div>
            </div>
        </div>

        {{-- Detail per Aspek --}}
        <div class="space-y-6">
            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                <i data-lucide="layers" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('user_gap.competency_details') }}
            </h3>

            @foreach($this->gapResult['aspects'] as $aspectData)
            <div class="md-panel overflow-hidden md-panel-hover group">
                <div class="px-6 py-5 bg-slate-50 dark:bg-slate-800/80 border-b border-gray-100 dark:border-slate-700/80 flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-gray-900 dark:text-gray-100 text-lg mb-1">{{ $aspectData['aspect']->name }}</h3>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 rounded text-[10px] font-extrabold uppercase tracking-wider shadow-sm">{{ __('user_gap.core_factor') }} {{ (int) $aspectData['aspect']->cf_weight }}%</span>
                            <span class="px-2 py-0.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 rounded text-[10px] font-extrabold uppercase tracking-wider shadow-sm">{{ __('user_gap.secondary_factor') }} {{ (int) $aspectData['aspect']->sf_weight }}%</span>
                        </div>
                    </div>
                    <div class="text-right bg-white dark:bg-slate-800 p-3 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                        <div class="text-2xl font-black leading-none {{ $aspectData['aspect_score'] >= 4 ? 'text-emerald-600 dark:text-emerald-400' : ($aspectData['aspect_score'] >= 3 ? 'text-amber-500 dark:text-amber-400' : 'text-red-500 dark:text-red-400') }}">
                            {{ $aspectData['aspect_score'] }}
                        </div>
                        <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mt-1">{{ __('user_gap.aspect_score') }}</div>
                    </div>
                </div>

                @if($aspectData['has_missing_assessment'])
                <div class="px-6 py-3 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-800/50 text-xs font-medium text-amber-800 dark:text-amber-400 flex items-start gap-2.5">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5"></i>
                    {{ __('user_gap.missing_assessment_warning') }}
                </div>
                @endif

                <div class="overflow-x-auto bg-white dark:bg-transparent">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700/80 text-sm">
                        <thead class="bg-white dark:bg-slate-800">
                            <tr class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-extrabold">{{ __('user_gap.criteria') }}</th>
                                <th class="px-4 py-3 text-center font-extrabold">{{ __('user_gap.factor') }}</th>
                                <th class="px-4 py-3 text-center font-extrabold">{{ __('user_gap.target') }}</th>
                                <th class="px-4 py-3 text-center font-extrabold">{{ __('user_gap.actual') }}</th>
                                <th class="px-4 py-3 text-center font-extrabold">{{ __('user_gap.gap') }}</th>
                                <th class="px-6 py-3 text-center font-extrabold">{{ __('user_gap.weight') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @foreach($aspectData['details'] as $d)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="px-6 py-3.5 text-gray-800 dark:text-gray-200 font-bold">{{ $d['criteria'] }}</td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm border {{ $d['factor_type'] === 'core' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-800/50' : 'bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700' }}">
                                        {{ $d['factor_type'] === 'core' ? __('user_gap.core') : __('user_gap.secondary') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-bold text-gray-700 dark:text-gray-300">{{ $d['target'] }}</td>
                                <td class="px-4 py-3.5 text-center font-bold text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10">{{ $d['actual'] }}</td>
                                <td class="px-4 py-3.5 text-center font-black {{ $d['gap'] > 0 ? 'text-emerald-500 dark:text-emerald-400' : ($d['gap'] < 0 ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500') }}">
                                    {{ $d['gap'] > 0 ? '+' . $d['gap'] : $d['gap'] }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-bold text-gray-600 dark:text-gray-400 bg-slate-50/50 dark:bg-slate-800/30">{{ $d['weight'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-start gap-2.5 px-2 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
            <i data-lucide="info" class="w-4 h-4 mt-0.5 text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
            <p class="font-medium leading-relaxed">
                {!! __('user_gap.info_note') !!}
            </p>
        </div>

        @endif
        @endif

    </div>

    <script>
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>