@php
    $fields = $model->customFields();
@endphp

@if($fields->isNotEmpty() && $model->exists)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Campos Personalizados</h3>
        <dl class="space-y-2 text-sm">
            @foreach($fields as $field)
                <div class="flex justify-between">
                    <dt class="text-gray-500">{{ $field->label }}</dt>
                    <dd>{{ $model->customFieldDisplayValue($field) ?? '-' }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
@endif
