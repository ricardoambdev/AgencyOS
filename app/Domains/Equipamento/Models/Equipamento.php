<?php

namespace App\Domains\Equipamento\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipamento extends Model
{
    use BelongsToCompany;
    use HasActivity;
    use HasUlid;
    use Searchable;
    use SoftDeletes;

    protected $table = 'equipamentos';

    protected $searchable = ['name', 'serial', 'description'];

    protected $fillable = [
        'company_id', 'name', 'type', 'status', 'owner_id', 'serial', 'purchase_date', 'description',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
