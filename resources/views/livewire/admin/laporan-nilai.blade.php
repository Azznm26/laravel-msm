<div
    x-data
    @open-export-url.window="window.open($event.detail.url, '_blank')"
    class="container mx-auto px-4 py-8 max-w-7xl font-sans" style="font-family: 'Inter', sans-serif; color: var(--ink);">

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

            --warning: #fbbf24;
            --warning-tint: rgba(251, 191, 36, 0.12);
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
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

        .md-btn-success-outline {
            background: var(--success-tint);
            color: var(--success);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .dark .md-btn-success-outline {
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .md-btn-success-outline:hover {
            background: #d1fae5;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
        }

        .dark .md-btn-success-outline:hover {
            background: rgba(52, 211, 153, 0.2);
        }

        .md-btn-danger-outline {
            background: var(--danger-tint);
            color: var(--danger);
            border: 1px solid #fecaca;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .dark .md-btn-danger-outline {
            border: 1px solid rgba(248, 113, 113, 0.3);
        }

        .md-btn-danger-outline:hover {
            background: #fee2e2;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.1);
        }

        .dark .md-btn-danger-outline:hover {
            background: rgba(248, 113, 113, 0.2);
        }
    </style>

    <div class="space-y-6 sm:space-y-8">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/40">
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                    </div>
                    {{ __('report.title') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 ml-1 font-medium">
                    {{ __('report.subtitle') }}
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3">
                <button
                    type="button"
                    wire:click="exportExcel"
                    wire:loading.attr="disabled"
                    wire:target="exportExcel"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-success-outline font-bold text-sm shadow-sm flex-1 sm:flex-none disabled:opacity-60">
                    <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5"></i> {{ __('report.export_excel') }}
                </button>
                <button
                    type="button"
                    wire:click="exportPdf"
                    wire:loading.attr="disabled"
                    wire:target="exportPdf"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-danger-outline font-bold text-sm shadow-sm flex-1 sm:flex-none disabled:opacity-60">
                    <i data-lucide="file-text" class="w-4.5 h-4.5"></i> {{ __('report.export_pdf') }}
                </button>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="md-panel p-5 flex flex-col md:flex-row gap-4 md:items-center">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4.5 h-4.5 text-gray-400 dark:text-gray-500 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="{{ __('report.search_placeholder') }}"
                    class="w-full pl-10 pr-4 py-2.5 md-input text-sm font-medium">
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <select
                    wire:model.live="statusFilter"
                    class="w-full md:w-48 px-4 py-2.5 md-input text-sm font-medium cursor-pointer">
                    <option value="all">{{ __('report.status_all') }}</option>
                    <option value="lulus">{{ __('report.status_passed') }}</option>
                    <option value="gagal">{{ __('report.status_failed') }}</option>
                </select>

                @if($search !== '' || $statusFilter !== 'all')
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="text-xs font-bold text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 bg-gray-50 dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-red-200 dark:hover:border-red-800 flex items-center gap-1.5 transition-colors shrink-0 whitespace-nowrap">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> {{ __('report.reset') }}
                </button>
                @endif
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5" wire:loading.class="opacity-60" wire:target="search,statusFilter">
            {{-- Total Peserta --}}
            <div class="md-panel p-5 border-l-4 border-l-amber-400 dark:border-l-amber-500 flex items-center justify-between group hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('report.stat_total_participants') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-1.5 group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors">{{ $this->stats['total_peserta'] }}</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-amber-500 dark:text-amber-400 border border-amber-100 dark:border-amber-800/40 shadow-sm"><i data-lucide="users" class="w-7 h-7"></i></div>
            </div>

            {{-- Rata-rata Nilai --}}
            <div class="md-panel p-5 border-l-4 border-l-blue-500 flex items-center justify-between group hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('report.stat_avg_score') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-1.5 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ number_format($this->stats['avg_score'], 1) }}%</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/40 shadow-sm"><i data-lucide="percent" class="w-7 h-7"></i></div>
            </div>

            {{-- Total Lulus --}}
            <div class="md-panel p-5 border-l-4 border-l-emerald-500 flex items-center justify-between group hover:shadow-md transition-shadow">
                <div>
                    <p class="text-xs font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('report.stat_total_passed') }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100 mt-1.5 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $this->stats['pass_count'] }} <span class="text-sm text-gray-400 dark:text-gray-500 font-bold ml-0.5">{{ __('report.tasks_unit') }}</span></p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/40 shadow-sm"><i data-lucide="check-circle" class="w-7 h-7"></i></div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="md-panel overflow-hidden relative">

            {{-- Overlay loading saat search/filter berjalan --}}
            <div
                wire:loading.flex
                wire:target="search,statusFilter,gotoPage,previousPage,nextPage"
                class="absolute inset-0 bg-white/60 dark:bg-gray-900/70 backdrop-blur-[2px] items-center justify-center z-10 hidden rounded-2xl">
                <i data-lucide="loader-2" class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin"></i>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-slate-50 dark:bg-gray-800/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[20%]">{{ __('report.column_participant') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[30%]">{{ __('report.column_task') }}</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">{{ __('report.column_status') }}</th>
                            <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">{{ __('report.column_final_score') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[20%]">{{ __('report.column_submit_time') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($this->participants as $participant)

                        @foreach($participant->taskResults as $index => $res)
                        <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors group">

                            {{-- Kolom Peserta (tampil hanya di baris pertama) --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top border-r border-gray-50 dark:border-gray-800">
                                @if($index === 0)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 mt-0.5">
                                        <div class="h-10 w-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-700 dark:text-blue-400 font-bold border border-blue-200 dark:border-blue-800/40 text-sm overflow-hidden shadow-sm">
                                            @if($participant->photo)
                                            <img src="{{ asset('storage/' . $participant->photo) }}" class="h-full w-full object-cover">
                                            @else
                                            {{ strtoupper(substr($participant->name, 0, 1)) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-3.5 mt-0.5">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $participant->name }}</div>
                                        @if($participant->id_badge)
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $participant->id_badge) }}" target="_blank" class="flex items-center gap-1.5 w-fit text-[11px] font-medium text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline">
                                                <i data-lucide="badge-check" class="w-3.5 h-3.5"></i> {{ __('report.id_badge', ['id' => $participant->id_badge]) }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </td>

                            {{-- Judul Task --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $res->task->judul }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">
                                    @php
                                    $typeColor = match($res->task->jenis_task) {
                                    'pilihan_ganda' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800/40',
                                    'survey' => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800/40',
                                    default => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/40'
                                    };
                                    $typeKey = 'report.task_type.' . $res->task->jenis_task;
                                    $typeLabel = __($typeKey);
                                    if ($typeLabel === $typeKey) {
                                    $typeLabel = ucwords(str_replace('_', ' ', $res->task->jenis_task));
                                    }
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider border shadow-sm {{ $typeColor }}">
                                        {{ $typeLabel }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                $isPassed = ($res->status == 'passed') || ($res->persentase_benar >= 80);
                                @endphp

                                @if($isPassed)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40 shadow-sm">
                                    <i data-lucide="award" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('report.passed') }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/40 shadow-sm">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5 mr-1.5"></i> {{ __('report.failed') }}
                                </span>
                                @endif
                            </td>

                            {{-- Nilai Akhir --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($res->task->jenis_task == 'pilihan_ganda')
                                <span class="text-2xl font-black {{ $res->persentase_benar >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ number_format($res->persentase_benar, 1) }}%
                                </span>
                                <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-1">
                                    {{ __('report.correct_count', ['correct' => $res->total_skor, 'total' => $res->jumlah_soal]) }}
                                </div>
                                @else
                                <span class="text-sm font-medium text-gray-400 dark:text-gray-500 italic">{{ __('report.not_applicable') }}</span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold text-gray-500 dark:text-gray-400">
                                <div class="text-gray-700 dark:text-gray-300 mb-0.5">{{ $res->created_at->format('d M Y') }}</div>
                                <div>{{ $res->created_at->format('H:i') }} {{ __('report.timezone_label') }}</div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Pemisah antar Peserta --}}
                        @if(!$loop->last)
                        <tr class="bg-gray-50/50 dark:bg-gray-800/30">
                            <td colspan="5" class="py-1 border-t border-b border-gray-100 dark:border-gray-800"></td>
                        </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="file-x" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <p class="text-base font-bold text-gray-700 dark:text-gray-200">{{ __('report.empty_title') }}</p>
                                    <p class="text-sm mt-1.5 font-medium text-gray-500 dark:text-gray-400">{{ __('report.empty_subtitle') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($this->participants->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                {{ $this->participants->links() }}
            </div>
            @endif
        </div>

    </div>

    {{-- Re-init lucide icons setiap kali Livewire selesai render (search, filter, paginasi) --}}
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