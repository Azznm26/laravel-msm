<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationBadge extends Component
{
    public function render()
    {
        // Hitung notifikasi yang belum dibaca
        $unreadCount = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return view('livewire.notification-badge', [
            'unreadCount' => $unreadCount
        ]);
    }
}
