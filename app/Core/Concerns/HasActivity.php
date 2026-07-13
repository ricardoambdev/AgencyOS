<?php

namespace App\Core\Concerns;

use App\Core\Models\Attachment;
use App\Core\Models\Comment;
use App\Core\Models\Favorite;
use App\Core\Models\Tag;
use App\Core\Models\Timeline;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasActivity
{
    public function timeline(): MorphMany
    {
        return $this->morphMany(Timeline::class, 'entity')->latest();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'entity')
            ->whereNull('parent_id')
            ->latest();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'entity')->latest();
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables')
            ->withTimestamps();
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'entity');
    }

    public function isFavoritedBy(?int $userId = null): bool
    {
        $userId ??= auth()->id();

        if (! $userId) {
            return false;
        }

        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function syncTags(iterable $names): void
    {
        $companyId = $this->company_id;
        $ids = [];

        foreach ($names as $name) {
            $name = trim((string) $name);

            if ($name === '') {
                continue;
            }

            $slug = \Illuminate\Support\Str::slug($name);

            $tag = \App\Core\Models\Tag::firstOrCreate(
                ['company_id' => $companyId, 'slug' => $slug],
                ['name' => $name, 'color' => '#6b7280'],
            );

            $ids[] = $tag->id;
        }

        $this->tags()->sync($ids);
    }

    public function recordActivity(string $type, ?string $description = null, ?int $causerId = null): \App\Core\Models\Timeline
    {
        return $this->timeline()->create([
            'company_id' => $this->company_id,
            'type' => $type,
            'title' => $description ?? $type,
            'description' => $description,
            'user_id' => $causerId ?? (auth()->id() ?? $this->getAttribute('user_id')),
        ]);
    }
}
