<?php

namespace App\Core\Listeners;

use App\Core\Engines\WebhookEngine;
use App\Core\Events\EntityEvent;

class DispatchWebhooksListener
{
    public function handle(EntityEvent $event): void
    {
        $name = class_basename($event);

        app(WebhookEngine::class)->trigger($name, $event->model);
    }
}
