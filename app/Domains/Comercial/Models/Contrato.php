<?php

namespace App\Domains\Comercial\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Cliente\Models\Cliente;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use HasUlid;
    use BelongsToCompany;
    use SoftDeletes;
    use HasActivity;
    use Searchable;

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'signed_at' => 'date',
    ];

    protected $fillable = [
        'company_id', 'number', 'client_id', 'responsavel_id', 'title', 'type',
        'value', 'currency', 'start_date', 'end_date', 'status', 'renewal_type',
        'renewal_date', 'signed_at', 'description',
    ];

    protected $searchable = ['title', 'number', 'description'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'client_id');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function scopeFilter($q, array $f)
    {
        if (! empty($f['status'])) {
            $q->where('status', $f['status']);
        }
        if (! empty($f['type'])) {
            $q->where('type', $f['type']);
        }
        if (! empty($f['client_id'])) {
            $q->where('client_id', $f['client_id']);
        }
        if (! empty($f['q'])) {
            $q->where(function ($q) use ($f) {
                $q->where('title', 'like', '%'.$f['q'].'%')
                  ->orWhere('number', 'like', '%'.$f['q'].'%');
            });
        }

        return $q;
    }
}
