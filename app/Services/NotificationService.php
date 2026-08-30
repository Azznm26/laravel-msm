<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class NotificationService
{
    public function __construct(protected Messaging $messaging) {}

    /**
     * Kirim notifikasi ke satu user (simpan ke DB + push FCM).
     */
    public function sendToUser(User $user, string $type, string $title, string $body, array $data = [])
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        $this->pushFcm($user, $title, $body, $data);

        return $notification;
    }

    /**
     * Kirim notifikasi ke banyak user sekaligus (mis. pengumuman).
     */
    public function sendToUsers(iterable $users, string $type, string $title, string $body, array $data = [])
    {
        foreach ($users as $user) {
            $this->sendToUser($user, $type, $title, $body, $data);
        }
    }

    /**
     * Kirim ke semua user dengan role tertentu.
     */
    public function sendToRole(array $roles, string $type, string $title, string $body, array $data = [])
    {
        $users = User::whereIn('role', $roles)->get();
        $this->sendToUsers($users, $type, $title, $body, $data);
    }

    protected function pushFcm(User $user, string $title, string $body, array $data = [])
    {
        $tokens = $user->deviceTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            return;
        }

        // FCM data payload harus berupa string semua
        $stringData = array_map('strval', $data);

        $message = CloudMessage::new()
            ->withNotification(FcmNotification::create($title, $body))
            ->withData($stringData);

        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::error('FCM push gagal: ' . $e->getMessage());
        }
    }
}
