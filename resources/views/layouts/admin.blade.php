<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function applyDarkMode() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        applyDarkMode();

        document.addEventListener('livewire:navigated', () => {
            applyDarkMode();
        });
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        html.dark body {
            background: #0f172a;
            color: #e2e8f0;
        }

        .font-display {
            font-family: 'Manrope', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        html.dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>

    @livewireStyles
</head>

<body x-data="{
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen')) ?? true,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        }
    }" x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarOpen', JSON.stringify(value)))"
    class="bg-[#f8fafc] dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300">

    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        <x-layout.navbar class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-100 dark:border-slate-700 text-slate-800 dark:text-slate-100 z-30 relative" />

        <div class="flex flex-1 overflow-hidden">

            <x-layout.sidebar>
                @unlessrole('super_admin')
                <a href="{{ route('user.dashboard') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 mb-3 pb-3 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                    <span class="font-bold text-sm">Kembali ke Portal User</span>
                </a>
                @endunlessrole {{-- Dashboard Link (Terbuka untuk semua yang lolos AdminAccessMiddleware) --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                {{-- Notifikasi Link --}}
                <a href="{{ route('notifications') }}" wire:navigate
                    class="flex items-center justify-between p-3 rounded-xl transition-all duration-200 mt-1 {{ request()->routeIs('notifications') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="bell" class="h-5 w-5"></i>
                        <span class="font-bold text-sm">Notifikasi</span>
                    </div>

                    @auth
                    <livewire:notification-badge />
                    @endauth
                </a>

                {{-- ========================================== --}}
                {{-- MASTER DATA ACCORDION --}}
                {{-- Accordion-nya muncul kalau user punya SALAH SATU dari
                     permission terkait (manage-users, manage-departments,
                     manage-tasks, manage-career-path). Tiap link di dalam
                     dicek permission-nya masing-masing, jadi kalau user
                     cuma punya sebagian permission, cuma link itu yang
                     muncul — bukan semua-atau-tidak-sama-sekali. --}}
                {{-- ========================================== --}}
                @canany(['manage-users', 'manage-departments', 'manage-tasks', 'manage-career-path'])
                <div x-data="{ open: true }" class="mt-3">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full p-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200 font-medium">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="database" class="h-5 w-5 text-slate-400 dark:text-slate-500"></i>
                            <span class="font-bold text-sm">Master Data</span>
                        </div>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'"
                            class="w-4 h-4 transition-transform duration-200"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 pr-2 mt-1.5 space-y-1">
                        @can('manage-users')
                        <a href="{{ route('admin.approval.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.approval.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="user-check" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Approval User</span>
                        </a>

                        <a href="{{ route('admin.manage-user.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.manage-user.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="users" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage User</span>
                        </a>
                        @endcan

                        @can('manage-departments')
                        <a href="{{ route('admin.departments.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.departments.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="hard-hat" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage Department</span>
                        </a>

                        <a href="{{ route('admin.jabatan.indexDepartments') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.jabatan.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="briefcase" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage Jabatan</span>
                        </a>
                        @endcan

                        @can('manage-tasks')
                        <a href="{{ route('admin.task.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.task.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="list-checks" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage Task</span>
                        </a>
                        @endcan

                        @can('manage-career-path')
                        <a href="{{ route('admin.career-path.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.career-path.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="git-fork" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Career Path</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @endcanany


                {{-- ========================================== --}}
                {{-- RBAC / MANAJEMEN AKSES ACCORDION --}}
                {{-- Permission 'manage-rbac' — defaultnya cuma dicentang
                     ke role super_admin lewat halaman Manage Roles, tapi
                     bisa dikasih ke role lain kapan saja lewat UI, tanpa
                     ubah kode ini lagi. --}}
                {{-- ========================================== --}}
                @can('manage-rbac')
                <div x-data="{ open: true }" class="mt-3">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full p-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200 font-medium">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="shield-check" class="h-5 w-5 text-slate-400 dark:text-slate-500"></i>
                            <span class="font-bold text-sm">Manajemen Akses</span>
                        </div>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'"
                            class="w-4 h-4 transition-transform duration-200"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 pr-2 mt-1.5 space-y-1">
                        <a href="{{ route('admin.roles.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="tags" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage Roles</span>
                        </a>

                        <a href="{{ route('admin.permissions.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.permissions.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="key-round" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Manage Permissions</span>
                        </a>

                        <a href="{{ route('admin.user-roles.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.user-roles.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="user-cog" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Assign Role ke User</span>
                        </a>
                    </div>
                </div>
                @endcan


                {{-- ========================================== --}}
                {{-- MONITORING ACCORDION --}}
                {{-- (Sudah benar dari awal: pakai permission 'view-reports') --}}
                {{-- ========================================== --}}
                @can('view-reports')
                <div x-data="{ open: true }" class="mt-3">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full p-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors duration-200 font-medium">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="activity" class="h-5 w-5 text-slate-400 dark:text-slate-500"></i>
                            <span class="font-bold text-sm">Monitoring</span>
                        </div>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'"
                            class="w-4 h-4 transition-transform duration-200"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 pr-2 mt-1.5 space-y-1">
                        <a href="{{ route('admin.career-monitoring.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.career-monitoring.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="line-chart" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Career Monitoring</span>
                        </a>

                        <a href="{{ route('admin.task-monitoring.index') }}"
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.task-monitoring.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="file-check-2" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Task Monitoring</span>
                        </a>

                        <a href="{{ route('admin.reports.index') }}" wire:navigate
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Laporan Nilai</span>
                        </a>

                        <a href="{{ route('admin.competency.index') }}" wire:navigate
                            class="flex items-center space-x-3 p-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.competency.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 font-medium' }}">
                            <i data-lucide="target" class="h-4 w-4"></i>
                            <span class="text-sm font-bold">Kelola Gap Analysis</span>
                        </a>
                    </div>
                </div>
                @endcan

            </x-layout.sidebar>

            {{-- Sidebar Overlay untuk Mobile --}}
            <div x-show="sidebarOpen" @click="toggleSidebar()"
                class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/60 backdrop-blur-sm z-20 lg:hidden"
                x-transition:enter="transition-opacity ease-in-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in-out duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            <main class="flex-1 overflow-y-auto transition-all duration-300"
                :class="sidebarOpen ? 'ml-0 lg:ml-64' : 'ml-0'">

                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    @isset($slot)
                    {{ $slot }}
                    @endisset

                    @yield('content')
                </div>

            </main>
        </div>

        <x-layout.footer />
    </div>

    @stack('scripts')

    {{-- Global Alert (Slightly adjusted colors for Material layout) --}}
    <div x-data="globalAlert()" x-show="visible" x-transition.opacity.duration.300ms x-cloak
        class="fixed top-5 right-5 z-50 flex items-center space-x-3 px-4 py-3.5 rounded-xl border shadow-lg text-sm font-semibold transition-all"
        :class="{
            'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-700/50 text-emerald-800 dark:text-emerald-300': type === 'success',
            'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700/50 text-red-800 dark:text-red-300': type === 'error',
            'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-700/50 text-amber-800 dark:text-amber-300': type === 'warning',
            'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700/50 text-blue-800 dark:text-blue-300': type === 'info'
        }">
        <template x-if="type === 'success'">
            <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400"></i>
        </template>

        <template x-if="type === 'error'">
            <i data-lucide="x-circle" class="h-5 w-5 flex-shrink-0 text-red-500 dark:text-red-400"></i>
        </template>

        <template x-if="type === 'warning'">
            <i data-lucide="alert-triangle" class="h-5 w-5 flex-shrink-0 text-amber-500 dark:text-amber-400"></i>
        </template>

        <template x-if="type === 'info'">
            <i data-lucide="info" class="h-5 w-5 flex-shrink-0 text-blue-500 dark:text-blue-400"></i>
        </template>

        <span x-text="message" class="font-medium"></span>
    </div>

    {{-- Toast Center Manager --}}
    <div x-data="toastCenterManager()"
        class="fixed inset-0 flex flex-col items-center justify-center space-y-4 z-[9999] pointer-events-none" x-cloak>
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                x-transition:leave-end="scale-95 opacity-0 translate-y-4"
                class="flex items-center space-x-4 px-6 py-4 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-gray-100 dark:border-slate-700 pointer-events-auto">

                <template x-if="toast.type === 'success'">
                    <div class="p-2.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <i data-lucide="check" class="h-6 w-6"></i>
                    </div>
                </template>

                <template x-if="toast.type === 'error'">
                    <div class="p-2.5 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </div>
                </template>

                <template x-if="toast.type === 'warning'">
                    <div class="p-2.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                    </div>
                </template>

                <template x-if="toast.type === 'info'">
                    <div class="p-2.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <i data-lucide="info" class="h-6 w-6"></i>
                    </div>
                </template>

                <div class="text-center sm:text-left px-2">
                    <p class="font-extrabold text-gray-900 dark:text-gray-100 text-lg" x-text="toast.title"></p>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-0.5" x-text="toast.message"></p>
                </div>

                <button @click="removeToast(toast.id)"
                    class="ml-2 p-1 text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors focus:outline-none">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        function scrollActiveMenuIntoView() {
            const activeLink = document.querySelector('aside nav a.bg-blue-600');
            if (activeLink) {
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        document.addEventListener('DOMContentLoaded', scrollActiveMenuIntoView);
        document.addEventListener('livewire:navigated', scrollActiveMenuIntoView);

        function globalAlert() {
            return {
                visible: false,
                message: '',
                type: 'info',
                showAlert(message, type = 'info') {
                    this.message = message;
                    this.type = type;
                    this.visible = true;
                    setTimeout(() => this.visible = false, 4000);
                }
            }
        }

        function toastCenterManager() {
            return {
                toasts: [],
                addToast(title, message, type = 'info') {
                    const id = Date.now();
                    this.toasts.push({
                        id,
                        title,
                        message,
                        type,
                        visible: true
                    });
                    setTimeout(() => this.removeToast(id), 4000);
                },
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => this.toasts.splice(index, 1), 300);
                    }
                }
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
            setTimeout(() => {
                const manager = document.querySelector('[x-data="toastCenterManager()"]');
                if (manager && manager._x_dataStack) {
                    manager._x_dataStack[0].addToast('{{ ucfirst($type) }}', "{{ session($type) }}", '{{ $type }}');
                }
            }, 200);
            @endif
            @endforeach
        });
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('deleteModal', () => ({
                show: false,
                deleteUrl: '',
                openDeleteModal(id) {
                    this.deleteUrl = typeof id === 'string' ? id : `/admin/departments/${id}`;
                    this.show = true;
                }
            }));
        });
    </script>

    @livewireScripts

</body>

</html>