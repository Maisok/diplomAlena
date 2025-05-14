<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Chat $chat)
    {
        return $user->id === $chat->parent_id || $user->id === $chat->participant_id;
    }

    public function startWithEducator(User $user, User $educator)
    {
        return $user->isParent() && $educator->isEducator();
    }
}