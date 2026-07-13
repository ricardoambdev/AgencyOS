<?php

namespace App\Domains\Projeto\Models;

use App\Core\Concerns\HasUlid;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTemplateTask extends Model
{
    use HasUlid;

    protected $table = 'project_template_tasks';

    protected $fillable = [
        'project_template_id', 'title', 'description', 'priority', 'estimated_hours', 'order',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:2',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ProjectTemplate::class, 'project_template_id');
    }
}
