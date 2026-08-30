<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public function getUnreadCountProperty(): int
    {
        return Notification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    public function getRecentProperty()
    {
        return Notification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(6)
            ->get();
    }

    public function markAsRead(int $id)
    {
        $notification = Notification::query()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        $route = $notification->data['route'] ?? null;

        if ($route) {
            return $this->redirect($route, navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.notification-bell', [
            'unreadCount' => $this->unreadCount,
            'recent' => $this->recent,
        ]);
    }
}
