<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'workflows';

    protected $fillable = [
        'company_id', 'name', 'entity_type', 'definition', 'active',
    ];

    protected $casts = [
        'definition' => 'array',
        'active' => 'boolean',
    ];
}
