<?php

namespace App\Domains\Crm\Actions;

use App\Core\Support\Action;
use App\Domains\Crm\Events\LeadCreated;
use App\Domains\Crm\Models\Lead;

class CreateLeadAction extends Action
{
    public function handle(array $data): Lead
    {
        $lead = Lead::create($data + ['status' => $data['status'] ?? 'new']);

        LeadCreated::dispatch($lead);

        return $lead;
    }
}
