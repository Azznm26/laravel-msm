<div
    x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }"
    class="min-h-screen px-4 sm:px-6 py-6 sm:py-8 bg-[#f8fafc] dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors duration-300"
    style="font-family: 'Inter', sans-serif;">

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

        /* ---------- Mobile card row for user list (only < sm) ---------- */
        .usr-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
    </style>

    {{-- FLASH MESSAGES --}}
    @if (session()->has('success'))
    <div class="mb-6 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-400">
        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="mb-6 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl text-sm flex items-center gap-3 shadow-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-800 dark:text-red-400">
        <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 text-red-500 dark:text-red-400"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- VIEW 1: DAFTAR DEPARTMENT --}}
    {{-- ======================================================== --}}
    @if($viewMode === 'departments')
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                <i data-lucide="users" class="w-5 h-5 sm:w-6 sm:h-6"></i>
            </div>
            <span class="leading-tight">{{ __('admin_manage_users.title_manage_users') }}</span>
        </h1>
        <p class="text-xs sm:text-sm mt-2 ml-0 sm:ml-1 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.subtitle_departments') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @forelse($departments as $dept)
        <div wire:click="viewUsers({{ $dept->id }}, '{{ addslashes($dept->nama_department) }}')"
            class="relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 sm:p-6 cursor-pointer active:scale-[0.98] group overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1.5 hover:border-blue-300 dark:hover:border-blue-500">

            <!-- Hover Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

            <!-- Top Accent Line (Selalu Muncul) -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600 dark:bg-blue-500"></div>

            <div class="relative z-10 pt-1">
                <div class="flex items-start justify-between mb-4 sm:mb-5 gap-2">
                    <!-- Icon -->
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm border border-slate-100 dark:border-slate-700/50 group-hover:border-blue-600 dark:group-hover:border-blue-500 group-hover:shadow-md shrink-0">
                        <i data-lucide="building-2" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </div>

                    <!-- Badge -->
                    <span class="px-2.5 sm:px-3 py-1 sm:py-1.5 text-[10px] sm:text-[11px] font-extrabold rounded-full bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors duration-300 shadow-sm whitespace-nowrap">
                        {{ $dept->users_count }} {{ __('admin_manage_users.badge_active') }}
                    </span>
                </div>

                <!-- Title -->
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-800 dark:text-slate-100 line-clamp-2 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors duration-300">
                    {{ $dept->nama_department }}
                </h3>

                <!-- Action Text -->
                <div class="mt-3 sm:mt-4 flex items-center text-sm font-bold text-slate-400 dark:text-slate-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                    <span>{{ __('admin_manage_users.view_employees') }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2 transform group-hover:translate-x-1.5 transition-transform duration-300 ease-out"></i>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full md-panel p-8 sm:p-12 text-center flex flex-col items-center justify-center border-dashed border-slate-200 dark:border-slate-700">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="inbox" class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-500"></i>
            </div>
            <h3 class="text-base sm:text-lg font-extrabold text-gray-800 dark:text-white">{{ __('admin_manage_users.empty_dept_title') }}</h3>
            <p class="text-sm mt-1.5 font-medium text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.empty_dept_desc') }}</p>
        </div>
        @endforelse
    </div>

    {{-- ======================================================== --}}
    {{-- VIEW 2: DAFTAR USER BERDASARKAN DEPT --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'users')
    <div class="mb-6 sm:mb-8">
        <button wire:click="backToDepartments" class="flex items-center text-sm font-bold mb-3 sm:mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_manage_users.back_to_dept') }}
        </button>
        <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                <i data-lucide="users" class="w-5 h-5 sm:w-6 sm:h-6"></i>
            </div>
            <span class="leading-tight">{{ __('admin_manage_users.employees') }}: {{ $selectedDepartmentName }}</span>
        </h1>
    </div>

    {{-- DESKTOP / TABLET: TABEL (hidden on mobile) --}}
    <div class="hidden sm:block md-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="text-xs uppercase tracking-wider bg-slate-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_manage_users.th_employee') }}</th>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_manage_users.th_position') }}</th>
                        <th class="px-6 py-4 font-extrabold">{{ __('admin_manage_users.th_primary_path') }}</th>
                        <th class="px-6 py-4 font-extrabold text-right">{{ __('admin_manage_users.th_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80 bg-white dark:bg-transparent">
                    @forelse($users as $usr)
                    <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/20 transition-colors group" wire:key="usr-row-{{ $usr->id }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $usr->name }}</div>
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $usr->email }}</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">{{ $usr->jabatan->nama_jabatan ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">{{ $usr->careerPath->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="editUser({{ $usr->id }})" class="p-2 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50" title="{{ __('admin_manage_users.tooltip_edit') }}">
                                    <i data-lucide="edit" class="w-4.5 h-4.5"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="deleteId = {{ $usr->id }}; deleteName = '{{ addslashes($usr->name) }}'; showDeleteModal = true"
                                    class="p-2 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50"
                                    title="{{ __('admin_manage_users.tooltip_delete') }}">
                                    <i data-lucide="trash" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="users" class="w-8 h-8 text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-base font-bold text-gray-700 dark:text-gray-300">{{ __('admin_manage_users.empty_users') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE: CARD LIST (hidden on sm and up) --}}
    <div class="sm:hidden space-y-3">
        @forelse($users as $usr)
        <div wire:key="usr-card-{{ $usr->id }}" class="usr-card p-4">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-gray-800 dark:text-gray-100 text-sm truncate">{{ $usr->name }}</p>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ $usr->email }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button wire:click="editUser({{ $usr->id }})" class="p-2.5 md-icon-btn bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 active:scale-95" title="{{ __('admin_manage_users.tooltip_edit') }}">
                        <i data-lucide="edit" class="w-4.5 h-4.5"></i>
                    </button>
                    <button
                        type="button"
                        @click="deleteId = {{ $usr->id }}; deleteName = '{{ addslashes($usr->name) }}'; showDeleteModal = true"
                        class="p-2.5 md-icon-btn bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 active:scale-95"
                        title="{{ __('admin_manage_users.tooltip_delete') }}">
                        <i data-lucide="trash" class="w-4.5 h-4.5"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ __('admin_manage_users.th_position') }}</span>
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $usr->jabatan->nama_jabatan ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-0.5">{{ __('admin_manage_users.th_primary_path') }}</span>
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">{{ $usr->careerPath->name ?? '-' }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="usr-card px-6 py-14 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-14 h-14 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                    <i data-lucide="users" class="w-7 h-7 text-gray-400 dark:text-gray-500"></i>
                </div>
                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('admin_manage_users.empty_users') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ======================================================== --}}
    {{-- VIEW 3: FORM EDIT USER --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'edit')
    <div class="mb-6 sm:mb-8">
        <button wire:click="backToUsers" class="flex items-center text-sm font-bold mb-3 sm:mb-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i> {{ __('admin_manage_users.back_to_users') }}
        </button>
        <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                <i data-lucide="user-cog" class="w-5 h-5 sm:w-6 sm:h-6"></i>
            </div>
            <span class="leading-tight break-words">{{ __('admin_manage_users.edit_user') }}: {{ $name }}</span>
        </h1>
    </div>

    <div class="md-panel overflow-hidden">
        <form wire:submit.prevent="updateUser" class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

                {{-- Kiri: Informasi Profil & Struktur --}}
                <div class="space-y-5 sm:space-y-6">
                    <h3 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/80 pb-3 flex items-center gap-2">
                        <i data-lucide="contact" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_manage_users.profile_info') }}
                    </h3>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_full_name') }}</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900" required>
                        @error('name') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_email') }}</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900" required>
                            @error('email') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_id_badge') }}</label>
                            <input type="text" wire:model="id_badge" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900">
                            @error('id_badge') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_password') }}</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900" placeholder="{{ __('admin_manage_users.ph_password') }}">
                        @error('password') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    <h3 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/80 pb-3 pt-2 sm:pt-4 flex items-center gap-2">
                        <i data-lucide="network" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_manage_users.org_structure') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_department') }}</label>
                            <select wire:model.live="department_id" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900" required>
                                <option value="">{{ __('admin_manage_users.opt_select') }}</option>
                                @foreach($deptWithPaths as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_department }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-500 dark:text-gray-400">{{ __('admin_manage_users.lbl_position') }}</label>
                            <select wire:model="jabatan_id" class="w-full px-4 py-3 md-input text-sm font-medium bg-white dark:bg-slate-900 disabled:bg-gray-100 disabled:text-gray-400 dark:disabled:bg-slate-800 dark:disabled:text-slate-500" required @disabled(!$department_id)>
                                <option value="">{{ __('admin_manage_users.opt_select') }}</option>
                                @foreach($jabatans as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('jabatan_id') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Kanan: Penugasan Career Path --}}
                <div class="space-y-5 sm:space-y-6">
                    <h3 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700/80 pb-3 flex items-center gap-2">
                        <i data-lucide="git-fork" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i> {{ __('admin_manage_users.career_path_assignment') }}
                    </h3>

                    <div class="p-4 sm:p-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl shadow-sm">
                        <label class="block text-xs font-extrabold text-blue-800 dark:text-blue-300 uppercase tracking-wider mb-1.5">{{ __('admin_manage_users.lbl_primary_path') }}</label>
                        <p class="text-[11px] font-medium text-blue-600 dark:text-blue-400 mb-4">{{ __('admin_manage_users.desc_primary_path') }}</p>
                        <select wire:model="primary_career_path_id" class="w-full px-4 py-3 md-input bg-white dark:bg-slate-900 border-blue-200 dark:border-blue-700/50 text-sm font-medium" required>
                            <option value="">{{ __('admin_manage_users.opt_primary_path') }}</option>
                            @foreach($allCareerPaths as $path)
                            <option value="{{ $path->id }}">{{ $path->name }}</option>
                            @endforeach
                        </select>
                        @error('primary_career_path_id') <span class="text-xs font-bold text-red-500 dark:text-red-400 mt-1.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5 text-gray-700 dark:text-gray-300">{{ __('admin_manage_users.lbl_secondary_paths') }}</label>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mb-3">{{ __('admin_manage_users.desc_secondary_paths') }}</p>
                        <select wire:model="secondary_career_path_ids" multiple class="w-full px-4 py-3 md-input bg-white dark:bg-slate-900 text-sm font-medium h-40 sm:h-48">
                            @foreach($deptWithPaths as $dept)
                            <optgroup label="{{ $dept->nama_department }}">
                                @foreach($dept->careerPaths as $path)
                                <option value="{{ $path->id }}">{{ $path->name }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-8 flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-5 sm:pt-6 border-t border-gray-100 dark:border-slate-700/80">
                <button type="button" wire:click="backToUsers" class="w-full sm:w-auto px-6 py-3 text-sm font-bold md-btn-neutral">
                    {{ __('admin_manage_users.btn_cancel') }}
                </button>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 text-sm font-bold md-btn-primary flex items-center justify-center gap-2" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateUser">{{ __('admin_manage_users.btn_save_changes') }}</span>
                    <span wire:loading wire:target="updateUser" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('admin_manage_users.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- MODAL: KONFIRMASI HAPUS KARYAWAN --}}
    {{-- ======================================================== --}}
    <div
        x-show="showDeleteModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:px-4 bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm"
        style="display: none;">

        <div class="absolute inset-0" @click="showDeleteModal = false"></div>

        <div
            x-show="showDeleteModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative bg-white dark:bg-slate-800 rounded-t-2xl sm:rounded-2xl w-full sm:max-w-sm overflow-hidden shadow-xl border border-gray-100 dark:border-slate-700"
            @click.stop>

            <div class="p-5 sm:p-6 border-b border-red-50 dark:border-red-900/50 bg-red-50/50 dark:bg-red-900/20 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 sm:h-14 sm:w-14 rounded-full bg-red-100 dark:bg-red-900/50 mb-3 sm:mb-4 shadow-sm border border-red-200 dark:border-red-800/50">
                    <i data-lucide="trash-2" class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white">{{ __('admin_manage_users.modal_del_title') }}</h3>
                <p class="text-sm mt-2 text-gray-500 dark:text-gray-400 font-medium">
                    {{ __('admin_manage_users.modal_del_confirm') }} <span class="font-bold text-gray-800 dark:text-gray-200" x-text="deleteName"></span>?
                    {{ __('admin_manage_users.modal_del_irreversible') }}
                </p>
            </div>

            <div class="p-5 sm:p-6 flex flex-col-reverse sm:flex-row justify-center gap-2 sm:gap-3 bg-white dark:bg-slate-800">
                <button
                    type="button"
                    @click="showDeleteModal = false"
                    class="w-full px-6 py-3 sm:py-2.5 text-sm font-bold md-btn-neutral flex-1">
                    {{ __('admin_manage_users.btn_cancel') }}
                </button>
                <button
                    type="button"
                    @click="$wire.deleteUser(deleteId); showDeleteModal = false"
                    class="w-full px-6 py-3 sm:py-2.5 text-sm font-bold md-btn-danger-solid flex-1">
                    {{ __('admin_manage_users.btn_delete') }}
                </button>
            </div>
        </div>
    </div>

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