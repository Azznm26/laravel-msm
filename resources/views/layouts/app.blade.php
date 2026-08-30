<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>@yield('title', 'Portal Karyawan') - BluePath Mining</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Plugin Alpine Collapse -->
    <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            if (typeof Collapse !== 'undefined') {
                Alpine.plugin(Collapse);
            } else {
                console.warn('Alpine Collapse plugin gagal dimuat — fitur collapse sidebar dinonaktifkan sementara.');
            }
        });
    </script>

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

    <script src="https://unpkg.com/lucide@latest"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        html.dark body {
            background: #0f172a;
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

        /* Cegah body scroll saat sidebar mobile terbuka */
        body.sidebar-locked {
            overflow: hidden;
        }

        @media (min-width: 1024px) {
            body.sidebar-locked {
                overflow: auto;
            }
        }
    </style>

    @livewireStyles
</head>

{{-- Tambahkan @livewire:navigated.window="closeOnMobile()" agar sidebar tertutup otomatis setelah klik menu di HP --}}

<body x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeOnMobile() {
            if(window.innerWidth < 1024) {
                this.sidebarOpen = false;
            }
        }
    }"
    x-init="$watch('sidebarOpen', value => {
        document.body.classList.toggle('sidebar-locked', value && window.innerWidth < 1024);
    })"
    @@livewire:navigated.window="closeOnMobile()"
    @@resize.window="if (window.innerWidth >= 1024) { document.body.classList.remove('sidebar-locked'); }"
    class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-100 selection:text-blue-900 dark:selection:bg-blue-900 dark:selection:text-blue-100 transition-colors duration-300">

    <div class="min-h-screen flex flex-col">

        {{-- Top Navbar (sticky agar tombol hamburger selalu terjangkau saat scroll di HP) --}}
        <x-layout.navbar class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm text-gray-800 dark:text-gray-100" />

        <div class="flex flex-1 relative">

            {{-- BACKDROP SIDEBAR MOBILE --}}
            <div x-show="sidebarOpen"
                @click="toggleSidebar()"
                x-cloak
                class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/60 backdrop-blur-sm z-20 lg:hidden"
                x-transition:enter="transition-opacity ease-in-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in-out duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            {{-- SIDEBAR --}}
            {{-- Catatan: komponen x-layout.sidebar perlu class dasar seperti
                 "fixed inset-y-0 left-0 top-16 z-30 w-64 -translate-x-full lg:translate-x-0
                 transition-transform duration-300 overflow-y-auto"
                 dengan :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                 agar animasi slide-in/out di HP berjalan mulus. Kirim isi file
                 komponennya kalau mau saya sesuaikan juga. --}}
            <x-layout.sidebar>
                <a href="{{ route('user.dashboard') }}" wire:navigate @click="closeOnMobile()"
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                    <i data-lucide="home" class="h-5 w-5"></i>
                    <span class="font-bold text-sm">Beranda</span>
                </a>

                @canany(['manage-users', 'manage-departments', 'manage-tasks', 'manage-career-path', 'view-reports', 'view-history', 'manage-rbac'])
                <a href="{{ route('admin.dashboard') }}" wire:navigate @click="closeOnMobile()"
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 mt-3 border-t border-slate-200 dark:border-slate-700 pt-4">
                    <i data-lucide="shield" class="h-5 w-5"></i>
                    <span class="font-bold text-sm">Panel Admin</span>
                </a>
                @endcanany

                <div x-data="{ open: {{ request()->routeIs('user.career-path.*') || request()->routeIs('user.tasks.*') ? 'true' : 'false' }} }" class="mt-3">
                    <button @click.prevent.stop="open = !open"
                        class="flex items-center justify-between w-full p-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 transition-all duration-200 focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="trending-up" class="h-5 w-5 opacity-70"></i>
                            <span class="font-bold text-sm">Karir & Tugas</span>
                        </div>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'" class="w-4.5 h-4.5 transition-transform duration-300"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 pr-2 mt-1 space-y-1">
                        <a href="{{ route('user.career-path.index') }}" wire:navigate @click="closeOnMobile()"
                            class="flex items-center space-x-3 p-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('user.career-path.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 font-semibold' }}">
                            <i data-lucide="map" class="h-4.5 w-4.5"></i>
                            <span class="text-sm">Jalur Karir Saya</span>
                        </a>

                        <a href="{{ route('user.tasks.index') }}" wire:navigate @click="closeOnMobile()"
                            class="flex items-center space-x-3 p-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('user.tasks.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 font-semibold' }}">
                            <i data-lucide="clipboard-check" class="h-4.5 w-4.5"></i>
                            <span class="text-sm">Daftar Tugas</span>
                        </a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('user.help.*') ? 'true' : 'false' }} }" class="mt-3">
                    <button @click.prevent.stop="open = !open"
                        class="flex items-center justify-between w-full p-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-blue-700 dark:hover:text-blue-400 transition-all duration-200 focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="life-buoy" class="h-5 w-5 opacity-70"></i>
                            <span class="font-bold text-sm">{{ __('help.menu.label') }}</span>
                        </div>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'" class="w-4.5 h-4.5 transition-transform duration-300"></i>
                    </button>

                    <div x-show="open" x-collapse class="pl-4 pr-2 mt-1 space-y-1">
                        <a href="{{ route('user.help.guide') }}" wire:navigate @click="closeOnMobile()"
                            class="flex items-center space-x-3 p-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('user.help.guide') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 font-semibold' }}">
                            <i data-lucide="book-open" class="h-4.5 w-4.5"></i>
                            <span class="text-sm">{{ __('help.menu.guide') }}</span>
                        </a>

                        <a href="{{ route('user.help.sop') }}" wire:navigate @click="closeOnMobile()"
                            class="flex items-center space-x-3 p-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('user.help.sop') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 font-semibold' }}">
                            <i data-lucide="file-text" class="h-4.5 w-4.5"></i>
                            <span class="text-sm">{{ __('help.menu.sop') }}</span>
                        </a>

                        <a href="{{ route('user.help.support') }}" wire:navigate @click="closeOnMobile()"
                            class="flex items-center space-x-3 p-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('user.help.support') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-extrabold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 font-semibold' }}">
                            <i data-lucide="headphones" class="h-4.5 w-4.5"></i>
                            <span class="text-sm">{{ __('help.menu.support') }}</span>
                        </a>
                    </div>
                </div>

            </x-layout.sidebar>

            <main class="flex-1 py-4 sm:py-6 px-3 sm:px-6 lg:px-8 w-full min-w-0 transition-all duration-300"
                :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">

                {{-- Mini Profile Card untuk Mobile --}}
                <div class="lg:hidden mb-4 sm:mb-6 flex items-center justify-between bg-white dark:bg-slate-800 p-3.5 sm:p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 shadow-inner shrink-0">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-extrabold uppercase tracking-wider">Selamat datang,</p>
                            <p class="text-base font-black text-gray-900 dark:text-gray-100 truncate">{{ explode(' ', auth()->user()->name)[0] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Slot untuk Komponen Livewire --}}
                @isset($slot)
                {{ $slot }}
                @endisset

                {{-- Yield untuk Komponen Blade Konvensional --}}
                @yield('content')

            </main>
        </div>

        <x-layout.footer />
    </div>

    @stack('scripts')

    {{-- Alert Global --}}
    <div x-data="globalAlert()" x-show="visible" x-transition.opacity.duration.300ms x-cloak
        class="fixed top-4 right-4 left-4 sm:top-6 sm:right-6 sm:left-auto z-50 flex items-center space-x-3 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl border text-sm font-bold shadow-lg"
        :class="{
                'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-700/50 text-emerald-800 dark:text-emerald-300': type === 'success',
                'bg-rose-50 dark:bg-rose-900/30 border-rose-200 dark:border-rose-700/50 text-rose-800 dark:text-rose-300': type === 'error',
                'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-700/50 text-amber-800 dark:text-amber-300': type === 'warning',
                'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700/50 text-blue-800 dark:text-blue-300': type === 'info'
            }">
        <template x-if="type === 'success'"><i data-lucide="check-circle-2" class="h-5 w-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400"></i></template>
        <template x-if="type === 'error'"><i data-lucide="alert-octagon" class="h-5 w-5 flex-shrink-0 text-rose-600 dark:text-rose-400"></i></template>
        <template x-if="type === 'warning'"><i data-lucide="alert-triangle" class="h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400"></i></template>
        <template x-if="type === 'info'"><i data-lucide="info" class="h-5 w-5 flex-shrink-0 text-blue-600 dark:text-blue-400"></i></template>
        <span x-text="message"></span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

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
    </script>

    @isset($approvalNotification)
    @include('components.layout.approval-notification-modal', [
    'approvalNotification' => $approvalNotification,
    'cacheKey' => $cacheKey ?? null
    ])
    @endisset

    @livewireScripts

</body>

</html>