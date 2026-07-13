<?php

namespace App\Domains\Wiki\Actions;

use App\Core\Support\Action;
use App\Domains\Wiki\Models\Artigo;
use Illuminate\Support\Str;

class UpdateArtigoAction extends Action
{
    public function handle(Artigo $artigo, array $data): Artigo
    {
        if (! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']).'-'.substr((string) Str::ulid(), -6);
        }

        $artigo->update($data);

        return $artigo;
    }
}
