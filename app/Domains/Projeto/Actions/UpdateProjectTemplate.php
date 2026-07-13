<?php

namespace App\Domains\Projeto\Actions;

use App\Domains\Projeto\Models\ProjectTemplate;

class UpdateProjectTemplate
{
    public function handle(ProjectTemplate $template, array $data): ProjectTemplate
    {
        $tasks = $data['tasks'] ?? null;
        unset($data['tasks']);

        $template->update($data);
        $template->recordActivity('updated', 'Template atualizado');

        if ($tasks !== null) {
            app(CreateProjectTemplate::class)->syncTasks($template, $tasks);
        }

        return $template;
    }
}
