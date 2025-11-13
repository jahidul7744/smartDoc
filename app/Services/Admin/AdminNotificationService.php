<?php

namespace App\Services\Admin;

use App\Notifications\AdminBroadcastNotification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class AdminNotificationService
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return DatabaseNotification::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function unreadCount(): int
    {
        return DatabaseNotification::query()
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(array $notificationIds): int
    {
        return DatabaseNotification::query()
            ->whereIn('id', $notificationIds)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(): int
    {
        return DatabaseNotification::query()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function delete(array $notificationIds): int
    {
        return DatabaseNotification::query()
            ->whereIn('id', $notificationIds)
            ->delete();
    }

    public function broadcastToAll(string $title, string $message, ?string $actionUrl = null): void
    {
        $recipients = User::query()
            ->whereIn('role', ['patient', 'doctor', 'diagnostic_center'])
            ->get();

        Notification::send($recipients, new AdminBroadcastNotification($title, $message, $actionUrl));
    }

    public function broadcastToRole(string $role, string $title, string $message, ?string $actionUrl = null): void
    {
        $recipients = User::query()
            ->where('role', $role)
            ->get();

        Notification::send($recipients, new AdminBroadcastNotification($title, $message, $actionUrl));
    }

    public function broadcastToUsers(Collection $userIds, string $title, string $message, ?string $actionUrl = null): void
    {
        $recipients = User::query()
            ->whereIn('id', $userIds)
            ->get();

        Notification::send($recipients, new AdminBroadcastNotification($title, $message, $actionUrl));
    }
}

