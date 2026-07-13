<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Events\ProjectCreated;
use App\Domains\Projeto\Models\Projeto;

class CreateProjetoAction extends Action
{
    public function handle(array $data): Projeto
    {
        $projeto = Projeto::create($data);

        ProjectCreated::dispatch($projeto);

        return $projeto;
    }
}
