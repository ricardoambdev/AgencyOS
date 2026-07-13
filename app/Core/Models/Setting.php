<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'settings';

    protected $fillable = [
        'company_id', 'key', 'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
