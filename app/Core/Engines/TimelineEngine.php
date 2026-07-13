<?php

namespace App\Core\Engines;

use App\Core\Models\Timeline;
use Illuminate\Database\Eloquent\Model;

class TimelineEngine
{
    public function record(
        Model $entity,
        string $type,
        string $title,
        ?string $description = null,
        ?array $metadata = null,
        ?int $userId = null
    ): Timeline {
        return Timeline::create([
            'company_id' => $entity->company_id ?? null,
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'user_id' => $userId,
        ]);
    }

    public function for(Model $entity)
    {
        return Timeline::query()
            ->where('entity_type', get_class($entity))
            ->where('entity_id', $entity->getKey())
            ->latest();
    }

    public function forContext(int $limit = 25)
    {
        return Timeline::query()
            ->latest()
            ->take($limit)
            ->get();
    }
}
