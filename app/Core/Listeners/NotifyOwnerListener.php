<?php

namespace App\Core\Listeners;

use App\Core\Engines\NotificationEngine;
use App\Core\Events\EntityEvent;

class NotifyOwnerListener
{
    public function handle(EntityEvent $event): void
    {
        $model = $event->model;

        $ownerId = $model->owner_id ?? $model->assignee_id ?? null;

        if (! $ownerId || $ownerId == auth()->id()) {
            return;
        }

        $label = class_basename($model);

        app(NotificationEngine::class)->notify(
            $ownerId,
            "Novo {$label}",
            "Você foi definido como responsável por ".(($model->name ?? $model->title) ?? $label),
            null
        );
    }
}
