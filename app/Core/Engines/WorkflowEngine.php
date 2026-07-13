<?php

namespace App\Core\Engines;

use App\Core\Models\Workflow;
use Illuminate\Support\Facades\Cache;

class WorkflowEngine
{
    public function definitionFor(string $entityType): ?Workflow
    {
        return Cache::remember("workflow:{$entityType}", 3600, function () use ($entityType) {
            return Workflow::query()
                ->where('entity_type', $entityType)
                ->where('active', true)
                ->first();
        });
    }

    public function states(string $entityType): array
    {
        $wf = $this->definitionFor($entityType);

        return $wf ? ($wf->definition['states'] ?? []) : [];
    }

    public function canTransition(string $entityType, ?string $from, string $to): bool
    {
        $wf = $this->definitionFor($entityType);

        if (! $wf) {
            return true;
        }

        $transitions = $wf->definition['transitions'] ?? [];

        foreach ($transitions as $transition) {
            if (($transition['from'] === $from || $transition['from'] === '*') && $transition['to'] === $to) {
                return true;
            }
        }

        return false;
    }

    public function transition(string $entityType, ?string $from, string $to): bool
    {
        return $this->canTransition($entityType, $from, $to);
    }
}
