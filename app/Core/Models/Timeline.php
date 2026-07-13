<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Timeline extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'timelines';

    protected $fillable = [
        'company_id', 'entity_type', 'entity_id', 'type',
        'title', 'description', 'metadata', 'user_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Usuario\Models\User::class);
    }
}
