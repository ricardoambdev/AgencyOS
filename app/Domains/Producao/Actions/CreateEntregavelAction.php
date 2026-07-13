<?php

namespace App\Domains\Producao\Actions;

use App\Core\Support\Action;
use App\Domains\Producao\Models\Entregavel;

class CreateEntregavelAction extends Action
{
    public function handle(array $data): Entregavel
    {
        return Entregavel::create($data);
    }
}
