<div wire:poll.15s>
    {{-- ============================================= --}}
    {{-- MATERIAL DESIGN: Elevation (Shadows), Rounded, Blue Palette --}}
    {{-- ============================================= --}}
    <style>
        .gp-console {
            /* Material Blue Palette */
            --bg: #F0F4F8;
            /* Light blue-gray background */
            --surface: #ffffff;
            --ink: #1e293b;
            /* Slate 800 */
            --ink-soft: #64748b;
            /* Slate 500 */

            --primary: #2563eb;
            /* Blue 600 */
            --primary-dark: #1d4ed8;
            /* Blue 700 */
            --primary-tint: #eff6ff;
            /* Blue 50 */

            --success: #10b981;
            /* Emerald 500 */
            --success-tint: #ecfdf5;
            /* Emerald 50 */

            --danger: #ef4444;
            /* Red 500 */
            --danger-tint: #fef2f2;
            /* Red 50 */

            --warning: #f59e0b;
            /* Amber 500 */
            --warning-tint: #fffbeb;
            /* Amber 50 */

            --border: #e2e8f0;
            /* Slate 200 */

            font-family: 'Inter', sans-serif;
            color: var(--ink);
        }

        /* ---------- DARK MODE: Override variable-variable palet ---------- */
        html.dark .gp-console {
            --bg: #0f172a;
            /* Slate 900 */
            --surface: #1e293b;
            /* Slate 800 */
            --ink: #f1f5f9;
            /* Slate 100 */
            --ink-soft: #94a3b8;
            /* Slate 400 */

            --primary: #3b82f6;
            /* Blue 500, sedikit lebih terang biar kontras di gelap */
            --primary-dark: #60a5fa;
            /* Blue 400 */
            --primary-tint: rgba(59, 130, 246, 0.15);

            --success: #34d399;
            /* Emerald 400 */
            --success-tint: rgba(52, 211, 153, 0.15);

            --danger: #f87171;
            /* Red 400 */
            --danger-tint: rgba(248, 113, 113, 0.15);

            --warning: #fbbf24;
            /* Amber 400 */
            --warning-tint: rgba(251, 191, 36, 0.15);

            --border: #334155;
            /* Slate 700 */
        }

        @keyframes gp-fade-up {
            0% {
                opacity: 0;
                transform: translateY(15px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gp-fade-in {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes gp-pop {
            0% {
                opacity: 0;
                transform: scale(0.92);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes gp-ping {

            75%,
            100% {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        @keyframes gp-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .gp-card-enter {
            animation: gp-fade-up 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .gp-row-enter {
            animation: gp-fade-in 0.35s ease both;
        }

        .gp-pop-enter {
            animation: gp-pop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        /* ---------- Material Surface Panel ---------- */
        .gp-panel {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            /* Sangat tipis */
            border-radius: 16px;
            /* Sudut lebih melengkung ala M3 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
        }

        html.dark .gp-panel {
            border: 1px solid rgba(51, 65, 85, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.2);
        }

        .gp-panel-hover {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .gp-panel-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            border-color: transparent;
        }

        html.dark .gp-panel-hover:hover {
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
        }

        /* ---------- Icon Badge ---------- */
        .gp-badge {
            background: var(--primary-tint);
            color: var(--primary-dark);
            border-radius: 12px;
        }

        /* ---------- Readout (Numbers) ---------- */
        .gp-readout {
            color: var(--ink);
            letter-spacing: -0.02em;
        }

        /* ---------- Status Dot ---------- */
        .gp-dot {
            background: var(--success);
            border-radius: 999px;
        }

        /* ---------- Progress Bar ---------- */
        .gp-bar-track {
            background: var(--border);
            border-radius: 999px;
            overflow: hidden;
        }

        .gp-bar-fill {
            background: var(--primary);
            border-radius: 999px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ---------- Status Pill ---------- */
        .gp-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ---------- Search Input (dipakai untuk filter user online) ---------- */
        .gp-search-input {
            width: 100%;
            padding: 8px 12px 8px 34px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--ink);
            transition: all 0.2s ease;
        }

        .gp-search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-tint);
        }

        /* ---------- Refresh Button ---------- */
        .gp-refresh-btn {
            transition: transform 0.2s ease;
        }

        .gp-refresh-btn:active i {
            animation: gp-spin 0.6s linear;
        }

        .gp-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .gp-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }

        html.dark .gp-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .gp-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        @media (prefers-reduced-motion: reduce) {

            .gp-card-enter,
            .gp-row-enter,
            .gp-pop-enter,
            .gp-panel-hover {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>

    <div class="gp-console min-h-screen px-4 sm:px-6 py-8" style="background: var(--bg);">

        {{-- HEADER --}}
        <div class="mb-6 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-4 gp-card-enter">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight flex items-center justify-center sm:justify-start gap-3">
                    <div class="p-2 gp-badge rounded-xl shadow-sm">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    </div>
                    {{ __('dashboard.title') }}
                </h1>
                <p class="text-sm mt-2 sm:ml-12" style="color: var(--ink-soft);">{{ __('dashboard.subtitle') }}</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Info terakhir diperbarui + tombol refresh manual --}}
                <div class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-full shadow-sm">
                    <span class="text-xs font-semibold" style="color: var(--ink-soft);">
                        {{ __('dashboard.last_updated') ?? 'Diperbarui' }}: {{ now()->format('H:i:s') }}
                    </span>
                    <button type="button" wire:click="$refresh" wire:loading.class="opacity-50"
                        class="gp-refresh-btn p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                        title="{{ __('dashboard.refresh_now') ?? 'Segarkan sekarang' }}">
                        <i data-lucide="refresh-cw" class="w-4 h-4" style="color: var(--primary);"></i>
                    </button>
                </div>

                {{-- Indikator Live --}}
                <div class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-full shadow-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full rounded-full gp-dot opacity-50" style="animation: gp-ping 2s cubic-bezier(0,0,0.2,1) infinite;"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 gp-dot"></span>
                    </span>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ __('dashboard.system_active') }}</span>
                </div>
            </div>
        </div>

        {{-- ALERT: Departemen dengan tingkat kelulusan rendah --}}
        @if(isset($deptAlerts) && $deptAlerts->isNotEmpty())
        <div class="mb-6 gp-panel gp-card-enter p-4 flex items-start gap-3" style="background: var(--warning-tint); border-color: var(--warning);">
            <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--warning);"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold" style="color: var(--ink);">
                    {{ __('dashboard.dept_alert_title') ?? 'Departemen perlu perhatian' }}
                </p>
                <p class="text-xs mt-1 font-medium" style="color: var(--ink-soft);">
                    {{ __('dashboard.dept_alert_desc') ?? 'Tingkat kelulusan di bawah ' . 60 . '% pada:' }}
                </p>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($deptAlerts as $dept)
                    <span class="gp-pill" style="background: var(--danger-tint); color: var(--danger);">
                        {{ $dept->label }} — {{ $dept->pass_rate }}%
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- KARTU STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
            @foreach ($cards as $i => $card)
            <div wire:key="stat-card-{{ $i }}"
                class="gp-panel gp-panel-hover p-5 gp-card-enter flex items-center"
                style="animation-delay: {{ $i * 50 }}ms;">
                <div class="p-3 rounded-xl gp-badge">
                    <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold uppercase tracking-wider" style="color: var(--ink-soft);">{{ $card['title'] }}</p>
                    <p class="text-2xl font-extrabold gp-readout mt-0.5">{{ number_format($card['count']) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- SEDANG AKTIF (+ pencarian) --}}
            <div class="col-span-1 gp-panel p-6 flex flex-col h-[440px] gp-card-enter" style="animation-delay: 100ms;">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold flex items-center gap-2">
                        <i data-lucide="radio" class="w-5 h-5" style="color: var(--primary);"></i>
                        {{ __('dashboard.active_now') }}
                    </h2>
                    <span class="gp-pill shadow-sm" style="background: var(--success-tint); color: var(--success);">
                        {{ $onlineUsers->count() }} {{ __('dashboard.online') }}
                    </span>
                </div>

                {{-- Pencarian user online, real-time via Livewire --}}
                <div class="relative mb-4">
                    <i data-lucide="search" class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2" style="color: var(--ink-soft);"></i>
                    <input type="text" wire:model.live.debounce.300ms="searchOnline"
                        placeholder="{{ __('dashboard.search_online') ?? 'Cari user online...' }}"
                        class="gp-search-input">
                </div>

                <div class="flex-1 overflow-y-auto space-y-3 pr-2 gp-scrollbar">
                    @forelse($onlineUsers as $i => $user)
                    <div wire:key="online-user-{{ $user->id }}"
                        class="flex items-center p-3 rounded-xl gp-row-enter bg-gray-50 dark:bg-slate-700/50 border border-gray-100 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                        style="animation-delay: {{ $i * 50 }}ms;">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white shadow-sm" style="background: var(--primary);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-100 truncate">{{ $user->name }}</p>
                            <p class="text-xs truncate font-medium mt-0.5" style="color: var(--ink-soft);">{{ $user->jabatan->nama_jabatan ?? __('dashboard.employee') }}</p>
                        </div>
                        <div class="ml-2 flex-shrink-0">
                            <div class="w-2.5 h-2.5 gp-dot shadow-sm"></div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-8 gp-pop-enter">
                        <i data-lucide="{{ trim($searchOnline) !== '' ? 'search-x' : 'coffee' }}" class="w-10 h-10 mb-3" style="color: var(--border);"></i>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400">
                            {{ trim($searchOnline) !== '' ? (__('dashboard.no_search_result') ?? 'Tidak ditemukan') : __('dashboard.no_activity') }}
                        </p>
                        <p class="text-xs font-medium mt-1" style="color: var(--ink-soft);">
                            {{ trim($searchOnline) !== '' ? '' : __('dashboard.all_offline') }}
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- TOP 5 TASK --}}
            <div class="col-span-1 lg:col-span-2 gp-panel p-6 h-[440px] flex flex-col gp-card-enter" style="animation-delay: 140ms;">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-bold flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-5 h-5" style="color: var(--primary);"></i>
                        {{ __('dashboard.top5_task') }}
                    </h2>
                </div>

                <div class="space-y-6 flex-1 overflow-y-auto pr-2 gp-scrollbar">
                    @forelse($avgScores as $i => $score)
                    <div class="gp-row-enter" style="animation-delay: {{ $i * 70 }}ms;">
                        <div class="flex justify-between text-sm mb-2.5">
                            <span class="font-bold text-gray-700 dark:text-gray-300 truncate pr-4" title="{{ $score->judul }}">
                                {{ \Illuminate\Support\Str::limit($score->judul, 60) }}
                            </span>
                            <span class="font-extrabold" style="color: var(--primary);">{{ number_format($score->avg_score, 1) }}%</span>
                        </div>
                        <div class="w-full h-2.5 gp-bar-track">
                            <div class="gp-bar-fill h-full" style="width: {{ min(100, $score->avg_score) }}%;"></div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-center gp-pop-enter">
                        <i data-lucide="inbox" class="w-12 h-12 mb-3" style="color: var(--border);"></i>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('dashboard.no_score_data') }}</p>
                    </div>
                    @endforelse
                </div>

                {{-- TOP PERFORMER: leaderboard singkat di bawah Top 5 Task --}}
                @if(isset($topPerformers) && $topPerformers->isNotEmpty())
                <div class="mt-6 pt-5 border-t border-gray-100 dark:border-slate-700">
                    <h3 class="text-xs font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: var(--ink-soft);">
                        <i data-lucide="trophy" class="w-4 h-4" style="color: var(--warning);"></i>
                        {{ __('dashboard.top_performer') ?? 'Top Performer' }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($topPerformers as $i => $performer)
                        <span class="gp-pill shadow-sm" style="background: var(--primary-tint); color: var(--primary-dark); text-transform: none; font-size: 12px;">
                            <span class="font-black">#{{ $i + 1 }}</span> {{ $performer->name }} · {{ number_format($performer->avg_score, 1) }}%
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- GRAFIK: Submit Harian & User per Department    --}}
        {{-- ============================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

            {{-- GRAFIK SUBMIT 7 HARI TERAKHIR (+ growth badge) --}}
            <div class="col-span-1 lg:col-span-2 gp-panel p-6 h-[340px] flex flex-col gp-card-enter" style="animation-delay: 160ms;">
                <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
                    <h2 class="text-base font-bold flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-5 h-5" style="color: var(--primary);"></i>
                        {{ __('dashboard.submit_7days') }}
                    </h2>
                    <div class="flex items-center gap-2">
                        @if(isset($growthDirection))
                        <span class="gp-pill shadow-sm" style="
                            background: {{ $growthDirection === 'up' ? 'var(--success-tint)' : ($growthDirection === 'down' ? 'var(--danger-tint)' : 'var(--border)') }};
                            color: {{ $growthDirection === 'up' ? 'var(--success)' : ($growthDirection === 'down' ? 'var(--danger)' : 'var(--ink-soft)') }};
                        ">
                            <i data-lucide="{{ $growthDirection === 'up' ? 'arrow-up-right' : ($growthDirection === 'down' ? 'arrow-down-right' : 'minus') }}" class="w-3 h-3"></i>
                            {{ abs($growthPercent) }}% {{ __('dashboard.vs_yesterday') ?? 'vs kemarin' }}
                        </span>
                        @endif
                        <span class="text-xs font-bold px-3 py-1 bg-gray-100 dark:bg-slate-700 rounded-full" style="color: var(--ink-soft);">
                            {{ __('dashboard.total') }}: {{ number_format($submissionsPerDay->sum('count')) }}
                        </span>
                    </div>
                </div>

                <div class="flex-1 relative"
                    wire:key="chart-daily-{{ md5(json_encode($submissionsPerDay)) }}"
                    x-data="gpLineChart(@js($submissionsPerDay->pluck('label')), @js($submissionsPerDay->pluck('count')))"
                    x-init="render()">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            {{-- GRAFIK USER PER DEPARTMENT --}}
            <div class="col-span-1 gp-panel p-6 h-[340px] flex flex-col gp-card-enter" style="animation-delay: 200ms;">
                <h2 class="text-base font-bold flex items-center gap-2 mb-5">
                    <i data-lucide="building-2" class="w-5 h-5" style="color: var(--primary);"></i>
                    {{ __('dashboard.user_per_dept') }}
                </h2>

                <div class="flex-1 relative"
                    wire:key="chart-dept-{{ md5(json_encode($usersPerDept)) }}"
                    x-data="gpBarChart(@js($usersPerDept->pluck('label')), @js($usersPerDept->pluck('count')))"
                    x-init="render()">
                    @if($usersPerDept->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-center gp-pop-enter">
                        <i data-lucide="building-2" class="w-10 h-10 mb-3" style="color: var(--border);"></i>
                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('dashboard.no_dept_data') }}</p>
                    </div>
                    @else
                    <canvas x-ref="canvas"></canvas>
                    @endif
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

            {{-- TINGKAT KELULUSAN GLOBAL --}}
            <div class="gp-panel p-6 flex flex-col items-center justify-center min-h-[350px] gp-card-enter" style="animation-delay: 180ms;">
                <h2 class="text-base font-bold mb-8 w-full text-left flex items-center gap-2">
                    <i data-lucide="target" class="w-5 h-5" style="color: var(--primary);"></i>
                    {{ __('dashboard.graduation_rate') }}
                </h2>

                <div class="relative w-44 h-44 flex items-center justify-center gp-pop-enter" style="animation-delay: 260ms;">
                    <svg class="w-full h-full transform -rotate-90 filter drop-shadow-sm">
                        <circle cx="88" cy="88" r="76" stroke="currentColor" stroke-width="14" fill="transparent" style="color: var(--border);" />
                        <circle cx="88" cy="88" r="76" stroke="currentColor" stroke-width="14" fill="transparent"
                            stroke-dasharray="477.5"
                            stroke-dashoffset="{{ 477.5 - (477.5 * $accuracy) / 100 }}"
                            style="color: var(--primary); transition: stroke-dashoffset 1.2s cubic-bezier(0.4, 0, 0.2, 1);"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-black gp-readout">{{ $accuracy }}<span class="text-xl">%</span></span>
                        <span class="text-xs font-bold uppercase tracking-wider mt-1" style="color: var(--ink-soft);">{{ __('dashboard.passed') }}</span>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between w-full max-w-sm px-4">
                    <div class="flex flex-col items-center gp-row-enter" style="animation-delay: 300ms;">
                        <span class="text-[10px] uppercase tracking-widest font-bold mb-2 text-gray-500 dark:text-gray-400">{{ __('dashboard.passed') }}</span>
                        <span class="gp-pill shadow-sm" style="background: var(--success-tint); color: var(--success); font-size: 13px;">{{ number_format($passedAttempts) }}</span>
                    </div>
                    <div class="flex flex-col items-center gp-row-enter" style="animation-delay: 350ms;">
                        <span class="text-[10px] uppercase tracking-widest font-bold mb-2 text-gray-500 dark:text-gray-400">{{ __('dashboard.failed') }}</span>
                        <span class="gp-pill shadow-sm" style="background: var(--danger-tint); color: var(--danger); font-size: 13px;">{{ number_format($failedAttempts) }}</span>
                    </div>
                    <div class="flex flex-col items-center gp-row-enter" style="animation-delay: 400ms;">
                        <span class="text-[10px] uppercase tracking-widest font-bold mb-2 text-gray-500 dark:text-gray-400">{{ __('dashboard.total') }}</span>
                        <span class="gp-pill shadow-sm" style="background: var(--primary-tint); color: var(--primary-dark); font-size: 13px;">{{ number_format($totalAttempts) }}</span>
                    </div>
                </div>
            </div>

            {{-- SUBMIT TASK TERBARU --}}
            <div class="gp-panel p-0 flex flex-col overflow-hidden min-h-[350px] gp-card-enter" style="animation-delay: 220ms;">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <h2 class="text-base font-bold flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5" style="color: var(--primary);"></i>
                        {{ __('dashboard.recent_submissions') }}
                    </h2>
                </div>

                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="text-xs uppercase tracking-wider bg-gray-50 dark:bg-slate-900/40 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-4 font-bold">{{ __('dashboard.employee_col') }}</th>
                                <th class="px-6 py-4 font-bold">{{ __('dashboard.task_col') }}</th>
                                <th class="px-6 py-4 font-bold text-center">{{ __('dashboard.status_col') }}</th>
                                <th class="px-6 py-4 font-bold text-right">{{ __('dashboard.time_col') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @forelse($recentActivities as $i => $act)
                            <tr wire:key="activity-{{ $act->id }}" class="gp-row-enter hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors" style="animation-delay: {{ $i * 45 }}ms;">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-sm" style="background: var(--primary-dark);">
                                            {{ strtoupper(substr($act->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $act->user->name ?? __('dashboard.unknown') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4" style="color: var(--ink-soft);">
                                    <span class="block max-w-[150px] truncate font-medium" title="{{ $act->task->judul ?? '-' }}">
                                        {{ $act->task->judul ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                    $lulus = ($act->status == 'passed') || ($act->persentase_benar >= 80);
                                    @endphp
                                    <span class="gp-pill shadow-sm" style="background: {{ $lulus ? 'var(--success-tint)' : 'var(--danger-tint)' }}; color: {{ $lulus ? 'var(--success)' : 'var(--danger)' }};">
                                        @if($lulus)
                                        <i data-lucide="check" class="w-3 h-3"></i> {{ __('dashboard.passed') }}
                                        @else
                                        <i data-lucide="x" class="w-3 h-3"></i> {{ __('dashboard.failed') }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-semibold" style="color: var(--ink-soft);">
                                    {{ $act->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gp-pop-enter">
                                        <i data-lucide="clipboard-x" class="w-10 h-10 mb-3" style="color: var(--border);"></i>
                                        <p class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('dashboard.no_submissions') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    document.addEventListener('alpine:init', () => {
        // Palette Config for Chart.js — dibaca ulang tiap render agar ikut dark mode
        function getGpColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return isDark ? {
                primary: '#3b82f6',
                primaryDark: '#60a5fa',
                primaryLight: 'rgba(59, 130, 246, 0.20)',
                success: '#34d399',
                ink: '#f1f5f9',
                inkSoft: '#94a3b8',
                border: '#334155',
                tooltipBg: '#334155',
            } : {
                primary: '#2563eb',
                primaryDark: '#1d4ed8',
                primaryLight: 'rgba(37, 99, 235, 0.15)',
                success: '#10b981',
                ink: '#1e293b',
                inkSoft: '#64748b',
                border: '#e2e8f0',
                tooltipBg: '#1e293b',
            };
        }

        Alpine.data('gpLineChart', (labels, data) => ({
            render() {
                const GP_COLORS = getGpColors();
                new Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Submit',
                            data: data,
                            borderColor: GP_COLORS.primary,
                            backgroundColor: GP_COLORS.primaryLight,
                            borderWidth: 3, // Slightly thicker for modern look
                            tension: 0.4, // Smoother curve (Material style)
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: GP_COLORS.primary,
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: GP_COLORS.tooltipBg,
                                titleFont: {
                                    family: 'Inter',
                                    size: 13
                                },
                                bodyFont: {
                                    family: 'Inter',
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: GP_COLORS.inkSoft,
                                    font: {
                                        family: 'Inter'
                                    }
                                },
                                grid: {
                                    color: GP_COLORS.border,
                                    borderDash: [4, 4]
                                },
                                border: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    color: GP_COLORS.inkSoft,
                                    font: {
                                        family: 'Inter'
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        }));

        Alpine.data('gpBarChart', (labels, data) => ({
            render() {
                const GP_COLORS = getGpColors();
                new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'User',
                            data: data,
                            backgroundColor: GP_COLORS.primary, // Using primary blue instead of green for consistency
                            hoverBackgroundColor: GP_COLORS.primaryDark,
                            borderRadius: 6,
                            maxBarThickness: 40,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: GP_COLORS.tooltipBg,
                                titleFont: {
                                    family: 'Inter',
                                    size: 13
                                },
                                bodyFont: {
                                    family: 'Inter',
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: GP_COLORS.inkSoft,
                                    font: {
                                        family: 'Inter'
                                    }
                                },
                                grid: {
                                    color: GP_COLORS.border,
                                    borderDash: [4, 4]
                                },
                                border: {
                                    display: false
                                }
                            },
                            x: {
                                ticks: {
                                    color: GP_COLORS.inkSoft,
                                    font: {
                                        family: 'Inter'
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        }));

    });
</script>
@endpush
@endonce