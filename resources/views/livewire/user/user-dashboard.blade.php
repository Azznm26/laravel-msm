<div class="space-y-4 sm:space-y-6 md:space-y-8 font-sans" style="font-family: 'Inter', sans-serif;">

    <style>
        /* ---------- Material Design 3 Styling ---------- */
        .md-panel {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            /* Sedikit lebih kecil untuk mobile */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 640px) {
            .md-panel {
                border-radius: 20px;
            }
        }

        .md-panel-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Support for md-panel */
        :is(.dark .md-panel) {
            background: #1e293b;
            /* Tailwind slate-800 */
            border-color: rgba(51, 65, 85, 0.8);
            /* Tailwind slate-700 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }
    </style>

    {{-- Header Sapaan --}}
    <div class="px-1 sm:px-2">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
            {{ __('user_dashboard.hello') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋
        </h1>
        <p class="text-xs sm:text-sm md:text-base text-gray-500 dark:text-gray-400 mt-1 sm:mt-1.5 font-medium">
            {{ __('user_dashboard.welcome_subtitle') }}
        </p>
    </div>

    {{-- Notifikasi Jika Belum Punya Jabatan --}}
    @if($error)
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 p-6 sm:p-8 rounded-2xl sm:rounded-3xl flex flex-col items-center justify-center text-center shadow-sm">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 sm:mb-5 shadow-sm border border-amber-100 dark:border-amber-800/50">
            <i data-lucide="lock" class="w-6 h-6 sm:w-7 sm:h-7 text-amber-500 dark:text-amber-400"></i>
        </div>
        <h2 class="text-lg sm:text-xl font-extrabold text-amber-900 dark:text-amber-300 mb-1.5 sm:mb-2">
            {{ __('user_dashboard.access_locked') }}
        </h2>
        <p class="text-xs sm:text-sm font-medium text-amber-700 dark:text-amber-400/80 max-w-md">
            {{ $error }} <br class="hidden sm:block">
            {{ __('user_dashboard.contact_admin') }}
        </p>
    </div>
    @else

    {{-- Kartu Info Utama (Atas) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 md:gap-6">

        {{-- Kartu Karir (Hero Card) --}}
        <a href="{{ route('user.career-path.index') }}" wire:navigate class="col-span-1 lg:col-span-2 bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 text-white shadow-lg shadow-blue-600/30 dark:shadow-blue-900/20 relative overflow-hidden group hover:scale-[1.015] transition-all duration-300 block border border-blue-500/50 dark:border-blue-600/30">
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4 group-hover:scale-110 transition-transform duration-700 ease-out pointer-events-none">
                <i data-lucide="trending-up" class="w-40 h-40 sm:w-56 sm:h-56"></i>
            </div>

            <div class="relative z-10 flex flex-col h-full justify-between gap-6 sm:gap-8 md:gap-6">
                <div>
                    <p class="text-blue-100 font-extrabold tracking-widest text-[10px] sm:text-[11px] uppercase mb-1.5 sm:mb-2 opacity-90">
                        {{ __('user_dashboard.current_position') }}
                    </p>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">{{ $currentLevelName }}</h2>
                    <p class="text-blue-100 font-medium text-sm sm:text-base mt-1 sm:mt-1.5 opacity-90">{{ $primaryPath->name ?? __('user_dashboard.primary_path') }}</p>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-blue-200 text-[10px] sm:text-xs font-bold mb-1 uppercase tracking-wider">
                            {{ __('user_dashboard.total_experience') }}
                        </p>
                        <p class="text-2xl sm:text-3xl font-black flex items-center gap-1.5 sm:gap-2">
                            <i data-lucide="star" class="w-5 h-5 sm:w-6 sm:h-6 fill-current text-amber-400"></i>
                            {{ number_format($totalXp) }} <span class="text-sm sm:text-lg font-bold text-blue-200">EXP</span>
                        </p>
                    </div>
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 transition-colors">
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5 text-white"></i>
                    </div>
                </div>
            </div>
        </a>

        {{-- Kartu Ringkasan Tugas --}}
        <div class="md-panel p-5 sm:p-6 md:p-8 flex flex-col justify-between h-full">
            <div>
                <h3 class="font-extrabold text-gray-900 dark:text-white text-base sm:text-lg flex items-center gap-2 sm:gap-2.5 mb-4 sm:mb-5">
                    <i data-lucide="clipboard-list" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"></i>
                    {{ __('user_dashboard.task_status') }}
                </h3>

                <div class="space-y-3 sm:space-y-3.5">
                    <div class="flex items-center justify-between p-3 sm:p-3.5 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 rounded-xl sm:rounded-2xl shadow-sm">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <div class="p-1.5 sm:p-2 bg-white dark:bg-rose-900/50 rounded-lg sm:rounded-xl shadow-sm border border-rose-50 dark:border-rose-800/50 text-rose-500 dark:text-rose-400"><i data-lucide="clock" class="w-4 h-4 sm:w-4.5 sm:h-4.5"></i></div>
                            <span class="font-extrabold text-xs sm:text-sm text-rose-700 dark:text-rose-400">{{ __('user_dashboard.pending') }}</span>
                        </div>
                        <span class="text-lg sm:text-xl font-black text-rose-700 dark:text-rose-400">{{ $pendingCount }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 sm:p-3.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-xl sm:rounded-2xl shadow-sm">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <div class="p-1.5 sm:p-2 bg-white dark:bg-emerald-900/50 rounded-lg sm:rounded-xl shadow-sm border border-emerald-50 dark:border-emerald-800/50 text-emerald-500 dark:text-emerald-400"><i data-lucide="check-circle-2" class="w-4 h-4 sm:w-4.5 sm:h-4.5"></i></div>
                            <span class="font-extrabold text-xs sm:text-sm text-emerald-700 dark:text-emerald-400">{{ __('user_dashboard.completed') }}</span>
                        </div>
                        <span class="text-lg sm:text-xl font-black text-emerald-700 dark:text-emerald-400">{{ $completedCount }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-gray-100 dark:border-slate-700 text-center">
                <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 font-medium">
                    {{ __('user_dashboard.total_overall') }}: <strong class="text-gray-800 dark:text-gray-200 font-extrabold">{{ $taskCount }} {{ __('user_dashboard.tasks') }}</strong>
                </p>
            </div>
        </div>

    </div>

    {{-- Daftar Tugas Terbaru (Bawah) --}}
    <div class="md-panel overflow-hidden mt-1 sm:mt-2">
        <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="font-extrabold text-gray-900 dark:text-white text-base sm:text-lg flex items-center gap-2 sm:gap-2.5">
                <i data-lucide="bell-ring" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400"></i>
                {{ __('user_dashboard.recent_tasks') }}
            </h3>
            <a href="{{ route('user.tasks.index') }}" wire:navigate class="text-xs sm:text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1 transition-colors px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-700">
                {{ __('user_dashboard.see_all') }} <i data-lucide="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4"></i>
            </a>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
            @forelse($recentTasks as $task)
            <a href="{{ route('user.tasks.index', $task->id) }}" wire:navigate class="flex items-center justify-between p-4 sm:p-6 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                <div class="flex items-center gap-3 sm:gap-4.5">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-sm shrink-0 border
                             {{ $task->my_color === 'emerald' ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50' : 
                                ($task->my_color === 'amber' ? 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50' : 
                                ($task->my_color === 'rose' ? 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50' : 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50')) }}">

                        @if($task->jenis_task === 'upload_file') <i data-lucide="file-up" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        @elseif($task->jenis_task === 'pilihan_ganda') <i data-lucide="help-circle" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        @else <i data-lucide="clipboard-pen" class="w-5 h-5 sm:w-6 sm:h-6"></i> @endif
                    </div>

                    <div>
                        <h4 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors line-clamp-1 mb-0.5 sm:mb-1">{{ $task->judul }}</h4>
                        <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-[11px] font-medium text-gray-500 dark:text-gray-400">
                            <span class="font-bold text-gray-600 dark:text-gray-300 flex items-center"><i data-lucide="star" class="w-3 h-3 sm:w-3.5 sm:h-3.5 inline mr-1 text-amber-400 fill-current"></i>+{{ $task->exp_reward }} EXP</span>
                            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-2.5 h-2.5 sm:w-3 sm:h-3"></i> {{ $task->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block">
                    <span class="px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-lg text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider shadow-sm border
                             {{ $task->my_color === 'emerald' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50' : 
                                ($task->my_color === 'amber' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50' : 
                                ($task->my_color === 'rose' ? 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50' : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50')) }}">
                        {{ $task->my_status }}
                    </span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-300 dark:text-gray-600 sm:hidden"></i>
            </a>
            @empty
            <div class="p-8 sm:p-12 md:p-16 text-center bg-white dark:bg-transparent flex flex-col items-center justify-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-sm">
                    <i data-lucide="coffee" class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 dark:text-gray-500"></i>
                </div>
                <h4 class="font-extrabold text-gray-900 dark:text-gray-200 text-base sm:text-lg">
                    {{ __('user_dashboard.all_tasks_done') }}
                </h4>
                <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 mt-1 sm:mt-1.5">
                    {{ __('user_dashboard.no_new_tasks') }}
                </p>
            </div>
            @endforelse
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