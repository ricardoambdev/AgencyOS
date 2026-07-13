<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Models\Projeto;

class UpdateProjetoAction extends Action
{
    public function handle(Projeto $projeto, array $data): Projeto
    {
        $projeto->update($data);

        return $projeto;
    }
}
