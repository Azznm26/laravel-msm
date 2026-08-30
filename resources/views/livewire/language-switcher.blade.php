<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open"
        class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-600 transition-all shadow-sm">
        <span class="h-6 w-6 rounded-full overflow-hidden shrink-0">
            <img src="{{ asset('images/' . $currentLocale . '.png') }}" alt="{{ strtoupper($currentLocale) }}" class="h-full w-full object-cover object-center">
        </span>
    </button>
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-cloak
        class="absolute right-0 mt-2 w-36 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 ring-1 ring-black/5 dark:ring-white/10 overflow-hidden z-50">
        <button wire:click="setLocale('id')"
            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-all {{ $currentLocale === 'id' ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-600 dark:text-gray-300' }}">
            <span class="h-5 w-5 shrink-0 rounded-full overflow-hidden border border-black/5">
                <img src="{{ asset('images/id.png') }}" alt="ID" class="h-full w-full object-cover object-center">
            </span>
            Indonesia
        </button>
        <button wire:click="setLocale('en')"
            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-all {{ $currentLocale === 'en' ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-600 dark:text-gray-300' }}">
            <span class="h-5 w-5 shrink-0 rounded-full overflow-hidden border border-black/5">
                <img src="{{ asset('images/en.png') }}" alt="EN" class="h-full w-full object-cover object-center">
            </span>
            English
        </button>
    </div>
</div>