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
    </style>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Jejak Langkah (Breadcrumbs) --}}
    <div class="flex items-center gap-2 text-xs font-bold text-gray-400 dark:text-gray-500 mb-6 bg-white dark:bg-slate-800 w-max px-4 py-2 rounded-full border border-gray-100 dark:border-slate-700 shadow-sm">
        <span class="{{ $viewMode === 'departments' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-md' : '' }} transition-colors">{{ __('admin_task_management.bread_department') }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="{{ $viewMode === 'jabatans' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-md' : '' }} transition-colors">{{ __('admin_task_management.bread_position') }}</span>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="{{ in_array($viewMode, ['tasks', 'form', 'questions']) ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-md' : '' }} transition-colors">{{ __('admin_task_management.bread_task') }}</span>
    </div>

    {{-- ======================================================== --}}
    {{-- TAHAP 1: PILIH DEPARTMENT --}}
    {{-- ======================================================== --}}
    @if($viewMode === 'departments')
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            {{ __('admin_task_management.title_departments') }}
        </h1>
        <p class="text-sm mt-2 ml-1 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.subtitle_departments') }}</p>
    </div>

    <div class="mb-6 p-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl shadow-sm">
        <h2 class="text-sm font-extrabold text-blue-900 dark:text-blue-300 flex items-center gap-2 mb-2">
            <i data-lucide="info" class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_task_management.guide_title') }}
        </h2>
        <p class="text-xs font-medium text-blue-800 dark:text-blue-400 leading-relaxed">
            {!! __('admin_task_management.guide_desc') !!}
        </p>
    </div>

    <div class="mb-6">
        <div class="relative max-w-md">
            <i data-lucide="search" class="w-4.5 h-4.5 text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2"></i>
            <input type="text" wire:model.live.debounce.300ms="searchDepartment" placeholder="{{ __('admin_task_management.search_dept') }}" class="w-full pl-11 pr-4 py-2.5 md-input text-sm font-medium shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($departments as $dept)
        <div wire:click="selectDepartment({{ $dept->id }}, '{{ $dept->nama_department }}')"
            class="relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 cursor-pointer group overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1.5 hover:border-blue-300 dark:hover:border-blue-500">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500"></div>

            <div class="relative z-10 pt-1">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm border border-slate-100 dark:border-slate-700/50 group-hover:border-blue-600 group-hover:shadow-md">
                        <i data-lucide="layers" class="w-6 h-6"></i>
                    </div>
                    <span class="px-3 py-1.5 text-[11px] font-extrabold rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300 shadow-sm">
                        {{ $dept->tasks_count }} {{ __('admin_task_management.task_count') }}
                    </span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors duration-300 line-clamp-2">
                    {{ $dept->nama_department }}
                </h3>
                <div class="mt-4 flex items-center text-sm font-bold text-slate-400 dark:text-slate-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                    <span>{{ __('admin_task_management.view_manage_task') }}</span> <i data-lucide="arrow-right" class="w-4.5 h-4.5 ml-1.5 transform group-hover:translate-x-1.5 transition-transform duration-300 ease-out"></i>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full md-panel p-12 text-center flex flex-col items-center justify-center border-dashed border-slate-300 dark:border-slate-700">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-300 dark:text-slate-500"></i>
            </div>
            <h3 class="text-lg font-extrabold text-gray-800 dark:text-white">{{ __('admin_task_management.no_dept') }}</h3>
            <p class="text-sm mt-1.5 font-medium text-gray-500 dark:text-gray-400">
                @if($searchDepartment)
                {{ __('admin_task_management.no_dept_search', ['search' => $searchDepartment]) }}
                @else
                {{ __('admin_task_management.no_dept_avail') }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- ======================================================== --}}
    {{-- TAHAP 2: PILIH JABATAN --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'jabatans')
    <div class="mb-6">
        <button wire:click="backToDepartments" class="flex items-center text-sm font-bold mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_task_management.back_to_dept') }}
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="briefcase" class="w-6 h-6"></i>
            </div>
            {{ __('admin_task_management.position') }}: {{ $selectedDepartmentName }}
        </h1>
        <p class="text-sm mt-2 ml-1 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.subtitle_position') }}</p>
    </div>

    <div class="mb-6">
        <div class="relative max-w-md">
            <i data-lucide="search" class="w-4.5 h-4.5 text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2"></i>
            <input type="text" wire:model.live.debounce.300ms="searchJabatan" placeholder="{{ __('admin_task_management.search_pos') }}" class="w-full pl-11 pr-4 py-2.5 md-input text-sm font-medium shadow-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($jabatans as $jabatan)
        <div wire:click="selectJabatan({{ $jabatan->id }}, '{{ $jabatan->nama_jabatan }}')"
            class="relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 cursor-pointer group overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-300 dark:hover:border-blue-500">

            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 border border-slate-100 dark:border-slate-700/50 group-hover:border-blue-600">
                    <i data-lucide="user-badge" class="w-5 h-5"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors duration-300 mb-3 line-clamp-2">
                    {{ $jabatan->nama_jabatan }}
                </h3>
                <span class="inline-flex items-center px-3 py-1.5 text-[11px] font-extrabold rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:border-blue-200 transition-colors shadow-sm">
                    <i data-lucide="check-square" class="w-3.5 h-3.5 mr-1.5"></i> {{ $jabatan->tasks_count }} {{ __('admin_task_management.task_available') }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full md-panel p-12 text-center flex flex-col items-center justify-center border-dashed border-slate-300 dark:border-slate-700">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="briefcase" class="w-10 h-10 text-slate-300 dark:text-slate-500"></i>
            </div>
            <h3 class="text-lg font-extrabold text-gray-800 dark:text-white">{{ __('admin_task_management.no_pos') }}</h3>
            <p class="text-sm mt-1.5 font-medium text-gray-500 dark:text-gray-400">
                @if($searchJabatan)
                {{ __('admin_task_management.no_pos_search', ['search' => $searchJabatan]) }}
                @else
                {{ __('admin_task_management.no_pos_avail') }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- ======================================================== --}}
    {{-- TAHAP 3: DAFTAR TASK --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'tasks')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <button wire:click="backToJabatans" class="flex items-center text-sm font-bold mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_task_management.back_to_pos') }}
            </button>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                </div>
                {{ __('admin_task_management.task') }}: {{ $selectedJabatanName }}
            </h1>
        </div>
        <button wire:click="create" class="flex items-center gap-2 px-5 py-3 md-btn-primary font-bold text-sm shadow-md">
            <i data-lucide="plus" class="w-4.5 h-4.5"></i> {{ __('admin_task_management.create_task') }}
        </button>
    </div>

    <div class="mb-6">
        <div class="relative max-w-md">
            <i data-lucide="search" class="w-4.5 h-4.5 text-gray-400 dark:text-gray-500 absolute left-4 top-1/2 -translate-y-1/2"></i>
            <input type="text" wire:model.live.debounce.300ms="searchTask" placeholder="{{ __('admin_task_management.search_task') }}" class="w-full pl-11 pr-4 py-2.5 md-input text-sm font-medium shadow-sm">
        </div>
    </div>

    <div class="md-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_task_management.th_title') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_task_management.th_type') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_task_management.th_questions') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_task_management.th_reward') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_task_management.th_status') }}</th>
                        <th class="px-6 py-4 font-extrabold text-right">{{ __('admin_task_management.th_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
                    @forelse($tasks as $t)
                    <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $t->judul }}</div>
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                                @if($t->deadline)
                                <i data-lucide="clock" class="w-3 h-3 inline-block align-middle mr-0.5"></i> {{ __('admin_task_management.deadline') }}: {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}
                                @else
                                {{ __('admin_task_management.no_deadline') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                            $typeColor = match($t->jenis_task) {
                            'pilihan_ganda' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/50',
                            'survey' => 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800/50',
                            default => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800/50'
                            };
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg border {{ $typeColor }} text-[10px] font-extrabold uppercase tracking-wider shadow-sm">
                                @if($t->jenis_task === 'pilihan_ganda') {{ __('admin_task_management.type_quiz') }}
                                @elseif($t->jenis_task === 'survey') {{ __('admin_task_management.type_survey') }}
                                @else {{ __('admin_task_management.type_upload') }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-400">
                            @if($t->jenis_task === 'pilihan_ganda')
                            {{ $t->questions_count }} {{ __('admin_task_management.q_count') }}
                            @else
                            <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-extrabold text-amber-500 dark:text-amber-400">+{{ $t->exp_reward }} EXP</td>
                        <td class="px-6 py-4 text-center">
                            @if($t->status === 'published')
                            <span class="px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider shadow-sm">{{ __('admin_task_management.status_pub') }}</span>
                            @else
                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 text-[10px] font-extrabold uppercase tracking-wider shadow-sm">{{ __('admin_task_management.status_draft') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Tombol Lihat Pertanyaan (Hanya Muncul jika Pilihan Ganda / Survei) --}}
                                @if(in_array($t->jenis_task, ['pilihan_ganda', 'survey']))
                                <button wire:click="viewQuestions({{ $t->id }})" class="p-2 md-icon-btn bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50" title="Lihat Pertanyaan">
                                    <i data-lucide="list-checks" class="w-4.5 h-4.5"></i>
                                </button>
                                @endif

                                <a href="{{ route('admin.task.assign', $t->id) }}" wire:navigate class="p-2 md-icon-btn bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50" title="{{ __('admin_task_management.assign_task') }}">
                                    <i data-lucide="user-check" class="w-4.5 h-4.5"></i>
                                </a>
                                <button wire:click="edit({{ $t->id }})" class="p-2 md-icon-btn bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50" title="{{ __('admin_task_management.edit') }}">
                                    <i data-lucide="edit" class="w-4.5 h-4.5"></i>
                                </button>
                                <button @click="$dispatch('open-delete-modal', { id: {{ $t->id }}, name: '{{ addslashes($t->judul) }}' })"
                                    class="p-2 md-icon-btn bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50" title="{{ __('admin_task_management.delete') }}">
                                    <i data-lucide="trash" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700 dark:text-gray-300 mb-4">
                                    @if($searchTask)
                                    {{ __('admin_task_management.no_task_search', ['search' => $searchTask]) }}
                                    @else
                                    {{ __('admin_task_management.no_task_avail') }}
                                    @endif
                                </p>
                                @if(!$searchTask)
                                <button wire:click="create" class="inline-flex items-center gap-2 px-5 py-2.5 md-btn-primary text-xs font-bold shadow-md">
                                    <i data-lucide="plus" class="w-4 h-4"></i> {{ __('admin_task_management.create_first_task') }}
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAHAP 3.5: LIHAT PERTANYAAN (QUESTIONS MODE) --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'questions')
    <div class="mb-6">
        <button wire:click="backToTasks" class="flex items-center text-sm font-bold mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> Kembali ke Daftar Task
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl shadow-sm border border-indigo-100 dark:border-indigo-800/50">
                <i data-lucide="list-checks" class="w-6 h-6"></i>
            </div>
            Daftar Pertanyaan: {{ $viewedTask->judul }}
        </h1>
        <p class="text-sm mt-2 ml-1 text-gray-500 dark:text-gray-400">
            Tipe Task: <span class="uppercase font-bold text-indigo-500 dark:text-indigo-400">{{ str_replace('_', ' ', $viewedTask->jenis_task) }}</span>
        </p>
    </div>

    <div class="md-panel overflow-hidden p-6 sm:p-8">
        @if($viewedTask->jenis_task === 'pilihan_ganda')
        @if($viewedTask->questions->isEmpty())
        <div class="text-center py-12">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 italic">Belum ada pertanyaan pilihan ganda yang ditambahkan untuk task ini.</p>
        </div>
        @else
        <div class="space-y-5">
            @foreach($viewedTask->questions as $index => $question)
            <div class="p-5 border border-gray-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 rounded-xl">
                <p class="font-extrabold text-gray-800 dark:text-gray-200 mb-4 leading-relaxed">
                    <span class="text-blue-600 dark:text-blue-400 mr-1">{{ $index + 1 }}.</span> {{ $question->pertanyaan }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-medium">
                    {{-- Opsi A --}}
                    <div class="px-4 py-3 rounded-lg border transition-colors {{ strtolower($question->jawaban_benar) === 'a' ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400' }}">
                        <span class="font-extrabold mr-2 {{ strtolower($question->jawaban_benar) === 'a' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">A.</span> {{ $question->pilihan_a }}
                        @if(strtolower($question->jawaban_benar) === 'a') <i data-lucide="check-circle-2" class="w-4.5 h-4.5 inline float-right text-emerald-500 dark:text-emerald-400"></i> @endif
                    </div>
                    {{-- Opsi B --}}
                    <div class="px-4 py-3 rounded-lg border transition-colors {{ strtolower($question->jawaban_benar) === 'b' ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400' }}">
                        <span class="font-extrabold mr-2 {{ strtolower($question->jawaban_benar) === 'b' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">B.</span> {{ $question->pilihan_b }}
                        @if(strtolower($question->jawaban_benar) === 'b') <i data-lucide="check-circle-2" class="w-4.5 h-4.5 inline float-right text-emerald-500 dark:text-emerald-400"></i> @endif
                    </div>
                    {{-- Opsi C --}}
                    <div class="px-4 py-3 rounded-lg border transition-colors {{ strtolower($question->jawaban_benar) === 'c' ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400' }}">
                        <span class="font-extrabold mr-2 {{ strtolower($question->jawaban_benar) === 'c' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">C.</span> {{ $question->pilihan_c }}
                        @if(strtolower($question->jawaban_benar) === 'c') <i data-lucide="check-circle-2" class="w-4.5 h-4.5 inline float-right text-emerald-500 dark:text-emerald-400"></i> @endif
                    </div>
                    {{-- Opsi D --}}
                    <div class="px-4 py-3 rounded-lg border transition-colors {{ strtolower($question->jawaban_benar) === 'd' ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-400' }}">
                        <span class="font-extrabold mr-2 {{ strtolower($question->jawaban_benar) === 'd' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500' }}">D.</span> {{ $question->pilihan_d }}
                        @if(strtolower($question->jawaban_benar) === 'd') <i data-lucide="check-circle-2" class="w-4.5 h-4.5 inline float-right text-emerald-500 dark:text-emerald-400"></i> @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @elseif($viewedTask->jenis_task === 'survey')
        @if($viewedTask->surveyQuestions->isEmpty())
        <div class="text-center py-12">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 italic">Belum ada pertanyaan survei yang ditambahkan untuk task ini.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($viewedTask->surveyQuestions as $index => $question)
            <div class="p-5 border border-gray-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/50 rounded-xl flex items-start gap-3">
                <span class="font-extrabold text-purple-600 dark:text-purple-400">{{ $index + 1 }}.</span>
                <p class="font-bold text-gray-800 dark:text-gray-200 leading-relaxed">{{ $question->pertanyaan }}</p>
            </div>
            @endforeach
        </div>
        @endif

        @else
        <div class="text-center py-12">
            <i data-lucide="file-up" class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Task bertipe <strong>Upload File</strong> tidak memiliki daftar pertanyaan tertulis.</p>
        </div>
        @endif
    </div>


    {{-- MODAL HAPUS TASK --}}
    <div x-data="{ open: false, taskId: null, taskName: '' }"
        @open-delete-modal.window="open = true; taskId = $event.detail.id; taskName = $event.detail.name"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm px-4">

        <div x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="bg-white dark:bg-slate-800 w-full max-w-md overflow-hidden rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700">

            <div class="p-6 border-b border-red-50 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/20 text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 dark:bg-red-900/50 mb-4 shadow-sm border border-red-200 dark:border-red-800/50">
                    <i data-lucide="trash" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('admin_task_management.del_title') }}</h3>
                <p class="text-sm mt-2 text-gray-500 dark:text-gray-400 font-medium">{!! __('admin_task_management.del_desc') !!}</p>
            </div>

            <div class="p-6 flex items-center justify-center gap-3 bg-white dark:bg-slate-800">
                <button type="button" @click="open = false" class="px-6 py-2.5 text-sm font-bold md-btn-neutral flex-1">
                    {{ __('admin_task_management.btn_cancel') }}
                </button>
                <button type="button" @click="$wire.delete(taskId); open = false" class="px-6 py-2.5 text-sm font-bold md-btn-danger-solid flex-1">
                    {{ __('admin_task_management.btn_delete') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAHAP 4: FORM CREATE / EDIT --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'form')
    <div class="mb-6">
        <button wire:click="backToTasks" class="flex items-center text-sm font-bold mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_task_management.back_cancel') }}
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                <i data-lucide="{{ $taskId ? 'edit-3' : 'plus-square' }}" class="w-6 h-6"></i>
            </div>
            {{ $taskId ? __('admin_task_management.edit_task') : __('admin_task_management.create_task') }}
        </h1>
        <p class="text-sm mt-2 ml-1 font-medium text-gray-500 dark:text-gray-400">{{ __('admin_task_management.form_subtitle') }}</p>
    </div>

    <div class="md-panel overflow-hidden max-w-4xl">
        <form wire:submit.prevent="store" class="p-6 sm:p-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_title') }}</label>
                    <input type="text" wire:model="judul" placeholder="{{ __('admin_task_management.ph_title') }}" class="w-full px-4 py-3 md-input text-sm font-medium" required>
                    @error('judul') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_desc') }}</label>
                    <textarea wire:model="deskripsi" rows="3" placeholder="{{ __('admin_task_management.ph_desc') }}" class="w-full px-4 py-3 md-input text-sm font-medium" required></textarea>
                    @error('deskripsi') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="bg-blue-50/50 dark:bg-blue-900/10 p-5 rounded-2xl border border-blue-100/50 dark:border-blue-800/30">
                    <label class="block text-xs font-extrabold uppercase tracking-wider mb-2 text-blue-800 dark:text-blue-300">{{ __('admin_task_management.lbl_type') }}</label>
                    <select wire:model="jenis_task" class="w-full px-4 py-3 md-input bg-white dark:bg-slate-900 text-sm font-medium" required>
                        <option value="pilihan_ganda">{{ __('admin_task_management.opt_quiz') }}</option>
                        <option value="survey">{{ __('admin_task_management.opt_survey') }}</option>
                        <option value="upload_file">{{ __('admin_task_management.opt_upload') }}</option>
                    </select>
                    <p class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 mt-2"><i data-lucide="info" class="w-3.5 h-3.5 inline align-middle mb-0.5"></i> {{ __('admin_task_management.note_type') }}</p>
                    @error('jenis_task') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_target_pos') }}</label>
                    <select wire:model="jabatan_id" class="w-full px-4 py-3 md-input bg-white dark:bg-slate-900 text-sm font-medium disabled:bg-gray-100 disabled:text-gray-400 dark:disabled:bg-slate-800 dark:disabled:text-slate-500" required>
                        @foreach($jabatansForForm as $jab)
                        <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_reward') }}</label>
                    <div class="relative">
                        <i data-lucide="star" class="w-4.5 h-4.5 absolute left-3.5 top-1/2 -translate-y-1/2 text-amber-400"></i>
                        <input type="number" wire:model="exp_reward" min="1" class="w-full pl-10 pr-4 py-3 md-input text-sm font-medium" required>
                    </div>
                    <p class="text-[11px] font-medium text-gray-400 dark:text-gray-500 mt-2">{{ __('admin_task_management.note_reward') }}</p>
                    @error('exp_reward') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_pub_status') }}</label>
                    <select wire:model="status" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900" required>
                        <option value="draft">{{ __('admin_task_management.opt_draft') }}</option>
                        <option value="published">{{ __('admin_task_management.opt_published') }}</option>
                    </select>
                    @error('status') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_task_management.lbl_deadline') }}</label>
                    <input type="date" wire:model="deadline" class="w-full px-4 py-3 md-input text-sm font-medium">
                    <p class="text-[11px] font-medium text-gray-400 dark:text-gray-500 mt-2">{{ __('admin_task_management.note_deadline') }}</p>
                    @error('deadline') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/80 flex justify-end gap-3 mt-8">
                <button type="button" wire:click="backToTasks" class="px-6 py-3 text-sm font-bold md-btn-neutral">
                    {{ __('admin_task_management.btn_cancel') }}
                </button>
                <button type="submit" class="px-6 py-3 text-sm font-bold md-btn-primary flex items-center gap-2" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="store">{{ __('admin_task_management.btn_save_next') }} <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i></span>
                    <span wire:loading wire:target="store" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('admin_task_management.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>