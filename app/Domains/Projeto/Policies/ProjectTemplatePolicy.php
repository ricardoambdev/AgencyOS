<?php

namespace App\Domains\Projeto\Policies;

use App\Domains\Usuario\Models\User;
use App\Domains\Projeto\Models\ProjectTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectTemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->currentRole() !== null;
    }

    public function view(User $user, ProjectTemplate $template): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ProjectTemplate $template): bool
    {
        return true;
    }

    public function delete(User $user, ProjectTemplate $template): bool
    {
        return true;
    }
}
