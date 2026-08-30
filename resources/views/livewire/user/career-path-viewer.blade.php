<div class="min-h-screen px-4 sm:px-6 py-8 bg-[#f8fafc] dark:bg-slate-900 font-sans text-slate-900 dark:text-slate-100" style="font-family: 'Inter', sans-serif;">

    <style>
        /* ---------- Material Design 3 Colors & Components ---------- */
        .md-panel {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-panel-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            border-color: transparent;
        }

        .md-btn-outline {
            background: #ffffff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .md-btn-outline:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.05);
        }

        /* ---------- Dark Mode Support ---------- */
        :is(.dark .md-panel) {
            background: #1e293b;
            /* slate-800 */
            border-color: rgba(51, 65, 85, 0.8);
            /* slate-700 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        :is(.dark .md-panel-hover:hover) {
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.3);
            border-color: rgba(71, 85, 105, 0.5);
            /* slate-600 */
        }

        :is(.dark .md-btn-outline) {
            background: transparent;
            color: #60a5fa;
            /* blue-400 */
            border-color: #1e3a8a;
            /* blue-900 */
        }

        :is(.dark .md-btn-outline:hover) {
            background: rgba(30, 58, 138, 0.3);
            /* blue-900/30 */
            border-color: #3b82f6;
            /* blue-500 */
        }
    </style>

    @if (session()->has('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 rounded-2xl flex items-center gap-3 shadow-sm">
        <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0"></i>
        <span class="font-bold text-sm">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- TAMPILAN INDEX (DAFTAR JALUR KARIR) --}}
    {{-- ======================================================== --}}
    @if($viewMode === 'index')

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 rounded-xl shadow-sm">
                <i data-lucide="map" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            {{ __('user_career.my_career_paths') }}
        </h1>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 ml-1">
            {{ __('user_career.index_subtitle') }}
        </p>
    </div>

    @if($myPaths->isEmpty())
    <div class="md-panel p-12 sm:p-16 text-center border-dashed dark:border-slate-700">
        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
            <i data-lucide="lock" class="w-10 h-10 text-slate-400 dark:text-slate-500"></i>
        </div>
        <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-200">{{ __('user_career.no_career_path') }}</h3>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 max-w-md mx-auto">
            {{ __('user_career.no_career_path_desc') }}
        </p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        @foreach($myPaths as $path)
        <div class="md-panel md-panel-hover flex flex-col relative overflow-hidden {{ $path->is_primary ? 'ring-2 ring-blue-500 border-transparent shadow-md shadow-blue-500/10 dark:shadow-blue-900/20' : '' }}">

            {{-- Accent Line for Primary Path --}}
            @if($path->is_primary)
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500"></div>
            @endif

            <div class="p-6 sm:p-8 flex-1">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        @if($path->is_primary)
                        <span class="inline-block px-3 py-1.5 bg-blue-600 dark:bg-blue-500 text-white text-[10px] font-extrabold uppercase tracking-widest rounded-lg mb-3 shadow-sm">
                            {{ __('user_career.primary_path') }}
                        </span>
                        @else
                        <span class="inline-block px-3 py-1.5 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600/50 text-slate-600 dark:text-slate-300 text-[10px] font-extrabold uppercase tracking-widest rounded-lg mb-3">
                            {{ __('user_career.extra_path') }}
                        </span>
                        @endif
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-gray-100 leading-tight">{{ $path->name }}</h2>
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-0.5">
                            {{ __('user_career.total_exp') }}
                        </span>
                        <span class="text-xl font-black text-amber-500 dark:text-amber-400 flex items-center justify-end gap-1">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i> {{ number_format($path->pivot->total_exp) }}
                        </span>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 rounded-2xl p-5 shadow-inner">
                    <div class="flex justify-between items-end mb-3 text-sm font-bold">
                        <span class="text-gray-800 dark:text-gray-200 bg-white dark:bg-slate-800 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                            {{ __('user_career.lvl') }} {{ $path->pivot->current_level }}
                        </span>
                        @if($path->calculated_next_level)
                        <span class="text-gray-400 dark:text-gray-500">{{ __('user_career.lvl') }} {{ $path->calculated_next_level->level }}</span>
                        @else
                        <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 rounded-md border border-emerald-200 dark:border-emerald-800/50">MAX</span>
                        @endif
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-gray-200/80 dark:bg-slate-700 rounded-full h-3.5 mb-3 overflow-hidden shadow-inner border border-gray-200/50 dark:border-slate-600">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-400 h-full rounded-full transition-all duration-1000 ease-out flex items-center justify-end pr-1.5" style="width: {{ $path->progress_percentage }}%">
                            @if($path->progress_percentage > 15)
                            <span class="text-[8px] font-extrabold text-white opacity-90">{{ $path->progress_percentage }}%</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center font-medium mt-3">
                        @if($path->calculated_next_level)
                        <strong class="text-blue-600 dark:text-blue-400">{{ $path->progress_percentage }}%</strong>
                        {{ __('user_career.towards') }}
                        <strong class="text-gray-700 dark:text-gray-300">{{ $path->calculated_next_level->jabatan->nama_jabatan ?? __('user_career.next_position') }}</strong>
                        @else
                        {{ __('user_career.reached_peak') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 dark:border-slate-700/80 bg-white dark:bg-slate-800/50">
                <button wire:click="showDetails({{ $path->id }})" class="w-full py-3 md-btn-outline text-sm font-bold shadow-sm flex items-center justify-center gap-2">
                    {{ __('user_career.view_roadmap') }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- TAMPILAN SHOW (DETAIL & ROADMAP) --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'show')

    <div class="mb-8">
        <button wire:click="backToIndex" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4 w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('user_career.back_to_list') }}
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 rounded-xl shadow-sm">
                <i data-lucide="milestone" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            {{ $careerPath->name }}
        </h1>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-2 ml-1">
            {{ __('user_career.show_subtitle') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- Kolom Kiri: Ringkasan --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-600/20 dark:shadow-blue-900/20 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4 pointer-events-none">
                    <i data-lucide="trending-up" class="w-40 h-40"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-blue-100 text-[10px] font-extrabold uppercase tracking-widest mb-1.5 opacity-90">{{ __('user_career.current_position') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-black leading-tight mb-8">
                        {{ $currentLevelModel->jabatan->nama_jabatan ?? __('user_career.level') . ' ' . $currentLevelModel->level }}
                    </h2>

                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2 text-blue-50">
                            <span class="uppercase tracking-wider">{{ __('user_career.exp_points') }}</span>
                            <span>{{ number_format($totalExp) }} / {{ $nextLevelModel ? number_format($nextLevelModel->exp_required) : 'MAX' }}</span>
                        </div>
                        <div class="w-full bg-black/20 rounded-full h-2.5 mb-2 overflow-hidden shadow-inner border border-white/10">
                            <div class="bg-white dark:bg-blue-200 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md-panel p-6 sm:p-8">
                <h3 class="font-extrabold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('user_career.path_info') }}
                </h3>
                <ul class="space-y-4 text-sm font-medium">
                    <li class="flex justify-between items-center border-b border-gray-100 dark:border-slate-700/80 pb-3">
                        <span class="text-gray-500 dark:text-gray-400">{{ __('user_career.joined_date') }}</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200 bg-slate-50 dark:bg-slate-800 px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700">
                            {{ \Carbon\Carbon::parse($pivotData->pivot->start_date)->format('d M Y') }}
                        </span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-100 dark:border-slate-700/80 pb-3">
                        <span class="text-gray-500 dark:text-gray-400">{{ __('user_career.total_levels') }}</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200 bg-slate-50 dark:bg-slate-800 px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700">
                            {{ $levels->count() }} {{ __('user_career.level_count') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Kolom Kanan: Timeline Roadmap --}}
        <div class="lg:col-span-2">
            <div class="md-panel p-6 sm:p-8">
                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg mb-8 flex items-center gap-2">
                    <i data-lucide="map" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('user_career.position_roadmap') }}
                </h3>

                <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 sm:ml-4 space-y-8 pb-4">
                    @foreach($levels->reverse() as $lvl)
                    @php
                    $isCompleted = $pivotData->pivot->current_level > $lvl->level;
                    $isCurrent = $pivotData->pivot->current_level == $lvl->level;
                    $isLocked = $pivotData->pivot->current_level < $lvl->level;
                        @endphp

                        <div class="relative pl-8 sm:pl-10 group">
                            {{-- Titik Milestone --}}
                            @if($isCompleted)
                            <div class="absolute -left-[11px] top-1.5 w-5 h-5 rounded-full bg-emerald-500 ring-4 ring-white dark:ring-slate-800 flex items-center justify-center shadow-sm">
                                <i data-lucide="check" class="w-3 h-3 text-white"></i>
                            </div>
                            @elseif($isCurrent)
                            <div class="absolute -left-[11px] top-1.5 w-5 h-5 rounded-full bg-blue-600 dark:bg-blue-500 ring-4 ring-blue-50 dark:ring-blue-900/30 animate-pulse"></div>
                            <div class="absolute -left-[11px] top-1.5 w-5 h-5 rounded-full bg-blue-600 dark:bg-blue-500 ring-4 ring-white dark:ring-slate-800 shadow-sm flex items-center justify-center">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            @else
                            <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-slate-200 dark:bg-slate-700 ring-4 ring-white dark:ring-slate-800 transition-colors group-hover:bg-slate-300 dark:group-hover:bg-slate-600"></div>
                            @endif

                            {{-- Konten Milestone --}}
                            <div class="bg-white dark:bg-slate-800 rounded-2xl border {{ $isCurrent ? 'border-blue-500 dark:border-blue-500 ring-4 ring-blue-50 dark:ring-blue-900/20 shadow-md' : 'border-slate-200 dark:border-slate-700 group-hover:border-slate-300 dark:group-hover:border-slate-600' }} p-5 sm:p-6 transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-widest mb-2 shadow-sm 
                                            {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50' : 
                                            ($isCurrent ? 'bg-blue-600 text-white dark:bg-blue-500' : 'bg-slate-50 text-slate-500 border border-slate-200 dark:bg-slate-700/50 dark:text-slate-400 dark:border-slate-700') }}">
                                            {{ __('user_career.level') }} {{ $lvl->level }}
                                        </span>
                                        <h4 class="text-lg font-extrabold {{ $isLocked ? 'text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ $lvl->jabatan->nama_jabatan ?? __('user_career.unknown_position') }}
                                        </h4>
                                    </div>

                                    @if($isLocked)
                                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 dark:text-gray-400 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-lg w-fit shadow-sm">
                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500"></i> {{ __('user_career.need') }} {{ number_format($lvl->exp_required) }} EXP
                                    </div>
                                    @elseif($isCurrent)
                                    <div class="flex items-center gap-1.5 text-[11px] font-extrabold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 px-3 py-1.5 rounded-lg w-fit shadow-sm">
                                        <i data-lucide="crosshair" class="w-3.5 h-3.5"></i> {{ __('user_career.your_position') }}
                                    </div>
                                    @endif
                                </div>

                                @if($lvl->description)
                                <p class="text-sm font-medium leading-relaxed {{ $isLocked ? 'text-gray-400 dark:text-gray-500' : 'text-gray-600 dark:text-gray-300' }} mt-2">
                                    {{ $lvl->description }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                </div>
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