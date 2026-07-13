<?php

namespace App\Domains\Financeiro\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasUlid;
use App\Domains\Financeiro\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use BelongsToCompany;
    use HasUlid;
    use SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'company_id', 'type', 'category', 'amount', 'date', 'description', 'invoice_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
