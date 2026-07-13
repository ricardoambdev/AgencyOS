<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'favorites';

    protected $fillable = [
        'company_id', 'user_id', 'entity_type', 'entity_id',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
