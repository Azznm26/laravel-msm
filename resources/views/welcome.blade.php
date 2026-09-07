<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Manajemen Karir - BluePath Mining' }}</title>

    {{-- Cegah "flash" tema salah sebelum body dirender --}}
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
                var savedLang = localStorage.getItem('lang');
                if (savedLang) {
                    document.documentElement.setAttribute('lang', savedLang);
                }
            } catch (e) {}
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- AOS (Animate On Scroll) Library untuk efek fade/slide saat digulir --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

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
        }

        .dark {
            --primary: #3b82f6;
            --primary-hover: #60a5fa;
            --primary-tint: rgba(59, 130, 246, 0.12);

            --surface: #1e293b;
            --background: #0b1220;
            --border: #334155;

            --ink: #f1f5f9;
            --ink-soft: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--ink);
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* ---------- Navbar ---------- */
        nav.nav-scrolled {
            box-shadow: 0 8px 24px -8px rgba(37, 99, 235, 0.12);
        }

        .dark nav.nav-scrolled {
            box-shadow: 0 8px 24px -8px rgba(0, 0, 0, 0.4);
        }

        .logo-box {
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
        }

        .group:hover .logo-box {
            transform: scale(1.08) rotate(-6deg);
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.45);
        }

        /* ---------- Buttons ---------- */
        .md-btn-primary {
            position: relative;
            overflow: hidden;
            background: var(--primary);
            color: #ffffff;
            border-radius: 9999px;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -2px rgba(37, 99, 235, 0.1);
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .md-btn-primary::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transform: translateX(-120%);
            transition: transform 0.6s ease;
        }

        .md-btn-primary:hover::before {
            transform: translateX(120%);
        }

        .md-btn-primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 14px 24px -6px rgba(37, 99, 235, 0.45), 0 6px 10px -6px rgba(37, 99, 235, 0.25);
            transform: translateY(-4px) scale(1.03);
        }

        .md-btn-primary:active {
            transform: translateY(-1px) scale(0.98);
            transition: transform 0.1s ease;
        }

        .md-btn-primary i {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .md-btn-primary:hover i {
            transform: translateX(4px);
        }

        .md-btn-outline {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            color: var(--ink);
            border: 1px solid var(--border);
            border-radius: 9999px;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .dark .md-btn-outline {
            background: rgba(30, 41, 59, 0.8);
        }

        .md-btn-outline:hover {
            background: var(--surface);
            border-color: #93c5fd;
            box-shadow: 0 10px 20px -4px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .md-btn-outline i {
            transition: transform 0.35s ease;
        }

        .md-btn-outline:hover i {
            transform: translateY(3px);
        }

        /* ---------- Icon toggle buttons (theme / language) ---------- */
        .icon-toggle-btn {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border);
            color: var(--ink);
            border-radius: 9999px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .dark .icon-toggle-btn {
            background: rgba(30, 41, 59, 0.8);
        }

        .icon-toggle-btn:hover {
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
        }

        /* ---------- Feature Cards ---------- */
        .feature-card {
            position: relative;
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            overflow: hidden;
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                border-color 0.45s ease,
                background-color 0.4s ease;
        }

        .dark .feature-card {
            border-color: rgba(51, 65, 85, 0.8);
        }

        .feature-card::after {
            content: "";
            position: absolute;
            top: -60%;
            left: -60%;
            width: 220%;
            height: 220%;
            background: radial-gradient(circle at center, rgba(37, 99, 235, 0.08), transparent 60%);
            opacity: 0;
            transform: scale(0.6);
            transition: opacity 0.5s ease, transform 0.5s ease;
            pointer-events: none;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.015);
            box-shadow: 0 24px 40px -8px rgba(37, 99, 235, 0.16), 0 10px 14px -8px rgba(0, 0, 0, 0.03);
            border-color: #bfdbfe;
        }

        .dark .feature-card:hover {
            box-shadow: 0 24px 40px -8px rgba(0, 0, 0, 0.5), 0 10px 14px -8px rgba(0, 0, 0, 0.3);
            border-color: #3b82f6;
        }

        .feature-card:hover::after {
            opacity: 1;
            transform: scale(1);
        }

        .feature-icon-box {
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1),
                background-color 0.4s ease, box-shadow 0.4s ease;
        }

        .feature-card:hover .feature-icon-box {
            transform: rotate(10deg) scale(1.12);
        }

        .feature-icon-box i {
            transition: color 0.3s ease, transform 0.4s ease;
        }

        .feature-card:hover .feature-icon-box i {
            transform: scale(1.1);
        }

        /* ---------- Hero background ---------- */
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px;
            transition: background-color 0.4s ease;
        }

        .dark .hero-pattern {
            background-color: #0b1220;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
        }

        .hero-glow {
            background: radial-gradient(circle, rgba(59, 130, 246, 0.22), rgba(99, 102, 241, 0.08) 55%, transparent 75%);
            filter: blur(90px);
            animation: drift 10s ease-in-out infinite;
        }

        .dark .hero-glow {
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32), rgba(99, 102, 241, 0.12) 55%, transparent 75%);
        }

        .hero-glow-secondary {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.14), transparent 70%);
            filter: blur(80px);
            animation: drift-reverse 13s ease-in-out infinite;
        }

        .dark .hero-glow-secondary {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2), transparent 70%);
        }

        @keyframes drift {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                transform: translate(-46%, -54%) scale(1.12);
            }
        }

        @keyframes drift-reverse {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                transform: translate(-54%, -46%) scale(1.08);
            }
        }

        /* Floating animation for badge/elements */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* Gradient text shimmer on the headline highlight */
        .gradient-text {
            background-size: 200% auto;
            animation: gradient-shift 6s ease-in-out infinite;
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% center;
            }

            50% {
                background-position: 100% center;
            }

            100% {
                background-position: 0% center;
            }
        }

        /* Scroll cue chevron */
        .scroll-cue {
            animation: bob 1.8s ease-in-out infinite;
        }

        @keyframes bob {

            0%,
            100% {
                transform: translateY(0);
                opacity: 0.6;
            }

            50% {
                transform: translateY(6px);
                opacity: 1;
            }
        }

        /* Theme toggle icon swap */
        .theme-icon-dark {
            display: none;
        }

        .dark .theme-icon-dark {
            display: inline-block;
        }

        .dark .theme-icon-light {
            display: none;
        }

        /* ---------- Mobile menu ---------- */
        #mobileMenu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.3s ease;
        }

        #mobileMenu.open {
            max-height: 400px;
            opacity: 1;
        }

        /* Hamburger -> X animation */
        #menuToggle span {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #menuToggle.open span:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }

        #menuToggle.open span:nth-child(2) {
            opacity: 0;
        }

        #menuToggle.open span:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        /* Respect users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }
        }

        /* ---------- Small-screen refinements ---------- */
        @media (max-width: 400px) {

            .hero-glow,
            .hero-glow-secondary {
                width: 320px !important;
                height: 320px !important;
            }
        }
    </style>

    @livewireStyles
</head>

<body class="antialiased flex flex-col min-h-screen selection:bg-blue-200 selection:text-blue-900 dark:selection:bg-blue-800 dark:selection:text-blue-100">

    {{-- NAVBAR DENGAN EFEK BLUR DINAMIS & ANIMASI HOVER --}}
    <nav id="mainNav" class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-slate-800 transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                {{-- Logo --}}
                <div class="flex items-center gap-2 sm:gap-3 group cursor-pointer" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                    {{-- Box Ikon dengan efek scale & rotate --}}
                    <div class="logo-box p-1.5 sm:p-2 bg-gradient-to-tr from-blue-600 to-blue-800 rounded-xl sm:rounded-2xl shadow-md transition-all duration-500 ease-out group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-blue-500/40 flex items-center justify-center">
                        {{-- Ikon Kompas berputar --}}
                        <i data-lucide="compass" class="w-5 h-5 sm:w-6 sm:h-6 text-white transition-transform duration-700 ease-in-out group-hover:rotate-180"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-900 dark:text-slate-100 text-sm sm:text-lg font-black tracking-widest uppercase leading-none mt-1 transition-colors duration-300 group-hover:text-blue-700 dark:group-hover:text-blue-400">Career Path</span>
                        <span class="hidden xs:inline text-[8px] sm:text-[9px] text-blue-600 dark:text-blue-400 font-extrabold uppercase tracking-[0.2em] sm:tracking-[0.25em] opacity-90 group-hover:opacity-100 transition-opacity duration-300">BluePath Mining</span>
                    </div>
                </div>

                {{-- Nav Links & Login (desktop) --}}
                <div class="flex items-center gap-2 sm:gap-4 lg:gap-7">

                    {{-- Nav Link 1 dengan Animated Underline --}}
                    <a href="#fitur" class="relative hidden md:inline-block text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300 group py-2">
                        <span data-i18n="nav_features">Fitur Utama</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 rounded-full transition-all duration-300 ease-out group-hover:w-full"></span>
                    </a>

                    {{-- Nav Link 2 dengan Animated Underline --}}
                    <a href="#tentang" class="relative hidden md:inline-block text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300 group py-2">
                        <span data-i18n="nav_about">Tentang Sistem</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 rounded-full transition-all duration-300 ease-out group-hover:w-full"></span>
                    </a>

                    {{-- Toggle Bahasa (ID / EN) --}}
                    <button id="langToggle" type="button" aria-label="Ganti bahasa / Switch language"
                        class="icon-toggle-btn w-9 h-9 sm:w-11 sm:h-10 flex items-center justify-center text-[11px] sm:text-xs font-black uppercase tracking-wide shrink-0">
                        <span id="langToggleLabel">ID</span>
                    </button>

                    {{-- Toggle Dark Mode --}}
                    <button id="themeToggle" type="button" aria-label="Toggle dark mode"
                        class="icon-toggle-btn w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center shrink-0">
                        <i data-lucide="sun" class="theme-icon-light w-4 h-4 sm:w-5 sm:h-5"></i>
                        <i data-lucide="moon" class="theme-icon-dark w-4 h-4 sm:w-5 sm:h-5"></i>
                    </button>

                    {{-- Tombol CTA (Login/Dashboard) dengan animasi geser ikon --}}
                    @auth
                    <a href="{{ route('user.dashboard') }}" class="group hidden sm:flex px-6 py-2.5 md-btn-primary text-sm font-bold items-center gap-2">
                        <span data-i18n="nav_dashboard">Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-300 ease-out group-hover:translate-x-1"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="group hidden sm:flex px-6 py-2.5 md-btn-primary text-sm font-bold items-center gap-2 shadow-md hover:shadow-lg hover:shadow-blue-500/20">
                        <span data-i18n="nav_login">Masuk</span>
                        <i data-lucide="log-in" class="w-4 h-4 transition-transform duration-300 ease-out group-hover:translate-x-1"></i>
                    </a>
                    @endauth

                    {{-- Hamburger (mobile only) --}}
                    <button id="menuToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu"
                        class="md:hidden icon-toggle-btn w-9 h-9 flex flex-col items-center justify-center gap-[5px] shrink-0">
                        <span class="block w-4 h-0.5 bg-current rounded-full"></span>
                        <span class="block w-4 h-0.5 bg-current rounded-full"></span>
                        <span class="block w-4 h-0.5 bg-current rounded-full"></span>
                    </button>
                </div>
            </div>

            {{-- Mobile dropdown menu --}}
            <div id="mobileMenu" class="md:hidden">
                <div class="flex flex-col gap-1 pb-4 pt-1 border-t border-gray-100 dark:border-slate-800">
                    <a href="#fitur" class="mobile-nav-link px-2 py-3 rounded-xl text-sm font-bold text-gray-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        <span data-i18n="nav_features">Fitur Utama</span>
                    </a>
                    <a href="#tentang" class="mobile-nav-link px-2 py-3 rounded-xl text-sm font-bold text-gray-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        <span data-i18n="nav_about">Tentang Sistem</span>
                    </a>
                    @auth
                    <a href="{{ route('user.dashboard') }}" class="sm:hidden mt-1 px-4 py-3 md-btn-primary text-sm font-bold flex items-center justify-center gap-2">
                        <span data-i18n="nav_dashboard">Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="sm:hidden mt-1 px-4 py-3 md-btn-primary text-sm font-bold flex items-center justify-center gap-2">
                        <span data-i18n="nav_login">Masuk</span>
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow pt-16 sm:pt-20">

        {{-- HERO SECTION --}}
        <section class="relative pt-16 pb-24 sm:pt-28 sm:pb-36 lg:pt-40 lg:pb-48 overflow-hidden hero-pattern">
            <!-- Background Glow with slow drifting motion -->
            <div class="hero-glow absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[340px] h-[340px] sm:w-[500px] sm:h-[500px] lg:w-[700px] lg:h-[700px] rounded-full pointer-events-none"></div>
            <div class="hero-glow-secondary absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] h-[280px] sm:w-[400px] sm:h-[400px] lg:w-[560px] lg:h-[560px] rounded-full pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

                {{-- Badge Atas --}}
                <div data-aos="fade-down" data-aos-duration="800">
                    <span class="inline-flex items-center gap-2 py-1.5 px-4 sm:px-5 rounded-full bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 text-blue-700 dark:text-blue-300 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest mb-5 sm:mb-6 shadow-sm animate-float text-center">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping shrink-0"></span>
                        <span data-i18n="hero_badge">Platform Pengembangan SDM Terpadu</span>
                    </span>
                </div>

                {{-- Judul Utama --}}
                <h1 data-aos="fade-up" data-aos-duration="900" data-aos-delay="100" class="text-3xl xs:text-4xl sm:text-6xl lg:text-7xl font-black text-gray-900 dark:text-slate-100 tracking-tight leading-[1.2] sm:leading-[1.15] max-w-5xl mx-auto mb-5 sm:mb-6">
                    <span data-i18n="hero_title_pre">Akselerasi Potensi Karyawan dengan</span>
                    <span class="gradient-text text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600 dark:from-blue-400 dark:via-indigo-400 dark:to-blue-400" data-i18n="hero_title_highlight">Jalur Karir Cerdas</span>
                </h1>

                {{-- Deskripsi --}}
                <p data-aos="fade-up" data-aos-duration="900" data-aos-delay="200" class="text-sm sm:text-xl text-gray-500 dark:text-slate-400 font-medium max-w-2xl mx-auto mb-8 sm:mb-10 leading-relaxed px-2" data-i18n="hero_desc">
                    Sistem informasi modern untuk memetakan jalur promosi, mengevaluasi kompetensi menggunakan metode <i>Profile Matching</i>, dan melacak perkembangan karir secara objektif.
                </p>

                {{-- Tombol Aksi (CTA) --}}
                <div data-aos="fade-up" data-aos-duration="900" data-aos-delay="300" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4 px-4 sm:px-0">
                    @auth
                    <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 md-btn-primary text-sm sm:text-base font-bold flex items-center justify-center gap-2">
                        <span data-i18n="hero_cta_dashboard">Buka Dashboard Saya</span> <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 md-btn-primary text-sm sm:text-base font-bold flex items-center justify-center gap-2">
                        <span data-i18n="hero_cta_start">Mulai Sekarang</span> <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    @endauth
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 md-btn-outline text-sm sm:text-base font-bold flex items-center justify-center gap-2">
                        <span data-i18n="hero_cta_learn">Pelajari Lebih Lanjut</span> <i data-lucide="chevron-down" class="w-5 h-5 scroll-cue"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- FEATURES SECTION --}}
        <section id="fitur" class="py-16 sm:py-24 bg-white dark:bg-slate-900 relative z-20 -mt-6 sm:-mt-10 rounded-t-[2rem] sm:rounded-t-[3rem] shadow-[0_-20px_50px_rgba(37,99,235,0.04)] dark:shadow-[0_-20px_50px_rgba(0,0,0,0.2)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div data-aos="fade-up" class="text-center max-w-3xl mx-auto mb-12 sm:mb-20">
                    <h2 class="text-2xl sm:text-4xl font-black text-gray-900 dark:text-slate-100 mb-3 sm:mb-4 tracking-tight" data-i18n="features_title">Dirancang Untuk Transparansi Karir</h2>
                    <p class="text-gray-500 dark:text-slate-400 font-medium text-sm sm:text-base" data-i18n="features_desc">Platform ini membantu manajemen dan karyawan untuk secara kolaboratif merencanakan pencapaian karir yang jelas, terukur, dan akuntabel.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8">
                    {{-- Fitur 1 --}}
                    <div data-aos="fade-up" data-aos-delay="100" class="feature-card p-6 sm:p-8 group">
                        <div class="feature-icon-box relative z-10 w-12 h-12 sm:w-14 sm:h-14 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 group-hover:bg-blue-600 shadow-sm border border-blue-100 dark:border-blue-500/20">
                            <i data-lucide="map" class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600 dark:text-blue-400 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="relative z-10 text-lg sm:text-xl font-extrabold text-gray-900 dark:text-slate-100 mb-2.5 sm:mb-3" data-i18n="feature1_title">Pemetaan Jalur Karir</h3>
                        <p class="relative z-10 text-gray-500 dark:text-slate-400 font-medium text-sm leading-relaxed" data-i18n="feature1_desc">
                            Visualisasikan langkah demi langkah perjalanan karir karyawan dari posisi awal hingga mencapai tingkat manajerial tertinggi secara transparan.
                        </p>
                    </div>

                    {{-- Fitur 2 --}}
                    <div data-aos="fade-up" data-aos-delay="200" class="feature-card p-6 sm:p-8 group">
                        <div class="feature-icon-box relative z-10 w-12 h-12 sm:w-14 sm:h-14 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 group-hover:bg-emerald-500 shadow-sm border border-emerald-100 dark:border-emerald-500/20">
                            <i data-lucide="target" class="w-6 h-6 sm:w-7 sm:h-7 text-emerald-600 dark:text-emerald-400 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="relative z-10 text-lg sm:text-xl font-extrabold text-gray-900 dark:text-slate-100 mb-2.5 sm:mb-3" data-i18n="feature2_title">Gap Analysis Kompetensi</h3>
                        <p class="relative z-10 text-gray-500 dark:text-slate-400 font-medium text-sm leading-relaxed">
                            <span data-i18n="feature2_desc_pre">Gunakan metode</span> <span class="font-bold text-gray-700 dark:text-slate-300">Profile Matching</span> <span data-i18n="feature2_desc_post">untuk membandingkan kompetensi aktual karyawan dengan standar jabatan yang dituju secara presisi.</span>
                        </p>
                    </div>

                    {{-- Fitur 3 --}}
                    <div data-aos="fade-up" data-aos-delay="300" class="feature-card p-6 sm:p-8 group">
                        <div class="feature-icon-box relative z-10 w-12 h-12 sm:w-14 sm:h-14 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 group-hover:bg-amber-500 shadow-sm border border-amber-100 dark:border-amber-500/20">
                            <i data-lucide="clipboard-check" class="w-6 h-6 sm:w-7 sm:h-7 text-amber-600 dark:text-amber-400 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="relative z-10 text-lg sm:text-xl font-extrabold text-gray-900 dark:text-slate-100 mb-2.5 sm:mb-3" data-i18n="feature3_title">Manajemen Evaluasi & Tugas</h3>
                        <p class="relative z-10 text-gray-500 dark:text-slate-400 font-medium text-sm leading-relaxed">
                            <span data-i18n="feature3_desc_pre">Kumpulkan</span> <i class="text-gray-700 dark:text-slate-300 font-semibold">Experience Points</i> <span data-i18n="feature3_desc_post">(EXP) dengan menyelesaikan kuis interaktif, survei, dan mengunggah laporan penugasan lapangan.</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CALL TO ACTION (BOTTOM) --}}
        <section id="tentang" class="py-16 sm:py-24 bg-gradient-to-b from-white to-slate-50 dark:from-slate-900 dark:to-slate-950 border-y border-gray-100 dark:border-slate-800 relative overflow-hidden">
            <div data-aos="zoom-in" data-aos-duration="800" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-12 text-center relative z-10">
                <span class="text-blue-600 dark:text-blue-400 font-extrabold text-[10px] sm:text-xs uppercase tracking-widest bg-blue-50 dark:bg-blue-500/10 px-3 sm:px-3.5 py-1.5 rounded-full border border-blue-100 dark:border-blue-500/20 inline-block mb-4 animate-float" data-i18n="cta2_badge">Siap Bertumbuh Bersama?</span>
                <h2 class="text-2xl sm:text-4xl font-black text-gray-900 dark:text-slate-100 mb-3 sm:mb-4 tracking-tight" data-i18n="cta2_title">Siap Mencapai Level Karir Selanjutnya?</h2>
                <p class="text-gray-500 dark:text-slate-400 font-medium text-sm sm:text-base mb-7 sm:mb-8 max-w-2xl mx-auto px-2" data-i18n="cta2_desc">Masuk ke dalam portal untuk melihat roadmap pengembangan karir Anda dan mulai selesaikan evaluasi kompetensi hari ini.</p>
                <a href="{{ route('login') }}" class="inline-flex w-full sm:w-auto px-9 py-3.5 sm:py-4 md-btn-primary text-sm sm:text-base font-bold items-center justify-center gap-2 shadow-lg">
                    <span data-i18n="cta2_button">Masuk ke Portal Sekarang</span> <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <footer class="bg-slate-50 dark:bg-slate-950 border-t border-gray-200 dark:border-slate-800 py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-5 sm:gap-6">

                {{-- Logo & Indicator --}}
                <div class="flex items-center gap-3">
                    <div class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                    </div>
                    <span class="text-[11px] sm:text-xs font-extrabold text-gray-500 dark:text-slate-400 uppercase tracking-widest text-center">
                        Career Path - BluePath Mining
                    </span>
                </div>

                {{-- Copyright --}}
                <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 text-center">
                    &copy; {{ date('Y') }} BluePath Mining. <span data-i18n="footer_rights">Hak Cipta Dilindungi.</span>
                </p>

                {{-- Links --}}
                <div class="flex gap-5 sm:gap-6 text-[11px] text-gray-400 dark:text-slate-500 uppercase tracking-widest font-extrabold">
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300" data-i18n="footer_help">Bantuan</a>
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300" data-i18n="footer_privacy">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Script inisialisasi AOS, icon Lucide, Dark Mode, & Bahasa --}}
    <script>
        // ---------- Kamus Terjemahan ----------
        const translations = {
            id: {
                nav_features: 'Fitur Utama',
                nav_about: 'Tentang Sistem',
                nav_dashboard: 'Dashboard',
                nav_login: 'Masuk',
                hero_badge: 'Platform Pengembangan SDM Terpadu',
                hero_title_pre: 'Akselerasi Potensi Karyawan dengan',
                hero_title_highlight: 'Jalur Karir Cerdas',
                hero_desc: 'Sistem informasi modern untuk memetakan jalur promosi, mengevaluasi kompetensi menggunakan metode <i>Profile Matching</i>, dan melacak perkembangan karir secara objektif.',
                hero_cta_dashboard: 'Buka Dashboard Saya',
                hero_cta_start: 'Mulai Sekarang',
                hero_cta_learn: 'Pelajari Lebih Lanjut',
                features_title: 'Dirancang Untuk Transparansi Karir',
                features_desc: 'Platform ini membantu manajemen dan karyawan untuk secara kolaboratif merencanakan pencapaian karir yang jelas, terukur, dan akuntabel.',
                feature1_title: 'Pemetaan Jalur Karir',
                feature1_desc: 'Visualisasikan langkah demi langkah perjalanan karir karyawan dari posisi awal hingga mencapai tingkat manajerial tertinggi secara transparan.',
                feature2_title: 'Gap Analysis Kompetensi',
                feature2_desc_pre: 'Gunakan metode',
                feature2_desc_post: 'untuk membandingkan kompetensi aktual karyawan dengan standar jabatan yang dituju secara presisi.',
                feature3_title: 'Manajemen Evaluasi & Tugas',
                feature3_desc_pre: 'Kumpulkan',
                feature3_desc_post: '(EXP) dengan menyelesaikan kuis interaktif, survei, dan mengunggah laporan penugasan lapangan.',
                cta2_badge: 'Siap Bertumbuh Bersama?',
                cta2_title: 'Siap Mencapai Level Karir Selanjutnya?',
                cta2_desc: 'Masuk ke dalam portal untuk melihat roadmap pengembangan karir Anda dan mulai selesaikan evaluasi kompetensi hari ini.',
                cta2_button: 'Masuk ke Portal Sekarang',
                footer_rights: 'Hak Cipta Dilindungi.',
                footer_help: 'Bantuan',
                footer_privacy: 'Kebijakan Privasi'
            },
            en: {
                nav_features: 'Key Features',
                nav_about: 'About the System',
                nav_dashboard: 'Dashboard',
                nav_login: 'Sign In',
                hero_badge: 'Integrated Talent Development Platform',
                hero_title_pre: 'Accelerate Employee Potential with a',
                hero_title_highlight: 'Smart Career Path',
                hero_desc: 'A modern information system to map promotion paths, evaluate competencies using the <i>Profile Matching</i> method, and track career progress objectively.',
                hero_cta_dashboard: 'Open My Dashboard',
                hero_cta_start: 'Get Started',
                hero_cta_learn: 'Learn More',
                features_title: 'Built for Career Transparency',
                features_desc: 'This platform helps management and employees collaboratively plan clear, measurable, and accountable career achievements.',
                feature1_title: 'Career Path Mapping',
                feature1_desc: 'Visualize an employee\'s career journey step by step, from their starting position to the highest managerial level, with full transparency.',
                feature2_title: 'Competency Gap Analysis',
                feature2_desc_pre: 'Use the',
                feature2_desc_post: 'method to precisely compare employees\' actual competencies against the standards required for their target position.',
                feature3_title: 'Evaluation & Task Management',
                feature3_desc_pre: 'Earn',
                feature3_desc_post: '(XP) by completing interactive quizzes, surveys, and uploading field assignment reports.',
                cta2_badge: 'Ready to Grow Together?',
                cta2_title: 'Ready for Your Next Career Level?',
                cta2_desc: 'Sign in to the portal to view your career development roadmap and start completing competency evaluations today.',
                cta2_button: 'Sign In to the Portal',
                footer_rights: 'All Rights Reserved.',
                footer_help: 'Help',
                footer_privacy: 'Privacy Policy'
            }
        };

        function applyLanguage(lang) {
            const dict = translations[lang] || translations.id;
            document.querySelectorAll('[data-i18n]').forEach((el) => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] !== undefined) {
                    el.innerHTML = dict[key];
                }
            });
            document.documentElement.setAttribute('lang', lang);
            const label = document.getElementById('langToggleLabel');
            if (label) label.textContent = lang.toUpperCase();
            try {
                localStorage.setItem('lang', lang);
            } catch (e) {}
        }

        function getInitialLanguage() {
            try {
                const saved = localStorage.getItem('lang');
                if (saved && translations[saved]) return saved;
            } catch (e) {}
            const browserLang = (navigator.language || 'id').slice(0, 2);
            return translations[browserLang] ? browserLang : 'id';
        }

        function applyTheme(isDark) {
            document.documentElement.classList.toggle('dark', isDark);
            try {
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            } catch (e) {}
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const btn = document.getElementById('menuToggle');
            if (!menu || !btn) return;
            menu.classList.remove('open');
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi plugin AOS untuk animasi scroll
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    once: true, // Animasi hanya berjalan sekali saat discroll ke bawah
                    offset: 50, // Jarak trigger animasi
                    duration: 800,
                    easing: 'ease-out-cubic'
                });
            }

            // Inisialisasi ikon Lucide
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Navbar shadow yang lebih tegas saat discroll
            const nav = document.getElementById('mainNav');
            const onScroll = () => {
                if (window.scrollY > 12) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            };
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();

            // ---------- Dark Mode Toggle ----------
            const themeToggle = document.getElementById('themeToggle');
            themeToggle.addEventListener('click', () => {
                const isDark = !document.documentElement.classList.contains('dark');
                applyTheme(isDark);
            });

            // Ikuti perubahan preferensi sistem jika user belum pernah memilih manual
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                try {
                    if (!localStorage.getItem('theme')) {
                        applyTheme(e.matches);
                    }
                } catch (err) {}
            });

            // ---------- Language Toggle ----------
            const currentLang = getInitialLanguage();
            applyLanguage(currentLang);

            const langToggle = document.getElementById('langToggle');
            langToggle.addEventListener('click', () => {
                const active = document.documentElement.getAttribute('lang') === 'en' ? 'id' : 'en';
                applyLanguage(active);
            });

            // ---------- Mobile Hamburger Menu ----------
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', () => {
                    const isOpen = mobileMenu.classList.toggle('open');
                    menuToggle.classList.toggle('open', isOpen);
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                // Tutup menu saat salah satu link mobile diklik
                mobileMenu.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', closeMobileMenu);
                });

                // Tutup menu saat layar diperbesar ke ukuran desktop
                window.matchMedia('(min-width: 768px)').addEventListener('change', (e) => {
                    if (e.matches) closeMobileMenu();
                });
            }
        });
    </script>

    @livewireScripts
</body>

</html>