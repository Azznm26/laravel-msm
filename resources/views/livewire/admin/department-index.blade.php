<div class="min-h-screen px-4 sm:px-6 py-6 sm:py-8 bg-[#f8fafc] dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors duration-300" style="font-family: 'Inter', sans-serif;">

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

        .md-btn-danger {
            background: var(--danger-tint);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .md-btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.1);
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

        /* ---------- Mobile card row (only visible < sm) ---------- */
        .dept-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
    </style>

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight flex items-center gap-3 text-gray-900 dark:text-white">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                    <i data-lucide="hard-hat" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <span class="leading-tight">{{ __('admin_department.title') }}</span>
            </h1>
            <p class="text-xs sm:text-sm mt-2 ml-0 sm:ml-1 text-gray-500 dark:text-gray-400">{{ __('admin_department.subtitle') }}</p>
        </div>

        {{-- Buttons: stack full-width on mobile, inline on larger screens --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            <button wire:click="create" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 md-btn-primary font-bold text-sm order-1 sm:order-2">
                <i data-lucide="plus" class="w-4.5 h-4.5"></i> {{ __('admin_department.btn_add_dept') }}
            </button>
            <button wire:click="confirmDeleteAll" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 md-btn-danger font-bold text-sm order-2 sm:order-1">
                <i data-lucide="trash-2" class="w-4.5 h-4.5"></i> {{ __('admin_department.btn_delete_all') }}
            </button>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="mb-6">
        <div class="relative w-full sm:max-w-md">
            <i data-lucide="search" class="w-4.5 h-4.5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
            <input type="text"
                wire:model.live.debounce.400ms="search"
                placeholder="{{ __('admin_department.search_placeholder') }}"
                class="w-full pl-11 pr-10 py-3 sm:py-2.5 md-input text-sm font-medium">
            @if(!empty($search))
            <button type="button" wire:click="$set('search', '')"
                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                title="{{ __('admin_department.clear_search') }}">
                <i data-lucide="x" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </button>
            @endif
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
    <div class="mb-6 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 dark:text-emerald-400 shrink-0"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="mb-6 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 dark:text-red-400 shrink-0"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ================= DESKTOP / TABLET: TABLE (hidden on mobile) ================= --}}
    <div class="hidden sm:block md-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs uppercase tracking-wider bg-slate-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-extrabold w-16 text-center">{{ __('admin_department.th_id') }}</th>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_department.th_name') }}</th>
                        <th class="px-6 py-4 font-extrabold text-right">{{ __('admin_department.th_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
                    @forelse($departments as $dept)
                    <tr wire:key="dept-row-{{ $dept->id }}" class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group">
                        <td class="px-6 py-4 text-center font-bold text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">#{{ $dept->id }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-200">{{ $dept->nama_department }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $dept->id }})" class="p-2 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50" title="{{ __('admin_department.tooltip_edit') }}">
                                    <i data-lucide="edit" class="w-4.5 h-4.5"></i>
                                </button>
                                <button x-data @click="$dispatch('open-delete-modal', { id: {{ $dept->id }}, name: '{{ addslashes($dept->nama_department) }}' })" class="p-2 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50" title="{{ __('admin_department.tooltip_delete') }}">
                                    <i data-lucide="trash" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="{{ !empty($search) ? 'search-x' : 'building' }}" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700 dark:text-gray-300">
                                    @if(!empty($search))
                                    {{ __('admin_department.no_data_search', ['search' => $search]) }}
                                    @else
                                    {{ __('admin_department.no_data') }}
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MOBILE: CARD LIST (hidden on sm and up) ================= --}}
    <div class="sm:hidden space-y-3">
        @forelse($departments as $dept)
        <div wire:key="dept-card-{{ $dept->id }}" class="dept-card p-4 flex items-center justify-between gap-3">
            <div class="min-w-0 flex-1">
                <span class="inline-block text-[11px] font-bold text-gray-400 dark:text-gray-500 mb-0.5">#{{ $dept->id }}</span>
                <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate">{{ $dept->nama_department }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button wire:click="edit({{ $dept->id }})" class="p-2.5 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 active:scale-95" title="{{ __('admin_department.tooltip_edit') }}">
                    <i data-lucide="edit" class="w-4.5 h-4.5"></i>
                </button>
                <button x-data @click="$dispatch('open-delete-modal', { id: {{ $dept->id }}, name: '{{ addslashes($dept->nama_department) }}' })" class="p-2.5 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 active:scale-95" title="{{ __('admin_department.tooltip_delete') }}">
                    <i data-lucide="trash" class="w-4.5 h-4.5"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="dept-card px-6 py-14 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-14 h-14 bg-gray-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-3">
                    <i data-lucide="{{ !empty($search) ? 'search-x' : 'building' }}" class="w-7 h-7 text-gray-400 dark:text-gray-500"></i>
                </div>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">
                    @if(!empty($search))
                    {{ __('admin_department.no_data_search', ['search' => $search]) }}
                    @else
                    {{ __('admin_department.no_data') }}
                    @endif
                </p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- MODAL: TAMBAH / EDIT --}}
    <div x-show="$wire.isModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm sm:px-4">

        <div x-show="$wire.isModalOpen"
            @click.outside="$wire.closeModal()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white dark:bg-slate-800 w-full sm:max-w-md overflow-hidden rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 max-h-[90vh] overflow-y-auto">

            <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-base sm:text-lg font-extrabold flex items-center gap-2 text-gray-900 dark:text-white">
                    <i data-lucide="building-2" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    {{ $department_id ? __('admin_department.edit_dept') : __('admin_department.add_dept') }}
                </h3>
            </div>

            <div class="p-5 sm:p-6">
                <div class="space-y-4 mb-6 sm:mb-8">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_department.lbl_name') }}</label>
                        <input type="text" wire:model.live="nama_department" autofocus
                            placeholder="{{ __('admin_department.ph_name') }}"
                            class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900">
                        @error('nama_department') <span class="text-xs font-bold mt-1.5 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3">
                    <button type="button" wire:click="closeModal" class="px-5 py-3 sm:py-2.5 text-sm font-bold md-btn-neutral">
                        {{ __('admin_department.btn_cancel') }}
                    </button>
                    <button type="button" wire:click="store" class="px-5 py-3 sm:py-2.5 text-sm font-bold md-btn-primary flex items-center justify-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">{{ __('admin_department.btn_save') }}</span>
                        <span wire:loading wire:target="store" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('admin_department.saving') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: HAPUS SATU --}}
    {{-- MODAL: HAPUS SATU --}}
    <div x-data="{ open: false, deptId: null, deptName: '' }"
        @open-delete-modal.window="open = true; deptId = $event.detail.id; deptName = $event.detail.name"
        @close-modal.window="open = false" {{-- TAMBAHKAN BARIS INI: Telinga untuk sinyal Livewire --}}
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm sm:px-4">

        <div x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="bg-white dark:bg-slate-800 w-full sm:max-w-md overflow-hidden rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700">

            <div class="p-5 sm:p-6 border-b border-red-50 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/20 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-14 sm:w-14 rounded-full bg-red-100 dark:bg-red-900/50 mb-3 sm:mb-4 shadow-sm border border-red-200 dark:border-red-800/50">
                    <i data-lucide="trash" class="h-5 w-5 sm:h-6 sm:w-6 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white">{{ __('admin_department.del_title') }}</h3>
                <p class="text-sm mt-2 text-gray-500 dark:text-gray-400 font-medium">
                    {{ __('admin_department.del_confirm_prefix') }} <span class="font-bold text-gray-800 dark:text-gray-200" x-text="deptName"></span>? {{ __('admin_department.del_confirm_suffix') }}
                </p>
            </div>

            <div class="p-5 sm:p-6 flex flex-col-reverse sm:flex-row items-center justify-center gap-2 sm:gap-3 bg-white dark:bg-slate-800">
                <button type="button" @click="open = false" class="w-full px-6 py-3 sm:py-2.5 text-sm font-bold md-btn-neutral flex-1">
                    {{ __('admin_department.btn_cancel') }}
                </button>

                {{-- PERBAIKAN: Hapus 'open = false' dari sini, biarkan backend yang menutupnya setelah divalidasi --}}
                <button type="button" @click="$wire.delete(deptId)" class="w-full px-6 py-3 sm:py-2.5 text-sm font-bold md-btn-danger-solid flex-1">
                    {{ __('admin_department.btn_delete') }}
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL: HAPUS SEMUA --}}
    <div x-show="$wire.isDeleteAllModalOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm sm:px-4">

        <div x-show="$wire.isDeleteAllModalOpen"
            @click.outside="$wire.closeDeleteAllModal()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="bg-white dark:bg-slate-800 w-full sm:max-w-md overflow-hidden rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700">

            <div class="p-5 sm:p-6 border-b border-red-50 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/20 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-14 sm:w-14 rounded-full bg-red-100 dark:bg-red-900/50 mb-3 sm:mb-4 shadow-sm border border-red-200 dark:border-red-800/50">
                    <i data-lucide="alert-triangle" class="h-6 w-6 sm:h-7 sm:w-7 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white">{{ __('admin_department.del_all_title') }}</h3>
                <p class="text-sm mt-2 text-gray-500 dark:text-gray-400 font-medium">{{ __('admin_department.del_all_desc') }}</p>
            </div>

            <div class="p-5 sm:p-6">
                <div class="mb-6 sm:mb-8">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_department.lbl_admin_password') }}</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-gray-400 dark:text-gray-500"></i>
                        <input type="password" wire:model="password"
                            placeholder="{{ __('admin_department.ph_admin_password') }}"
                            class="w-full pl-10 pr-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900">
                    </div>
                    @error('password') <span class="text-xs font-bold mt-1.5 block text-red-500 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3">
                    <button type="button" wire:click="closeDeleteAllModal" class="px-5 py-3 sm:py-2.5 text-sm font-bold md-btn-neutral">
                        {{ __('admin_department.btn_cancel') }}
                    </button>
                    <button type="button" wire:click="executeDeleteAll" class="px-5 py-3 sm:py-2.5 text-sm font-bold md-btn-danger-solid flex items-center justify-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="executeDeleteAll">{{ __('admin_department.btn_exec_delete') }}</span>
                        <span wire:loading wire:target="executeDeleteAll" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('admin_department.verifying') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    </script>
</div>