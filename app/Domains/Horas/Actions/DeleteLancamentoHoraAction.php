<?php

namespace App\Domains\Horas\Actions;

use App\Core\Support\Action;
use App\Domains\Horas\Models\LancamentoHora;

class DeleteLancamentoHoraAction extends Action
{
    public function handle(LancamentoHora $lancamento): void
    {
        $lancamento->delete();
    }
}
