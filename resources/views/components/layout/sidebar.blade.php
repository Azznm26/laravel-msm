<div>
    {{-- BACKDROP (Latar Gelap di Mobile) --}}
    <div x-show="sidebarOpen"
        @click="toggleSidebar()"
        x-cloak
        class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/60 backdrop-blur-sm z-30 lg:hidden"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    {{-- SIDEBAR UTAMA --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-800 transform duration-300 ease-in-out border-r border-gray-100 dark:border-slate-700 shadow-2xl lg:shadow-none flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex items-center space-x-3">
                <div class="p-1.5 bg-gradient-to-tr from-blue-600 to-blue-800 rounded-xl shadow-sm border border-blue-200 dark:border-blue-700 flex items-center justify-center">
                    <img src="{{ asset('images/logo-survey.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=CP&background=2563EB&color=fff'" alt="Logo" class="h-6 w-6 object-contain" style="filter: brightness(0) invert(1);">
                </div>
                <span class="font-extrabold text-gray-900 dark:text-white tracking-wider text-sm uppercase">Career Path</span>
            </div>

            <button @click="toggleSidebar()"
                class="p-1.5 rounded-lg text-gray-400 dark:text-gray-500 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors lg:hidden focus:outline-none">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- NAVIGASI AREA --}}
        <nav @click="if(window.innerWidth < 1024 && $event.target.closest('a')) sidebarOpen = false" class="flex-1 p-4 space-y-1.5 overflow-y-auto scrollbar-thin">
            {{ $slot }}
        </nav>

        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-center">
            <p class="text-[10px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                {{ date('Y') }} &copy; BLUEPATH
            </p>
        </div>
    </aside>

    {{-- TOMBOL TRIGGER MENGAMBANG (khusus Desktop) --}}
    {{-- Disembunyikan di mobile (hidden, baru muncul lg:flex) karena navbar sudah punya --}}
    {{-- tombol hamburang sendiri di sana. Kalau keduanya tampil bersamaan di mobile, --}}
    {{-- posisinya berdekatan dan terlihat "menabrak" logo, seperti pada screenshot Anda. --}}
    <button @click="toggleSidebar()" x-cloak
        class="hidden lg:flex fixed z-50 p-2.5 text-slate-500 dark:text-slate-400 transition-all duration-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 shadow-sm rounded-xl hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 focus:outline-none top-6"
        :class="sidebarOpen ? 'left-[272px]' : 'left-4'">

        <i x-show="!sidebarOpen" data-lucide="align-left" class="w-5 h-5"></i>
        <i x-show="sidebarOpen" data-lucide="chevrons-left" class="w-5 h-5"></i>
    </button>
</div>