<?php

namespace App\Domains\Config\Actions;

use App\Domains\Config\Models\CustomField;

class CreateCustomField
{
    public function handle(array $data): CustomField
    {
        if (isset($data['options']) && is_array($data['options'])) {
            $data['options'] = $this->normalizeOptions($data['options']);
        }

        return CustomField::create($data);
    }

    protected function normalizeOptions(array $options): array
    {
        $normalized = [];

        foreach ($options as $option) {
            if (is_array($option)) {
                $key = $option['value'] ?? null;
                $label = $option['label'] ?? null;
            } else {
                $key = $option;
                $label = $option;
            }

            if ($key === null || $key === '') {
                continue;
            }

            $normalized[$key] = $label;
        }

        return $normalized;
    }
}
