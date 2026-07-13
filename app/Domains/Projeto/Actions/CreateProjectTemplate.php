<?php

namespace App\Domains\Projeto\Actions;

use App\Domains\Projeto\Models\ProjectTemplate;

class CreateProjectTemplate
{
    public function handle(array $data): ProjectTemplate
    {
        $tasks = $data['tasks'] ?? [];
        unset($data['tasks']);

        $template = ProjectTemplate::create($data);
        $template->recordActivity('created', 'Template criado');

        $this->syncTasks($template, $tasks);

        return $template;
    }

    public function syncTasks(ProjectTemplate $template, array $tasks): void
    {
        $template->templateTasks()->delete();

        foreach ($tasks as $index => $task) {
            if (empty($task['title'])) {
                continue;
            }

            $template->templateTasks()->create([
                'title' => $task['title'],
                'description' => $task['description'] ?? null,
                'priority' => $task['priority'] ?? null,
                'estimated_hours' => $task['estimated_hours'] ?? 0,
                'order' => $index,
            ]);
        }
    }
}
