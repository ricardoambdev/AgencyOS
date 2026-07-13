<?php

namespace App\Core\Engines;

use App\Core\Models\Audit;
use App\Core\Support\CompanyContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditEngine
{
    public function record(
        string $action,
        ?Model $entity = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null,
        ?string $ip = null
    ): Audit {
        return Audit::create([
            'company_id' => app(CompanyContext::class)->id(),
            'user_id' => $userId ?? (Auth::id() ?? null),
            'ip' => $ip ?? request()->ip(),
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity ? $entity->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'created_at' => now(),
        ]);
    }
}
