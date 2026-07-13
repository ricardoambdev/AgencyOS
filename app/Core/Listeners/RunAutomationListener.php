<?php

namespace App\Core\Listeners;

use App\Core\Engines\AutomationEngine;
use App\Core\Events\EntityEvent;

class RunAutomationListener
{
    public function handle(EntityEvent $event): void
    {
        $name = class_basename($event);

        app(AutomationEngine::class)->run($name, $event->model);
    }
}
