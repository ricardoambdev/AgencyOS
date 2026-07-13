<?php

namespace App\Domains\Equipamento\Actions;

use App\Core\Support\Action;
use App\Domains\Equipamento\Models\Equipamento;

class UpdateEquipamentoAction extends Action
{
    public function handle(Equipamento $equipamento, array $data): Equipamento
    {
        $equipamento->update($data);

        return $equipamento;
    }
}
