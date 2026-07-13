<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use BelongsToCompany;
    use HasUlid;

    protected $table = 'tags';

    protected $fillable = [
        'company_id', 'name', 'color', 'slug',
    ];
}
