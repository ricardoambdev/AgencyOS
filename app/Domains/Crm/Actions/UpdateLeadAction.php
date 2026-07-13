<?php

namespace App\Domains\Crm\Actions;

use App\Core\Support\Action;
use App\Domains\Crm\Models\Lead;

class UpdateLeadAction extends Action
{
    public function handle(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead;
    }
}
