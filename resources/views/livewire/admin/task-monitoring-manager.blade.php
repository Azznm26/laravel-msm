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

        .md-btn-success-solid {
            background: var(--success);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-btn-success-solid:hover {
            background: #059669;
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
    @if (session()->has('error'))
    <div class="mb-6 px-5 py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-500 dark:text-red-400"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODE: DAFTAR TASK --}}
    {{-- ========================================== --}}
    @if($viewMode === 'list')
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                {{ __('admin_tasks.title') }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium ml-1">{{ __('admin_tasks.subtitle') }}</p>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto mt-2 lg:mt-0">
            <button wire:click="exportExcel" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-success-solid font-bold text-sm flex-1 sm:flex-none">
                <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5"></i> {{ __('admin_tasks.export_csv') }}
            </button>
            <button wire:click="exportPdf" type="button" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-danger-solid font-bold text-sm flex-1 sm:flex-none" wire:loading.attr="disabled">
                <i data-lucide="file-text" class="w-4.5 h-4.5" wire:loading.remove wire:target="exportPdf"></i>
                <span wire:loading.remove wire:target="exportPdf">{{ __('admin_tasks.export_pdf') }}</span>
                <span wire:loading wire:target="exportPdf" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('admin_tasks.processing') }}
                </span>
            </button>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="md-panel p-5 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="relative sm:col-span-2 lg:col-span-1 xl:col-span-2">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 transform -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('admin_tasks.search_placeholder') }}" class="w-full pl-10 pr-4 py-2.5 md-input text-sm font-medium">
        </div>
        <div>
            <select wire:model.live="departmentId" class="w-full px-4 py-2.5 md-input text-sm font-medium">
                <option value="">{{ __('admin_tasks.filter_department') }}</option>
                @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option> @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="jabatanId" class="w-full px-4 py-2.5 md-input text-sm font-medium">
                <option value="">{{ __('admin_tasks.filter_position') }}</option>
                @foreach($availableJabatans as $jab) <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option> @endforeach
            </select>
        </div>
        <div>
            <select wire:model.live="jenisTask" class="w-full px-4 py-2.5 md-input text-sm font-medium">
                <option value="">{{ __('admin_tasks.filter_task_type') }}</option>
                <option value="upload_file">{{ __('admin_tasks.task_type_upload') }}</option>
                <option value="pilihan_ganda">{{ __('admin_tasks.task_type_quiz') }}</option>
                <option value="survey">{{ __('admin_tasks.task_type_survey') }}</option>
            </select>
        </div>
        <div>
            <select wire:model.live="statusPengerjaan" class="w-full px-4 py-2.5 md-input text-sm font-medium">
                <option value="">{{ __('admin_tasks.filter_status') }}</option>
                <option value="done">{{ __('admin_tasks.status_done') }}</option>
                <option value="pending">{{ __('admin_tasks.status_pending') }}</option>
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
                            {{ __('admin_tasks.th_employee') }} <i data-lucide="{{ $sortBy === 'name' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevrons-up-down' }}" class="w-3.5 h-3.5 inline ml-1 opacity-50 group-hover:opacity-100 {{ $sortBy === 'name' ? 'text-blue-600 dark:text-blue-400 opacity-100' : '' }}"></i>
                        </th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_tasks.th_type') }}</th>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_tasks.th_task_title') }}</th>
                        <th class="px-6 py-4 font-extrabold text-center cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 transition-colors group" wire:click="sort('date')">
                            {{ __('admin_tasks.th_updated_at') }} <i data-lucide="{{ $sortBy === 'date' ? ($sortOrder === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevrons-up-down' }}" class="w-3.5 h-3.5 inline ml-1 opacity-50 group-hover:opacity-100 {{ $sortBy === 'date' ? 'text-blue-600 dark:text-blue-400 opacity-100' : '' }}"></i>
                        </th>
                        <th class="px-6 py-4 font-extrabold text-center">{{ __('admin_tasks.th_status') }}</th>
                        <th class="px-6 py-4 font-extrabold text-right">{{ __('admin_tasks.th_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
                    @forelse($paginatedData as $row)
                    <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $row['user']->name }}</div>
                            <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-0.5">{{ $row['user']->jabatan->nama_jabatan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row['task']->jenis_task == 'upload_file')
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 shadow-sm px-2.5 py-1.5 rounded-lg">{{ __('admin_tasks.badge_file') }}</span>
                            @elseif($row['task']->jenis_task == 'pilihan_ganda')
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-100 dark:border-purple-800/50 shadow-sm px-2.5 py-1.5 rounded-lg">{{ __('admin_tasks.badge_quiz') }}</span>
                            @else
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50 shadow-sm px-2.5 py-1.5 rounded-lg">{{ __('admin_tasks.badge_survey') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4"><span class="font-bold text-gray-700 dark:text-gray-300 truncate block max-w-[220px]">{{ $row['task']->judul }}</span></td>
                        <td class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $row['submitted_at'] ? $row['submitted_at']->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm border {{ $row['badge_color'] }}">{{ $row['status'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="showDetail({{ $row['user']->id }}, {{ $row['task']->id }})" class="p-2 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 border border-transparent hover:border-amber-200 dark:hover:border-amber-800/50 shadow-sm" title="{{ __('admin_tasks.btn_view_detail') }}">
                                <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="search-x" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700 dark:text-gray-300">{{ __('admin_tasks.no_data_title') }}</p>
                                <p class="text-sm mt-1 text-gray-500 dark:text-gray-400 font-medium">{{ __('admin_tasks.no_data_desc') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paginatedData->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/80">
            {{ $paginatedData->links() }}
        </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- MODE: DETAIL HASIL TASK --}}
    {{-- ========================================== --}}
    @elseif($viewMode === 'detail')
    <div class="mb-6 flex justify-between items-start">
        <button wire:click="backToList" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_tasks.back_to_list') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="lg:col-span-1 space-y-6">
            {{-- User Info Card --}}
            <div class="md-panel p-6 sm:p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-16 bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900"></div>
                <div class="relative z-10 w-24 h-24 mx-auto rounded-full bg-white dark:bg-slate-800 border-4 border-white dark:border-slate-800 flex items-center justify-center text-3xl font-black text-blue-600 dark:text-blue-400 shadow-md mt-4">
                    {{ strtoupper(substr($detailData['user']->name, 0, 1)) }}
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mt-4">{{ $detailData['user']->name }}</h3>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">{{ $detailData['user']->jabatan->nama_jabatan ?? '-' }}</p>
                <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700/50 rounded-xl text-left shadow-inner">
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 flex justify-between items-center"><span class="font-bold text-gray-800 dark:text-gray-200">{{ __('admin_tasks.id_badge') }}</span> {{ $detailData['user']->id_badge ?? '-' }}</p>
                    <div class="h-px w-full bg-gray-200 dark:bg-slate-700 my-2"></div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400 flex justify-between items-center"><span class="font-bold text-gray-800 dark:text-gray-200">{{ __('admin_tasks.email') }}</span> {{ $detailData['user']->email }}</p>
                </div>
            </div>

            {{-- Approval Actions for Upload File --}}
            @if($detailData['task']->jenis_task === 'upload_file' && $detailData['detail'])
            <div class="md-panel p-6">
                <h4 class="text-sm font-extrabold text-gray-900 dark:text-white mb-4 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_tasks.approval_action') }}
                </h4>
                @if($detailData['detail']->is_approved)
                <div class="p-5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-center shadow-sm">
                    <i data-lucide="check-circle" class="w-10 h-10 text-emerald-500 dark:text-emerald-400 mx-auto mb-3"></i>
                    <p class="text-sm font-extrabold text-emerald-800 dark:text-emerald-400">{{ __('admin_tasks.file_approved') }}</p>
                    <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-300 mt-1.5 bg-emerald-100 dark:bg-emerald-900/50 py-1 px-2 rounded-lg inline-block">{{ __('admin_tasks.exp_given', ['exp' => $detailData['task']->exp_reward]) }}</p>
                </div>
                @else
                <div class="flex gap-3">
                    <button wire:click="approveUpload({{ $detailData['detail']->id }})" wire:confirm="{{ __('admin_tasks.confirm_approve') }}" class="flex-1 py-2.5 md-btn-success-solid text-sm font-bold flex justify-center items-center gap-1.5"><i data-lucide="check" class="w-4 h-4"></i> {{ __('admin_tasks.btn_approve') }}</button>
                    <button wire:click="rejectUpload({{ $detailData['detail']->id }})" wire:confirm="{{ __('admin_tasks.confirm_reject') }}" class="flex-1 py-2.5 md-btn-danger-solid text-sm font-bold flex justify-center items-center gap-1.5"><i data-lucide="x" class="w-4 h-4"></i> {{ __('admin_tasks.btn_reject') }}</button>
                </div>
                <p class="text-[10px] text-center mt-3 font-medium text-gray-500 dark:text-gray-400">{{ __('admin_tasks.approval_note') }}</p>
                @endif
            </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="md-panel p-6 sm:p-8 min-h-full flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start border-b border-gray-100 dark:border-slate-700/80 pb-5 mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $detailData['task']->judul }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin_tasks.task_status') }} <span class="font-bold text-blue-600 dark:text-blue-400 px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 rounded-md border border-blue-100 dark:border-blue-800/50">{{ $detailData['status'] }}</span></p>
                    </div>
                    <span class="px-3.5 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 font-extrabold rounded-xl text-xs flex items-center gap-1.5 shadow-sm whitespace-nowrap"><i data-lucide="star" class="w-4 h-4 text-amber-500 dark:text-amber-400"></i> {{ $detailData['task']->exp_reward }} EXP</span>
                </div>

                @if(!$detailData['detail'])
                <div class="text-center py-16 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-gray-200 dark:border-slate-700 flex-1 flex flex-col items-center justify-center">
                    <i data-lucide="clock" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4"></i>
                    <p class="text-base font-bold text-gray-600 dark:text-gray-400">{{ __('admin_tasks.not_completed') }}</p>
                </div>
                @else

                {{-- TAMPILAN BERKAS (UPLOAD) --}}
                @if($detailData['task']->jenis_task === 'upload_file')
                <div class="p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-xl border border-blue-200 dark:border-blue-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm shrink-0"><i data-lucide="file-check-2" class="w-7 h-7"></i></div>
                        <div>
                            <p class="text-base font-extrabold text-gray-900 dark:text-white mb-0.5">{{ __('admin_tasks.file_uploaded') }}</p>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $detailData['uploaded_at']->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    <a href="{{ Storage::url($detailData['detail']->file_path) }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 md-btn-primary text-sm font-bold flex items-center justify-center gap-2">
                        <i data-lucide="external-link" class="w-4 h-4"></i> {{ __('admin_tasks.open_file') }}
                    </a>
                </div>

                {{-- TAMPILAN QUIZ --}}
                @elseif($detailData['task']->jenis_task === 'pilihan_ganda')
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 p-6 bg-slate-50 dark:bg-slate-800/80 rounded-2xl border border-gray-100 dark:border-slate-700/50 shadow-inner gap-4">
                    <div class="text-center sm:text-left w-full sm:w-auto">
                        <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('admin_tasks.passing_score') }}</p>
                        <p class="text-4xl font-black {{ $detailData['score'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $detailData['score'] }}%</p>
                    </div>
                    <div class="h-px w-full sm:h-12 sm:w-px bg-gray-200 dark:bg-slate-700"></div>
                    <div class="text-center sm:text-right w-full sm:w-auto">
                        <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('admin_tasks.correct_answers') }}</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-white">{{ $detailData['raw_score'] }} <span class="text-lg text-gray-400 dark:text-gray-500">/ {{ $detailData['total_soal'] }}</span></p>
                    </div>
                </div>

                <h4 class="text-sm font-extrabold text-gray-800 dark:text-white mb-4 uppercase tracking-wider flex items-center gap-2">{{ __('admin_tasks.answer_details') }}</h4>
                <div class="space-y-4">
                    @foreach($detailData['answers'] as $idx => $ans)
                    @php $isCorrect = $ans->jawaban_user === $ans->question->jawaban_benar; @endphp
                    <div class="p-5 rounded-xl border {{ $isCorrect ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 shadow-sm' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 shadow-sm' }}">
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-3 leading-relaxed">{{ $idx + 1 }}. {{ $ans->question->pertanyaan }}</p>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs font-bold">
                            <span class="flex items-center gap-1.5 {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-700 dark:text-red-400' }}">
                                <i data-lucide="{{ $isCorrect ? 'check-circle' : 'x-circle' }}" class="w-4.5 h-4.5"></i>
                                {{ __('admin_tasks.answer') }} {{ strtoupper($ans->jawaban_user) }}
                            </span>
                            @if(!$isCorrect)
                            <span class="text-gray-500 dark:text-gray-300 px-2.5 py-1 bg-white dark:bg-slate-800 rounded-md border border-gray-200 dark:border-slate-700">{{ __('admin_tasks.key_answer') }} {{ strtoupper($ans->question->jawaban_benar) }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- TAMPILAN SURVEY --}}
                @elseif($detailData['task']->jenis_task === 'survey')
                <div class="space-y-5">
                    @foreach($detailData['surveyAnswers'] as $idx => $ans)
                    <div class="p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50 shadow-sm">
                        <p class="text-sm font-extrabold text-gray-900 dark:text-gray-100 mb-3">{{ $idx + 1 }}. {{ $ans->surveyQuestion->pertanyaan }}</p>
                        <div class="p-4 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 text-sm font-medium text-gray-700 dark:text-gray-300 italic shadow-inner">"{{ $ans->jawaban_teks }}"</div>
                    </div>
                    @endforeach
                </div>
                @endif

                @endif
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