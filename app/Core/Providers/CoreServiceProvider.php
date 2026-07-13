<?php

namespace App\Core\Providers;

use App\Core\Engines\AttachmentEngine;
use App\Core\Engines\AuditEngine;
use App\Core\Engines\AutomationEngine;
use App\Core\Engines\CommentEngine;
use App\Core\Engines\NotificationEngine;
use App\Core\Engines\SearchEngine;
use App\Core\Engines\SettingsEngine;
use App\Core\Engines\TimelineEngine;
use App\Core\Engines\WorkflowEngine;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TimelineEngine::class, TimelineEngine::class);
        $this->app->singleton(CommentEngine::class, CommentEngine::class);
        $this->app->singleton(AttachmentEngine::class, AttachmentEngine::class);
        $this->app->singleton(NotificationEngine::class, NotificationEngine::class);
        $this->app->singleton(AuditEngine::class, AuditEngine::class);
        $this->app->singleton(SearchEngine::class, SearchEngine::class);
        $this->app->singleton(WorkflowEngine::class, WorkflowEngine::class);
        $this->app->singleton(AutomationEngine::class, AutomationEngine::class);
        $this->app->singleton(SettingsEngine::class, SettingsEngine::class);
    }

    public function boot(): void
    {
        //
    }
}
