<?php

namespace App\Core\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Domains\Projeto\Models\Projeto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    use BelongsToCompany;

    protected $table = 'checklist_items';

    protected $fillable = [
        'company_id', 'project_id', 'label', 'done', 'order',
    ];

    protected $casts = [
        'done' => 'boolean',
        'order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
