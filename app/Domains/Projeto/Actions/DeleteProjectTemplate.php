<?php

namespace App\Domains\Projeto\Actions;

use App\Domains\Projeto\Models\ProjectTemplate;

class DeleteProjectTemplate
{
    public function handle(ProjectTemplate $template): void
    {
        $template->recordActivity('deleted', 'Template excluído');
        $template->delete();
    }
}
