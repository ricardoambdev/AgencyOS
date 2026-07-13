<?php

namespace App\Domains\Projeto\Controllers;

use App\Domains\Projeto\Actions\DeleteTaskAction;
use App\Domains\Projeto\Actions\UpdateTaskAction;
use App\Domains\Projeto\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        $data = $request->validate([
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'logged_hours' => ['nullable', 'numeric'],
        ]);

        app(UpdateTaskAction::class)->handle($task, $data);

        return redirect()->back()->with('status', 'Tarefa atualizada.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $projeto = $task->project_id;
        app(DeleteTaskAction::class)->handle($task);

        return redirect()->back()->with('status', 'Tarefa removida.');
    }
}
