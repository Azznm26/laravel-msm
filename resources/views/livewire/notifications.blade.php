<div>
    {{-- HEADER --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div class="flex items-center">
            {{-- PERBAIKAN: Rute dinamis berdasarkan role user --}}
            <a href="{{ in_array(auth()->user()->role, ['admin', 'super_admin']) ? route('admin.dashboard') : route('user.dashboard') }}" wire:navigate
                class="mr-3.5 flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2.5 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-black text-slate-900">Notifikasi</h1>
        </div>

        @if ($hasUnread)
        <button
            wire:click="markAllAsRead"
            wire:loading.attr="disabled"
            wire:target="markAllAsRead"
            class="px-1 py-1.5 text-xs font-extrabold text-blue-600 hover:text-blue-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="markAllAsRead">Tandai semua dibaca</span>
            <span wire:loading wire:target="markAllAsRead">Memproses...</span>
        </button>
        @endif
    </div>

    {{-- LIST / EMPTY STATE --}}
    @if ($notifications->isEmpty())
    <div class="flex flex-col items-center justify-center px-10 py-24 text-center">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-[20px] border border-slate-200 bg-slate-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                <line x1="3" y1="3" x2="21" y2="21" stroke-width="2" />
            </svg>
        </div>
        <p class="mb-1.5 text-base font-extrabold text-slate-900">Belum ada notifikasi</p>
        <p class="max-w-xs text-sm leading-snug text-slate-500">
            Tugas baru, persetujuan, dan pengumuman akan muncul di sini.
        </p>
    </div>
    @else
    <div class="space-y-3 px-5 pb-8 pt-4">
        @foreach ($notifications as $notification)
        @php
        $config = match ($notification->type) {
        'task' => ['color' => '#C9A84C', 'bg' => 'bg-amber-50'],
        'approval' => ['color' => '#2563eb', 'bg' => 'bg-blue-50'],
        'announcement' => ['color' => '#10b981', 'bg' => 'bg-emerald-50'],
        default => ['color' => '#64748b', 'bg' => 'bg-slate-50'],
        };
        $isUnread = is_null($notification->read_at);
        @endphp

        <button
            wire:click="markAsRead({{ $notification->id }})"
            wire:key="notif-{{ $notification->id }}"
            class="flex w-full items-start rounded-2xl border bg-white p-3.5 text-left transition hover:bg-slate-50 shadow-sm {{ $isUnread ? 'border-l-[3px]' : 'border-gray-100' }}"
            style="{{ $isUnread ? 'border-color:' . $config['color'] . ';border-left-color:' . $config['color'] . ';' : '' }}">
            <span class="mr-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-[14px] border border-slate-200 {{ $config['bg'] }}">
                <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $config['color'] }}"></span>
            </span>

            <span class="min-w-0 flex-1">
                <span class="mb-1 flex items-center">
                    <span class="truncate text-sm {{ $isUnread ? 'font-black' : 'font-bold' }} text-slate-900">
                        {{ $notification->title }}
                    </span>
                    @if ($isUnread)
                    <span class="ml-2 h-[7px] w-[7px] shrink-0 rounded-full bg-red-500"></span>
                    @endif
                </span>

                <span class="block text-xs leading-snug text-slate-500 line-clamp-2">
                    {{ $notification->body }}
                </span>

                <span class="mt-1.5 block text-[10px] font-bold text-slate-400">
                    {{ $notification->created_at->locale('id')->diffForHumans() }}
                </span>
            </span>
        </button>
        @endforeach
    </div>

    <div class="px-5">
        {{ $notifications->links() }}
    </div>
    @endif
</div>