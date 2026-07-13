<?php

namespace App\Domains\Config\Policies;

use App\Domains\Usuario\Models\User;
use App\Domains\Config\Models\CustomField;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomFieldPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->currentRole() !== null;
    }

    public function view(User $user, CustomField $field): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CustomField $field): bool
    {
        return true;
    }

    public function delete(User $user, CustomField $field): bool
    {
        return true;
    }
}
