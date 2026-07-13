<?php

namespace App\Domains\Cliente\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Projeto\Models\Projeto;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cliente extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use Searchable;
    use SoftDeletes;

    protected $table = 'clientes';

    protected $searchable = ['name', 'email', 'document'];

    protected $fillable = [
        'company_id', 'name', 'email', 'phone', 'document', 'type', 'address', 'owner_id', 'portal_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->portal_token)) {
                $model->portal_token = Str::random(40);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class, 'client_id');
    }

    public function portalUrl(): string
    {
        return url("/portal/{$this->portal_token}");
    }
}
