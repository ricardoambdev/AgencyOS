<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'company_id', 'entity_type', 'entity_id', 'parent_id',
        'user_id', 'body', 'visibility', 'reactions', 'mentions',
    ];

    protected $casts = [
        'reactions' => 'array',
        'mentions' => 'array',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Usuario\Models\User::class);
    }
}
