<?php

namespace App\Domains\Equipamento\Policies;

use App\Domains\Usuario\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipamentoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool { return $user->currentRole() !== null; }
    public function view(User $user, $model): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, $model): bool { return true; }
    public function delete(User $user, $model): bool { return true; }
}
