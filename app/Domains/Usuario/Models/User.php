<?php

namespace App\Domains\Usuario\Models;

use App\Core\Concerns\HasUlid;
use App\Domains\Company\Models\Company;
use App\Domains\Company\Models\Role;
use App\Domains\Projeto\Models\Task;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasUlid;
    use MustVerifyEmailTrait;
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'ulid', 'name', 'email', 'password', 'avatar', 'timezone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot(['role_id', 'status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function currentRole(): ?Role
    {
        $membership = $this->companies()
            ->wherePivot('company_id', app(\App\Core\Support\CompanyContext::class)->id())
            ->first();

        if (! $membership || ! $membership->pivot->role_id) {
            return null;
        }

        return Role::find($membership->pivot->role_id);
    }

    public function canCapability(string $capability): bool
    {
        $role = $this->currentRole();

        return $role ? $role->hasCapability($capability) : false;
    }
}
