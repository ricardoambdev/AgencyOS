<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Models\Task;

class UpdateTaskAction extends Action
{
    public function handle(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }
}
