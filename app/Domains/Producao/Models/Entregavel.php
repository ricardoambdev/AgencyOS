<?php

namespace App\Domains\Producao\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entregavel extends Model
{
    use BelongsToCompany;
    use HasActivity;
    use HasUlid;
    use Searchable;
    use SoftDeletes;

    protected $table = 'entregaveis';

    protected $searchable = ['name', 'description'];

    protected $fillable = [
        'company_id', 'project_id', 'name', 'type', 'status', 'owner_id',
        'due_date', 'description', 'version', 'client_visible',
    ];

    protected $casts = [
        'due_date' => 'date',
        'client_visible' => 'boolean',
        'version' => 'integer',
    ];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class, 'project_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
