<?php

namespace App\Core\Support;

use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class Action
{
    protected function actor(): ?Authenticatable
    {
        return auth()->user();
    }

    protected function actorId(): ?int
    {
        return auth()->id();
    }
}
