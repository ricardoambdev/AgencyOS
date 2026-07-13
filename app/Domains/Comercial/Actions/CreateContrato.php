<?php

namespace App\Domains\Comercial\Actions;

use App\Domains\Comercial\Models\Contrato;

class CreateContrato
{
    public function handle(array $data): Contrato
    {
        if (empty($data['number'])) {
            $data['number'] = 'CTR-'.now()->format('Y').'-'.
                str_pad((Contrato::whereNotNull('number')->count() + 1), 4, '0', STR_PAD_LEFT);
        }

        $contrato = Contrato::create($data);
        $contrato->recordActivity('created', 'Contrato criado');

        return $contrato;
    }
}
