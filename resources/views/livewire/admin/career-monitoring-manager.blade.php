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

        .md-btn-success-solid {
            background: var(--success);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-btn-success-solid:hover {
            background: #059669;
            /* Emerald 600 */
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            transform: translateY(-1px);
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

        .md-btn-outline {
            background: var(--surface);
            color: var(--primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .md-btn-outline:hover {
            background: var(--primary-tint);
            border-color: #bfdbfe;
        }

        :is(.dark .md-btn-outline:hover) {
            border-color: #3b82f6;
            /* blue-500 */
            color: #60a5fa;
            /* blue-400 */
        }
    </style>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODE: DAFTAR MONITORING --}}
    {{-- ========================================== --}}
    @if($viewMode === 'list')
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                </div>
                {{ __('admin_monitoring.title') }}
            </h1>
            <p class="text-sm mt-2 ml-1 text-gray-500 dark:text-gray-400">{!! __('admin_monitoring.subtitle') !!}</p>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto mt-2 lg:mt-0">
            <button wire:click="exportExcel" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-success-solid font-bold text-sm flex-1 sm:flex-none">
                <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5"></i> {{ __('admin_monitoring.export_csv') }}
            </button>
            <button wire:click="exportPdf" type="button" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-danger-solid font-bold text-sm flex-1 sm:flex-none" wire:loading.attr="disabled">
                <i data-lucide="file-text" class="w-4.5 h-4.5" wire:loading.remove wire:target="exportPdf"></i>
                <span wire:loading.remove wire:target="exportPdf">{{ __('admin_monitoring.export_pdf') }}</span>
                <span wire:loading wire:target="exportPdf" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('admin_monitoring.processing') }}
                </span>
            </button>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="md-panel p-5 mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="relative">
            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('admin_monitoring.search_label') }}</label>
            <i data-lucide="search" class="absolute left-3.5 top-[38px] transform -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin_monitoring.search_placeholder') }}"
                class="w-full pl-10 pr-4 py-2.5 md-input text-sm font-medium">
        </div>
        <div>
            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('admin_monitoring.filter_department') }}</label>
            <select wire:model.live="departmentId" class="w-full px-4 py-2.5 md-input text-sm font-medium">
                <option value="">{{ __('admin_monitoring.all_departments') }}</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5 ml-1">{{ __('admin_monitoring.filter_position') }}</label>
            <select wire:model.live="jabatanId" class="w-full px-4 py-2.5 md-input text-sm font-medium disabled:bg-gray-100 dark:disabled:bg-slate-800 disabled:text-gray-400 dark:disabled:text-slate-500" @if(empty($availableJabatans)) disabled @endif>
                <option value="">{{ __('admin_monitoring.all_positions') }}</option>
                @foreach($availableJabatans as $jab)
                <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="md-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-extrabold cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors group" wire:click="sort('name')">
                            {{ __('admin_monitoring.table_employee') }} <i data-lucide="{{ $sortBy === 'name' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevrons-up-down' }}" class="w-3.5 h-3.5 inline ml-1 opacity-50 group-hover:opacity-100 {{ $sortBy === 'name' ? 'text-blue-600 dark:text-blue-400 opacity-100' : '' }}"></i>
                        </th>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_monitoring.table_position') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors group" wire:click="sort('current_level')">
                            {{ __('admin_monitoring.table_main_level') }} <i data-lucide="{{ $sortBy === 'current_level' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevrons-up-down' }}" class="w-3.5 h-3.5 inline ml-1 opacity-50 group-hover:opacity-100 {{ $sortBy === 'current_level' ? 'text-blue-600 dark:text-blue-400 opacity-100' : '' }}"></i>
                        </th>
                        <th class="px-6 py-4 font-extrabold text-center cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors group" wire:click="sort('total_exp')">
                            {{ __('admin_monitoring.table_total_exp') }} <i data-lucide="{{ $sortBy === 'total_exp' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevrons-up-down' }}" class="w-3.5 h-3.5 inline ml-1 opacity-50 group-hover:opacity-100 {{ $sortBy === 'total_exp' ? 'text-blue-600 dark:text-blue-400 opacity-100' : '' }}"></i>
                        </th>
                        <th class="px-6 py-4 font-extrabold text-right">{{ __('admin_monitoring.table_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
                    @forelse($usersWithProgress as $row)
                    <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $row['name'] }}</div>
                            <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5"><i data-lucide="badge-check" class="w-3 h-3 inline align-baseline mr-0.5"></i> {{ $row['id_badge'] ?? 'NO-BADGE' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $row['jabatan_name'] }}</div>
                            <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">{{ $row['department_name'] }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 font-black text-sm shadow-sm">
                                {{ $row['current_level'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50 font-extrabold rounded-lg text-xs shadow-sm">
                                {{ number_format($row['total_exp']) }}
                            </span>
                            @if($row['secondary_paths_count'] > 0)
                            <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1.5">+{{ $row['secondary_paths_count'] }} {{ __('admin_monitoring.other_paths') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="showDetail({{ $row['id'] }})" class="px-4 py-2.5 md-btn-outline font-bold text-xs shadow-sm flex items-center gap-1.5 ml-auto">
                                {{ __('admin_monitoring.detail_progress') }} <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="search-x" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700 dark:text-gray-300">{{ __('admin_monitoring.no_data_title') }}</p>
                                <p class="text-sm mt-1 text-gray-500 dark:text-gray-400 font-medium">{{ __('admin_monitoring.no_data_desc') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($usersWithProgress->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/80">
            {{ $usersWithProgress->links() }}
        </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- MODE: DETAIL USER --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'detail')
    <div class="mb-8 flex flex-col items-start gap-4">
        <button wire:click="backToList" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_monitoring.back_to_list') }}
        </button>

        <div class="md-panel p-6 sm:p-8 flex flex-col md:flex-row items-center gap-6 w-full">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 flex items-center justify-center text-white font-extrabold text-3xl shadow-inner shrink-0 border-2 border-white dark:border-slate-800 ring-4 ring-blue-50 dark:ring-blue-900/30">
                {{ strtoupper(substr($activeUser->name, 0, 1)) }}
            </div>
            <div class="text-center md:text-left flex-1">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $activeUser->name }}</h2>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600/50 rounded-md text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i data-lucide="badge-check" class="w-3 h-3 inline mr-1"></i> {{ $activeUser->id_badge ?? 'NO-BADGE' }}</span>
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600/50 rounded-md text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i data-lucide="briefcase" class="w-3 h-3 inline mr-1"></i> {{ $activeUser->jabatan->nama_jabatan ?? 'N/A' }}</span>
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600/50 rounded-md text-[10px] font-extrabold uppercase tracking-wider shadow-sm"><i data-lucide="building" class="w-3 h-3 inline mr-1"></i> {{ $activeUser->department->nama_department ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-extrabold text-gray-900 dark:text-white mb-5 px-2 flex items-center gap-2">
        <i data-lucide="activity" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_monitoring.career_path_progress') }}
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($careerPathsData as $path)
        <div class="md-panel p-6 relative overflow-hidden group {{ $activeUser->career_path_id === $path->id ? 'ring-2 ring-blue-500 dark:ring-blue-400 border-transparent' : '' }}">

            {{-- Accent line on hover --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></div>

            @if($activeUser->career_path_id === $path->id)
            <div class="absolute top-0 right-0 bg-blue-600 dark:bg-blue-500 text-white text-[9px] font-extrabold px-3 py-1.5 rounded-bl-xl uppercase tracking-widest shadow-sm z-10">{{ __('admin_monitoring.primary_path') }}</div>
            @endif

            <h4 class="text-lg font-extrabold text-gray-900 dark:text-white mb-1.5 pr-16 leading-tight group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $path->name }}</h4>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6 flex items-center gap-1.5"><i data-lucide="award" class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400"></i> {{ $path->monitoring_current_title }}</p>

            <div class="flex justify-between items-end mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 font-black flex items-center justify-center text-sm shadow-sm">
                        {{ $path->monitoring_current_level }}
                    </div>
                    <span class="text-[11px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{!! __('admin_monitoring.current_level') !!}</span>
                </div>
                <div class="text-right">
                    <span class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ number_format($path->monitoring_total_exp) }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase">/ {{ $path->monitoring_next_exp > 0 ? number_format($path->monitoring_next_exp) : 'MAX' }} EXP</span>
                </div>
            </div>

            <div class="w-full h-4 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner">
                <div class="h-full bg-emerald-500 dark:bg-emerald-400 transition-all duration-1000 ease-out flex items-center justify-end pr-2" style="width: {{ $path->monitoring_percentage }}%">
                    @if($path->monitoring_percentage > 10)
                    <span class="text-[9px] text-white dark:text-slate-900 font-extrabold opacity-90">{{ $path->monitoring_percentage }}%</span>
                    @endif
                </div>
            </div>

            @if($path->monitoring_status === 'Max Level')
            <div class="mt-5 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800/50 text-center shadow-sm">
                <span class="text-[11px] font-extrabold text-amber-700 dark:text-amber-400 uppercase tracking-widest"><i data-lucide="medal" class="w-4 h-4 inline align-text-bottom mr-1"></i> {{ __('admin_monitoring.max_level_reached') }}</span>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full py-16 text-center md-panel flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                <i data-lucide="map-x" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
            </div>
            <p class="text-base font-bold text-gray-700 dark:text-gray-300">{{ __('admin_monitoring.no_career_path') }}</p>
        </div>
        @endforelse
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