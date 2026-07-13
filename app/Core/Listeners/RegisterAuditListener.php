<?php

namespace App\Core\Listeners;

use App\Core\Engines\AuditEngine;
use App\Core\Events\EntityEvent;

class RegisterAuditListener
{
    public function handle(EntityEvent $event): void
    {
        $model = $event->model;

        app(AuditEngine::class)->record(
            'created',
            $model,
            [],
            $model->getAttributes()
        );
    }
}
