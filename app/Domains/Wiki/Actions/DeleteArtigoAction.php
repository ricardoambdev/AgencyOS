<?php

namespace App\Domains\Wiki\Actions;

use App\Core\Support\Action;
use App\Domains\Wiki\Models\Artigo;

class DeleteArtigoAction extends Action
{
    public function handle(Artigo $artigo): void
    {
        $artigo->delete();
    }
}
