<?php

namespace App\Domains\Crm\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use Searchable;
    use SoftDeletes;

    protected $table = 'leads';

    protected $searchable = ['name', 'email', 'company_name'];

    protected $fillable = [
        'company_id', 'name', 'email', 'phone', 'company_name', 'source',
        'status', 'owner_id', 'value', 'notes', 'converted_at', 'converted_client_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'converted_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function convertedClient(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'converted_client_id');
    }
}
