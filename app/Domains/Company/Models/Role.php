<?php

namespace App\Domains\Company\Models;

use App\Core\Concerns\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasUlid;

    protected $table = 'roles';

    protected $fillable = [
        'company_id', 'name', 'slug', 'capabilities',
    ];

    protected $casts = [
        'capabilities' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Domains\Usuario\Models\User::class, 'company_user')
            ->withPivot(['status', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    public function hasCapability(string $capability): bool
    {
        $caps = $this->capabilities ?? [];

        if (in_array('*', $caps, true)) {
            return true;
        }

        return in_array($capability, $caps, true);
    }
}
