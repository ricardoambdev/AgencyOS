<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'automations';

    protected $fillable = [
        'company_id', 'name', 'event', 'conditions', 'actions', 'active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'actions' => 'array',
        'active' => 'boolean',
    ];
}
