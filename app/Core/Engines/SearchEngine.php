<?php

namespace App\Core\Engines;

class SearchEngine
{
    protected array $types = [
        'leads' => \App\Domains\Crm\Models\Lead::class,
        'clients' => \App\Domains\Cliente\Models\Cliente::class,
        'projects' => \App\Domains\Projeto\Models\Projeto::class,
        'tasks' => \App\Domains\Projeto\Models\Task::class,
        'events' => \App\Domains\Agenda\Models\Evento::class,
    ];

    public function register(string $key, string $model): void
    {
        $this->types[$key] = $model;
    }

    public function search(?string $term, ?int $limit = 10): array
    {
        if (blank($term)) {
            return [];
        }

        $results = [];

        foreach ($this->types as $key => $model) {
            if (! class_exists($model)) {
                continue;
            }

            $items = $model::query()->globalSearch($term, $limit)->get();

            if ($items->isNotEmpty()) {
                $results[$key] = $items;
            }
        }

        return $results;
    }
}
