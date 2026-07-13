<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Models\Projeto;

class DeleteProjetoAction extends Action
{
    public function handle(Projeto $projeto): void
    {
        $projeto->delete();
    }
}
