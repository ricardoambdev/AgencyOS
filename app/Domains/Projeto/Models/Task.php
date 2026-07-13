<?php

namespace App\Domains\Projeto\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use Searchable;
    use SoftDeletes;

    protected $table = 'tasks';

    protected $searchable = ['title', 'description'];

    protected $fillable = [
        'company_id', 'project_id', 'parent_id', 'title', 'description',
        'status', 'priority', 'assignee_id', 'due_date',
        'estimated_hours', 'logged_hours',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
        'logged_hours' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Projeto::class, 'project_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
