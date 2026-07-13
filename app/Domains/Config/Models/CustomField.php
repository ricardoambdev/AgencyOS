<?php

namespace App\Domains\Config\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use HasUlid;
    use BelongsToCompany;
    use SoftDeletes;

    protected $table = 'custom_fields';

    protected $fillable = [
        'company_id', 'entity_type', 'name', 'label', 'type', 'options', 'is_filterable', 'is_required', 'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_filterable' => 'boolean',
        'is_required' => 'boolean',
    ];

    public const TYPES = [
        'text', 'number', 'currency', 'date', 'time', 'boolean',
        'file', 'image', 'select', 'multiselect', 'relation',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
