<?php

namespace App\Domains\Config\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CustomFieldValue extends Model
{
    use BelongsToCompany;

    protected $table = 'custom_field_values';

    protected $fillable = [
        'company_id', 'custom_field_id', 'entity_type', 'entity_id', 'value',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
