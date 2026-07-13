@php
    $entityType = get_class($model);
    $companyId = $model->company_id ?? app(\App\Core\Support\CompanyContext::class)->id();
    $fields = \App\Domains\Config\Models\CustomField::where('entity_type', $entityType)
        ->where('company_id', $companyId)
        ->orderBy('order')
        ->get();
    $values = $model->exists ? $model->customFieldValues->keyBy('custom_field_id') : collect();
@endphp

@if($fields->isNotEmpty())
    <div class="border-t border-gray-200 pt-4 mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Campos Personalizados</h3>
        <div class="space-y-4">
            @foreach($fields as $field)
                @php
                    $inputName = 'custom_field_'.$field->id;
                    $current = $values->get($field->id);
                    $currentValue = $current?->value;
                    if ($field->type === 'multiselect' && $currentValue) {
                        $currentValue = json_decode($currentValue, true) ?? [];
                    }
                    $required = $field->is_required ? 'required' : '';
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ $field->label }}@if($field->is_required)*@endif</label>
                    @switch($field->type)
                        @case('textarea')
                        @case('text')
                            <input type="text" name="{{ $inputName }}" value="{{ old($inputName, $currentValue) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                            @break
                        @case('number')
                        @case('currency')
                            <input type="number" step="0.01" name="{{ $inputName }}" value="{{ old($inputName, $currentValue) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                            @break
                        @case('date')
                            <input type="date" name="{{ $inputName }}" value="{{ old($inputName, $currentValue) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                            @break
                        @case('time')
                            <input type="time" name="{{ $inputName }}" value="{{ old($inputName, $currentValue) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                            @break
                        @case('boolean')
                            <input type="checkbox" name="{{ $inputName }}" value="1" {{ old($inputName, $currentValue) ? 'checked' : '' }}>
                            @break
                        @case('select')
                            <select name="{{ $inputName }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" {{ $required }}>
                                <option value="">—</option>
                                @foreach(($field->options ?? []) as $k => $v)
                                    <option value="{{ $k }}" {{ old($inputName, $currentValue) == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @break
                        @case('multiselect')
                            <select name="{{ $inputName }}[]" multiple class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                                @foreach(($field->options ?? []) as $k => $v)
                                    <option value="{{ $k }}" {{ is_array($currentValue) && in_array($k, $currentValue) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @break
                        @case('image')
                        @case('file')
                            @if($currentValue)<p class="text-xs text-gray-500 mt-1">Atual: {{ basename($currentValue) }}</p>@endif
                            <input type="file" name="{{ $inputName }}" class="mt-1 w-full text-sm">
                            @break
                        @default
                            <input type="text" name="{{ $inputName }}" value="{{ old($inputName, $currentValue) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                    @endswitch
                </div>
            @endforeach
        </div>
    </div>
@endif
