<?php

namespace App\Domains\Equipamento\Actions;

use App\Core\Support\Action;
use App\Domains\Equipamento\Models\Equipamento;

class DeleteEquipamentoAction extends Action
{
    public function handle(Equipamento $equipamento): void
    {
        $equipamento->delete();
    }
}
