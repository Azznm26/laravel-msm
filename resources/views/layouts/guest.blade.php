<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masuk - BluePath Mining' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

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

        .input-material {
            width: 100%;
            padding: 14px 16px 14px 44px;
            font-size: 15px;
            color: #111827;
            background-color: #FAFAFA;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: none;
            transition: color 0.3s ease;
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
            right: 12px;
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

    {{-- VIEW KOMPONEN LIVEWIRE AKAN DI-RENDER DI SINI --}}
    {{ $slot }}

    @livewireScripts
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();

        document.addEventListener('livewire:navigated', () => lucide.createIcons());
        document.addEventListener('livewire:initialized', () => lucide.createIcons());
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>

</html>