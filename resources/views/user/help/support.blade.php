{{--
    resources/views/user/help/support.blade.php
    Teks diambil dari resources/lang/{locale}/help.php lewat __('help.support.*').
    $itContact dikirim dari route (lihat routes-snippet.php).

    Catatan layout:
    - Mobile   : container sempit, 1 kolom, header ditumpuk vertikal, kartu kontak ditumpuk.
    - Desktop  : container lebih lebar, header jadi 2 kolom (judul + tombol locale sejajar),
                 WhatsApp tetap full-width sebagai highlight, sedangkan Telepon & Email
                 disejajarkan 2 kolom (md:grid-cols-2).
--}}
@extends('layouts.app')

@section('title', __('help.support.title'))

@section('content')
@php
$waLink = 'https://wa.me/' . $itContact['whatsapp'] . '?text=' . urlencode(__('help.support.wa_message'));
$mailLink = 'mailto:' . $itContact['email'] . '?subject=' . urlencode(__('help.support.mail_subject'));
@endphp

<div class="max-w-2xl md:max-w-5xl mx-auto px-4 md:px-8">

    {{-- Tombol Kembali --}}
    <a href="{{ route('user.dashboard') }}" wire:navigate
        class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-6 md:mb-8">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>{{ __('help.back') }}</span>
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6 md:mb-8">
        <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="p-2.5 md:p-3 rounded-2xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 shrink-0">
                <i data-lucide="headphones" class="w-6 h-6 md:w-7 md:h-7 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ __('help.support.title') }}
                </h1>
                <p class="text-sm md:text-base font-semibold text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('help.support.subtitle') }}
                </p>
            </div>
        </div>

        {{-- ⚠️ Sesuaikan nama route 'locale.switch' dengan route switch-locale yang sudah kamu punya --}}
        <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'id' ? 'en' : 'id']) }}"
            class="self-start md:self-auto shrink-0 px-3 py-1.5 rounded-full border-2 border-slate-200 dark:border-slate-700 text-xs font-black uppercase tracking-wide text-slate-600 dark:text-slate-300 hover:border-blue-300 dark:hover:border-blue-600 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            {{ app()->getLocale() === 'id' ? 'EN' : 'ID' }}
        </a>
    </div>

    {{-- Jam Layanan --}}
    <div class="flex items-center gap-2.5 p-3.5 md:p-4 rounded-2xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 mb-6 md:mb-8">
        <i data-lucide="clock" class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0"></i>
        <p class="text-xs md:text-sm font-bold text-slate-800 dark:text-slate-200">
            {{ __('help.support.hours_label') }}: {{ __('help.support.hours') }}
        </p>
    </div>

    {{-- Kartu Kontak --}}
    <div class="space-y-3.5 md:space-y-4">

        {{-- WhatsApp (highlight, tetap full-width) --}}
        <a href="{{ $waLink }}" target="_blank" rel="noopener"
            class="flex items-center gap-3.5 p-4 md:p-5 rounded-2xl border-2 border-b-4 border-blue-700 bg-blue-600 hover:bg-blue-700 transition-colors">
            <div class="w-11 h-11 md:w-12 md:h-12 rounded-2xl border-2 border-white/40 bg-white/20 flex items-center justify-center shrink-0">
                <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm md:text-base font-extrabold text-white">WhatsApp</p>
                <p class="text-xs md:text-sm font-semibold text-white/85">{{ __('help.support.wa_desc') }}</p>
            </div>
            <div class="w-8 h-8 rounded-full border-2 border-white/40 bg-white/20 flex items-center justify-center shrink-0">
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-white"></i>
            </div>
        </a>

        {{-- Telepon & Email: ditumpuk di mobile, 2 kolom di desktop --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 md:gap-4">

            {{-- Telepon --}}
            <a href="tel:{{ $itContact['phone'] }}"
                class="flex items-center gap-3.5 p-4 md:p-5 rounded-2xl border-2 border-b-4 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-200 dark:hover:border-blue-800 transition-colors">
                <div class="w-11 h-11 md:w-12 md:h-12 rounded-2xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                    <i data-lucide="phone" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm md:text-base font-extrabold text-slate-900 dark:text-slate-100">{{ __('help.support.call_title') }}</p>
                    <p class="text-xs md:text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $itContact['phone'] }}</p>
                </div>
                <div class="w-8 h-8 rounded-full border-2 border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 flex items-center justify-center shrink-0">
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                </div>
            </a>

            {{-- Email --}}
            <a href="{{ $mailLink }}"
                class="flex items-center gap-3.5 p-4 md:p-5 rounded-2xl border-2 border-b-4 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-200 dark:hover:border-blue-800 transition-colors">
                <div class="w-11 h-11 md:w-12 md:h-12 rounded-2xl border-2 border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                    <i data-lucide="mail" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm md:text-base font-extrabold text-slate-900 dark:text-slate-100">Email</p>
                    <p class="text-xs md:text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $itContact['email'] }}</p>
                </div>
                <div class="w-8 h-8 rounded-full border-2 border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 flex items-center justify-center shrink-0">
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                </div>
            </a>

        </div>

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