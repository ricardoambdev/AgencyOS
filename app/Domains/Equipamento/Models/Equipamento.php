<?php

namespace App\Domains\Equipamento\Models;

use App\Core\Concerns\BelongsToCompany;
use App\Core\Concerns\HasActivity;
use App\Core\Concerns\HasUlid;
use App\Core\Concerns\Searchable;
use App\Domains\Usuario\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipamento extends Model
{
    use BelongsToCompany;
    use HasActivity;
    use HasUlid;
    use Searchable;
    use SoftDeletes;

    protected $table = 'equipamentos';

    protected $searchable = ['name', 'serial', 'description'];

    protected $fillable = [
        'company_id', 'name', 'type', 'status', 'owner_id', 'serial', 'purchase_date', 'description',
        'valor', 'tem_seguro', 'valor_seguro', 'situacao',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'valor' => 'decimal:2',
        'tem_seguro' => 'boolean',
        'valor_seguro' => 'decimal:2',
    ];

    public static function situacoes(): array
    {
        return [
            'funcional' => 'Funcional',
            'desatualizada' => 'Desatualizada',
            'quebrada' => 'Quebrada',
        ];
    }

    public static function situacaoVariant(string $situacao): string
    {
        return match ($situacao) {
            'funcional' => 'success',
            'desatualizada' => 'warning',
            'quebrada' => 'danger',
            default => 'neutral',
        };
    }

    public function tempoDeUso(): ?string
    {
        if (!$this->purchase_date) {
            return null;
        }

        $now = now();
        $totalMonths = $this->purchase_date->diffInMonths($now);
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;
        $days = $this->purchase_date->copy()->addMonths($totalMonths)->diffInDays($now);

        if ($years >= 1) {
            if ($months === 6) {
                return $years === 1 ? '1 ano e meio' : "{$years} anos e meio";
            }

            $parts = [$years === 1 ? '1 ano' : "{$years} anos"];
            if ($months > 0) {
                $parts[] = $months === 1 ? '1 mês' : "{$months} meses";
            }

            return implode(' e ', $parts);
        }

        if ($months >= 1) {
            return $months === 1 ? '1 mês' : "{$months} meses";
        }

        return $days === 1 ? '1 dia' : "{$days} dias";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
