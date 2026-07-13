<?php

namespace App\Domains\Comercial\Actions;

use App\Domains\Comercial\Models\Contrato;

class DeleteContrato
{
    public function handle(Contrato $contrato): void
    {
        $contrato->recordActivity('deleted', 'Contrato excluído');
        $contrato->delete();
    }
}
