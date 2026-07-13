<?php

namespace App\Domains\Projeto\Actions;

use App\Core\Support\Action;
use App\Domains\Projeto\Models\Task;

class DeleteTaskAction extends Action
{
    public function handle(Task $task): void
    {
        $task->delete();
    }
}
