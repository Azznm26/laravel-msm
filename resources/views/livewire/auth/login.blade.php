<div>
    {{-- Tombol Toggle Dark Mode --}}
    <button type="button" onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');"
        class="fixed top-3 right-3 sm:top-6 sm:right-6 z-50 p-2 sm:p-2.5 rounded-full border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 shadow-sm transition-colors">
        <i data-lucide="sun" class="w-4 h-4 sm:w-5 sm:h-5 hidden dark:block"></i>
        <i data-lucide="moon" class="w-4 h-4 sm:w-5 sm:h-5 block dark:hidden"></i>
    </button>

    {{-- Language Switcher (ID / EN) --}}
    <div class="fixed top-3 left-3 sm:top-6 sm:left-6 z-50 flex items-center gap-0.5 rounded-full border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-1">
        @foreach (['id' => 'ID', 'en' => 'EN'] as $locale => $label)
        <a href="{{ route('locale.switch', $locale) }}"
            class="px-2.5 py-1 rounded-full text-xs font-bold transition-colors {{ app()->getLocale() === $locale ? 'bg-primary-600 text-white' : 'text-slate-500 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="min-h-screen flex items-center justify-center p-3 sm:p-4 md:p-8">

        {{-- COMPONENT MODAL --}}
        <x-layout.modal-login />

        {{-- Main Material Card Container --}}
        <div class="max-w-5xl w-full bg-white dark:bg-slate-800 rounded-2xl sm:rounded-[28px] shadow-material overflow-hidden flex flex-col lg:flex-row relative animate-fade-in">

            {{-- Indeterminate Loading Bar --}}
            <div wire:loading wire:target="authenticate" class="absolute top-0 left-0 w-full h-1 bg-primary-100 dark:bg-primary-900/40 z-50 overflow-hidden">
                <div class="loading-bar"></div>
            </div>

            {{-- ===== PANEL KIRI ===== --}}
            <div class="lg:w-5/12 bg-primary-600 dark:bg-primary-800 text-white flex flex-col justify-between relative p-6 sm:p-10 lg:p-14 overflow-hidden">
                <div class="absolute top-0 right-0 -mr-10 -mt-10 sm:-mr-16 sm:-mt-16 w-48 h-48 sm:w-72 sm:h-72 rounded-full bg-primary-500 dark:bg-primary-600 opacity-60 dark:opacity-40 blur-3xl pointer-events-none animate-float"></div>
                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 sm:-ml-16 sm:-mb-16 w-36 h-36 sm:w-56 sm:h-56 rounded-full bg-primary-800 dark:bg-primary-900 opacity-50 blur-2xl pointer-events-none animate-float-delayed"></div>

                <div class="relative z-10 animate-fade-in delay-100 flex flex-col h-full justify-center">
                    <div class="mb-6 sm:mb-12">
                        <div class="inline-flex items-center justify-center p-4 sm:p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl sm:rounded-[24px] shadow-lg">
                            <img src="{{ asset('images/logo-survey.png') }}" alt="{{ config('app.name') }}" class="h-10 sm:h-16 lg:h-20 w-auto object-contain drop-shadow-md" style="filter: brightness(0) invert(1);">
                        </div>
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-[1.2] sm:leading-[1.15] mb-3 sm:mb-5 text-white tracking-tight">
                        {!! __('Satu portal untuk<br class="hidden sm:block"> pengembangan karir.') !!}
                    </h1>
                    <p class="text-primary-100 leading-relaxed font-medium text-sm opacity-90">
                        {{ __('Pantau jalur promosi, evaluasi kompetensi, dan kelola target pencapaian Anda secara transparan dan terukur bersama kami.') }}
                    </p>
                </div>

                <div class="relative z-10 animate-fade-in delay-200 hidden lg:block mt-12">
                    <div class="bg-primary-700/40 dark:bg-black/20 backdrop-blur-md border border-primary-500/30 dark:border-white/10 rounded-2xl p-5 shadow-sm inline-flex items-center gap-4">
                        <div class="p-3 bg-white/10 rounded-xl">
                            <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-primary-200 uppercase tracking-widest mb-0.5">{{ __('Sistem Keamanan') }}</p>
                            <p class="font-display text-lg font-extrabold text-white leading-none">{{ __('Terenkripsi 256-bit') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== PANEL KANAN (FORM LOGIN) ===== --}}
            <div class="lg:w-7/12 p-6 sm:p-10 lg:p-16 flex items-center justify-center bg-white dark:bg-slate-800 relative">

                {{-- Overlay Loading --}}
                <div wire:loading.flex wire:target="authenticate" class="absolute inset-0 z-40 bg-white/90 dark:bg-slate-800/90 backdrop-blur-[4px] flex-col items-center justify-center rounded-b-2xl lg:rounded-b-none lg:rounded-r-[24px] px-4 text-center">
                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 mb-4 sm:mb-6">
                        <div class="absolute inset-0 rounded-full border-4 border-primary-100 dark:border-primary-900/50"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-primary-600 dark:border-primary-400 border-t-transparent animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="lock" class="w-5 h-5 sm:w-6 sm:h-6 text-primary-600 dark:text-primary-400 animate-pulse"></i>
                        </div>
                    </div>
                    <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-slate-100 tracking-tight">{{ __('Memverifikasi Akun') }}</h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-2 animate-pulse">{{ __('Mohon tunggu sebentar...') }}</p>
                </div>

                <div class="max-w-md w-full relative z-10">

                    {{-- Error Flash Message --}}
                    @if (session()->has('error'))
                    <div class="mb-6 sm:mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-600 dark:text-red-400 mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-bold text-red-800 dark:text-red-300">{{ __('Autentikasi Gagal') }}</h3>
                            <p class="text-sm font-medium text-red-700 dark:text-red-400 mt-0.5">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mb-6 sm:mb-10 text-center lg:text-left animate-fade-in delay-100">
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-slate-100 mb-2 tracking-tight">{{ __('Selamat Datang Kembali') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-slate-400 font-medium">
                            {{ __('Silakan masuk ke akun Anda, atau') }}
                            <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-800 dark:hover:text-primary-300 transition-colors">{{ __('daftar akun baru') }}</a>
                        </p>
                    </div>

                    <form wire:submit="authenticate" class="space-y-5 sm:space-y-6 animate-fade-in delay-200">
                        {{-- Input Email --}}
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Alamat Email') }}</label>
                            <div class="relative">
                                <i data-lucide="mail" class="field-icon w-5 h-5"></i>
                                <input id="email" type="email" inputmode="email" autocomplete="email" wire:model="email" required autofocus
                                    placeholder="{{ __('nama@perusahaan.com') }}" class="input-material text-base sm:text-sm">
                            </div>
                            @error('email')
                            <span class="text-xs font-bold text-red-600 block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div class="space-y-2" x-data="{ showPassword: false }">
                            <div class="flex items-center justify-between gap-2">
                                <label for="password" class="block text-sm font-bold text-gray-700 dark:text-slate-300">{{ __('Kata Sandi') }}</label>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors whitespace-nowrap">
                                    {{ __('Lupa sandi?') }}
                                </a>
                                @endif
                            </div>
                            <div class="relative">
                                <i data-lucide="lock" class="field-icon w-5 h-5"></i>
                                <input id="password" x-bind:type="showPassword ? 'text' : 'password'" autocomplete="current-password" wire:model="password" required placeholder="••••••••" class="input-material pr-12 text-base sm:text-sm">
                                <button type="button" @click="showPassword = !showPassword" aria-label="{{ __('Tampilkan atau sembunyikan kata sandi') }}" class="eye-btn focus:outline-none focus:ring-2 focus:ring-primary-100 dark:focus:ring-primary-900/40">
                                    <i data-lucide="eye" x-show="!showPassword" class="w-5 h-5"></i>
                                    <i data-lucide="eye-off" x-show="showPassword" class="w-5 h-5" style="display:none;"></i>
                                </button>
                            </div>
                            @error('password')
                            <span class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="pt-1">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer group">
                                <input id="remember_me" type="checkbox" wire:model="remember"
                                    class="w-5 h-5 sm:w-4.5 sm:h-4.5 text-primary-600 border-gray-300 dark:border-slate-600 dark:bg-slate-700 rounded focus:ring-primary-500 focus:ring-2 transition-all cursor-pointer">
                                <span class="text-sm font-bold text-gray-600 dark:text-slate-400 group-hover:text-gray-900 dark:group-hover:text-slate-100 transition-colors">{{ __('Ingat saya di perangkat ini') }}</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="btn-material w-full py-3.5 sm:py-4 px-4 rounded-xl text-sm font-bold flex items-center justify-center gap-2.5 disabled:opacity-70 disabled:cursor-not-allowed mt-4 shadow-md min-h-[48px]"
                            wire:loading.attr="disabled">
                            <i data-lucide="log-in" wire:loading.remove wire:target="authenticate" class="w-5 h-5"></i>
                            <span wire:loading.remove wire:target="authenticate">{{ __('Masuk ke Portal') }}</span>
                            <span wire:loading wire:target="authenticate">{{ __('Memproses...') }}</span>
                        </button>

                        <div class="flex items-center gap-4 my-6 sm:my-8">
                            <div class="flex-1 h-px bg-gray-200 dark:bg-slate-700"></div>
                            <span class="text-[10px] font-extrabold text-gray-400 dark:text-slate-500 uppercase tracking-widest">{{ __('ATAU') }}</span>
                            <div class="flex-1 h-px bg-gray-200 dark:bg-slate-700"></div>
                        </div>

                        <button type="button" class="w-full py-3.5 px-4 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-600 hover:border-gray-400 dark:hover:border-slate-500 active:bg-gray-100 dark:active:bg-slate-600 rounded-xl text-sm font-bold flex items-center justify-center gap-3 transition-all shadow-sm min-h-[48px]">
                            <svg class="w-[18px] h-[18px] flex-shrink-0" viewBox="0 0 18 18">
                                <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4" />
                                <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853" />
                                <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05" />
                                <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335" />
                            </svg>
                            <span class="truncate">{{ __('Lanjutkan dengan Google') }}</span>
                        </button>

                        <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium mt-6 leading-relaxed">
                            {{ __('Dengan masuk, Anda menyetujui') }}
                            <a href="{{ Route::has('terms') ? route('terms') : '#' }}" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">{{ __('Syarat & Ketentuan') }}</a>
                            {{ __('serta') }}
                            <a href="{{ Route::has('privacy') ? route('privacy') : '#' }}" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">{{ __('Kebijakan Privasi') }}</a>
                            {{ __('kami.') }}
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>