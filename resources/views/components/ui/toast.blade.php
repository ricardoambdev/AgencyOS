@php
    $messages = collect();
    if (session('status')) $messages->push(['type' => 'success', 'text' => session('status')]);
    if (session('success')) $messages->push(['type' => 'success', 'text' => session('success')]);
    if (session('error')) $messages->push(['type' => 'danger', 'text' => session('error')]);
    foreach ($errors->all() as $err) $messages->push(['type' => 'danger', 'text' => $err]);
@endphp

@if($messages->isNotEmpty())
    <div x-data="{ items: @js($messages->toArray()) }" x-init="setTimeout(() => items = [], 6000)"
         class="fixed bottom-4 right-4 z-[60] flex w-full max-w-sm flex-col gap-2">
        <template x-for="(m, i) in items" :key="i">
            <div x-show="true" x-transition
                 class="flex items-start gap-3 rounded-xl border px-4 py-3 text-sm shadow-card surface"
                 :class="{
                    'border-emerald-200 bg-emerald-50 dark:bg-emerald-900/30': m.type === 'success',
                    'border-red-200 bg-red-50 dark:bg-red-900/30': m.type === 'danger'
                 }">
                <i data-lucide="check-circle" x-show="m.type === 'success'" class="mt-0.5 shrink-0 text-emerald-600"></i>
                <i data-lucide="alert-circle" x-show="m.type === 'danger'" class="mt-0.5 shrink-0 text-red-600"></i>
                <span class="flex-1 text-neutral-800 dark:text-neutral-100" x-text="m.text"></span>
                <button @click="items.splice(i, 1)" class="text-muted hover:text-neutral-800"><i data-lucide="x" class="sm"></i></button>
            </div>
        </template>
    </div>
@endif
