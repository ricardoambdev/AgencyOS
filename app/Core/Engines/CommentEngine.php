<?php

namespace App\Core\Engines;

use App\Core\Models\Comment;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;

class CommentEngine
{
    public function add(
        Model $entity,
        ?int $userId,
        string $body,
        string $visibility = 'internal',
        ?int $parentId = null
    ): Comment {
        return Comment::create([
            'company_id' => app(CompanyContext::class)->id(),
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'user_id' => $userId,
            'body' => $body,
            'visibility' => $visibility,
            'parent_id' => $parentId,
        ]);
    }

    public function for(Model $entity)
    {
        return Comment::query()
            ->where('entity_type', get_class($entity))
            ->where('entity_id', $entity->getKey())
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest();
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return (new Comment())->replies();
    }
}
