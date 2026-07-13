<?php

namespace App\Core\Engines;

use App\Core\Models\Notification;
use App\Core\Support\CompanyContext;

class NotificationEngine
{
    public function notify(
        int $userId,
        string $title,
        string $body = '',
        ?string $link = null,
        string $type = 'system'
    ): Notification {
        return Notification::create([
            'company_id' => app(CompanyContext::class)->id(),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'link' => $link,
        ]);
    }

    public function unreadFor(int $userId)
    {
        return Notification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->latest();
    }

    public function allFor(int $userId)
    {
        return Notification::query()
            ->where('user_id', $userId)
            ->latest();
    }
}
