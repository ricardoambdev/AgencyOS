<?php

namespace App\Domains\Projeto\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasCustomFields;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projeto extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use HasCustomFields;
    use Searchable;
    use SoftDeletes;

    protected $table = 'projetos';

    protected $searchable = ['name', 'description'];

    protected $fillable = [
        'company_id', 'client_id', 'name', 'status', 'owner_id',
        'start_date', 'end_date', 'budget', 'description',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
