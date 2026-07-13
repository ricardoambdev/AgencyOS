<?php

namespace App\Domains\Cliente\Actions;

use App\Core\Support\Action;
use App\Domains\Cliente\Models\Cliente;

class DeleteClienteAction extends Action
{
    public function handle(Cliente $cliente): void
    {
        $cliente->delete();
    }
}
