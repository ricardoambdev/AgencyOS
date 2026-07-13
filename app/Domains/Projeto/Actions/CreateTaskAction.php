<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Events\TaskCreated;
use App\Domains\Projeto\Models\Task;

class CreateTaskAction extends Action
{
    public function handle(array $data): Task
    {
        $task = Task::create($data);

        TaskCreated::dispatch($task);

        return $task;
    }
}
