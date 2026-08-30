<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 bg-[#f8fafc] dark:bg-slate-900 text-slate-900 dark:text-slate-100 min-h-screen" style="font-family:'Inter', sans-serif;">
    <div class="max-w-7xl mx-auto space-y-5 sm:space-y-6">

        <style>
            /* ---------- Material Design Colors & Components ---------- */
            :root {
                --primary: #2563eb;
                --primary-hover: #1d4ed8;
                --primary-tint: #eff6ff;

                --surface: #ffffff;
                --background: #f8fafc;
                --border: #e2e8f0;

                --ink: #1e293b;
                --ink-soft: #64748b;

                --success: #10b981;
                --success-tint: #ecfdf5;
                --danger: #ef4444;
                --danger-tint: #fef2f2;
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
                --success-tint: rgba(16, 185, 129, 0.15);
                --danger-tint: rgba(239, 68, 68, 0.15);
                --warning-tint: rgba(245, 158, 11, 0.15);
            }

            .md-panel {
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: 16px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
                transition: box-shadow 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
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

            .md-btn-success {
                background: var(--success-tint);
                color: var(--success);
                border: 1px solid rgba(16, 185, 129, 0.2);
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .md-btn-success:hover {
                background: rgba(16, 185, 129, 0.2);
                box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
            }

            .md-btn-danger {
                background: var(--danger-tint);
                color: var(--danger);
                border: 1px solid rgba(239, 68, 68, 0.2);
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .md-btn-danger:hover {
                background: rgba(239, 68, 68, 0.2);
                box-shadow: 0 2px 4px rgba(239, 68, 68, 0.1);
            }

            .md-btn-neutral {
                background: var(--background);
                color: var(--ink-soft);
                border: 1px solid var(--border);
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .md-btn-neutral:hover {
                background: var(--border);
                color: var(--ink);
            }

            .md-tab {
                border-radius: 999px;
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }
        </style>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
        <div class="px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 shrink-0"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- ===================================================== --}}
        {{-- MODE: LIST (Tabs Pending / Diterima / Ditolak / Semua) --}}
        {{-- ===================================================== --}}
        @if($viewMode === 'list')

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 class="text-xl sm:text-3xl font-extrabold flex items-center gap-2.5 sm:gap-3 tracking-tight text-gray-900 dark:text-white">
                    <div class="p-1.5 sm:p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                        <i data-lucide="user-check" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </div>
                    {{ __('admin_approval.title') }}
                </h1>
                <p class="text-xs sm:text-sm mt-2 ml-0.5 sm:ml-1 text-gray-500 dark:text-gray-400">
                    {{ __('admin_approval.subtitle') }}
                </p>
            </div>
        </div>

        {{-- Tabs (Material Filter Chips) --}}
        {{-- Di HP: scroll horizontal (nowrap) supaya chip tidak turun berdesakan; label counter tetap ringkas --}}
        <div class="md-panel p-3 flex items-center gap-2 overflow-x-auto sm:flex-wrap sm:overflow-visible scrollbar-thin">
            <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mr-1 ml-2 shrink-0 hidden sm:inline">{{ __('admin_approval.filter_status') }}</span>
            @foreach([
            'pending' => ['label' => __('admin_approval.tab_pending'), 'icon' => 'clock', 'color' => 'amber'],
            'approved' => ['label' => __('admin_approval.tab_approved'), 'icon' => 'check-circle', 'color' => 'emerald'],
            'rejected' => ['label' => __('admin_approval.tab_rejected'), 'icon' => 'x-circle', 'color' => 'red'],
            'all' => ['label' => __('admin_approval.tab_all'), 'icon' => 'list', 'color' => 'slate'],
            ] as $key => $tab)
            <button
                type="button"
                wire:click="$set('statusFilter', '{{ $key }}')"
                @class([ 'md-tab flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-bold shrink-0' , "bg-{$tab['color']}-50 text-{$tab['color']}-700 border-{$tab['color']}-200 shadow-sm dark:bg-{$tab['color']}-900/30 dark:text-{$tab['color']}-400 dark:border-{$tab['color']}-800/50"=> $statusFilter === $key,
                'text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-slate-800 border-gray-100 dark:border-transparent' => $statusFilter !== $key,
                ])
                >
                <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                {{ $tab['label'] }}
                <span @class([ 'ml-1 sm:ml-1.5 px-1.5 sm:px-2 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide' , "bg-{$tab['color']}-200 text-{$tab['color']}-800 dark:bg-{$tab['color']}-900/50 dark:text-{$tab['color']}-300"=> $statusFilter === $key,
                    'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400' => $statusFilter !== $key,
                    ])>{{ $tabCounts[$key] }}</span>
            </button>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="md-panel p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div class="relative w-full max-w-md">
                <i data-lucide="search" class="w-4.5 h-4.5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="{{ __('admin_approval.search_placeholder') }}"
                    class="w-full pl-10 pr-10 py-2.5 md-input text-sm font-medium">
                @if(!empty($search))
                <button type="button" wire:click="$set('search', '')"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                    title="{{ __('admin_approval.clear_search') }}">
                    <i data-lucide="x" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                </button>
                @endif
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- DATA: Tabel penuh di layar md+, kartu di HP --}}
        {{-- ============================================= --}}
        <div class="md-panel overflow-hidden relative">

            <div
                wire:loading.flex
                wire:target="search,statusFilter,gotoPage,previousPage,nextPage,approveUser,rejectUser,revertToPending"
                class="absolute inset-0 items-center justify-center z-10 hidden rounded-2xl backdrop-blur-[2px] bg-white/60 dark:bg-slate-900/60">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-blue-600 dark:text-blue-400"></i>
            </div>

            {{-- ---------- TAMPILAN TABEL (md ke atas) ---------- --}}
            <div class="overflow-x-auto hidden md:block">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[25%]">{{ __('admin_approval.user') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[18%]">{{ __('admin_approval.department') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[18%]">{{ __('admin_approval.position') }}</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[12%]">{{ __('admin_approval.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[12%]">{{ __('admin_approval.updated_at') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">{{ __('admin_approval.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-transparent divide-y divide-gray-100 dark:divide-slate-700/80">
                        @forelse($pendingUsers as $user)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group">
                            {{-- User --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm overflow-hidden text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 shadow-sm border border-blue-200 dark:border-blue-800">
                                        @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" class="h-full w-full object-cover">
                                        @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Department --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $user->department?->nama_department ?? '-' }}</div>
                                @if($user->requested_department)
                                <div class="text-[11px] font-bold mt-1 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded inline-block">{{ __('admin_approval.req') }} {{ $user->requested_department }}</div>
                                @endif
                            </td>

                            {{-- Jabatan --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $user->jabatan?->nama_jabatan ?? '-' }}</div>
                                @if($user->requested_jabatan)
                                <div class="text-[11px] font-bold mt-1 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded inline-block">{{ __('admin_approval.req') }} {{ $user->requested_jabatan }}</div>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($user->status == 1)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                                    <i data-lucide="check" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('admin_approval.tab_approved') }}
                                </span>
                                @elseif($user->status == -1)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50 shadow-sm">
                                    <i data-lucide="x" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('admin_approval.tab_rejected') }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 shadow-sm">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('admin_approval.tab_pending') }}
                                </span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-500 dark:text-gray-400">
                                <div class="text-gray-700 dark:text-gray-300 font-semibold mb-0.5">{{ $user->updated_at->format('d M Y') }}</div>
                                <div>{{ $user->updated_at->format('H:i') }} WIB</div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($user->status == 0)
                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="openAssignMode({{ $user->id }})"
                                        class="px-3 py-1.5 md-btn-success text-xs font-bold flex items-center gap-1.5">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ __('admin_approval.btn_approve') }}
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="rejectUser({{ $user->id }})"
                                        wire:confirm="{{ __('admin_approval.confirm_reject', ['name' => $user->name]) }}"
                                        class="px-3 py-1.5 md-btn-danger text-xs font-bold flex items-center gap-1.5">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i> {{ __('admin_approval.btn_reject') }}
                                    </button>
                                </div>
                                @else
                                <button
                                    type="button"
                                    wire:click="revertToPending({{ $user->id }})"
                                    wire:confirm="{{ __('admin_approval.confirm_revert', ['name' => $user->name]) }}"
                                    class="px-3 py-1.5 md-btn-neutral text-xs font-bold flex items-center gap-1.5 ml-auto">
                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400"></i> {{ __('admin_approval.btn_cancel') }}
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="{{ !empty($search) ? 'search-x' : 'inbox' }}" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <p class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ __('admin_approval.no_data') }}</p>
                                    <p class="text-sm mt-1 text-gray-500 dark:text-gray-400 font-medium">
                                        {{ !empty($search) ? __('admin_approval.no_search_result') : __('admin_approval.no_users_category') }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ---------- TAMPILAN KARTU (khusus HP, di bawah md) ---------- --}}
            <div class="block md:hidden divide-y divide-gray-100 dark:divide-slate-700/80">
                @forelse($pendingUsers as $user)
                <div class="p-4 hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors">
                    {{-- Baris atas: avatar + nama + status --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm overflow-hidden text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 shadow-sm border border-blue-200 dark:border-blue-800 shrink-0">
                                @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" class="h-full w-full object-cover">
                                @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</div>
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                            </div>
                        </div>

                        @if($user->status == 1)
                        <span class="inline-flex items-center shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                            <i data-lucide="check" class="w-3 h-3 mr-1"></i> {{ __('admin_approval.tab_approved') }}
                        </span>
                        @elseif($user->status == -1)
                        <span class="inline-flex items-center shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                            <i data-lucide="x" class="w-3 h-3 mr-1"></i> {{ __('admin_approval.tab_rejected') }}
                        </span>
                        @else
                        <span class="inline-flex items-center shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ __('admin_approval.tab_pending') }}
                        </span>
                        @endif
                    </div>

                    {{-- Detail: department, jabatan, waktu --}}
                    <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                        <div>
                            <div class="text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider text-[10px] mb-0.5">{{ __('admin_approval.department') }}</div>
                            <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $user->department?->nama_department ?? '-' }}</div>
                            @if($user->requested_department)
                            <div class="text-[10px] font-bold mt-1 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-1.5 py-0.5 rounded inline-block">{{ __('admin_approval.req') }} {{ $user->requested_department }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider text-[10px] mb-0.5">{{ __('admin_approval.position') }}</div>
                            <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $user->jabatan?->nama_jabatan ?? '-' }}</div>
                            @if($user->requested_jabatan)
                            <div class="text-[10px] font-bold mt-1 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-1.5 py-0.5 rounded inline-block">{{ __('admin_approval.req') }} {{ $user->requested_jabatan }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-3">
                        {{ __('admin_approval.updated_at') }}: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $user->updated_at->format('d M Y, H:i') }} WIB</span>
                    </div>

                    {{-- Aksi --}}
                    @if($user->status == 0)
                    <div class="flex gap-2">
                        <button
                            type="button"
                            wire:click="openAssignMode({{ $user->id }})"
                            class="flex-1 px-3 py-2 md-btn-success text-xs font-bold flex items-center justify-center gap-1.5">
                            <i data-lucide="check" class="w-3.5 h-3.5"></i> {{ __('admin_approval.btn_approve') }}
                        </button>
                        <button
                            type="button"
                            wire:click="rejectUser({{ $user->id }})"
                            wire:confirm="{{ __('admin_approval.confirm_reject', ['name' => $user->name]) }}"
                            class="flex-1 px-3 py-2 md-btn-danger text-xs font-bold flex items-center justify-center gap-1.5">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i> {{ __('admin_approval.btn_reject') }}
                        </button>
                    </div>
                    @else
                    <button
                        type="button"
                        wire:click="revertToPending({{ $user->id }})"
                        wire:confirm="{{ __('admin_approval.confirm_revert', ['name' => $user->name]) }}"
                        class="w-full px-3 py-2 md-btn-neutral text-xs font-bold flex items-center justify-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400"></i> {{ __('admin_approval.btn_cancel') }}
                    </button>
                    @endif
                </div>
                @empty
                <div class="px-4 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                            <i data-lucide="{{ !empty($search) ? 'search-x' : 'inbox' }}" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <p class="text-base font-bold text-gray-700 dark:text-gray-300">{{ __('admin_approval.no_data') }}</p>
                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400 font-medium">
                            {{ !empty($search) ? __('admin_approval.no_search_result') : __('admin_approval.no_users_category') }}
                        </p>
                    </div>
                </div>
                @endforelse
            </div>

            @if($pendingUsers->hasPages())
            <div class="px-3 sm:px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800">
                {{ $pendingUsers->links() }}
            </div>
            @endif
        </div>

        {{-- ===================================================== --}}
        {{-- MODE: ASSIGN (Form approve user) --}}
        {{-- ===================================================== --}}
        @elseif($viewMode === 'assign' && $activeUser)

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
            <h1 class="text-lg sm:text-2xl font-extrabold flex items-center gap-2.5 sm:gap-3 tracking-tight text-gray-900 dark:text-white">
                <div class="p-1.5 sm:p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                    <i data-lucide="user-plus" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                {{ __('admin_approval.assign_title') }}
            </h1>
            <button
                type="button"
                wire:click="cancelAssign"
                class="text-sm font-bold flex items-center justify-center gap-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm w-full sm:w-auto">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> {{ __('admin_approval.back') }}
            </button>
        </div>

        <div class="md-panel p-4 sm:p-6 md:p-8 max-w-2xl">

            {{-- User Info Card --}}
            <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700 rounded-xl">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-full flex items-center justify-center font-bold text-lg sm:text-xl text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 shadow-inner border border-blue-200 dark:border-blue-800 shrink-0">
                        {{ strtoupper(substr($activeUser->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-sm font-bold text-gray-500 dark:text-gray-400 mb-0.5">{{ __('admin_approval.candidate_info') }}</div>
                        <div class="font-extrabold text-base sm:text-lg text-gray-900 dark:text-white truncate">{{ $activeUser->name }}</div>
                        <div class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">{{ $activeUser->email }}</div>
                    </div>
                </div>

                @if($activeUser->requested_department || $activeUser->requested_jabatan)
                <div class="mt-4 rounded-lg p-3 sm:p-3.5 text-xs sm:text-sm flex items-start gap-2.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 text-blue-800 dark:text-blue-300">
                    <i data-lucide="info" class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold">{{ __('admin_approval.user_request') }}</span> {{ __('admin_approval.applying_for') }}
                        <span class="font-bold">{{ $activeUser->requested_jabatan ?? '-' }}</span>
                        {{ __('admin_approval.in') }} <span class="font-bold">{{ $activeUser->requested_department ?? '-' }}</span>.
                    </div>
                </div>
                @endif
            </div>

            <form wire:submit.prevent="approveUser" class="space-y-5">

                {{-- Department --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('admin_approval.lbl_department') }}</label>
                    <select wire:model.live="department_id" class="w-full md-input text-sm px-4 py-2.5 font-medium">
                        <option value="">{{ __('admin_approval.select_department') }}</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="text-xs font-bold mt-1 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                {{-- Jabatan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('admin_approval.lbl_position') }}</label>
                    <select wire:model.live="jabatan_id" class="w-full md-input text-sm px-4 py-2.5 font-medium disabled:bg-gray-100 dark:disabled:bg-slate-800 disabled:text-gray-400 dark:disabled:text-slate-500 disabled:cursor-not-allowed" @disabled(!$department_id)>
                        <option value="">{{ __('admin_approval.select_position') }}</option>
                        @foreach($availableJabatans as $jab)
                        <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id') <span class="text-xs font-bold mt-1 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                {{-- Career Path --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('admin_approval.lbl_career_path') }}</label>
                    <select wire:model="career_path_id" class="w-full md-input text-sm px-4 py-2.5 font-medium disabled:bg-gray-100 dark:disabled:bg-slate-800 disabled:text-gray-400 dark:disabled:text-slate-500 disabled:cursor-not-allowed" @disabled(!$jabatan_id)>
                        <option value="">{{ __('admin_approval.select_career_path') }}</option>
                        @foreach($availableCareerPaths as $cp)
                        <option value="{{ $cp->id }}">{{ $cp->name }}</option>
                        @endforeach
                    </select>
                    @error('career_path_id') <span class="text-xs font-bold mt-1 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('admin_approval.lbl_role') }}</label>
                    <select wire:model="role" class="w-full md-input text-sm px-4 py-2.5 font-medium">
                        <option value="user">{{ __('admin_approval.role_user') }}</option>
                        @if(auth()->user()->role === 'super_admin')
                        <option value="admin">{{ __('admin_approval.role_admin') }}</option>
                        <option value="super_admin">{{ __('admin_approval.role_super_admin') }}</option>
                        @endif
                    </select>
                    @error('role') <span class="text-xs font-bold mt-1 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                    <button type="submit" class="px-6 py-3 md-btn-primary text-sm font-bold flex items-center justify-center gap-2 flex-1 sm:flex-none order-1">
                        <i data-lucide="user-check" class="w-4.5 h-4.5"></i> {{ __('admin_approval.btn_save_approve') }}
                    </button>
                    <button type="button" wire:click="cancelAssign" class="px-6 py-3 md-btn-neutral text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex-1 sm:flex-none text-center order-2">
                        {{ __('admin_approval.btn_cancel_form') }}
                    </button>
                </div>
            </form>
        </div>
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