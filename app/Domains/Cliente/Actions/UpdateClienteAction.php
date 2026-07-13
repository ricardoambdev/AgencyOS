<?php

namespace App\Domains\Cliente\Actions;

use App\Core\Support\Action;
use App\Domains\Cliente\Models\Cliente;

class UpdateClienteAction extends Action
{
    public function handle(Cliente $cliente, array $data): Cliente
    {
        $cliente->update($data);

        return $cliente;
    }
}
