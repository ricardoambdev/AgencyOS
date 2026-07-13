<?php

namespace App\Core\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeGlobalSearch(Builder $builder, string $term, int $limit = 10)
    {
        $term = trim($term);

        if ($term === '') {
            return $builder->whereRaw('1 = 0');
        }

        $builder->where(function (Builder $query) use ($term) {
            foreach ($this->searchableFields() as $field) {
                $query->orWhere($field, 'like', "%{$term}%");
            }
        });

        return $builder->limit($limit);
    }

    public function searchableFields(): array
    {
        return $this->searchable ?? ['name'];
    }
}
