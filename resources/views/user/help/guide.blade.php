{{--
    resources/views/user/help/guide.blade.php
    Teks diambil dari resources/lang/{locale}/help.php lewat __('help.guide.*').
    Bahasa mengikuti locale aktif di session (di-set oleh middleware SetLocale kamu).

    Catatan layout:
    - Mobile   : container sempit, 1 kolom, header ditumpuk vertikal.
    - Desktop  : container lebih lebar, header jadi 2 kolom (judul + tombol locale sejajar),
                 dan daftar section accordion ditampilkan 2 kolom (md:grid-cols-2).
--}}
@extends('layouts.app')

@section('title', __('help.guide.title'))

@section('content')
<div class="max-w-2xl md:max-w-5xl mx-auto px-4 md:px-8">

    {{-- Tombol Kembali --}}
    <a href="{{ route('user.dashboard') }}" wire:navigate
        class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-6 md:mb-8">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>{{ __('help.back') }}</span>
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-8 md:mb-10">
        <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="p-2.5 md:p-3 rounded-2xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 shrink-0">
                <i data-lucide="book-open" class="w-6 h-6 md:w-7 md:h-7 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ __('help.guide.title') }}
                </h1>
                <p class="text-sm md:text-base font-semibold text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('help.guide.subtitle') }}
                </p>
            </div>
        </div>

        {{-- ⚠️ Sesuaikan nama route 'locale.switch' dengan route switch-locale yang sudah kamu punya --}}
        <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'id' ? 'en' : 'id']) }}"
            class="self-start md:self-auto shrink-0 px-3 py-1.5 rounded-full border-2 border-slate-200 dark:border-slate-700 text-xs font-black uppercase tracking-wide text-slate-600 dark:text-slate-300 hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            {{ app()->getLocale() === 'id' ? 'EN' : 'ID' }}
        </a>
    </div>

    {{-- Accordion --}}
    <div x-data="{ openIndex: 0 }" class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 items-start">
        @foreach (__('help.guide.sections') as $index => $section)
        <div
            @click="openIndex = (openIndex === {{ $index }} ? -1 : {{ $index }})"
            :class="openIndex === {{ $index }} ? 'border-b-4 border-blue-300 dark:border-blue-700 md:col-span-2' : 'border-b-2'"
            class="cursor-pointer select-none rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-5 transition-all duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $section['icon'] }}" class="w-5 h-5 text-blue-600 dark:text-blue-400 pointer-events-none"></i>
                </div>
                <p class="flex-1 text-sm font-extrabold text-slate-900 dark:text-slate-100">
                    {{ $section['title'] }}
                </p>
                <i data-lucide="chevron-down"
                    :class="openIndex === {{ $index }} ? 'rotate-180 text-blue-600 dark:text-blue-400' : 'rotate-0 text-slate-400 dark:text-slate-500'"
                    class="w-5 h-5 shrink-0 transition-transform duration-300 pointer-events-none"></i>
            </div>

            <p x-show="openIndex === {{ $index }}" x-collapse
                class="text-sm font-semibold leading-relaxed text-slate-500 dark:text-slate-400 mt-3 pt-3 border-t-2 border-slate-100 dark:border-slate-700">
                {{ $section['body'] }}
            </p>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
    document.addEventListener('livewire:navigated', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endsection