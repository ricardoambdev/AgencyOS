<?php

namespace App\Domains\Projeto\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTemplate extends Model
{
    use HasUlid;
    use BelongsToCompany;
    use SoftDeletes;
    use HasActivity;
    use Searchable;

    protected $table = 'project_templates';

    protected $searchable = ['name', 'description'];

    protected $fillable = [
        'company_id', 'name', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function templateTasks(): HasMany
    {
        return $this->hasMany(ProjectTemplateTask::class)->orderBy('order');
    }
}
