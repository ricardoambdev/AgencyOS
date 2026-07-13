<?php

namespace App\Domains\Producao\Actions;

use App\Core\Support\Action;
use App\Domains\Producao\Models\Entregavel;

class UpdateEntregavelAction extends Action
{
    public function handle(Entregavel $entregavel, array $data): Entregavel
    {
        $entregavel->update($data);

        return $entregavel;
    }
}
