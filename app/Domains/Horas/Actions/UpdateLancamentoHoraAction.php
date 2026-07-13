<?php

namespace App\Domains\Horas\Actions;

use App\Core\Support\Action;
use App\Domains\Horas\Models\LancamentoHora;

class UpdateLancamentoHoraAction extends Action
{
    public function handle(LancamentoHora $lancamento, array $data): LancamentoHora
    {
        $lancamento->update($data);

        return $lancamento;
    }
}
