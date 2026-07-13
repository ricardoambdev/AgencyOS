<?php

namespace App\Core\Listeners;

use App\Core\Engines\AuditEngine;
use App\Core\Engines\TimelineEngine;
use App\Core\Events\EntityEvent;

class RecordTimelineListener
{
    public function handle(EntityEvent $event): void
    {
        $model = $event->model;
        $label = class_basename($model);

        app(TimelineEngine::class)->record(
            $model,
            'created',
            "{$label} criado(a)",
            null,
            null,
            auth()->id()
        );
    }
}
