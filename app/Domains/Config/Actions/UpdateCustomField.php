<?php

namespace App\Domains\Config\Actions;

use App\Domains\Config\Models\CustomField;

class UpdateCustomField
{
    public function handle(CustomField $field, array $data): CustomField
    {
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = app(CreateCustomField::class)->normalizeOptions($data['options']);
        }

        $field->update($data);

        return $field;
    }
}
