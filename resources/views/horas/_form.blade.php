<div class="space-y-4" x-data="horaForm()">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-ui.field label="Colaborador" name="user_id">
            <x-ui.select name="user_id" :options="['' => '—'] + $users->pluck('name', 'id')->toArray()" :selected="old('user_id', optional($lancamento)->user_id ?? auth()->id())" />
        </x-ui.field>
        <x-ui.field label="Data" name="date" required>
            <x-ui.input type="date" name="date" :value="old('date', optional(optional($lancamento)->date)->format('Y-m-d') ?? now()->format('Y-m-d'))" />
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-ui.field label="Projeto" name="project_id">
            <select name="project_id" x-model="projectId" @change="loadTasks()" class="mt-1 w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-app">
                <option value="">—</option>
                @foreach($projetos as $p)
                    <option value="{{ $p->id }}" {{ old('project_id', optional($lancamento)->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </x-ui.field>
        <x-ui.field label="Tarefa (opcional)" name="task_id">
            <select name="task_id" x-model="taskId" class="mt-1 w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-app">
                <option value="">—</option>
                <template x-for="t in tasks" :key="t.id">
                    <option :value="t.id" x-text="t.title" :selected="t.id == taskId"></option>
                </template>
            </select>
        </x-ui.field>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-ui.field label="Horas" name="hours" required>
            <x-ui.input type="number" name="hours" step="0.25" min="0.01" max="24" :value="old('hours', optional($lancamento)->hours)" />
        </x-ui.field>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-sm text-app">
                <input type="checkbox" name="billable" value="1" {{ old('billable', optional($lancamento)->billable ?? true) ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-brand focus:ring-[var(--ring)]">
                Horas faturáveis
            </label>
        </div>
    </div>

    <x-ui.field label="Descrição" name="description">
        <x-ui.textarea name="description" :value="old('description', optional($lancamento)->description)" rows="2" />
    </x-ui.field>
</div>

<script>
function horaForm() {
    return {
        projectId: '{{ old('project_id', optional($lancamento)->project_id ?? '') }}',
        taskId: '{{ old('task_id', optional($lancamento)->task_id ?? '') }}',
        tasks: @json($lancamento && $lancamento->project_id ? $lancamento->project->tasks()->orderBy('title')->get(['id','title']) : []),
        loadTasks() {
            if (! this.projectId) { this.tasks = []; this.taskId = ''; return; }
            fetch('/horas/tasks?project_id=' + this.projectId)
                .then(r => r.json())
                .then(d => { this.tasks = d; if (! this.tasks.find(t => t.id == this.taskId)) this.taskId = ''; });
        }
    }
}
</script>
