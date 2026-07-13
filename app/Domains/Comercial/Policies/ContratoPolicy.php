<?php

namespace App\Domains\Comercial\Policies;

use App\Domains\Usuario\Models\User;
use App\Domains\Comercial\Models\Contrato;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContratoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->currentRole() !== null;
    }

    public function view(User $user, Contrato $contrato): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contrato $contrato): bool
    {
        return true;
    }

    public function delete(User $user, Contrato $contrato): bool
    {
        return true;
    }
}
