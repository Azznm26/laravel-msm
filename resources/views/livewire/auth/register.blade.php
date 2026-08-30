<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Daftar Akun - BluePath Mining' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Manrope', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    boxShadow: {
                        'material': '0 10px 40px -10px rgba(0,0,0,0.08), 0 4px 6px -1px rgba(0,0,0,0.04)',
                        'material-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #F0F4F8;
            color: #1F2937;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        html.dark body {
            background-color: #0B1120;
            color: #E2E8F0;
        }

        /* Material Outlined Input */
        .input-material {
            width: 100%;
            padding: 12px 16px 12px 40px;
            /* Dikecilkan sedikit untuk mobile */
            font-size: 14px;
            /* Ukuran font lebih aman untuk mobile */
            color: #111827;
            background-color: #FAFAFA;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 640px) {
            .input-material {
                padding: 14px 16px 14px 44px;
                font-size: 15px;
            }
        }

        .input-material:focus {
            outline: none;
            background-color: #FFFFFF;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        html.dark .input-material {
            color: #F1F5F9;
            background-color: #1E293B;
            border: 1px solid #334155;
        }

        html.dark .input-material::placeholder {
            color: #64748B;
        }

        html.dark .input-material:focus {
            background-color: #1E293B;
            border-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        @media (min-width: 640px) {
            .field-icon {
                left: 16px;
            }
        }

        html.dark .field-icon {
            color: #64748B;
        }

        .relative:focus-within .field-icon {
            color: #2563eb;
        }

        html.dark .relative:focus-within .field-icon {
            color: #60A5FA;
        }

        .eye-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #9CA3AF;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .eye-btn:hover {
            background-color: #F3F4F6;
            color: #4B5563;
        }

        html.dark .eye-btn {
            color: #64748B;
        }

        html.dark .eye-btn:hover {
            background-color: #334155;
            color: #CBD5E1;
        }

        /* Material Button */
        .btn-material {
            background-color: #2563eb;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-material:hover {
            background-color: #1d4ed8;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            transform: translateY(-2px);
        }

        .btn-material:active {
            transform: translateY(0) scale(0.98);
        }

        /* Loading Bar Animation */
        .loading-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            background-color: #60A5FA;
            width: 100%;
            transform-origin: 0% 50%;
            animation: indeterminate 1.5s infinite linear;
        }

        @keyframes indeterminate {
            0% {
                transform: translateX(0) scaleX(0);
            }

            40% {
                transform: translateX(0) scaleX(0.4);
            }

            100% {
                transform: translateX(100%) scaleX(0.5);
            }
        }

        /* Entrance Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        /* Floating background animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        .animate-float {
            animation: float 8s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 8s ease-in-out infinite;
            animation-delay: 4s;
        }
    </style>

    @livewireStyles
</head>

<body class="antialiased">

    {{-- Wrapper tunggal --}}
    <div>

        {{-- Tombol Toggle Dark Mode --}}
        <button type="button" onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');"
            class="fixed top-4 right-4 sm:top-6 sm:right-6 z-50 p-2.5 rounded-full border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 shadow-sm transition-colors">
            <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
            <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
        </button>

        <!-- Padding luar disesuaikan untuk mobile (p-4) dan desktop (p-8) -->
        <div class="min-h-screen flex items-center justify-center p-4 sm:p-8">

            {{-- Main Material Card Container --}}
            <!-- Border radius disesuaikan: 2xl di mobile, [28px] di desktop -->
            <div class="max-w-5xl w-full bg-white dark:bg-slate-800 rounded-2xl sm:rounded-[28px] shadow-material overflow-hidden flex flex-col lg:flex-row relative animate-fade-in">

                {{-- Indeterminate Loading Bar --}}
                <div wire:loading wire:target="register" class="absolute top-0 left-0 w-full h-1 bg-primary-100 dark:bg-primary-900/40 z-50 overflow-hidden">
                    <div class="loading-bar"></div>
                </div>

                {{-- ===== PANEL KIRI - BRAND & INFO ===== --}}
                <!-- Padding dikurangi di mobile (p-8) agar form lebih cepat terlihat saat discroll -->
                <div class="w-full lg:w-5/12 bg-primary-600 dark:bg-primary-800 text-white flex flex-col justify-between relative p-8 sm:p-10 lg:p-14 overflow-hidden">

                    {{-- Animated Decorative Backgrounds --}}
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-72 h-72 rounded-full bg-primary-500 dark:bg-primary-600 opacity-60 dark:opacity-40 blur-3xl pointer-events-none animate-float"></div>
                    <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-56 h-56 rounded-full bg-primary-800 dark:bg-primary-900 opacity-50 blur-2xl pointer-events-none animate-float-delayed"></div>

                    <div class="relative z-10 animate-fade-in delay-100 flex flex-col h-full justify-center">

                        {{-- LOGO PERUSAHAAN --}}
                        <div class="mb-8 lg:mb-12 text-center lg:text-left">
                            <div class="inline-flex items-center justify-center p-4 sm:p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl sm:rounded-[24px] shadow-lg">
                                <!-- Logo lebih kecil di mobile (h-12), normal di sm (h-16) dan lg (h-20) -->
                                <img src="{{ asset('images/logo-survey.png') }}" alt="Logo Perusahaan" class="h-12 sm:h-16 lg:h-20 w-auto object-contain drop-shadow-md" style="filter: brightness(0) invert(1);">
                            </div>
                        </div>

                        {{-- Value prop --}}
                        <div class="text-center lg:text-left">
                            <!-- Ukuran font responsif -->
                            <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.5rem] font-extrabold leading-tight mb-4 text-white tracking-tight">
                                Mulai langkah awal<br class="hidden sm:block">karir Anda.
                            </h1>
                            <p class="text-primary-100 leading-relaxed font-medium text-sm sm:text-base opacity-90">
                                Buat akun untuk mengakses portal manajemen karir, evaluasi kompetensi, dan tugas harian secara real-time.
                            </p>
                        </div>
                    </div>

                    {{-- Statistik Ringkas / Badge (Hanya tampil di Desktop agar tidak menumpuk di Mobile) --}}
                    <div class="relative z-10 animate-fade-in delay-200 hidden lg:block mt-12">
                        <div class="bg-primary-700/40 dark:bg-black/20 backdrop-blur-md border border-primary-500/30 dark:border-white/10 rounded-2xl p-5 shadow-sm inline-flex items-center gap-4">
                            <div class="p-3 bg-white/10 rounded-xl">
                                <i data-lucide="shield-check" class="w-6 h-6 text-emerald-400"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-primary-200 uppercase tracking-widest mb-0.5">Sistem Keamanan</p>
                                <p class="font-display text-lg font-extrabold text-white leading-none">Terenkripsi 256-bit</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== PANEL KANAN - FORM REGISTER ===== --}}
                <!-- Padding disesuaikan: p-6 untuk mobile, p-10 untuk tablet, p-16 untuk desktop -->
                <div class="w-full lg:w-7/12 p-6 sm:p-10 lg:p-16 flex items-center justify-center bg-white dark:bg-slate-800 relative">

                    {{-- ANIMASI OVERLAY VERIFIKASI --}}
                    <!-- Rounded disesuaikan agar overlay pas dengan container di mobile dan desktop -->
                    <div wire:loading.flex wire:target="register" class="absolute inset-0 z-40 bg-white/90 dark:bg-slate-800/90 backdrop-blur-[4px] flex-col items-center justify-center rounded-b-2xl lg:rounded-bl-none lg:rounded-r-[28px]">
                        <div class="relative w-16 h-16 sm:w-20 sm:h-20 mb-6">
                            <div class="absolute inset-0 rounded-full border-4 border-primary-100 dark:border-primary-900/50"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-primary-600 dark:border-primary-400 border-t-transparent animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i data-lucide="user-plus" class="w-5 h-5 sm:w-6 sm:h-6 text-primary-600 dark:text-primary-400 animate-pulse"></i>
                            </div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-slate-100 tracking-tight text-center px-4">Memproses Pendaftaran</h3>
                        <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-slate-400 mt-2 animate-pulse text-center px-4">Mohon tunggu, sedang menyiapkan akun Anda...</p>
                    </div>

                    <div class="max-w-md w-full relative z-10">

                        {{-- Header Form --}}
                        <div class="mb-8 sm:mb-10 text-center lg:text-left animate-fade-in delay-100">
                            <!-- Ukuran judul lebih kecil di mobile -->
                            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-slate-100 mb-2 tracking-tight">Buat Akun Baru</h2>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-slate-400 font-medium">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 font-bold hover:text-primary-800 dark:hover:text-primary-300 transition-colors">Masuk di sini</a>
                            </p>
                        </div>

                        <form wire:submit="register" class="space-y-5 sm:space-y-6 animate-fade-in delay-200">

                            {{-- Nama Lengkap --}}
                            <div class="space-y-1.5 sm:space-y-2">
                                <label for="name" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-300">Nama Lengkap</label>
                                <div class="relative">
                                    <i data-lucide="user" class="field-icon w-4 h-4 sm:w-5 sm:h-5"></i>
                                    <input id="name" type="text" wire:model="name" required autofocus
                                        placeholder="Masukkan nama lengkap" class="input-material">
                                </div>
                                @error('name')
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="space-y-1.5 sm:space-y-2">
                                <label for="email" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-300">Alamat Email</label>
                                <div class="relative">
                                    <i data-lucide="mail" class="field-icon w-4 h-4 sm:w-5 sm:h-5"></i>
                                    <input id="email" type="email" wire:model="email" required
                                        placeholder="nama@perusahaan.com" class="input-material">
                                </div>
                                @error('email')
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- ID Badge --}}
                            <div class="space-y-1.5 sm:space-y-2">
                                <label for="id_badge" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-300">ID Badge</label>
                                <div class="relative">
                                    <i data-lucide="badge-check" class="field-icon w-4 h-4 sm:w-5 sm:h-5"></i>
                                    <input id="id_badge" type="text" wire:model="id_badge" required
                                        placeholder="Contoh: MIN-1234" class="input-material">
                                </div>
                                <!-- Tambahkan baris ini untuk memunculkan error -->
                                @error('id_badge')
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password & Konfirmasi (Tetap menggunakan grid yang responsif) --}}
                            <div x-data="{ showPassword: false }" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                                <div class="space-y-1.5 sm:space-y-2">
                                    <label for="password" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-300">
                                        {{ __('messages.password') }}
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="lock" class="field-icon w-4 h-4 sm:w-5 sm:h-5"></i>
                                        <input id="password"
                                            x-bind:type="showPassword ? 'text' : 'password'"
                                            wire:model="password" required
                                            placeholder="{{ __('messages.min_8_chars') }}"
                                            class="input-material pr-12">
                                    </div>
                                    @error('password')
                                    <span class="text-xs font-bold text-red-600 dark:text-red-400 mt-1.5 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-1.5 sm:space-y-2">
                                    <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-slate-300">
                                        {{ __('messages.password_confirmation') }}
                                    </label>
                                    <div class="relative">
                                        <i data-lucide="lock" class="field-icon w-4 h-4 sm:w-5 sm:h-5"></i>
                                        <input id="password_confirmation"
                                            x-bind:type="showPassword ? 'text' : 'password'"
                                            wire:model="password_confirmation" required
                                            placeholder="{{ __('messages.repeat_password') }}"
                                            class="input-material pr-12">
                                        <button type="button" @click="showPassword = !showPassword" class="eye-btn focus:outline-none focus:ring-2 focus:ring-primary-100 dark:focus:ring-primary-900/40">
                                            <i data-lucide="eye" x-show="!showPassword" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                            <i data-lucide="eye-off" x-show="showPassword" class="w-4 h-4 sm:w-5 sm:h-5" style="display:none;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit button --}}
                            <!-- Ukuran tombol sedikit disesuaikan untuk touch-target yang baik -->
                            <button type="submit"
                                class="btn-material w-full py-3.5 sm:py-4 px-4 rounded-xl text-sm font-bold flex items-center justify-center gap-2.5 disabled:opacity-70 disabled:cursor-not-allowed mt-4 shadow-md"
                                wire:loading.attr="disabled">
                                <i data-lucide="user-plus" wire:loading.remove wire:target="register" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                                <span wire:loading wire:target="register">Memproses Data...</span>
                            </button>

                            {{-- Footer note --}}
                            <p class="text-center text-[11px] sm:text-xs text-gray-500 dark:text-slate-400 font-medium mt-4 sm:mt-6 leading-relaxed px-2">
                                Dengan mendaftar, Anda menyetujui
                                <a href="#" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">Syarat &amp; Ketentuan</a>
                                serta <a href="#" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors">Kebijakan Privasi</a> kami.
                            </p>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();

        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
        document.addEventListener('livewire:initialized', () => {
            lucide.createIcons();
        });
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>

</body>

</html>