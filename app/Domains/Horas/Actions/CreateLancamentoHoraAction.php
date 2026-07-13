<?php

namespace App\Domains\Horas\Actions;

use App\Core\Support\Action;
use App\Domains\Horas\Models\LancamentoHora;

class CreateLancamentoHoraAction extends Action
{
    public function handle(array $data): LancamentoHora
    {
        return LancamentoHora::create($data);
    }
}
