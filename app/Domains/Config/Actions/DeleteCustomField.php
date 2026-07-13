<?php

namespace App\Domains\Config\Actions;

use App\Domains\Config\Models\CustomField;

class DeleteCustomField
{
    public function handle(CustomField $field): void
    {
        $field->delete();
    }
}
