<?php

namespace App\Domains\Producao\Actions;

use App\Core\Support\Action;
use App\Domains\Producao\Models\Entregavel;

class DeleteEntregavelAction extends Action
{
    public function handle(Entregavel $entregavel): void
    {
        $entregavel->delete();
    }
}
