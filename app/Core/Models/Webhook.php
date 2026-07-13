<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'webhooks';

    protected $fillable = [
        'company_id', 'name', 'url', 'secret', 'events', 'headers', 'active',
    ];

    protected $casts = [
        'events' => 'array',
        'headers' => 'array',
        'active' => 'boolean',
    ];
}
