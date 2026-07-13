<?php

namespace App\Core\Models;

use App\Domains\Usuario\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboard extends Model
{
    protected $table = 'user_dashboards';

    protected $fillable = [
        'user_id', 'widget', 'order', 'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
