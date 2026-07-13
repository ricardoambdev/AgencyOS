<?php

namespace App\Domains\Equipamento\Actions;

use App\Core\Support\Action;
use App\Domains\Equipamento\Models\Equipamento;

class CreateEquipamentoAction extends Action
{
    public function handle(array $data): Equipamento
    {
        return Equipamento::create($data);
    }
}
