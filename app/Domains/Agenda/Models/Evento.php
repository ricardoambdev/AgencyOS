<?php

namespace App\Domains\Agenda\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use Searchable;
    use SoftDeletes;

    protected $table = 'eventos';

    protected $searchable = ['title', 'location', 'description'];

    protected $fillable = [
        'company_id', 'title', 'description', 'start_at', 'end_at',
        'all_day', 'location', 'user_id', 'project_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'all_day' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Projeto::class, 'project_id');
    }
}
