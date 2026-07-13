<?php

namespace App\Providers;

use App\Core\Events\EntityEvent;
use App\Core\Listeners\NotifyOwnerListener;
use App\Core\Listeners\RecordTimelineListener;
use App\Core\Listeners\RegisterAuditListener;
use App\Core\Models\Comment;
use App\Core\Policies\CommentPolicy;
use App\Domains\Crm\Events\LeadCreated;
use App\Domains\Crm\Models\Lead;
use App\Domains\Crm\Policies\LeadPolicy;
use App\Domains\Cliente\Events\ClientCreated;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Cliente\Policies\ClientePolicy;
use App\Domains\Projeto\Events\ProjectCreated;
use App\Domains\Projeto\Events\TaskCreated;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Projeto\Models\Task;
use App\Domains\Projeto\Policies\ProjetoPolicy;
use App\Domains\Projeto\Policies\TaskPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Projeto::class, ProjetoPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);

        Gate::before(function ($user, $ability) {
            if (! method_exists($user, 'currentRole')) {
                return null;
            }

            if (is_null($user->currentRole())) {
                return false;
            }

            return null;
        });

        Gate::after(function ($user, $ability) {
            if (! str_starts_with($ability, 'cap:')) {
                return null;
            }

            if (! method_exists($user, 'canCapability')) {
                return null;
            }

            if (is_null($user->currentRole())) {
                return null;
            }

            return $user->canCapability(substr($ability, 4));
        });

        $entityEvents = [
            LeadCreated::class,
            ClientCreated::class,
            ProjectCreated::class,
            TaskCreated::class,
        ];

        foreach ($entityEvents as $event) {
            Event::listen($event, RecordTimelineListener::class);
            Event::listen($event, RegisterAuditListener::class);
            Event::listen($event, NotifyOwnerListener::class);
            Event::listen($event, \App\Core\Listeners\RunAutomationListener::class);
            Event::listen($event, \App\Core\Listeners\DispatchWebhooksListener::class);
        }
    }
}
