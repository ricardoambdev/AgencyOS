<?php

namespace App\Domains\Crm\Actions;

use App\Core\Support\Action;
use App\Domains\Crm\Models\Lead;

class DeleteLeadAction extends Action
{
    public function handle(Lead $lead): void
    {
        $lead->delete();
    }
}
