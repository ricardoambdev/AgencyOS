<?php

namespace App\Core\Engines;

use App\Core\Models\Automation;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;

class AutomationEngine
{
    public function run(string $event, ?Model $subject = null): void
    {
        $automations = Automation::query()
            ->where('event', $event)
            ->where('active', true)
            ->get();

        foreach ($automations as $automation) {
            if (! $this->conditionsMet($automation, $subject)) {
                continue;
            }

            foreach ($automation->actions as $action) {
                $this->execute($action, $subject, $automation);
            }
        }
    }

    protected function conditionsMet(Automation $automation, ?Model $subject): bool
    {
        foreach ($automation->conditions as $condition) {
            $field = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? '=';
            $value = $condition['value'] ?? null;

            if (! $subject || data_get($subject, $field) != $value && $operator === '=') {
                return false;
            }
        }

        return true;
    }

    protected function execute(array $action, ?Model $subject, Automation $automation): void
    {
        $type = $action['type'] ?? null;

        match ($type) {
            'notify' => $this->notify($action, $subject),
            'timeline' => $this->timeline($action, $subject),
            'webhook' => $this->webhook($action, $subject),
            default => null,
        };
    }

    protected function notify(array $action, ?Model $subject): void
    {
        $userId = $action['user_id'] ?? optional($subject)->owner_id ?? null;

        if ($userId) {
            app(NotificationEngine::class)->notify(
                $userId,
                $action['title'] ?? 'Automation',
                $action['body'] ?? '',
                $action['link'] ?? null
            );
        }
    }

    protected function timeline(array $action, ?Model $subject): void
    {
        if ($subject) {
            app(TimelineEngine::class)->record(
                $subject,
                'automation',
                $action['title'] ?? 'Automation executed'
            );
        }
    }

    protected function webhook(array $action, ?Model $subject): void
    {
        $url = $action['url'] ?? null;

        if (! $url) {
            return;
        }

        $payload = [
            'event' => $action['event'] ?? null,
            'subject' => $subject ? $subject->toArray() : null,
        ];

        \Illuminate\Support\Facades\Http::timeout(5)
            ->post($url, $payload);
    }
}
