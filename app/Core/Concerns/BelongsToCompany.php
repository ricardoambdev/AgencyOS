<?php

namespace App\Core\Concerns;

use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;

trait BelongsToCompany
{
    public static function bootBelongsToCompany(): void
    {
        static::addGlobalScope('company', function (Builder $builder) {
            $companyId = app(CompanyContext::class)->id();

            if ($companyId) {
                $builder->where($builder->getModel()->getTable().'.company_id', $companyId);
            }
        });

        static::creating(function (Model $model) {
            if (empty($model->company_id)) {
                $model->company_id = app(CompanyContext::class)->id();
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Company\Models\Company::class);
    }

    public function scopeWithoutCompanyScope(Builder $builder): Builder
    {
        return $builder->withoutGlobalScope('company');
    }
}
