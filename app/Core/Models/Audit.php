<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'audits';

    public $timestamps = false;

    protected $fillable = [
        'company_id', 'user_id', 'ip', 'action',
        'entity_type', 'entity_id', 'old_values', 'new_values', 'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
