<div
    x-data="{ open: false }"
    @click.outside="open = false"
    class="relative"
    wire:poll.30s>
    <button
        @click="open = !open"
        class="relative flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-300 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
        <span class="absolute -top-1 -right-1 flex h-4.5 min-w-[18px] items-center justify-center rounded-full bg-red-500 dark:bg-red-600 px-1 text-[10px] font-black text-white ring-2 ring-white dark:ring-slate-900">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @endif
    </button>

    {{-- Di HP: pakai `fixed` + `inset-x-4` supaya panel selalu pas di dalam lebar layar --}}
    {{-- (margin kiri-kanan sama rata), tidak bergantung pada posisi tombol bell. --}}
    {{-- Di layar sm ke atas: kembali ke `absolute right-0` seperti semula, lebar tetap w-80. --}}
    {{-- Sengaja tidak pakai class arbitrary calc() agar tidak bergantung pada Tailwind JIT scan. --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        x-cloak
        class="fixed inset-x-4 top-16 sm:absolute sm:inset-x-auto sm:top-auto sm:right-0 sm:mt-3 sm:w-80 z-50 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-slate-900 shadow-xl ring-1 ring-black/5 dark:ring-white/10 overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 bg-slate-50 dark:bg-slate-800/60 px-4 py-3">
            <span class="text-sm font-black text-slate-800 dark:text-slate-100">{{ __('notification.title') }}</span>
            <a href="{{ route('notifications') }}" wire:navigate class="text-xs font-extrabold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                {{ __('notification.see_all') }}
            </a>
        </div>

        @if ($recent->isEmpty())
        <div class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
            {{ __('notification.empty') }}
        </div>
        @else
        <div class="max-h-96 overflow-y-auto">
            @foreach ($recent as $notification)
            @php
            $color = match ($notification->type) {
            'task' => '#C9A84C',
            'approval' => '#2563eb',
            'announcement' => '#10b981',
            default => '#64748b',
            };
            $isUnread = is_null($notification->read_at);
            @endphp

            <button
                wire:click="markAsRead({{ $notification->id }})"
                wire:key="bell-notif-{{ $notification->id }}"
                class="flex w-full items-start gap-2.5 border-b border-gray-50 dark:border-gray-800/60 px-4 py-3 text-left last:border-b-0 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                <span class="mt-1 h-2 w-2 shrink-0 rounded-full" style="background-color: {{ $isUnread ? $color : 'transparent' }}"></span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-xs {{ $isUnread ? 'font-black' : 'font-semibold' }} text-slate-800 dark:text-slate-100">
                        {{ $notification->title }}
                    </span>
                    <span class="block truncate text-[11px] text-slate-500 dark:text-slate-400">
                        {{ $notification->body }}
                    </span>
                    <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500">
                        {{ $notification->created_at->locale(app()->getLocale())->diffForHumans() }}
                    </span>
                </span>
            </button>
            @endforeach
        </div>
        @endif
    </div>
</div>