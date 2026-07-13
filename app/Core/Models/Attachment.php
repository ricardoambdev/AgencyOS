<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use SoftDeletes;

    protected $table = 'attachments';

    protected $fillable = [
        'company_id', 'entity_type', 'entity_id', 'user_id',
        'name', 'path', 'disk', 'mime', 'size',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
