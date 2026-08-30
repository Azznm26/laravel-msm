<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Notifications extends Component
{
    use WithPagination;

    public bool $markingAll = false;

    /**
     * Query notifikasi milik user yang sedang login, terbaru dulu.
     */
    public function getNotificationsProperty()
    {
        return Notification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
    }

    public function getHasUnreadProperty(): bool
    {
        return Notification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->exists();
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca, lalu redirect
     * kalau ada route tujuan di payload data.
     */
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

    public function markAllAsRead()
    {
        $this->markingAll = true;

        Notification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->markingAll = false;
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->notifications,
            'hasUnread' => $this->hasUnread,
        ]);
    }
}
