<?php

namespace App\Core\Concerns;

use App\Domains\Config\Models\CustomField;
use App\Domains\Config\Models\CustomFieldValue;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

trait HasCustomFields
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'entity', 'entity_type', 'entity_id', 'id');
    }

    public function customFields(): \Illuminate\Database\Eloquent\Collection
    {
        return CustomField::where('entity_type', get_class($this))
            ->where('company_id', $this->company_id)
            ->orderBy('order')
            ->get();
    }

    public function syncCustomFieldsFromRequest(Request $request): void
    {
        foreach ($this->customFields() as $field) {
            $key = 'custom_field_'.$field->id;

            if ($field->type === 'file' || $field->type === 'image') {
                if (! $request->hasFile($key)) {
                    continue;
                }

                $path = $request->file($key)->store('custom/'.class_basename($this), 'public');
                $value = $path;
            } else {
                if (! $request->exists($key)) {
                    continue;
                }

                $value = $request->input($key);
            }

            if ($value === null || $value === '') {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $this->customFieldValues()->updateOrCreate(
                ['custom_field_id' => $field->id],
                [
                    'company_id' => $this->company_id,
                    'entity_type' => get_class($this),
                    'entity_id' => $this->getKey(),
                    'value' => (string) $value,
                ]
            );
        }
    }

    public function customFieldDisplayValue(CustomField $field): ?string
    {
        $row = $this->customFieldValues()
            ->where('custom_field_id', $field->id)
            ->first();

        if (! $row) {
            return null;
        }

        if (in_array($field->type, ['select', 'multiselect', 'radio'], true) && $field->options) {
            $options = $field->options;
            $selected = json_decode($row->value, true) ?? $row->value;

            if (is_array($selected)) {
                return collect($selected)->map(fn ($v) => $options[$v] ?? $v)->implode(', ');
            }

            return $options[$selected] ?? $selected;
        }

        if ($field->type === 'boolean') {
            return $row->value ? 'Sim' : 'Não';
        }

        return $row->value;
    }
}
