<nav class="sticky top-0 z-40 w-full border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm transition-all duration-300 ease-in-out"
    :class="sidebarOpen ? 'lg:pl-64' : 'pl-0 sm:pl-16'">
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20 w-full gap-2">

            {{-- Bagian Kiri: Tombol Menu (Mobile) & Brand --}}
            <div class="flex items-center gap-4 sm:gap-5 transition-all duration-300 flex-1 min-w-0">

                {{-- Tombol Toggle Sidebar Khusus Mobile --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden flex-shrink-0 p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/40 transition-colors">
                    <i data-lucide="menu" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </button>

                {{-- Logo & Brand --}}
                <div class="flex items-center gap-2 sm:gap-4 transition-all duration-300 min-w-0 flex-1"
                    :class="sidebarOpen ? 'lg:hidden' : 'flex'">
                    <div class="flex-shrink-0 p-1 sm:p-1.5 bg-gradient-to-tr from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 rounded-xl sm:rounded-2xl shadow-sm border border-blue-200 dark:border-blue-800/50">
                        <div class="bg-white dark:bg-slate-800 p-1 sm:p-1.5 rounded-lg sm:rounded-xl flex-shrink-0">
                            <img src="{{ asset('images/logo-survey.png') }}" alt="Logo" class="h-6 w-6 sm:h-8 sm:w-8 object-contain">
                        </div>
                    </div>
                    {{-- Font & tracking dikecilkan di HP supaya "Individual Dev Plan" (baris terpanjang) tetap muat utuh sebaris, tidak terpotong "..." --}}
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-gray-900 dark:text-white text-[11px] sm:text-lg font-extrabold tracking-tight sm:tracking-widest uppercase whitespace-nowrap">{{ __('app.career_path') }}</span>
                        <span class="text-[7px] sm:text-[10px] text-blue-600 dark:text-blue-400 font-extrabold uppercase tracking-tight sm:tracking-[0.2em] whitespace-nowrap">{{ __('app.individual_dev_plan') }}</span>
                    </div>
                </div>
            </div>

            {{-- Grup Kanan: Bahasa + Tema + Notifikasi + Profil --}}
            <div class="flex items-center gap-1 sm:gap-2 md:gap-4 flex-shrink-0">

                {{-- Komponen Switch Bahasa --}}
                <div class="hidden sm:block">
                    <livewire:language-switcher />
                </div>

                {{-- Dark Mode Toggle Button --}}
                <button
                    x-data="{
                        isDark: false,
                        init() {
                            this.isDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                            this.applyTheme();
                        },
                        applyTheme() {
                            if (this.isDark) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        },
                        toggleTheme() {
                            this.isDark = !this.isDark;
                            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                            this.applyTheme();
                        }
                    }"
                    @click="toggleTheme()"
                    class="flex-shrink-0 p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    title="Toggle Theme">

                    <div x-show="isDark" style="display: none;">
                        <i data-lucide="sun" class="w-5 h-5 text-amber-500"></i>
                    </div>
                    <div x-show="!isDark">
                        <i data-lucide="moon" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                </button>

                <livewire:notification-bell />

                {{-- User Profile Dropdown --}}
                <div class="relative flex-shrink-0" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center gap-2 sm:gap-3 p-1 sm:p-2 sm:pl-4 rounded-full bg-transparent sm:bg-white sm:dark:bg-slate-800 sm:border sm:border-slate-200 sm:dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-300 group shadow-none sm:shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                {{ Auth::user()->jabatan?->nama_jabatan ?? 'No Position' }}
                            </p>
                        </div>

                        {{-- User Avatar (Inisial) --}}
                        <div class="h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-600 dark:to-blue-900 flex items-center justify-center text-white font-bold text-sm shadow-inner ring-2 ring-white dark:ring-slate-800 flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    {{-- Dropdown Menu --}}
                    {{-- Dibatasi max-w agar tidak melebar keluar layar di HP, dan digeser sedikit ke kiri --}}
                    <div x-show="dropdownOpen"
                        @click.outside="dropdownOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                        x-cloak
                        class="absolute right-0 mt-3 w-[calc(100vw-1.5rem)] max-w-xs sm:w-64 bg-white dark:bg-slate-800 rounded-2xl shadow-xl py-2 z-50 border border-gray-100 dark:border-slate-700 overflow-hidden ring-1 ring-black/5 dark:ring-white/10">

                        <div class="px-4 sm:px-5 py-4 border-b border-gray-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <p class="text-[10px] font-extrabold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ __('app.connected_account') }}</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                            {{-- Tampilkan nama dan jabatan di dropdown untuk mode mobile --}}
                            <div class="md:hidden mt-2 pt-2 border-t border-gray-200 dark:border-slate-700">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                    {{ Auth::user()->jabatan?->nama_jabatan ?? 'No Position' }}
                                </p>
                            </div>
                        </div>

                        {{-- Switcher Bahasa untuk Mobile --}}
                        {{-- Diberi ikon + label teks agar tidak terlihat kosong (sebelumnya cuma bendera sendirian), --}}
                        {{-- dan padding disamakan dengan baris Profile Settings / Sign Out di bawahnya. --}}
                        <div class="block sm:hidden border-b border-gray-100 dark:border-slate-700 px-4 sm:px-5 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="languages" class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                                    <span class="text-sm font-medium text-gray-600 dark:text-slate-300">{{ __('app.language') }}</span>
                                </div>
                                <livewire:language-switcher />
                            </div>
                        </div>

                        <div class="py-2">
                            <a href="{{ route('profile') }}" wire:navigate
                                class="flex items-center px-4 sm:px-5 py-3 text-sm font-medium text-gray-600 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all">
                                <i data-lucide="settings" class="w-4.5 h-4.5 mr-3 text-blue-600 dark:text-blue-400 flex-shrink-0"></i> {{ __('app.profile_settings') }}
                            </a>
                        </div>

                        <div class="border-t border-gray-100 dark:border-slate-700 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center px-4 sm:px-5 py-3 text-sm font-bold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all">
                                    <i data-lucide="power" class="w-4.5 h-4.5 mr-3 flex-shrink-0"></i> {{ __('app.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>