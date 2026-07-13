<?php

namespace App\Domains\Cliente\Actions;

use App\Core\Support\Action;
use App\Domains\Cliente\Events\ClientCreated;
use App\Domains\Cliente\Models\Cliente;

class CreateClienteAction extends Action
{
    public function handle(array $data): Cliente
    {
        $cliente = Cliente::create($data);

        ClientCreated::dispatch($cliente);

        return $cliente;
    }
}
