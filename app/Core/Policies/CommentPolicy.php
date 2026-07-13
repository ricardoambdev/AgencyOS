<?php

namespace App\Core\Policies;

use App\Core\Models\Comment;
use App\Domains\Usuario\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }
}
