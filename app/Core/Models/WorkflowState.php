<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Domains\Company\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowState extends Model
{
    use BelongsToCompany;

    protected $table = 'workflow_states';

    protected $fillable = [
        'company_id', 'entity_type', 'slug', 'name',
        'color', 'order', 'is_initial', 'is_final',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_initial' => 'boolean',
        'is_final' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForEntity($query, string $entityType)
    {
        return $query->where('entity_type', $entityType)->orderBy('order');
    }

    public static function resolve(string $entityType, ?int $companyId = null): array
    {
        $companyId ??= app(\App\Core\Support\CompanyContext::class)->id();

        if ($companyId) {
            $states = static::query()
                ->where('company_id', $companyId)
                ->where('entity_type', $entityType)
                ->orderBy('order')
                ->get();

            if ($states->isNotEmpty()) {
                return $states->mapWithKeys(fn ($s) => [$s->slug => $s->name])->all();
            }
        }

        return \App\Core\Support\EntityCatalog::statusesFor($entityType);
    }

    public static function meta(string $entityType, ?int $companyId = null): array
    {
        $companyId ??= app(\App\Core\Support\CompanyContext::class)->id();

        if ($companyId) {
            $states = static::query()
                ->where('company_id', $companyId)
                ->where('entity_type', $entityType)
                ->orderBy('order')
                ->get();

            if ($states->isNotEmpty()) {
                return $states->mapWithKeys(fn ($s) => [
                    $s->slug => [
                        'name' => $s->name,
                        'color' => $s->color,
                        'is_initial' => $s->is_initial,
                        'is_final' => $s->is_final,
                    ],
                ])->all();
            }
        }

        $defaults = \App\Core\Support\EntityCatalog::statusesFor($entityType);

        return collect($defaults)->mapWithKeys(fn ($name, $slug) => [
            $slug => ['name' => $name, 'color' => null, 'is_initial' => false, 'is_final' => false],
        ])->all();
    }
}
