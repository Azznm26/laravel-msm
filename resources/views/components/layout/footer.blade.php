<footer class="mt-auto w-full border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 py-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">

            {{-- Brand & Live Indicator --}}
            <div class="flex items-center gap-2.5">
                <div class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 dark:bg-blue-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 dark:bg-blue-400"></span>
                </div>
                <span class="text-xs font-extrabold text-gray-500 dark:text-slate-400 uppercase tracking-widest">
                    {{ __('footer.brand_name') }}
                </span>
            </div>

            {{-- Copyright --}}
            <p class="text-[11px] font-medium text-gray-400 dark:text-slate-500 text-center">
                &copy; {{ date('Y') }} {{ __('footer.copyright_text') }}
            </p>

            {{-- Links --}}
            <div class="flex gap-6 text-[11px] text-gray-400 dark:text-slate-500 uppercase tracking-widest font-extrabold">
                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ __('footer.support') }}</a>
                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ __('footer.privacy') }}</a>
            </div>

        </div>
    </div>
</footer>