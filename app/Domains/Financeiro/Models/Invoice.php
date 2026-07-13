<?php

namespace App\Domains\Financeiro\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Domains\Cliente\Models\Cliente;
use App\Domains\Projeto\Models\Projeto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use HasActivity;
    use SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = [
        'company_id', 'number', 'client_id', 'project_id', 'status',
        'issued_at', 'due_at', 'subtotal', 'tax', 'total', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'issued_at' => 'date',
        'due_at' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
