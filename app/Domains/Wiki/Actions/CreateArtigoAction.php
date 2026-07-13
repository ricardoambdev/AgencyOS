<?php

namespace App\Domains\Wiki\Actions;

use App\Core\Support\Action;
use App\Domains\Wiki\Models\Artigo;
use Illuminate\Support\Str;

class CreateArtigoAction extends Action
{
    public function handle(array $data): Artigo
    {
        $data['slug'] = Str::slug($data['title']).'-'.substr((string) Str::ulid(), -6);

        return Artigo::create($data);
    }
}
