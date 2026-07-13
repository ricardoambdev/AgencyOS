<?php

namespace App\Domains\Company\Models;

use App\Core\Concerns\HasUlid;
use App\Core\Concerns\BelongsToCompany;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasUlid;
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'ulid', 'name', 'slug', 'workspace_template', 'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot(['role_id', 'status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(\App\Domains\Company\Models\CompanyUser::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
