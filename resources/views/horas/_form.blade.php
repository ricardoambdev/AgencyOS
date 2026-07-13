<div class="space-y-4" x-data="horaForm()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Colaborador</label>
            <select name="user_id" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id', optional($lancamento)->user_id ?? auth()->id()) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Data</label>
            <input type="date" name="date" value="{{ old('date', optional(optional($lancamento)->date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Projeto</label>
            <select name="project_id" x-model="projectId" @change="loadTasks()" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                @foreach($projetos as $p)
                    <option value="{{ $p->id }}" {{ old('project_id', optional($lancamento)->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tarefa (opcional)</label>
            <select name="task_id" x-model="taskId" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                <option value="">—</option>
                <template x-for="t in tasks" :key="t.id">
                    <option :value="t.id" x-text="t.title" :selected="t.id == taskId"></option>
                </template>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Horas</label>
            <input type="number" name="hours" step="0.25" min="0.01" max="24" value="{{ old('hours', optional($lancamento)->hours) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="billable" value="1" {{ old('billable', optional($lancamento)->billable ?? true) ? 'checked' : '' }}>
                Horas faturáveis
            </label>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Descrição</label>
        <textarea name="description" rows="2" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', optional($lancamento)->description) }}</textarea>
    </div>
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
