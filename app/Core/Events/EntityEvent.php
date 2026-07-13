<?php

namespace App\Core\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

abstract class EntityEvent
{
    use Dispatchable;

    public function __construct(public Model $model) {}
}
