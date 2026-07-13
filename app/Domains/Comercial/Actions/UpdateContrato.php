<?php

namespace App\Domains\Comercial\Actions;

use App\Domains\Comercial\Models\Contrato;

class UpdateContrato
{
    public function handle(Contrato $contrato, array $data): Contrato
    {
        $contrato->update($data);
        $contrato->recordActivity('updated', 'Contrato atualizado');

        return $contrato;
    }
}
